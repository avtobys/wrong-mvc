<?php

/**
 * @file
 * @brief контроллер управления моделями групп
 * 
 */

namespace Wrong\Models;

use Wrong\Database\Controller;
use Wrong\Database\Connect;
use Wrong\Rights\Group;

/**
 * @brief Groups контроллер управления моделями групп пользователей, расширяет Controller
 * 
 */

class Groups extends Controller
{
    /**
     * создает в бд запись для новой модели типа "группа"
     * 
     * @param array $arr массив данных модели
     * @param array $replace_path массив путей для замены в файле и параметры запроса.
     * 
     * @return int Последний вставленный идентификатор.
     */
    public static function create($arr)
    {
        $sth = Connect::$dbh->prepare("INSERT INTO `groups` (`name`, `owner_group`, `weight`, `path`) VALUES (:name, :owner_group, :weight, :path)");
        $sth->bindValue(':name', $arr['name']);
        $sth->bindValue(':owner_group', $arr['owner_group']);
        $sth->bindValue(':weight', $arr['weight']);
        $sth->bindValue(':path', $arr['path']);
        $sth->execute();

        if ($id = Connect::$dbh->lastInsertId()) {
            if (!empty($arr['add-groups'])) {
                /** Если указано $arr['add-groups'], то при создании новой группы добавляет ее в поле groups всех записей
                 * в таблицах actions, modals, selects, pages, users, если их группы доступа "все" или "все авторизованные" */
                foreach (['actions', 'modals', 'selects', 'pages', 'users', 'templates'] as $table) {
                    foreach (Connect::$dbh->query("SELECT * FROM `$table`") as $row) {
                        $array = json_decode($row->groups);
                        if (Group::is_users($array) || Group::is_not_system($array)) {
                            $array[] = $id;
                            $array = array_values(array_unique(array_map('intval', $array)));
                            Connect::$dbh->query("UPDATE `$table` SET `groups` = '" . json_encode($array) . "' WHERE `id` = $row->id");
                        }
                    }
                }
            }
            if (!empty($arr['add-groups-owner'])) {
                /** Если указано $arr['add-groups-owner'], то при создании новой группы добавляет ее в поле groups всех записей
                 * в таблицах actions, modals, selects, pages, users, если они доступны владельцу новой группы */
                foreach (['actions', 'modals', 'selects', 'pages', 'users', 'templates'] as $table) {
                    foreach (Connect::$dbh->query("SELECT * FROM `$table`") as $row) {
                        $array = json_decode($row->groups);
                        if (in_array($arr['owner_group'], $array) || $arr['owner_group'] == $row->owner_group) {
                            $array[] = $id;
                            $array = array_values(array_unique(array_map('intval', $array)));
                            Connect::$dbh->query("UPDATE `$table` SET `groups` = '" . json_encode($array) . "' WHERE `id` = $row->id");
                        }
                    }
                }
            }
            return $id;
        }
    }
}
