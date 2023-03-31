<?php

/**
 * @file
 * @brief подключение к БД
 * 
 */

namespace Wrong\Database;

use Wrong\Start\Env;

/**
 * @brief Connect создает подключение к базе данных
 * 
 */

class Connect
{
    public static $dbh;

    /**
     * создает новый объект PDO и сохраняет его в статическом свойстве $dbh
     * 
     * @param ignore_error Если установлено значение true, сценарий будет продолжать выполняться даже в
     * случае сбоя подключения к базе данных.
     * 
     * @return object PDO Обработчик базы данных.
     */
    public static function start($ignore_error = false)
    {
        $dsn = 'mysql:dbname=' . Env::$e->DB_DATABASE . ';host=' . Env::$e->DB_HOST . ';port=' . Env::$e->DB_PORT . ';charset=utf8mb4';
        try {
            self::$dbh = new \PDO($dsn, Env::$e->DB_USERNAME, Env::$e->DB_PASSWORD, [\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ]);
        } catch (\PDOException $e) {
            if (!$ignore_error) {
                exit($e->getMessage());
            }
        }
        return self::$dbh;
    }

    /**
     * закрывает соединение с базой данных
     */
    public static function close()
    {
        self::$dbh = null;
    }
}
