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

if (!($row = Wrong\Models\Crontabs::find($_POST['id']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if (!$user->access()->write($row)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

if ($row->method == 'CLI') {
    exit(json_encode(['error' => 'У CLI задач нельзя менять исполнителя!']));
}

if (!empty($_POST['user_id'])) {
    $row = Wrong\Auth\User::get($_POST['user_id']);
    if (!$row) {
        exit(json_encode(['error' => 'Исполнитель не найден в системе!']));
    }
    if (!$user->access()->write($row, true)) {
        exit(json_encode(['error' => 'Недостаточно прав для выполнения задач от этого пользователя']));
    }
} else {
    $_POST['user_id'] = 0;
}

if (Wrong\Models\Crontabs::set_performer($_POST)) {
    $mem = new Wrong\Memory\Cache('cron');
    $mem->delete($row->id);
    exit(json_encode(['result' => 'ok', 'message' => 'Исполнитель успешно установлен']));
}

exit(json_encode(['error' => 'Ошибка']));
