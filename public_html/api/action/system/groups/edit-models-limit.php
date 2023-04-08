<?php

/**
 * @file
 * @brief обработчик установки лимита моделей для группы
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

if (!($row = Wrong\Models\Groups::find($_POST['id']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if ($user->access()->is_system($row)) {
    exit(json_encode(['error' => 'Изменить лимит системного функционала нельзя!']));
}

if (!$user->access()->write($row)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}


$sth = $dbh->prepare("UPDATE `groups` SET `models_limit` = :models_limit WHERE `id` = :id");
$sth->bindValue(':models_limit', $_POST['models_limit']);
$sth->bindValue(':id', $row->id);
$sth->execute();
if ($sth->errorCode() == '00000') {
    exit(json_encode(['result' => 'ok', 'message' => 'Лимит успешно установлен']));
}

exit(json_encode(['error' => 'Ошибка']));
