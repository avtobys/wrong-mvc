<?php

/**
 * @file
 * @brief шаблон пустой
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';
$TEMPLATE_DATA = ob_get_contents();
ob_clean();

?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $row->name ?></title>
    <meta name="description" content="">
    <link rel="icon" type="image/png" sizes="64x64" href="/assets/system/img/favicon-64.png">
    <?= Wrong\Html\Get::stylesrc($_SERVER['DOCUMENT_ROOT'] . '/assets/system/css/main.min.css') ?>
</head>

<body>
    <?= $TEMPLATE_DATA ?>
    <?= Wrong\Html\Get::scriptsrc($_SERVER['DOCUMENT_ROOT'] . '/assets/system/js/main.min.js') ?>
</body>

</html>