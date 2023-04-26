<?php

/**
 * @file
 * @brief обработчик выполнения cron задачи по кнокпе из таблицы
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

set_time_limit(60);

header("Content-type: application/json");

if (!($row = Wrong\Models\Crontabs::find($_POST['id']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if (!$user->access()->write($row)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

if ($row->method == 'CLI' && !Wrong\Start\Env::$e->CRON_CLI) {
    exit(json_encode(['error' => 'Поддержка CLI команд отключена в системных настройках!']));
}

Wrong\Task\Stackjs::add('successToast("Задача выполняется...");', 0);
session_write_close();

$cmd = 'php -f ' . dirname(__DIR__, 4) . '/cron.php ' . $row->id . ' ' . microtime(true);
exec('(' . $cmd . ' &) > /dev/null 2>&1');

for ($i = 1; $i <= 33; $i++) {
    usleep(100000 * $i);
    if (intval(shell_exec("ps aux | grep '" . addcslashes($cmd, '.') . "' | wc -l")) == 0) {
        exit(json_encode(['result' => 'ok', 'message' => 'Задача выполнена!']));
    }
}

exit(json_encode(['result' => 'ok', 'message' => 'Задача всё ещё выполняется, следите за её потоками.']));
