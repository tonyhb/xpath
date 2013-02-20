<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Front extends Controller
{

    protected $_page;

    public function before()
    {
        App_Auth::Authenticate();

        // The page must have the trailing slash, allowing us to save the URI 
        // key for the index page ('/')
        $this->_page = "/".$this->request->param('page');
    }

    /**
     * Loads a website's page from a HTTP request.
     *
     */
    public function action_request()
    {
        if (App::$user_id)
        {
            // Show the page within an iFrame for editing
            $response = View::Factory('admin/edit')
                ->set('uri', $this->_page);

            return $this->response->body(View::Factory('_template')->set('body', $response));
        }

        if ( ! $page = Model::factory('Page')->render($this->_page))
            return $this->_404();

        $this->response->body($page['html']);
    }

    protected function _404()
    {
        $this->response->body("404");
    }

}
