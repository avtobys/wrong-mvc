<?php

/**
 * @file
 * @brief обработчик экспорта модели
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

$_REQUEST = array_map(function ($item) {
    if (is_string($item)) {
        return trim(htmlspecialchars($item, ENT_QUOTES));
    } else {
        return $item;
    }
}, $_REQUEST);


if (!($row = Wrong\Database\Controller::find($_REQUEST['id'], 'id', $_REQUEST['table']))) {
    exit('errorToast("Ошибка!");');
}

if (!in_array($row->owner_group, $user->subordinate_groups) && !in_array($row->owner_group, $user->groups)) {
    if (isset($_POST['copy'])) {
        exit(json_encode(['error' => 'Недостаточно прав!']));
    }
    exit('errorToast("Недостаточно прав!");');
}

if (!isset($_POST['copy'])) {
    !isset($_GET['save']) && exit('location.href="' . $request . '?save&id=' . $_REQUEST['id'] . '&table=' . $_REQUEST['table'] . '";');
}

$zipname = 'model-' . $_REQUEST['table'] . '-' . $_REQUEST['id'] . '.zip';

$zip = new ZipArchive;
$zip->open($zipname, ZipArchive::CREATE);
$zip->addFromString('model.json', json_encode($row));
$zip->addFromString('name.txt', $_REQUEST['table']);
if ($row->file) {
    $zip->addFile($_SERVER['DOCUMENT_ROOT'] . $row->file, basename($row->file));
}
$zip->close();

if (isset($_POST['copy'])) {
    $_FILES['file']['name'] = $zipname;
    $_FILES['file']['tmp_name'] = $zipname;
    require 'import-model.php';
    unlink($zipname);
}


header('Content-Type: application/zip');
header('Content-disposition: attachment; filename=' . $zipname);
header('Content-Length: ' . filesize($zipname));
readfile($zipname);
unlink($zipname);
