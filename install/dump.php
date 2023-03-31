<?php

/**
 * @file
 * @brief заливает дамп бд при установке системы
 */

use Wrong\Start\Env;
use Wrong\File\Locker;

if (!Locker::lock(basename(__FILE__, '.php'))) {
    dd("Не удалось получить доступ к файлам блокировки\nУдалите вручную файлы:\n\n" . dirname(__DIR__) . "/temp/lock-dump.lock\n" . dirname(__DIR__) . "/temp/lock-install.lock\n\nи попробуйте <a href=\"#step-2\">заново</a>");
}

if (!$dbh->query("SHOW TABLES")->fetchAll()) {
    try {
        exec('mysql ' . Env::$e->DB_DATABASE . ' --user=' . Env::$e->DB_USERNAME . ' --password=' . Env::$e->DB_PASSWORD . ' --port=' . Env::$e->DB_PORT  . ' --host=' . Env::$e->DB_HOST . ' --default-character-set=utf8 < ' . __DIR__ . '/wrongmvc.sql', $output, $result_code);
        if ($result_code) {
            throw new \Error('Dump failed');
        }
    } catch (\Throwable $th) {
        dd($th);
    }

    Locker::unlock(basename(__FILE__, '.php'));
}
