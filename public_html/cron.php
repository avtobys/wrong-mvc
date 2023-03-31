<?php

/**
 * @file
 * @brief запуск cron задач
 */

ignore_user_abort(true);
header('Connection: close');
function_exists('fastcgi_finish_request') && fastcgi_finish_request();
error_reporting(0);
set_time_limit(90);

/* отладочные функции */
require '../include/debug.php';

/* системный автозагрузчик */
require '../classes/autoload.php';

/* установка переменных среды приложения */
new Wrong\Start\Env();

/* автозагрузчик Composer */
require '../vendor/autoload.php';

$dbh = Wrong\Database\Connect::start();
try {
    Wrong\Start\Env::add($dbh->query("SELECT `name`, `value` FROM `settings`")->fetchAll(\PDO::FETCH_KEY_PAIR));
} catch (\Throwable $th) {
    dd($dbh->errorInfo());
}
$dbh = null;

if (!Wrong\Start\Env::$e->CRON_ACT) {
    exit;
}

if (!Wrong\File\Locker::lock(basename(__FILE__, '.php'), 300)) {
    goto exit_cron;
}

Wrong\Task\Cron::load();

sleep(2);
while (date('s') != '00') {
    usleep(400000);
}

Wrong\File\Locker::unlock(basename(__FILE__, '.php'));
Wrong\Curl\API::req('/cron.php?' . mt_rand(), 'GET', '', [], 1);

exit_cron:
