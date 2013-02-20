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
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="/_admin/assets/js/application.js"></script>
</body>
</html>
