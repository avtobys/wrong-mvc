<?php

/**
 * @file
 * @brief обработчик добавления новой cron задачи
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

$cli = $_POST['cli'];
array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});
$_POST['cli'] = $cli;

if (empty($_POST['owner_group']) || !in_array($_POST['owner_group'], $user->subordinate_groups)) {
    exit(json_encode(['error' => '"Группа владелец" не найдена среди подчиненных групп']));
}

if (($models_limit = Wrong\Database\Controller::find($_POST['owner_group'], 'id', 'groups')->models_limit) && $models_limit <= Wrong\Rights\Group::count_all_owner_models($_POST['owner_group'])) {
    exit(json_encode(['error' => 'Лимит моделей для данной группы исчерпан']));
}

if (empty($_POST['cli']) && (empty($_POST['request']) || !preg_match('#^/[a-z0-9]*#i', $_POST['request']))) {
    exit(json_encode(['error' => 'Неверный формат для "Запрос"']));
}

if (!empty($_POST['user_id'])) {
    $row = Wrong\Auth\User::get($_POST['user_id']);
    if (!$row) {
        exit(json_encode(['error' => 'Исполнитель не найден в системе!']));
    }
    $performer = new Wrong\Auth\User($row->id);
    if ($performer->weight > $user->weight) {
        exit(json_encode(['error' => 'Недостаточно прав для выполнения задач от этого пользователя']));
    }
} else {
    $_POST['user_id'] = 0;
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

if (empty($_POST['method']) || !in_array($_POST['method'], ['GET', 'POST', 'PUT', 'DELETE', 'CLI'])) {
    exit(json_encode(['error' => 'Метод запроса указан некорректно']));
}

if ($_POST['method'] == 'CLI') {
    $_POST['headers'] = $_POST['request'] = '';
}

$_POST['headers'] = array_map('trim', $_POST['headers']);
$_POST['headers'] = array_filter($_POST['headers']);
foreach ($_POST['headers'] as $key => $item) {
    $arr = explode(':', $item, 2);
    $_POST['headers'][$key] = trim($arr[0]) . ': ' . trim($arr[1]);
}
$_POST['headers'] = json_encode($_POST['headers']);

$_POST['data'] = array_map('trim', $_POST['data']);
$_POST['data'] = array_filter($_POST['data']);
foreach ($_POST['data'] as $key => $item) {
    $arr = array_map('trim', explode(':', $item, 2));
    $_POST['data'][$arr[0]] = $arr[1];
    unset($_POST['data'][$key]);
}
$_POST['data'] = json_encode($_POST['data']);


if (Wrong\Models\Crontabs::create($_POST)) {
    exit(json_encode(['result' => 'ok', 'message' => 'Успешно']));
}

exit(json_encode(['error' => 'Ошибка']));
