<?php

/**
 * @file
 * @brief обработчик массовое исключение из группы пользователей другой группы
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

set_time_limit(0);

header("Content-type: application/json");

if (!($row = Wrong\Models\Groups::find($_POST['id']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if ($user->access()->is_system($row)) {
    exit(json_encode(['error' => 'Этот функционал недоступен для системных групп!']));
}

if (!$user->access()->write($row)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

if (!($source = Wrong\Models\Groups::find($_POST['source_group'])) || !$user->access()->write($source, true)) {
    exit(json_encode(['error' => 'Ошибка']));
}

foreach ($dbh->query("SELECT * FROM `users` WHERE JSON_CONTAINS(`groups`, $source->id) = 1 AND JSON_CONTAINS(`groups`, $row->id) = 1") as $item) {
    if (in_array($item->owner_group, $user->subordinate_groups)) {
        $arr = json_decode($item->groups, true);
        $key = array_search($source->id, $arr);
        unset($arr[$key]);
        $arr = array_values(array_unique(array_map('intval', $arr)));
        $dbh->query("UPDATE `users` SET `groups` = '" . json_encode($arr) . "' WHERE `id` = $item->id");
    }
}

exit(json_encode(['result' => 'ok', 'message' => 'Пользователи группы <b>' . $row->name . '</b> исключены из группы <b>' . $source->name . '</b>']));
