<?php

/**
 * @file
 * @brief обработчик устанавливает расписание cron задачи
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});

if (!($row = Wrong\Database\Controller::find($_POST['id'], 'id', $_POST['table']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if (!in_array($row->owner_group, $user->subordinate_groups)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

try {
    $cron = Cron\CronExpression::factory($_POST['shedule']);
} catch (\Throwable $th) {
    exit(json_encode(['error' => $th->getMessage()]));
}

$shedules = [];
for ($i = 0; $i < 25; $i++) {
    $shedules[] = $cron->getNextRunDate(null, $i)->format('Y-m-d H:i:s');
}

if (count($shedules) != 25) {
    exit(json_encode(['error' => 'Расписание указано некорректно']));
}

$_POST['run_at'] = $shedules[0];

$sth = $dbh->prepare("UPDATE `crontabs` SET `shedule` = :shedule, `run_at` = :run_at WHERE `id` = :id");
$sth->bindValue(':shedule', $_POST['shedule']);
$sth->bindValue(':run_at', $shedules[0]);
$sth->bindValue(':id', $row->id);
$sth->execute();

if ($sth->errorCode() == '00000') {
    exit(json_encode(['result' => 'ok', 'message' => 'Расписание успешно установлено']));
}

exit(json_encode(['error' => 'Ошибка']));


