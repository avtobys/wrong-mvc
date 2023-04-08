<?php

/**
 * @file
 * @brief запуск cron задач
 */

ignore_user_abort(true);
error_reporting(0);
set_time_limit(60);

/* отладочные функции */
require __DIR__ . '/../include/debug.php';

/* автозагрузчик */
require __DIR__ . '/../vendor/autoload.php';

/* установка переменных среды приложения */
new Wrong\Start\Env();

if (!Wrong\Start\Env::$e->IS_CLI) {
    header('Connection: close');
    header('Content-Encoding: none');
    header('Content-length: 0');
}
function_exists('fastcgi_finish_request') && fastcgi_finish_request();


$mem = new Wrong\Memory\Cache('env-cron');
if ($arr = $mem->get('env-cron')) {
    Wrong\Start\Env::add($arr);
} else {
    $dbh = Wrong\Database\Connect::start();
    Wrong\Start\Env::add($dbh->query("SELECT `name`, `value` FROM `settings`")->fetchAll(\PDO::FETCH_KEY_PAIR));
    $mem->set('env-cron', array_map('intval', [
        'API' => Wrong\Start\Env::$e->API,
        'CRON_ACT' => Wrong\Start\Env::$e->CRON_ACT,
        'CRON_CLI' => Wrong\Start\Env::$e->CRON_CLI
    ]));
}

if (Wrong\Start\Env::$e->IS_CLI && !empty($argv[1]) && is_numeric($argv[1])) { // выполнение каждой отдельной задачи, консольный запуск
    $mem = new Wrong\Memory\Cache('cron');
    if (!($row = $mem->get($argv[1]))) {
        $row = Wrong\Models\Crontabs::find($argv[1]);
        $mem->set($argv[1], $row);
    }
    Wrong\Task\Cron::execute($row);
    $dbh = null;
    exit('success');
}

if (Wrong\Start\Env::$e->IS_CLI && !empty($argv[1]) && is_string($argv[1]) && is_numeric($argv[2])) { // запуск форков для поддержания потоков
    $mem = new Wrong\Memory\Cache('cron');
    if (!($row = $mem->get($argv[2]))) {
        $row = Wrong\Models\Crontabs::find($argv[2]);
        $mem->set($argv[1], $row);
    }
    Wrong\Task\Cron::fork($row);
    exit;
}

$dbh = null;

if (!Wrong\Start\Env::$e->CRON_ACT) {
    exit;
}

if (!Wrong\File\Locker::lock(basename(__FILE__, '.php'), 300)) { // блокировка потока
    goto exit_cron;
}

Wrong\Task\Cron::load();

sleep(2);
while (date('s') != '00') {
    usleep(400000);
}

Wrong\File\Locker::unlock(basename(__FILE__, '.php')); // разблокировка потока
Wrong\Curl\API::req('/cron.php?' . mt_rand(), 'GET', '', [], 0.001); // автозапуск себя

exit_cron:
