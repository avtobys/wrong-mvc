<?php

/**
 * @file
 * @brief контроллер управления моделями шаблонов
 * 
 */

namespace Wrong\Models;

use Wrong\Database\Controller;
use Wrong\Database\Connect;
use Wrong\File\Path;

/**
 * 
 * @brief Templates контроллер управления моделями выборок, расширяет Controller
 * 
 */

class Templates extends Controller implements ModelsInterface
{
    /**
     * создает в бд запись для новой модели типа "шаблон" и копирует указанный файл шаблона
     * 
     * @param array $arr массив данных модели
     * 
     * @return int Последний вставленный идентификатор.
     */
    public static function create($arr)
    {
        $dbh = Connect::getInstance()->dbh;
        Path::mkdir($_SERVER['DOCUMENT_ROOT'] . $arr['file']);
        if (copy($_SERVER['DOCUMENT_ROOT'] . '/../templates/' . $arr['type'] . '/system/empty.php', $_SERVER['DOCUMENT_ROOT'] . $arr['file'])) {
            if ($arr['type'] == 'modal') { // запишем имя в modal-title
                $file = new \SplFileObject($_SERVER['DOCUMENT_ROOT'] . $arr['file'], 'a+b');
                $file->flock(LOCK_EX);
                $file->rewind();
                $data = $file->fread($file->getSize());
                $data = preg_replace('#<h5 class="modal-title">[^<>]*</h5>#', '<h5 class="modal-title">' . $arr['name'] . '</h5>', $data);
                $file->ftruncate(0);
                $file->fwrite($data);
                $file->flock(LOCK_UN);
            }
            $sth = $dbh->prepare("INSERT INTO `templates` (`file`, `groups`, `owner_group`, `name`, `type`) VALUES (:file, :groups, :owner_group, :name, :type)");
            $arr['groups'] = json_encode($arr['groups']);
            $sth->bindValue(':file', $arr['file']);
            $sth->bindValue(':groups', $arr['groups']);
            $sth->bindValue(':owner_group', $arr['owner_group']);
            $sth->bindValue(':name', $arr['name']);
            $sth->bindValue(':type', $arr['type']);
            $sth->execute();
            return $dbh->lastInsertId();
        } else {
            Path::rmdir($_SERVER['DOCUMENT_ROOT'] . $arr['file']);
        }
    }

    /**
     * доступные шаблоны это шаблоны на которые у пользователя есть права read
     * 
     * @param mixed $value значение для поиска в столбце
     * @param string $column столбец для поиска значения
     * @param string $table имя таблицы
     * 
     * @return array Массив объектов.
     */
    public static function all_available($value = '', $column = 'id', $table = '')
    {
        global $user;
        $dbh = Connect::getInstance()->dbh;
        if (empty(self::$tables)) {
            self::set_tables();
        }
        $table = $table ?: self::table(get_called_class());
        if (!in_array($table, self::$tables)) return;
        if ($value) {
            $sth = $dbh->prepare("SELECT * FROM `$table` WHERE `$column` = :value");
            $sth->bindValue(':value', $value);
        } else {
            $sth = $dbh->prepare("SELECT * FROM `$table`");
        }
        $sth->execute();
        $arr = $sth->fetchAll();
        return array_filter($arr, function ($row) use ($user) {
            return $user->access()->read($row);
        });
    }
}
