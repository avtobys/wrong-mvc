<?php

/**
 * @file
 * @brief обработчик установки файла обработчика
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});

if (!($row = Wrong\Database\Controller::find($_POST['id'], 'id', $_POST['table']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if ($user->access()->is_system($row)) {
    exit(json_encode(['error' => 'Изменить имя файла обработчика системного функционала нельзя!']));
}

if (!$user->access()->write($row)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

if (
    Wrong\Database\Controller::find($_POST['file'], 'file', $_POST['table'])->file == $_POST['file'] &&
    Wrong\Database\Controller::find($_POST['file'], 'file', $_POST['table'])->id != $_POST['id']
) {
    exit(json_encode(['error' => 'Файл обработчик с таким именем уже зарегистрирован в БД!']));
}

Wrong\Check\Model::file($_POST, $_POST['table'], true);
Wrong\File\Path::mkdir($_SERVER['DOCUMENT_ROOT'] . $_POST['file']);

if (rename($_SERVER['DOCUMENT_ROOT'] . $row->file, $_SERVER['DOCUMENT_ROOT'] . $_POST['file'])) {
    Wrong\File\Path::rmdir($_SERVER['DOCUMENT_ROOT'] . $row->file);
    $sth = $dbh->prepare("UPDATE `{$_POST['table']}` SET `file` = ? WHERE `id` = ?");
    $sth->bindValue(1, $_POST['file']);
    $sth->bindValue(2, $_POST['id']);
    $sth->execute();
    if ($sth->errorCode() == '00000') {
        exit(json_encode(['result' => 'ok', 'message' => 'Файл обработчик успешно переименован']));
    }
}

exit(json_encode(['error' => 'Ошибка']));
