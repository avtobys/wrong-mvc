<?php

/**
 * @file
 * @brief обработчик добавления нового пользователя
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});

if (empty($_POST['email']) || !filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
    exit(json_encode(['error' => 'Email указан некорректно']));
}

if (empty($_POST['password']) || mb_strlen(trim($_POST['password']), 'utf-8') < 5) {
    exit(json_encode(['error' => 'В пароле должно быть минимум 5 символов']));
}

if (Wrong\Auth\User::match($_POST['email'])) {
    exit(json_encode(['error' => 'Пользователь с этим email уже существует']));
}

$_POST['groups'] = array_map('intval', array_values(array_intersect($user->subordinate_groups, empty($_POST['groups']) ? [] : array_keys($_POST['groups']))));

if (empty($_POST['owner_group']) || !in_array($_POST['owner_group'], $user->subordinate_groups)) {
    exit(json_encode(['error' => '"Группа владелец" не найдена среди подчиненных групп']));
}

if (($models_limit = Wrong\Database\Controller::find($_POST['owner_group'], 'id', 'groups')->models_limit) && $models_limit <= Wrong\Rights\Group::count_all_owner_models($_POST['owner_group'])) {
    exit(json_encode(['error' => 'Лимит моделей для данной группы исчерпан']));
}

if (Wrong\Models\Users::create($_POST['email'], $_POST['password'], $_POST['groups'], $_POST['owner_group'])) {
    exit(json_encode(['result' => 'ok', 'message' => 'Пользователь успешно создан']));
}

exit(json_encode(['error' => 'Ошибка']));
