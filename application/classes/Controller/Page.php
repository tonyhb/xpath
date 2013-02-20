<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Page extends Controller_Admin
{

    public function action_index()
    {
        echo "index";
    }

    public function action_create()
    {
        $data = $this->request->post("page");

        // @TODO: Try/Catch block to show error messages
        Model::factory("page")->create($data);

        HTTP::Redirect("/admin/page/show".$data['uri']);
    }

    public function action_show()
    {
        $uri = $this->request->param('uri');
        if ($uri == '')
            $uri = '/';

        if ( ! $page = Model::factory('Page')->render($uri))
        {
            $content = View::Factory('admin/new_page')
                ->set('uri', $uri);

            $content = View::Factory('_template')
                ->set('body', $content);

            return $this->response->body($content);
        }

        $this->response->body($page['html']);
    }

}
