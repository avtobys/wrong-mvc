<?php

/**
 * @file
 * @brief обработчик добавления новой группы
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});

if (empty($_POST['name'])) {
    exit(json_encode(['error' => 'Имя группы не указано']));
}

if (in_array($_POST['name'], Wrong\Rights\Group::$group_names)) {
    exit(json_encode(['error' => 'В системе уже есть группа с таким именем']));
}

if (empty($_POST['owner_group']) || !in_array($_POST['owner_group'], $user->subordinate_groups)) {
    exit(json_encode(['error' => '"Группа владелец" не найдена среди подчиненных групп']));
}

if ($_POST['weight'] > $user->weight_subordinate || $_POST['weight'] < 0) {
    exit(json_encode(['error' => 'Системный вес указан некорректно']));
}

if (empty($_POST['path'])) {
    exit(json_encode(['error' => 'Каталог по умолчанию не указан']));
}

if (!preg_match('#^[a-z0-9]+$#', $_POST['path'])) {
    exit(json_encode(['error' => 'Некорректное имя каталога по умолчанию, только символы нижнего регистра и цифры']));
}

if (($row = Wrong\Database\Controller::find($_POST['path'], 'path', 'groups')) && !in_array($row->id, $user->subordinate_groups)) {
    exit(json_encode(['error' => 'Недостаточно прав для использования каталога <b>' . $_POST['path'] . '</b>']));
}

if (($models_limit = Wrong\Database\Controller::find($_POST['owner_group'], 'id', 'groups')->models_limit) && $models_limit <= Wrong\Rights\Group::count_all_owner_models($_POST['owner_group'])) {
    exit(json_encode(['error' => 'Лимит моделей для данной группы исчерпан']));
}

if (Wrong\Models\Groups::create($_POST)) {
    exit(json_encode(['result' => 'ok', 'message' => 'Группа успешно создана']));
}

exit(json_encode(['error' => 'Ошибка']));
