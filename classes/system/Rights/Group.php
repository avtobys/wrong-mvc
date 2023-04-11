<?php

/**
 * @file
 * @brief управление, получение, проверки данных групп пользователей
 * 
 */


namespace Wrong\Rights;

use Wrong\Curl\API;
use Wrong\Database\Controller;
use Wrong\Start\Env;
use Wrong\Database\Connect;
use Wrong\Auth\User;
use Wrong\Models\Groups;

/**
 * @brief Group класс, содержащий статические методы, используемые для управления группами пользователей.
 * 
 */

class Group
{
    /** массив всех групп [id => name] */
    public static $group_names = [];

    /** все группы */
    public static $groups = []; // все группы

    /** все активные(включенные) группы */
    public static $groups_active = [];

    /** все группы кроме системы */
    public static $groups_not_system = [];

    /** все кроме гостей */
    public static $groups_owners = [];

    /** все кроме гостей и системы */
    public static $groups_users = [];

    /**
     * Возвращает минимальный вес группы из массива групп
     * 
     * @param array $arr массив идентификаторов групп
     * 
     * @return int Минимальный вес группы из массива групп.
     */
    public static function min_weight($arr)
    {
        $w = [];
        foreach (self::$groups as $row) {
            if (in_array($row->id, $arr)) {
                $w[] = $row->weight;
            }
        }
        return $w ? min($w) : 0;
    }

    /**
     * Возвращает максимальный вес группы из массива групп
     * 
     * @param array $arr массив идентификаторов групп
     * 
     * @return int Максимальный вес группы из массива групп.
     */
    public static function max_weight($arr)
    {
        $w = [];
        foreach (self::$groups as $row) {
            if (in_array($row->id, $arr)) {
                $w[] = $row->weight;
            }
        }
        return $w ? max($w) : 0;
    }

    /**
     * Принимает массив идентификаторов групп и возвращает идентификатор группы с наибольшим весом.
     * 
     * @param array $arr массив идентификаторов групп
     * 
     * @return int Идентификатор группы с наибольшим весом.
     */
    public static function max_weight_group($arr)
    {
        $weight = 0;
        $id = 0;
        foreach (self::$groups as $row) {
            if (in_array($row->id, $arr) && $row->weight > $weight) {
                $weight = $row->weight;
                $id = $row->id;
            }
        }
        return $id;
    }

    /**
     * Принимает строку идентификаторов групп в формате JSON и возвращает список имен групп,
     * разделенных запятыми.
     * 
     * @param string $json_groups json массив имен групп
     * 
     * @return string строка с группами
     */
    public static function groupNamesText($json_groups)
    {
        $arr = json_decode($json_groups, true);

        if (self::is_users($arr)) {
            return 'Все авторизованные';
        }

        if (self::is_not_system($arr)) {
            return 'Все';
        }

        $arr_keys = array_intersect(array_keys(self::$group_names), $arr);

        $text = implode(', ', array_map(function ($key) {
            return self::text($key);
        }, $arr_keys));

        return $text ?: 'Нет групп';
    }

    /**
     * Сортирует массив моделей по минимальному весу групп в каждой модели.
     * Сортировка отдает первыми элементы с наибольшим минимальным весом групп в них
     * @param array $arr массив моделей
     * 
     * @return array отсортированный массив
     */
    public static function weightSort($arr)
    {
        if (count($arr) > 1) {
            foreach ($arr as $key => $row) {
                $arr[$key]->weight = Group::min_weight(json_decode($row->groups, true));
            }
            uasort($arr, function ($a, $b) {
                if ($a->weight < $b->weight) {
                    return 1;
                } else if ($a->weight > $b->weight) {
                    return -1;
                } else {
                    if ($a->owner_group > $b->owner_group) {
                        return 1;
                    } else {
                        return -1;
                    }
                }
            });
        }
        return $arr;
    }

