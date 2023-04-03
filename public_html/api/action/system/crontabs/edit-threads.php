<?php

/**
 * @file
 * @brief обработчик настройки потоков и нагрузки для cron задачи
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});

if (!($row = Wrong\Models\Crontabs::find($_POST['id']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if (!in_array($row->owner_group, $user->subordinate_groups)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

if ($_POST['min'] < 1 || $_POST['min'] > 100000 || $_POST['max'] < 1 || $_POST['max'] > 100000 || $_POST['load'] < 1 || $_POST['load'] > 1000) {
    exit(json_encode(['error' => 'Ошибка']));
}

$threads = array_map('intval', ['min' => $_POST['min'], 'max' => $_POST['max'], 'load' => $_POST['load'], 'fixed' => intval(isset($_POST['fixed']))]);

$sth = $dbh->query("UPDATE `crontabs` SET `threads` = '" . json_encode($threads) . "' WHERE `id` = $row->id");

if ($sth->errorCode() == '00000') {
    exit(json_encode(['result' => 'ok', 'message' => 'Данные успешно установлены']));
}

exit(json_encode(['error' => 'Ошибка']));
