<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin extends Controller
{

    protected $_public_methods = array('login');

    public function before()
    {
        // Attempt loggin in - we may have shown the login form for any URL if 
        // the user wasn't authenticated.
        if ($data = $this->request->post("login"))
        {
            App_Auth::login($data);
        }

        // If this isn't a public method try running authentication. If we fail, 
        // always render the login action.
        if ( ! in_array($this->request->action(), $this->_public_methods))
        {
            if ( ! App_Auth::authenticate())
            {
                $this->request->action("login");
            }
        }
    }

    public function action_login()
    {
        if (App::$user_id != FALSE)
        {
            HTTP::Redirect("/admin/dashboard");
        }

        $response = View::Factory("_template")
            ->set("body", View::factory("admin/login"));

        $this->response->body($response);
    }

}