    /** 
     * Проверяет доступность модели для текущего пользователя или если указан id пользователя
     * (юзер состоит в группах доступа модели и она включена либо юзер является владельцем модели)
     * функция не сравнивает наличие прав по весу подчиненных групп, проверяются только группы доступа модели и его владелец
     * @param object $row проверяемая модель
     * @param int $id опционально, если указан проверяет для данного id пользователя
     * 
     * @return bool истинное означает наличие доступа к модели
     */
    public static function is_available_group($row, $id = null)
    {
        global $user;
        if ($id) {
            $user = new User($id);
        }
        return (bool) ((array_intersect($user->groups, json_decode($row->groups, true)) && $row->act) || ($user->id && in_array($row->owner_group, $user->groups)));
    }

    /**
     * получает все группы из базы данных и сохраняет их в статических свойствах класса.
     */
    public static function groups()
    {
        $obj = (object) [
            'id'          => 0,
            'name'        => 'Гости',
            'owner_group' => 1,
            'act'         => 1,
            'weight'      => 0
        ];
        self::$groups[0] = $obj;
        foreach (Connect::$dbh->query("SELECT * FROM `groups` ORDER BY `id` ASC")->fetchAll() as $row) {
            self::$groups[] = $row;
        }
        self::$groups_active = array_filter(self::$groups, function ($row) {
            return $row->act;
        });
        self::$groups_not_system = array_filter(self::$groups, function ($row) {
            return $row->id != 1;
        });
        self::$groups_owners = array_filter(self::$groups, function ($row) {
            return $row->id != 0;
        });
        self::$groups_users = array_filter(self::$groups, function ($row) {
            return !in_array($row->id, [0, 1]);
        });
        self::$group_names = array_column(self::$groups, 'name', 'id');
    }

    /**
     * Возвращает true, если идентификатор группы находится в массиве идентификаторов активных групп.
     * 
     * @param int $id Идентификатор группы.
     * 
     * @return bool Логическиое исттинное если id группы найден
     */
    public static function is_active($id)
    {
        return in_array($id, array_column(self::$groups_active, 'id'));
    }

    /**
     * Возвращает название группы с заданным идентификатором или «В процессе удаления», если группа не
     * существует.
     * 
     * @param int $id Идентификатор группы.
     */
    public static function text($id)
    {
        return (array_column(self::$groups, 'name', 'id')[$id] ?: 'В процессе удаления') . ' [' . $id . ']';
    }

    /**
     * Перебирает массив объектов всех групп и возвращает объект с тем же идентификатором, что и переданный в id
     * 
     * @param int $id Идентификатор группы, из которой вы хотите получить строку.
     * 
     * @return object объект группы с переданным идентификатором.
     */
    public static function row($id)
    {
        foreach (self::$groups as $row) {
            if ($row->id == $id) {
                return $row;
            }
        }
    }

    /**
     * прверяет соответствует ли переданный массив групп, всем группам за исключением гостей и системы
     * 
     * @param array $arr Массив идентификаторов групп для проверки
     * 
     * @return bool|null Истинное означает успешную проверку
     */
    public static function is_users($arr)
    {
        if (count($arr) == count(self::$groups_users) && !array_diff(array_column(self::$groups_users, 'id'), $arr)) {
            return true;
        }
    }

    /**
     * прверяет соответствует ли переданный массив групп, всем группам за исключением системы
     * 
     * @param array $arr Массив идентификаторов групп для проверки
     * 
     * @return bool|null Истинное означает успешную проверку
     */
    public static function is_not_system($arr)
    {
        if (count($arr) == count(self::$groups_not_system) && !array_diff(array_column(self::$groups_not_system, 'id'), $arr)) {
            return true;
        }
    }


    /**
     * обновляет в бд группы доступа для объекта
     * 
     * @param int $id Идентификатор обновляемой строки
     * @param array $arr Массив групп для установки.
     * @param string $table_name Имя обновляемой таблицы
     * 
     * @return int Количество строк, затронутых запросом.
     */
    public static function set_groups($id, $arr, $table_name)
    {
        $sth = Connect::$dbh->prepare("UPDATE {$table_name} SET `groups` = :groups WHERE `id` = :id");
        $sth->bindValue(':groups', json_encode($arr));
        $sth->bindValue(':id', $id);
        $sth->execute();
        return $sth->rowCount();
    }

