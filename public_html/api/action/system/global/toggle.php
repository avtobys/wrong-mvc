<?php

/**
 * @file
 * @brief обработчик включения/отключения моделей
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

$_POST = array_map(function ($item) {
    if (is_string($item)) {
        return trim($item);
    } else {
        return $item;
    }
}, $_POST);

if (!($row = Wrong\Database\Controller::find($_POST['id'], 'id', $_POST['table']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

$id = abs(intval($_POST['id']));

if (in_array($_POST['table'], ['users', 'groups']) && $row->owner_group == 1) {
    exit(json_encode(['error' => 'Системных пользователей и группы отключать нельзя!']));
}

if (!isset($row->owner_group) || !isset($row->act) || (!in_array($row->owner_group, $user->groups) && !in_array($row->owner_group, $user->subordinate_groups))) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

Wrong\Task\Cron::set_run_at();

$act = intval(Wrong\Database\Controller::toggle($id, $_POST['table'])->act);
$message = $act ? 'Функционал включен' : 'Функционал отключен. Группа - владелец <b>' . Wrong\Rights\Group::text($row->owner_group) . '</b> по прежнему имеет доступ';

exit(json_encode(['id' => $id, 'act' => $act, 'table' => $_POST['table'], 'message' => $message]));
