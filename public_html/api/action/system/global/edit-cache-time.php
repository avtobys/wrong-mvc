<?php

/**
 * @file
 * @brief обработчик изменения времени кеширования
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});

if (!($row = Wrong\Database\Controller::find($_POST['id'], 'id', $_POST['table']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if (!$user->access()->write($row, true)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

$sth = $dbh->prepare("UPDATE `{$_POST['table']}` SET `cache_time` = :cache_time WHERE `id` = :id");
$sth->bindValue(':cache_time', $_POST['cache_time']);
$sth->bindValue(':id', $_POST['id']);
$sth->execute();
if ($sth->errorCode() == '00000') {
    exit(json_encode(['result' => 'ok', 'message' => 'Успешно установлено']));
}

exit(json_encode(['error' => 'Ошибка']));
