<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Install extends Controller
{

    /**
     * An instance of the install model used to run installation methods
     *
     * @var Model_Install
     */
    protected $_install;

    /**
     * Detects whether the installation procedure has run before
     *
     */
    public function before()
    {
        $this->_install = Model::factory('install');

        if ($this->_install->is_installed())
        {
            HTTP::redirect('/admin');
        }
    }

    /**
     * Handles the installation process.
     *
     */
    public function action_request()
    {
        if ($data = $this->request->post())
        {
            try
            {
                $this->_install
                    ->connect($data['database'])
                    ->create_config_files($data)
                    ->create_database_tables()
                    ->create_user($data['admin']);

                return HTTP::redirect('/admin');
            }
            catch(Exception $e)
            {
                $this->_install->rollback();
            }
        }

        $this->response->body(View::factory('_template')->set('body', View::factory('install/form')));
    }

}
