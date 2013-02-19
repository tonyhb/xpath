<?php defined('SYSPATH') or die('No direct script access.');

class Model_User extends Model
{

    /**
     * Creates a new admin user based on an array of data passed
     *
     * @todo Complete method
     */
    public function create(Array $data)
    {
        $data['password'] = Model::factory('auth')->hash_password($data['password']);
    }

    public function load_by_username($username)
    {
        $result = DB::Query(Database::SELECT, "SELECT * FROM `xp_users` WHERE `username` = :username")
            ->bind(":username", $username)
            ->execute();

        if ($result->count() == 0)
            return FALSE;

        // Returns an array containing an array of values
        $result = $result->as_array();

        return $result[0];
    }

}
