<?php

/**
 * @file
 * @brief обработчик включения/выключения api по X-Auth-Token для пользователя
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

if (!($row = Wrong\Database\Controller::find($_POST['id'], 'id', 'users'))) {
    exit(json_encode(['error' => 'Ошибка']));
}

$id = abs(intval($_POST['id']));

if (!in_array($row->owner_group, $user->groups) && !in_array($row->owner_group, $user->subordinate_groups)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}


$sth = $dbh->prepare("UPDATE `users` SET `api_act` = IF (`api_act` = 1, 0, 1) WHERE `id` = ?");
$sth->bindValue(1, $id);
$sth->execute();

$act = intval(!$row->api_act);
$message = $act ? 'API по заголовкам X-Auth-Token включено' : 'API по заголовкам X-Auth-Token отключено';

exit(json_encode(['id' => $id, 'act' => $act, 'message' => $message]));
