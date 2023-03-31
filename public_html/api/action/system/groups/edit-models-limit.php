<?php

/**
 * @file
 * @brief обработчик установки лимита моделей для группы
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});


if (!($row = Wrong\Database\Controller::find($_POST['id'], 'id', $_POST['table']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if ($row->owner_group == 1) {
    exit(json_encode(['error' => 'Изменить лимит системного функционала нельзя!']));
}

if (!in_array($row->owner_group, $user->subordinate_groups)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}


$sth = $dbh->prepare("UPDATE `{$_POST['table']}` SET `models_limit` = :models_limit WHERE `id` = :id");
$sth->bindValue(':models_limit', $_POST['models_limit']);
$sth->bindValue(':id', $row->id);
$sth->execute();
if ($sth->errorCode() == '00000') {
    exit(json_encode(['result' => 'ok', 'message' => 'Лимит успешно установлен']));
}

exit(json_encode(['error' => 'Ошибка']));
