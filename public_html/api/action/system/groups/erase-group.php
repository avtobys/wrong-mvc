<?php

/**
 * @file
 * @brief обработчик очистки группы от всех моделей
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

set_time_limit(0);

header("Content-type: application/json");

if (!($row = Wrong\Models\Groups::find($_POST['id']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if (!$user->access()->write($row)) {
    exit(json_encode(['error' => 'Недостаточно прав']));
}

if ($user->access()->is_system($row)) {
    exit(json_encode(['error' => 'Системный функционал удалять нельзя!']));
}

Wrong\Rights\Group::delete_all_owner_models($_POST['id']);
exit(json_encode(['id' => $_POST['id'], 'message' => $dbh->query("SHOW TABLE STATUS WHERE Name = 'groups'")->fetch()->Comment . ' - успешно очищено!']));

