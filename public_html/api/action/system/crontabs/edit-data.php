<?php

/**
 * @file
 * @brief обработчик редактирования тела запроса для cron задачи
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

$_POST['data'] = array_map('trim', $_POST['data']);
$_POST['data'] = array_filter($_POST['data']);
foreach ($_POST['data'] as $key => $item) {
    $arr = array_map('trim', explode(':', $item, 2));
    $_POST['data'][$arr[0]] = $arr[1];
    unset($_POST['data'][$key]);
}
$_POST['data'] = json_encode($_POST['data']);

$sth = $dbh->prepare("UPDATE `crontabs` SET `data` = :data WHERE `id` = :id");
$sth->bindValue(':data', $_POST['data']);
$sth->bindValue(':id', $row->id);
$sth->execute();

if ($sth->errorCode() == '00000') {
    exit(json_encode(['result' => 'ok', 'message' => 'Данные успешно установлены']));
}

exit(json_encode(['error' => 'Ошибка']));


