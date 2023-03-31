<?php

/**
 * @file
 * @brief обработчик смены шаблона для страницы
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
    exit(json_encode(['error' => 'Изменить шаблон системного функционала нельзя!']));
}

if (!in_array($row->owner_group, $user->subordinate_groups)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

if (!in_array($_POST['template_id'], array_column(Wrong\Models\Templates::all_available(), 'id'))) {
    exit(json_encode(['error' => 'Этот шаблон вам недоступен!']));
}

$sth = $dbh->prepare("UPDATE `{$_POST['table']}` SET `template_id` = ? WHERE `id` = ?");
$sth->bindValue(1, $_POST['template_id']);
$sth->bindValue(2, $_POST['id']);
$sth->execute();
if ($sth->errorCode() == '00000') {
    exit(json_encode(['result' => 'ok', 'message' => 'Шаблон для страницы успешно установлен']));
}

exit(json_encode(['error' => 'Ошибка']));
