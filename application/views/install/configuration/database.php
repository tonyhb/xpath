<?php echo "<?php defined('SYSPATH') or die('No direct script access.');" ?>


return array(
    'default' => array(
        'type' => 'mysql',
        'connection' => array(
            'hostname' => '<?= $database['hostname'] ?>',
            'username' => '<?= $database['username'] ?>',
            'password' => '<?= $database['password'] ?>',
            'database' => '<?= $database['database'] ?>',
            'persistent' => FALSE,
        ),
        'table_prefix' => 'xp_',
        'charset'      => 'utf8',
    )
);
