<?php

/**
 * @file
 * @brief обработчик смены шаблона для страницы
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

if (!($row = Wrong\Models\Pages::find($_POST['id']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if ($user->access()->is_system($row)) {
    exit(json_encode(['error' => 'Изменить шаблон системного функционала нельзя!']));
}

if (!$user->access()->write($row)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

if (!in_array($_POST['template_id'], array_column(Wrong\Models\Templates::all_available(), 'id'))) {
    exit(json_encode(['error' => 'Этот шаблон вам недоступен!']));
}

$sth = $dbh->prepare("UPDATE `pages` SET `template_id` = :template_id WHERE `id` = :id");
$sth->bindValue(':template_id', $_POST['template_id']);
$sth->bindValue(':id', $_POST['id']);
$sth->execute();
if ($sth->errorCode() == '00000') {
    exit(json_encode(['result' => 'ok', 'message' => 'Шаблон для страницы успешно установлен']));
}

exit(json_encode(['error' => 'Ошибка']));
