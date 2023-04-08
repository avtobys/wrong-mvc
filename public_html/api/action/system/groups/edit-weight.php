<?php

/**
 * @file
 * @brief обработчик смены системного веса группы
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

if (!($row = Wrong\Models\Groups::find($_POST['id']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if ($user->access()->is_system($row)) {
    exit(json_encode(['error' => 'Изменить вес системного функционала нельзя!']));
}

if (!$user->access()->write($row)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

if ($_POST['weight'] > $user->weight_subordinate || $_POST['weight'] < 0) {
    exit(json_encode(['error' => 'Системный вес указан некорректно']));
}

$sth = $dbh->prepare("UPDATE `groups` SET `weight` = :weight WHERE `id` = :id");
$sth->bindValue(':weight', $_POST['weight']);
$sth->bindValue(':id', $row->id);
$sth->execute();
if ($sth->errorCode() == '00000') {
    exit(json_encode(['result' => 'ok', 'message' => 'Системный вес успешно установлен']));
}

exit(json_encode(['error' => 'Ошибка']));
