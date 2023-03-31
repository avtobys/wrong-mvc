<?php

/**
 * @file
 * @brief обработчик включения/выключения записи логов для группы
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

$_POST = array_map(function ($item) {
    if (is_string($item)) {
        return trim($item);
    } else {
        return $item;
    }
}, $_POST);

if (!($row = Wrong\Database\Controller::find($_POST['id'], 'id', 'groups'))) {
    exit(json_encode(['error' => 'Ошибка']));
}

$id = abs(intval($_POST['id']));

if (!in_array($row->owner_group, $user->groups) && !in_array($row->owner_group, $user->subordinate_groups)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}


$sth = $dbh->prepare("UPDATE `groups` SET `logs` = IF (`logs` = 1, 0, 1) WHERE `id` = ?");
$sth->bindValue(1, $id);
$sth->execute();

$act = intval(!$row->logs);
$message = $act ? 'Запись логов включена' : 'Запись логов отключена.';

exit(json_encode(['id' => $id, 'act' => $act, 'message' => $message]));
