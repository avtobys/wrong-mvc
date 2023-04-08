<?php

/**
 * @file
 * @brief обработчик установки групп доступа для модели
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

if (!($row = Wrong\Database\Controller::find($_POST['id'], 'id', $_POST['table']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if (!$user->access()->write($row, true)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

if ($_POST['table'] == 'users') {
    $groups = array_map('intval', array_values(array_intersect($user->subordinate_groups, empty($_POST['groups']) ? [] : array_keys($_POST['groups']))));
} else {
    $groups = array_map('intval', array_values(array_intersect(array_column(Wrong\Rights\Group::$groups_not_system, 'id'), empty($_POST['groups']) ? [] : array_keys($_POST['groups']))));
}

if (in_array(1, json_decode($row->groups)) && !in_array(1, array_keys($_POST['groups']))) {
    $groups[] = 1; // если система ранее была в группе то добавляем
}

Wrong\Rights\Group::set_groups($_POST['id'], $groups, $_POST['table']);
exit(json_encode(['result' => 'ok', 'message' => 'Группы доступа установлены']));
