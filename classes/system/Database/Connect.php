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

final class Connect
{
    private static $instance = null;

    /**
     * создает новый объект PDO и сохраняет его в статическом свойстве $dbh
     * 
     * @param ignore_error Если установлено значение true, сценарий будет продолжать выполняться даже в
     * случае сбоя подключения к базе данных.
     * 
     * @return object PDO Обработчик базы данных.
     */
    private function __construct($ignore_error = false)
    {
        $dsn = 'mysql:dbname=' . Env::$e->DB_DATABASE . ';host=' . Env::$e->DB_HOST . ';port=' . Env::$e->DB_PORT . ';charset=utf8mb4';
        try {
            $this->dbh = new \PDO($dsn, Env::$e->DB_USERNAME, Env::$e->DB_PASSWORD, [\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ]);
        } catch (\PDOException $e) {
            if (!$ignore_error) {
                exit($e->getMessage());
            }
        }
    }

    public static function getInstance($ignore_error = false)
    {
        if (self::$instance === null) {
            self::$instance = new self($ignore_error);
        }
        return self::$instance;
    }

    /**
     * закрывает соединение с базой данных
     */
    public function close()
    {
        $this->dbh = null;
        self::$instance = null;
    }

    public function __clone()
    {
    }

    public function __wakeup()
    {
    }
}
