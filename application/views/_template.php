<!doctype html>
<html>
<head>
    <title><?= isset($title) ? $title : '' ?></title>
    <link rel="stylesheet" href="/_admin/assets/css/application.css" media="all" />
</head>
<body>
    <?php if (App::$user_id): ?>
        <?= View::Factory('admin/_menu'); ?>
    <?php endif; ?>

    <?= $body ?>
    <script data-main="/_admin/assets/js/config" src="/_admin/assets/js/libs/requirejs.min.js"></script>
</body>
</html>
