<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin extends Controller
{

    protected $_public_methods = array('login');

    public function before()
    {
    }

    public function action_login()
    {
        $this->response->body("login");
    }

}
