<?php

/**
 * @file
 * @brief обработчик смены группы владельца для модели
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

if (!($row = Wrong\Database\Controller::find($_POST['id'], 'id', $_POST['table']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if ($user->access()->is_system($row)) {
    exit(json_encode(['error' => 'Изменить владельца системного функционала нельзя!']));
}

if (!$user->access()->write($row) || !in_array($_POST['owner_group'], $user->subordinate_groups)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

if (($models_limit = Wrong\Database\Controller::find($_POST['owner_group'], 'id', 'groups')->models_limit) && $models_limit <= Wrong\Rights\Group::count_all_owner_models($_POST['owner_group'])) {
    exit(json_encode(['error' => 'Лимит моделей для данной группы исчерпан']));
}

Wrong\Rights\Group::set_owner($_POST['id'], $_POST['owner_group'], $_POST['table']);
exit(json_encode(['result' => 'ok', 'message' => 'Группа владелец установлена']));
