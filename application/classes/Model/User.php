<?php defined('SYSPATH') or die('No direct script access.');

class Model_User extends Model
{

    /**
     * Creates a new admin user based on an array of data passed
     *
     */
    public function create(Array $data)
    {
        $data['password'] = $this->hash_password($data['password']);

    }

    /**
     * Hashes a user's password using the blowcrypt algorithm.
     *
     * @param string  plaintext password to hash
     * @param string  optional salt to hash the password with
     */
    public function hash_password($input, $salt = '')
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

}
