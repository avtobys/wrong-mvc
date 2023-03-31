<?php

/**
 * @file
 * @brief обработчик фильтров
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});

if (!in_array($_POST['table'], Wrong\Database\Controller::$tables)) {
    exit(json_encode(['error' => 'Ошибка']));
}

$table = $_POST['table'];

$_SESSION['filter'] = $_SESSION['filter'] ?? [];
$_SESSION['filter'][$table] = $_SESSION['filter'][$table] ?? [];

if (isset($_POST['reset'])) {
    unset($_SESSION['filter'][$_POST['table']]);
    exit(json_encode(['result' => 'ok', 'message' => 'Фильтр сброшен']));
}

unset($_POST['CSRF']);
unset($_POST['table']);


$_SESSION['filter'][$table] = array_map('array_values', $_POST);

exit(json_encode(['result' => 'ok', 'message' => 'Фильтр обновлён', 'filter' => $_SESSION['filter'][$table]]));
