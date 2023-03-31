<?php

/**
 * @file
 * @brief обработчик очистки логов действий
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});

$dbh->query("TRUNCATE `logs`");

exit(json_encode(['result' => 'ok', 'message' => 'Логи очищены']));

