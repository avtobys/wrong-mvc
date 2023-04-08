<?php

/**
 * @file
 * @brief обработчик включения/выключения api по X-Auth-Token для пользователя
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

if (!($row = Wrong\Models\Users::find($_POST['id']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if (!$user->access()->write($row, true)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

$sth = $dbh->prepare("UPDATE `users` SET `api_act` = IF (`api_act` = 1, 0, 1) WHERE `id` = :id");
$sth->bindValue(':id', $_POST['id']);
$sth->execute();

$act = intval(!$row->api_act);
$message = $act ? 'API по заголовкам X-Auth-Token включено' : 'API по заголовкам X-Auth-Token отключено';

exit(json_encode(['id' => $_POST['id'], 'act' => $act, 'message' => $message]));
