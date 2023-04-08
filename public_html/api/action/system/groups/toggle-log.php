<?php

/**
 * @file
 * @brief обработчик включения/выключения записи логов для группы
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

if (!($row = Wrong\Models\Groups::find($_POST['id']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if (!$user->access()->write($row, true)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

$sth = $dbh->prepare("UPDATE `groups` SET `logs` = IF (`logs` = 1, 0, 1) WHERE `id` = :id");
$sth->bindValue(':id', $_POST['id']);
$sth->execute();

$act = intval(!$row->logs);
$message = $act ? 'Запись логов включена' : 'Запись логов отключена.';

exit(json_encode(['id' => $_POST['id'], 'act' => $act, 'message' => $message]));
