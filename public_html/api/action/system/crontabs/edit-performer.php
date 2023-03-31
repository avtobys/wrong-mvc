<?php

/**
 * @file
 * @brief обработчик установки исполнителя для cron задачи
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

if (!empty($_POST['user_id'])) {
    $row = Wrong\Auth\User::get($_POST['user_id']);
    if (!$row) {
        exit(json_encode(['error' => 'Исполнитель не найден в системе!']));
    }
    $performer = new Wrong\Auth\User($row->id);
    if ($performer->weight > $user->weight) {
        exit(json_encode(['error' => 'Недостаточно прав для выполнения задач от этого пользователя']));
    }
} else {
    $_POST['user_id'] = 0;
}

if (Wrong\Models\Crontabs::set_performer($_POST)) {
    exit(json_encode(['result' => 'ok', 'message' => 'Исполнитель успешно установлен']));
}

exit(json_encode(['error' => 'Ошибка']));
