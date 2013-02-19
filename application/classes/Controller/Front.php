<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Front extends Controller
{

    /**
     * Loads a website's page from a HTTP request.
     *
     */
    public function action_request()
    {
        echo Debug::vars($this->request);
    }

}
