<?php

/**
 * @file
 * @brief обработчик изменения названий/имени для модели
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
    exit(json_encode(['error' => 'Изменить название системного функционала нельзя!']));
}

if (!$user->access()->write($row)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

if (
    Wrong\Database\Controller::find($_POST['name'], 'name', $_POST['table'])->name == $_POST['name'] &&
    Wrong\Database\Controller::find($_POST['name'], 'name', $_POST['table'])->id != $_POST['id'] &&
    !in_array($_POST['table'], ['pages'])
) {
    exit(json_encode(['error' => 'Уже есть запись с таким именем!']));
}

$sth = $dbh->prepare("UPDATE `{$_POST['table']}` SET `name` = :name WHERE `id` = :id");
$sth->bindValue(':name', $_POST['name']);
$sth->bindValue(':id', $_POST['id']);
$sth->execute();
if ($sth->errorCode() == '00000') {
    exit(json_encode(['result' => 'ok', 'message' => 'Успешно переименовано']));
}

exit(json_encode(['error' => 'Ошибка']));
