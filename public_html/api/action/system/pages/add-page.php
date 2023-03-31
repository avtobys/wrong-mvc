<?php

/**
 * @file
 * @brief обработчик добавления новой страницы
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});

$_POST['groups'] = array_map('intval', array_values(array_intersect(array_column(Wrong\Rights\Group::$groups_not_system, 'id'), empty($_POST['groups']) ? [] : array_keys($_POST['groups']))));

Wrong\Check\Model::create($_POST, 'pages');

if (!in_array($_POST['template_id'], array_column(Wrong\Models\Templates::all_available(), 'id'))) {
    exit(json_encode(['error' => 'У вас недостаточно прав для использования этого шаблона']));
}

if (Wrong\Models\Pages::create($_POST)) {
    exit(json_encode(['result' => 'ok', 'message' => 'Страница успешно создана']));
}

exit(json_encode(['error' => 'Неизвестная ошибка! Возможно что-то не так с правами на создание файлов и каталогов.']));
