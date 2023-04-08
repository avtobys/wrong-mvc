<?php

/**
 * @file
 * @brief обработчик изменения cli команды для cron задачи
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

if (!($row = Wrong\Models\Crontabs::find($_POST['id']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if (!$user->access()->write($row)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

$sth = $dbh->prepare("UPDATE `crontabs` SET `cli` = :cli WHERE `id` = :id");
$sth->bindValue(':cli', $_POST['cli']);
$sth->bindValue(':id', $_POST['id']);
$sth->execute();
if ($sth->errorCode() == '00000') {
    $mem = new Wrong\Memory\Cache('cron');
    $mem->delete($row->id);
    exit(json_encode(['result' => 'ok', 'message' => 'Команда обновлена!']));
}

exit(json_encode(['error' => 'Ошибка']));
