<?php

/**
 * @file
 * @brief обработчик редактирования кода файла модели
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

if (!($row = Wrong\Database\Controller::find($_POST['id'], 'id', $_POST['table']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if ($user->access()->is_system($row)) {
    exit(json_encode(['error' => 'Изменить код файла обработчика системного функционала нельзя!']));
}

if (!$user->access()->write($row)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

$_POST['code'] = str_replace("\r", '', $_POST['code']);

if ($_POST['code'] == file_get_contents($_SERVER['DOCUMENT_ROOT'] . $row->file)) {
    exit(json_encode(['result' => 'ok', 'message' => 'Файл оставлен без изменений']));
}

$file = new \SplFileObject($_SERVER['DOCUMENT_ROOT'] . $row->file, 'w+b');
$file->flock(LOCK_EX);
$file->ftruncate(0);
$file->rewind();
$file->fwrite($_POST['code']);
$file->flock(LOCK_UN);

clearstatcache(true, $_SERVER['DOCUMENT_ROOT'] . $row->file);
exit(json_encode(['result' => 'ok', 'message' => 'Файл успешно перезаписан', 'modified' => date('Y-m-d H:i:s', filemtime($_SERVER['DOCUMENT_ROOT'] . $row->file))]));
