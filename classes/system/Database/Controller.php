<?php

/**
 * @file
 * @brief контроллер БД запросов
 * 
 */

namespace Wrong\Database;

/**
 * @brief Controller контроллер для запросов к БД
 * 
 */

class Controller
{
    /** Статическое свойство, используемое для хранения имен таблиц из базы данных. */
    public static $tables = [];

    /**
     * принимает значение, столбец и таблицу и возвращает первую строку, которая соответствует
     * значению в столбце
     * 
     * @param string $value Значение для поиска.
     * @param string $column Столбец для поиска значения.
     * @param string $table имя таблицы
     * 
     * @return object строки, которая соответствует значению и столбцу.
     */
    public static function find($value, $column = 'id', $table = '')
    {
        if (empty(self::$tables)) {
            self::set_tables();
        }
        if (!Connect::$dbh) {
            Connect::$dbh = Connect::start();
        }
        $table = $table ?: self::table(get_called_class());
        if (!$table) return;
        if (!in_array($table, self::$tables)) return;
        $sth = Connect::$dbh->prepare("SELECT * FROM `$table` WHERE `$column` = :value");
        $sth->bindValue(':value', $value);
        $sth->execute();
        return $sth->fetch();
    }

    /**
     * Возвращает все строки из таблицы, либо строки соответствующие значению $value для столбца $column
     * 
     * @param string $value Значение для поиска в столбце.
     * @param string $column Столбец для поиска значения.
     * @param string $table Имя таблицы.
     * 
     * @return array объектов всех строк таблицы.
     */
    public static function all($value = '', $column = 'id', $table = '')
    {
        if (empty(self::$tables)) {
            self::set_tables();
        }
        if (!Connect::$dbh) {
            Connect::$dbh = Connect::start();
        }
        $table = $table ?: self::table(get_called_class());
        if (!in_array($table, self::$tables)) return;
        if ($value !== '') {
            $sth = Connect::$dbh->prepare("SELECT * FROM `$table` WHERE `$column` = :value");
            $sth->bindValue(':value', $value);
        } else {
            $sth = Connect::$dbh->prepare("SELECT * FROM `$table`");
        }
        $sth->execute();
        return $sth->fetchAll();
    }

    /**
     * возвращает количество строк в таблице, которые соответствуют заданному значению в данном
     * столбце или количество всех строк, если значение $value не указано.
     * 
     * @param $value Значение для поиска.
     * @param $column Столбец для поиска значения.
     * @param $table Имя таблицы.
     * 
     * @return int Количество строк в таблице.
     */
    public static function count($value = '', $column = 'id', $table = '', $where = '')
    {
        if (empty(self::$tables)) {
            self::set_tables();
        }
        if (!Connect::$dbh) {
            Connect::$dbh = Connect::start();
        }
        $table = $table ?: self::table(get_called_class());
        if (!in_array($table, self::$tables)) return;
        if ($value !== '') {
            $sth = Connect::$dbh->prepare("SELECT COUNT(*) FROM `$table` WHERE `$column` = :value $where");
            $sth->bindValue(':value', $value);
        } else {
            $sth = Connect::$dbh->prepare("SELECT COUNT(*) FROM `$table` $where");
        }
        $sth->execute();
        return $sth->fetchColumn();
    }

    /**
     * принимает идентификатор, имя таблицы, переключает столбец act и возвращает строку с идентификатором из таблицы с
     * переключенным столбцом действия.
     * 
     * @param int $id идентификатор строки, которую вы хотите переключить
     * @param int $table имя таблицы
     * 
     * @return object строки из таблицы соответствующий $id.
     */
    public static function toggle($id, $table)
    {
        if (!Connect::$dbh) {
            Connect::$dbh = Connect::start();
        }
        $sth = Connect::$dbh->prepare("UPDATE `$table` SET `act` = IF (`act` = 1, 0, 1) WHERE `id` = :id");
        $sth->bindValue(':id', $id);
        $sth->execute();
        if ($sth->rowCount()) {
            return self::find($id, 'id', $table);
        }
    }

    /**
     * берет имя класса, преобразует его в имя таблицы и возвращает имя таблицы, если оно существует
     * в базе данных.
     * 
     * @param string class Имя класса модели.
     * 
     * @return string Имя таблицы класса.
     */
    public static function table($class)
    {
        if (empty(self::$tables)) {
            self::set_tables();
        }

        $table = strtolower(basename(str_replace('\\', '/', $class)));

        if (!in_array($table, self::$tables)) {
            return;
        }

        return $table;
    }

    /**
     * получает все таблицы в базе данных и сохраняет их в массиве статического свойства self::$tables
     */
    public static function set_tables()
    {
        global $dbh;
        if (!$dbh) {
            $dbh = Connect::$dbh = Connect::start();
        }
        self::$tables = $dbh->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * возвращает basename request запроса по id строки
     * 
     * @param int $id Идентификатор, basename request для которого вы хотите получить.
     * 
     * @return string basename запроса.
     */
    public static function name($id)
    {
        return basename(self::find($id)->request);
    }
}
