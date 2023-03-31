<?php

/**
 * @file
 * @brief действие возвращает список расписания следующих крон задач по id задачи
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});

$_POST['id'] = abs(intval($_POST['id']));


try {
    $cron = Cron\CronExpression::factory($_POST['shedule']);
} catch (\Throwable $th) {
    exit(json_encode(['error' => $th->getMessage(), 'id' => $_POST['id']]));
}


$shedules = [];
for ($i = 0; $i < 25; $i++) {
    $shedules[] = $cron->getNextRunDate(null, $i)->format('Y-m-d H:i:s');
}


exit(json_encode(['result' => 'ok', 'message' => 'Успешно', 'id' => $_POST['id'], 'shedules' => $shedules]));
