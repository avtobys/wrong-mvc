<?php

/**
 * @file
 * @brief контроллер управления моделями крон задач
 * 
 */

namespace Wrong\Models;

use Wrong\Database\Controller;
use Wrong\Database\Connect;

/**
 * @brief Crontabs контроллер управления моделями крон задач, расширяет Controller
 * 
 */

class Crontabs extends Controller
{
    /**
     * создает в бд новую модель типа "крон задача"
     * 
     * @param array $arr массив данных модели
     * 
     * @return int Последний вставленный идентификатор.
     */
    public static function create($arr)
    {
        $sth = Connect::$dbh->prepare("INSERT INTO `crontabs` (`cli`, `request`, `user_id`, `shedule`, `method`, `headers`, `data`, `owner_group`, `note`, `run_at`) VALUES (:cli, :request, :user_id, :shedule, :method, :headers, :data, :owner_group, :note, :run_at)");
        $sth->bindValue(':cli', $arr['cli']);
        $sth->bindValue(':request', $arr['request']);
        $sth->bindValue(':user_id', $arr['user_id']);
        $sth->bindValue(':shedule', $arr['shedule']);
        $sth->bindValue(':method', $arr['method']);
        $sth->bindValue(':headers', $arr['headers']);
        $sth->bindValue(':data', $arr['data']);
        $sth->bindValue(':owner_group', $arr['owner_group']);
        $sth->bindValue(':note', $arr['note']);
        $sth->bindValue(':run_at', $arr['run_at']);
        $sth->execute();
        return Connect::$dbh->lastInsertId();
    }

    /**
     * устанавливает исполнителя для крон задачи
     * 
     * @param array $arr массив с id задачи и пользователя 
     */
    public static function set_performer($arr)
    {
        $sth = Connect::$dbh->prepare("UPDATE `crontabs` SET `user_id` = :user_id WHERE `id` = :id");
        $sth->bindValue(':user_id', $arr['user_id']);
        $sth->bindValue(':id', $arr['id']);
        $sth->execute();
        if ($sth->errorCode() == '00000') {
            return true;
        }
    }
}
