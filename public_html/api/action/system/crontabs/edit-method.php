<?php

/**
 * @file
 * @brief обработчик устанавливает метод запроса для cron задачи
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});

if (!($row = Wrong\Database\Controller::find($_POST['id'], 'id', $_POST['table']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if (!in_array($row->owner_group, $user->subordinate_groups)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}


$sth = $dbh->prepare("UPDATE `crontabs` SET `method` = :method WHERE `id` = :id");
$sth->bindValue(':method', $_POST['method']);
$sth->bindValue(':id', $row->id);
$sth->execute();

if ($sth->errorCode() == '00000') {
    exit(json_encode(['result' => 'ok', 'message' => 'Метод успешно установлен']));
}

exit(json_encode(['error' => 'Ошибка']));

