<?php

/**
 * @file
 * @brief обработчик включения/отключения моделей
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

if (!($row = Wrong\Database\Controller::find($_POST['id'], 'id', $_POST['table']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if (in_array($_POST['table'], ['users', 'groups']) && $user->access()->is_system($row)) {
    exit(json_encode(['error' => 'Системных пользователей и группы отключать нельзя!']));
}

if (!$user->access()->write($row)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

if ($_POST['table'] == 'crontabs') {
    $mem = new Wrong\Memory\Cache('cron');
    $mem->delete($row->id);
    Wrong\Task\Cron::set_run_at();
}

$act = intval(Wrong\Database\Controller::toggle($_POST['id'], $_POST['table'])->act);
$message = $act ? 'Функционал включен' : 'Функционал отключен. Группа - владелец <b>' . Wrong\Rights\Group::text($row->owner_group) . '</b> по прежнему имеет доступ';

exit(json_encode(['id' => $_POST['id'], 'act' => $act, 'table' => $_POST['table'], 'message' => $message]));
