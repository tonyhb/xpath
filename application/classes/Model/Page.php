<?php defined('SYSPATH') or die('No direct script access.');

class Model_Page extends Model
{

    public function render($uri)
    {
        if ( ! $data = $this->load_by_uri($uri))
            return FALSE;

        return $data;
    }

    public function create($data)
    {
        // Remove any empties
        $data = array_filter($data);

        // Get the uploaded file's HTML content
        $data['html'] = file_get_contents($_FILES['page']['tmp_name']['html']);

        if (array_keys($data) != array('name', 'uri', 'html'))
        {
            throw new Exception("A page name, uri, and HTML must be passed to create a new page");
        }

        if ($this->load_by_uri($data['uri']))
        {
            throw new Exception("A page with this URI already exists");
        }

        DB::Query(Database::INSERT, "INSERT INTO `xp_pages` (`name`, `uri`, `html`) VALUES (:name, :uri, :html)")
            ->bind(":name", $data['name'])
            ->bind(":uri", $data['uri'])
            ->bind(":html", $data['html'])
            ->execute();

        return TRUE;
    }

    public function load_by_uri($uri)
    {
        $result = DB::Query(Database::SELECT, "SELECT * FROM `xp_pages` WHERE `uri` = :uri")
            ->bind(":uri", $uri)
            ->execute();

        // Nothing here...
        if ($result->count() == 0)
            return FALSE;

        // Get the first page and return it
        $result = $result->as_array();
        return $result[0];
    }
}
