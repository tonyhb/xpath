<?php defined('SYSPATH') or die('No direct script access.');

class App_Auth extends Model
{

    /**
     * Authenticates a user based on their cookie.
     *
     * The cookie contains the user ID, expiration, and a secure hash of these. 
     * Forging this key requires knowing the app specific UUID, which we can 
     * assume is theoretically impossible (worst case scenario) to break.
     *
     * @return bool
     */
    public static function authenticate()
    {
        if ( ! $cookie = Cookie::get("app_auth"))
            return FALSE;

        list($user_id, $expiration, $cookie_hash) = explode('|', $cookie);

        // Expired. Delete the cookie and exit.
        if ($expiration < time())
        {
            Cookie::delete("app_auth");
            return FALSE;
        }

        $server_hash = self::_generate_cookie_hash($user_id, $expiration);

        // If the hash in the cookie doesn't match our securely generated hash, 
        // it has been forged. If it does, though, we've got the current user.
        if ($cookie_hash != $server_hash)
            return FALSE;

        // Every time the user is authenticated the cookie should also be re-set 
        // with a new expiry date...
        self::_set_cookie($user_id);

        App::$user_id = $user_id;

        return $user_id;
    }

    /**
     * Attempts to log in a user based on their username and password 
     * combination.
     *
     * @param  array  array with 'username' and 'password' keys
     * @return bool
     */
    public static function login($data)
    {
        if ( ! array_keys($data) == array('username', 'password'))
            return FALSE;

        // Select the current password from the database
        $user = Model::factory('user')->load_by_username($data['username']);

        // Get the salt from the user's password and hash the given password; if 
        // they match, we have the correct password supplied.
        $salt = substr($user['password'], 0, 22);
        $hash = self::hash_password($data['password'], $salt);

        if ($hash == $user['password'])
        {
            self::_set_cookie($user['id']);

            App::$user_id = $user['id'];

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Hashes a user's password using the blowcrypt algorithm.
     *
     * @param string  plaintext password to hash
     * @param string  optional salt to hash the password with
     */
    public static function hash_password($input, $salt = '')
    {
        if ($salt == '')
        {
            // Get the app salt for blowcrypt encryption
            $salt = Kohana::$config->load('app.salt');

            // Remove the dash characters from our V4 UUID and shorten to 22 
            // characters for blowcrypt encruption
            $salt = substr(str_replace('-', '', $salt), 0, 22);

            // Pseudo-randomise our app salt UUID on a per-user basis
            $salt = sha1($salt.uniqid());
        }

        // Add blowcrypt cost and watermarks
        $salt = "$2a$10$".$salt."$";
        $hash = crypt($input, $salt);

        // By default, the hash has the blowcrypt warermarks at the start of the 
        // string and the salt separated from the hash via a dot. This removes 
        // them.
        $hash = substr($hash, 7);
        return preg_replace('#\.#', '', $hash, 1);
    }

    /**
     * This sets an authentication cookie for a given user.
     *
     * @param int  The ID of the user for the cookie
     * @param int  Lifetime of cookie in seconds (defaults to 1 hour)
     */
    protected static function _set_cookie($user_id, $expires = 3600)
    {
        $cookie_expiration = time() + $expires;

        // Hash the cookie with the key, securely encrypting the cookie
        $hash = self::_generate_cookie_hash($user_id, $cookie_expiration);

        // Set the cookie. Add the hashed expiration and ID so we can reverse 
        // the process to test the cookie.
        Cookie::set("app_auth", $user_id."|".$cookie_expiration."|".$hash, $expires);
    }

    /**
     * Generates a hash from the user ID, cookie expiration and app UUID.
     *
     * The ID, expiration and UUID are hashed together to create a key only the 
     * server knows, which is then used to encrypt the cookie.
     *
     * Because only we know the UUID the cookie should be impossible to forge 
     * (either by changing the user ID, changing the expiration, or creating 
     * a cookie).
     *
     * This does not stop XSS stealing a cookie or CSRF.
     *
     * @return string  secure hash of cookie contents
     */
    protected static function _generate_cookie_hash($user_id, $expiration)
    {
        $key = hash_hmac('sha224', $expiration.$user_id, Kohana::$config->load("app.salt"));
        return hash_hmac('sha224', $expiration.$user_id, $key);
    }

}
