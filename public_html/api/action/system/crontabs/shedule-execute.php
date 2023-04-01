<?php

/**
 * @file
 * @brief обработчик выполнения cron задачи по кнокпе из таблицы
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

if (!($row = Wrong\Models\Crontabs::find($_POST['id']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if (!in_array($row->owner_group, $user->subordinate_groups)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

Wrong\Task\Cron::execute($row);

exit(json_encode(['result' => 'ok', 'message' => 'Задача выполнена!']));
