<?php

/**
 * @file
 * @brief обработчик смены системного веса группы
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
    exit(json_encode(['error' => 'Изменить вес системного функционала нельзя!']));
}

if (!in_array($row->owner_group, $user->subordinate_groups)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

if ($_POST['weight'] > $user->weight_subordinate || $_POST['weight'] < 0) {
    exit(json_encode(['error' => 'Системный вес указан некорректно']));
}

$sth = $dbh->prepare("UPDATE `{$_POST['table']}` SET `weight` = :weight WHERE `id` = :id");
$sth->bindValue(':weight', $_POST['weight']);
$sth->bindValue(':id', $row->id);
$sth->execute();
if ($sth->errorCode() == '00000') {
    exit(json_encode(['result' => 'ok', 'message' => 'Системный вес успешно установлен']));
}

exit(json_encode(['error' => 'Ошибка']));