    /**
     * обновляет идентификатор группы владельца для модели
     * 
     * @param int $id Идентификатор обновляемой модели
     * @param int $owner_group Идентификатор группы - владельца
     * @param string $table_name Имя обновляемой таблицы
     * 
     * @return int Количество строк, затронутых запросом.
     */
    public static function set_owner($id, $owner_group, $table_name)
    {
        $sth = Connect::$dbh->prepare("UPDATE {$table_name} SET `owner_group` = :owner_group WHERE `id` = :id");
        $sth->bindValue(':owner_group', $owner_group);
        $sth->bindValue(':id', $id);
        $sth->execute();
        return $sth->rowCount();
    }

    /**
     * Удаляет все модели, принадлежащие группе
     * 
     * @param int $id Идентификатор группы, которую нужно удалить.
     */
    public static function delete_all_owner_models($id)
    {
        global $user;
        $tables = ['actions', 'modals', 'selects', 'templates', 'pages', 'users', 'groups', 'crontabs'];
        if (Env::$e->API && $user->api_act && $user->x_auth_token) {
            $headers = ['X-Auth-Token: ' . $user->x_auth_token];
        } else {
            $headers = ['Cookie: ' . session_name() . '=' . session_id()];
        }
        foreach ($tables as $table) {
            foreach (Controller::all($id, 'owner_group', $table) as $row) {
                $data = http_build_query(['table' => $table, 'id' => $row->id, 'CSRF' => Env::$e->CSRF]);
                session_write_close();
                $res = API::req('/api/action/rm', 'POST', $data, $headers);
            }
        }
    }

    /**
     * подсчитывает количество записей в базе данных(моделей), принадлежащих определенной группе.
     * 
     * @param int $id Идентификатор группы, для которой будут подсчитаны модели.
     * @param bool $is_active Опциональная булевая для подсчета только активных моделей 
     * 
     * @return int Количество моделей, принадлежащих группе с данным идентификатором.
     */
    public static function count_all_owner_models($id, $is_active = false)
    {
        $tables = ['actions', 'modals', 'selects', 'templates', 'pages', 'users', 'groups', 'crontabs'];
        $count = 0;
        foreach ($tables as $table) {
            $count += Controller::count($id, 'owner_group', $table, ($is_active ? 'AND `act` = 1' : ''));
        }
        return $count;
    }

    /**
     * подсчитывает количество записей в базе данных(моделей), с доступами определенной группы.
     * 
     * @param int $id Идентификатор группы, для которой будут подсчитаны модели.
     * 
     * @return int Количество моделей, в группах доступа которых есть данный идентификатор.
     */
    public static function count_all_available_models($id)
    {
        global $dbh;
        $tables = ['actions', 'modals', 'selects', 'templates', 'pages', 'users'];
        $count = 0;
        foreach ($tables as $table) {
            foreach ($dbh->query("SELECT * FROM $table WHERE `owner_group` = $id OR `groups` LIKE '%$id%'") as $row) {
                $count += (int) ($row->owner_group == $id || in_array($id, json_decode($row->groups)));
            }
        }
        $count += Groups::count($id, 'owner_group');
        return $count;
    }

    /**
     * Проверяет, принадлежит ли файл только одной группе или используется другими группами. Важно при удалении файлов группы.
     * 
     * @param string $file Имя файла, который вы хотите проверить.
     * 
     * @return bool Истинное означает что файл принадлежит лишь одной группе
     */
    public static function is_one_owner_file($file)
    {
        $tables = ['actions', 'modals', 'selects', 'templates', 'pages'];
        $owners = [];
        foreach ($tables as $table) {
            $owners[] = Connect::$dbh->query("SELECT `owner_group` FROM `$table` WHERE `file` = '$file' LIMIT 1")->fetchColumn();
        }
        $owners = array_unique(array_filter($owners));
        return count($owners) > 1 ? false : true;
    }
}
