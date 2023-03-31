<?php

/**
 * @file
 * @brief обработчик добавления нового шаблона
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});

if (empty($_POST['name'])) {
    $_POST['name'] = 'Безымянный';
}

if (empty($_POST['type']) || !in_array($_POST['type'], ['page', 'modal'])) {
    exit(json_encode(['error' => 'Название шаблона не указано']));
}

$i = 1;
while (Wrong\Models\Templates::find($_POST['name'], 'name')) {
    if (preg_match('#(.+)\(\d+\)$#u', $_POST['name'], $matches)) {
        $_POST['name'] = $matches[1] . '(' . $i . ')';
    } else {
        $_POST['name'] = $_POST['name'] . '(1)';
    }
    $i++;
}

$_POST['groups'] = array_map('intval', array_values(array_intersect(array_column(Wrong\Rights\Group::$groups_not_system, 'id'), empty($_POST['groups']) ? [] : array_keys($_POST['groups']))));

Wrong\Check\Model::create($_POST, 'templates');

if (Wrong\Models\Templates::create($_POST)) {
    exit(json_encode(['result' => 'ok', 'message' => 'Шаблон успешно создан']));
}

exit(json_encode(['error' => 'Неизвестная ошибка! Возможно что-то не так с правами на создание файлов и каталогов.']));
