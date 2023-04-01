<?php

/**
 * @file
 * @brief запуск cron задач
 */


ignore_user_abort(true);
header('Connection: close');
header('Content-Encoding: none');
header('Content-length: 0');
function_exists('fastcgi_finish_request') && fastcgi_finish_request();
error_reporting(0);
set_time_limit(90);

/* отладочные функции */
require '../include/debug.php';

/* автозагрузчик */
require '../vendor/autoload.php';

/* установка переменных среды приложения */
new Wrong\Start\Env();

$dbh = Wrong\Database\Connect::start();

try {
    Wrong\Start\Env::add($dbh->query("SELECT `name`, `value` FROM `settings`")->fetchAll(\PDO::FETCH_KEY_PAIR));
} catch (\Throwable $th) {
    dd($dbh->errorInfo());
}

if (isset($_POST['id']) && $_SERVER['HTTP_X_AUTH_TOKEN'] == Wrong\Start\Env::$e->SYSTEM_SECRET_KEY) { // выполнение каждой отдельной задачи
    Wrong\Task\Cron::execute(Wrong\Models\Crontabs::find($_POST['id']));
    $dbh = null;
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
