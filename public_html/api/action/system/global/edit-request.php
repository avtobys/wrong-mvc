<?php

/**
 * @file
 * @brief обработчик установки request для запроса
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});


if (!($row = Wrong\Database\Controller::find($_POST['id'], 'id', $_POST['table']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if ($user->access()->is_system($row)) {
    exit(json_encode(['error' => 'Изменить запрос системного функционала нельзя!']));
}

if (!$user->access()->write($row)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

Wrong\Check\Model::request($_POST, $_POST['table']);

$sth = $dbh->prepare("UPDATE `{$_POST['table']}` SET `request` = :request WHERE `id` = :id");
$sth->bindValue(':request', $_POST['request']);
$sth->bindValue(':id', $_POST['id']);
$sth->execute();
if ($sth->errorCode() == '00000') {
    exit(json_encode(['result' => 'ok', 'message' => 'Запрос успешно переименован']));
}

exit(json_encode(['error' => 'Ошибка']));
