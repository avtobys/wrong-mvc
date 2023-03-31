<?php

/**
 * @file
 * @brief обработчик изменения комментария к модели
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});

if (!($row = Wrong\Database\Controller::find($_POST['id'], 'id', $_POST['table']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if (!in_array($row->owner_group, $user->subordinate_groups) && !in_array($row->owner_group, $user->groups)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

$sth = $dbh->prepare("UPDATE `{$_POST['table']}` SET `note` = :note WHERE `id` = :id");
$sth->bindValue(':note', $_POST['note']);
$sth->bindValue(':id', $_POST['id']);
$sth->execute();
if ($sth->errorCode() == '00000') {
    exit(json_encode(['result' => 'ok', 'message' => 'Успешно переименовано']));
}

exit(json_encode(['error' => 'Ошибка']));
