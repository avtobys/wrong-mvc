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
     * создает новый объект PDO и сохраняет его в свойстве $dbh
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

    /**
     * Получает экземпляр подключения к базе данных
     *
     * @param bool $ignore_error Если установлено значение true, сценарий будет продолжать выполняться даже в
     * случае сбоя подключения к базе данных.
     * 
     * @return object Connect
     */
    public static function getInstance($ignore_error = false)
    {
        if (self::$instance === null) {
            self::$instance = new self($ignore_error);
        } else {
            try {
                // Попытаемся выполнить произвольный запрос, чтобы проверить подключение
                self::$instance->dbh->query('SELECT 1');
            } catch (\PDOException $e) {
                // Если произошла ошибка, значит соединение утрачено и его нужно восстановить
                self::$instance = new self($ignore_error);
            }
        }

        return self::$instance;
    }

    /**
     * Закрывает соединение с базой данных
     */
    public function close()
    {
        $this->dbh = null;
        self::$instance = null;
    }

    /**
     * Запрещает клонирование экземпляра
     */
    public function __clone()
    {
        throw new \RuntimeException('Clone is not allowed');
    }

    /**
     * Запрещает десериализацию экземпляра
     */
    public function __wakeup()
    {
        throw new \RuntimeException('Deserialization is not allowed');
    }
}
