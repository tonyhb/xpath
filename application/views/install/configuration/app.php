<?php echo "<?php defined('SYSPATH') or die('No direct script access.');" ?>


return array(
    'salt' => '<?= UUID::v4() ?>',
);
