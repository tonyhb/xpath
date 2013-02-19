<?php defined('SYSPATH') or die('No direct script access.');

class Model_Install extends Model
{

    /**
     * A protected variable stating whether the app has been installed.
     *
     * @bool
     */
    protected $_installed = TRUE;

    /**
     * Holds the "install" database instance
     *
     * @var Database
     */
    protected $_db;

    /**
     * Creates a new instance of the Model_Install method.
     *
     * This also checks to see whether the app has already been installed.
     *
     * @return Model_Install  $this
     */
    public function __construct()
    {
        try
        {
            $result = DB::Query(Database::SELECT, "SELECT * from `xp_settings`")
            ->execute();
        }
        catch (Database_Exception $e)
        {
            $this->_installed = FALSE;
        }
    }

    /**
     * Creates a new database instance called "install" with the provided DB 
     * settings. We need to use this because at this point in the request 
     * Kohana's database settings are already cached.
     *
     * @return Model_Install  $this
     */
    public function connect($database_settings)
    {
        $settings = array(
            'type'         => 'mysql',
            'connection'   => $database_settings,
            'table_prefix' => 'xp_',
            'charset'      => 'utf8',
        );

        $this->_db = Database::instance("install", $settings);
        $this->_db->connect();

        return $this;
    }

    /**
     * Returns a boolean which states whether the app has been installed. The 
     * detection is run in the __construct method
     *
     * @return bool
     */
    public function is_installed()
    {
        return $this->_installed;
    }

    /**
     * Creates new Kohana configuration files based upon the user import. This 
     * handles database configuration and an app configuration file.
     *
     * @return Model_Install  $this
     */
    public function create_config_files(Array $data)
    {
        if ($this->is_installed())
        {
            throw new Exception("The app is already installed");
        }

        $content = View::factory('install/configuration/app');
        file_put_contents(APPPATH.'/config/app.php', $content);

        $content = View::factory('install/configuration/database')->set('database', $data['database']);
        file_put_contents(APPPATH.'/config/database.php', $content);

        return $this;
    }

    /**
     * Creates database tables for the app.
     *
     * @return Model_Install  $this;
     */
    public function create_database_tables()
    {
        if ($this->is_installed())
        {
            throw new Exception("The app is already installed");
        }

        $sql = Kohana::$config->load("sql");

        foreach ($sql as $create_syntax)
        {
            $this->_db->query(Database::UPDATE, $create_syntax);
        }

        return $this;
    }

    /**
     * Creates a new user in the installation process. It's included in this 
     * method because at this stage the DB connection relies on the "install" 
     * database instance.
     *
     * @return Model_Install  $this;
     */
    public function create_user($data)
    {
        $data['password'] = Model::factory('auth')->hash_password($data['password']);

        DB::Query(Database::INSERT, "INSERT INTO `xp_users` (`username`, `email`, `password`) VALUES (:username, :email, :password)")
            ->bind(":username", $data['username'])
            ->bind(":email", $data['email'])
            ->bind(":password", $data['password'])
            ->execute("install");

        return $this;
    }

    /**
     * Deletes configuration files and tables if the installation fails. This 
     * can only be called during an installation.
     *
     * @return void
     */
    public function rollback()
    {
        if ($this->is_installed())
        {
            throw new Exception("The app is already installed. To uninstall the app please follow guidelines in the documentation.");
        }

        // Remove configuration files
        if (file_exists(APPPATH.'/config/app.php'))
        {
            unlink(APPPATH.'/config/app.php');
        }

        if (file_exists(APPPATH.'/config/database.php'))
        {
            unlink(APPPATH.'/config/database.php');
        }

        // Delete our database tables.
        DB::Query(Database::INSERT, "DROP TABLE `xp_users`")->execute("install");
        DB::Query(Database::INSERT, "DROP TABLE `xp_settings`")->execute("install");
    }
}
