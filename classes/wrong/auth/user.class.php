<?php

/**
 * @file
 * @brief работа с данными пользователя
 * 
 */

namespace Wrong\Auth;

use Wrong\Rights\Group;
use Wrong\Start\Env;

/**
 * @brief User отвечает за работу с данными пользователя
 * 
 */
class User
{
    /** User ID пользователя, уникальный идентификатор */
    public $id;

    /** все активные группы в которых находится пользователь */
    public $groups = [0];

    /** группа с максимальным весом в которой находится пользователь */
    public $main_group_id = 0;

    /** все "подчинененные" по весу группы кроме гостей и системы */
    public $subordinate_groups = [];

    /** вес пользователя, максимальный вес из групп в которых он находится */
    public $weight = 0;

    /** максимальный подчиненный вес кроме собственного и системного */
    public $weight_subordinate = 0;

    /** доступные каталоги для записи из групп(все подчиненные группы кроме гостей) */
    public $writeble_paths = [];

    /** boolean - включена ли запись логов */
    public $write_log_actions = false;

    /** boolean - пользователь авторизован по апи */
    public $is_api = false;


    /**
     * конструктор для класса User
     * 
     * @param int $id ID пользователя
     */
    public function __construct($id)
    {
        global $request;
        if ($user = self::get($id)) {
            if (!$user->act && !empty($_SESSION['user_id'])) {
                $request = '/disabled';
                return;
            }
            $this->groups = '[0]';
            foreach ($user as $prop => $value) {
                $this->$prop = $value;
            }
            $this->groups = array_filter(json_decode($this->groups, true), function ($id) {
                return Group::is_active($id);
            }) ?: [0];
            $this->weight = Group::max_weight($this->groups);
            $this->subordinate_groups = array_column(array_filter(Group::$groups_owners, function ($row) {
                return ($row->weight < $this->weight || in_array($row->id, $this->groups)) && $row->id != 1;
            }), 'id') ?: [];
            $this->main_group_id = Group::max_weight_group($this->groups);
            $this->writeble_paths = array_column(array_filter(Group::$groups_owners, function ($row) {
                return ($row->weight < $this->weight || in_array($row->id, $this->groups));
            }), 'path') ?: [];
            $this->weight_subordinate = max(array_column(array_filter(Group::$groups_owners, function ($row) {
                return $row->weight <= $this->weight && $row->id != 1;
            }), 'weight')) - 1;
            $this->write_log_actions = max(array_column(array_filter(Group::$groups_owners, function ($row) {
                return in_array($row->id, $this->groups);
            }), 'logs'));
        }
    }


    /**
     * берет текстовый пароль, хэширует его и обновляет пароль пользователя в базе данных.
     * 
     * @param string $password Пароль для установки.
     * 
     * @return int Количество строк, затронутых запросом.
     */
    public function set_password($password)
    {
        global $dbh;
        if (!$this->id) return;
        $password = trim($password);
        $sth = $dbh->prepare("UPDATE `users` SET `md5password` = :md5password WHERE `id` = :id");
        $sth->bindValue(':md5password', md5($password));
        $sth->bindValue(':id', $this->id);
        $sth->execute();
        $this->md5password = md5($password);
        return $sth->rowCount();
    }

    /**
     * обновляет адрес электронной почты пользователя в базе данных.
     * 
     * @param string $email Адрес электронной почты для установки.
     * 
     * @return int Количество строк, затронутых запросом.
     */
    public function set_email($email)
    {
        global $dbh;
        if (!$this->id) return;
        $email = mb_strtolower(trim($email), 'utf-8');
        $sth = $dbh->prepare("UPDATE `users` SET `email` = ? WHERE `id` = ?");
        $sth->bindValue(1, $email);
        $sth->bindValue(2, $this->id);
        $sth->execute();
        $this->email = $email;
        return $sth->rowCount();
    }

    /**
     * обновляет время онлайн пользователя и его текущий ip адрес
     */
    public function set_online()
    {
        global $dbh;
        if (!$this->id) {
            self::session_reset();
            return;
        }
        if (!empty($_COOKIE['FROM_UID'])) return;
        $sth = $dbh->prepare("UPDATE `users` SET `date_online` = NOW(), `ip` = :ip WHERE `id` = :id AND `act` = 1");
        $sth->bindValue(':ip', Env::$e->IP);
        $sth->bindValue(':id', $this->id);
        $sth->execute();
    }

    /**
     * записывает крайний запрос пользователя в поле
     * 
     * @param string request
     * 
     */
    public function set_request($request)
    {
        global $dbh;
        if (!$this->id || in_array($request, ['/disabled', '/forbidden'])) {
            return;
        }
        if (!empty($_COOKIE['FROM_UID'])) return;
        $sth = $dbh->prepare("UPDATE `users` SET `request` = :request WHERE `id` = :id AND `act` = 1");
        $sth->bindValue(':request', $request);
        $sth->bindValue(':id', $this->id);
        $sth->execute();
    }

    /**
     * принимает идентификатор и возвращает пользовательский объект
     * 
     * @param int $id Идентификатор пользователя
     * 
     * @return object строки из бд для пользователя.
     */
    public static function get($id)
    {
        global $dbh;
        if (!$id) return;
        $sth = $dbh->prepare("SELECT * FROM `users` WHERE `id` = ?");
        $sth->execute([$id]);
        return $sth->fetch();
    }

    /**
     * сбрасывает сеанс авторизованного пользователя
     * 
     */
    public static function session_reset()
    {
        if (!empty($_COOKIE['FROM_UID'])) {
            setcookie('FROM_UID', 0, [
                'expires' => time() - 31536000,
                'path' => '/',
                'domain' => $_SERVER['HTTP_HOST'],
                'secure' => Env::$e->IS_SECURE,
                'httponly' => false,
                'samesite' => Env::$e->IS_SECURE ? 'None' : 'Lax'
            ]) or setcookie('FROM_UID', 0, time() - 31536000, '/', $_SERVER['HTTP_HOST'], Env::$e->IS_SECURE);
            return;
        }
        setcookie('UID', 0, [
            'expires' => time() - 31536000,
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'],
            'secure' => Env::$e->IS_SECURE,
            'httponly' => false,
            'samesite' => Env::$e->IS_SECURE ? 'None' : 'Lax'
        ]) or setcookie('UID', 0, time() - 31536000, '/', $_SERVER['HTTP_HOST'], Env::$e->IS_SECURE);
        $_COOKIE['UID'] = 0;
        $_SESSION['user_id'] = 0;
    }

    /**
     * устанавливает переменную сессии пользователя из инициализирующего id, либо устанавливает сессию по cookie UID - зашифрованный идентификатор, либо сбрасывает сессию
     * 
     * @param int $init_id инициализирующий идентификатор пользователя, для которого устанавливается сеанс.
     * 
     * @return int $id Идентификатор пользователя.
     */
    public static function session($init_id = 0)
    {
        if (!empty($_COOKIE['FROM_UID']) && ($id = Crypt::idDecrypt($_COOKIE['FROM_UID']))) {
            return $id;
        }
        if ($init_id) {
            $_SESSION['user_id'] = $init_id;
        }
        if (!empty($_SESSION['user_id'])) {
            $id = $_SESSION['user_id'];
            if (empty($_COOKIE['UID'])) {
                $uid = Crypt::idEncrypt($id);
                setcookie('UID', $uid, [
                    'expires' => time() + 31536000,
                    'path' => '/',
                    'domain' => $_SERVER['HTTP_HOST'],
                    'secure' => Env::$e->IS_SECURE,
                    'httponly' => false,
                    'samesite' => Env::$e->IS_SECURE ? 'None' : 'Lax'
                ]) or setcookie('UID', $uid, time() + 31536000, '/', $_SERVER['HTTP_HOST'], Env::$e->IS_SECURE);
                $_COOKIE['UID'] = $uid;
            }
        } elseif (!empty($_COOKIE['UID'])) {
            $id = Crypt::idDecrypt($_COOKIE['UID']);
            $_SESSION['user_id'] = (int) $id;
        } else {
            self::session_reset();
            $id = 0;
        }
        return $id;
    }


    /**
     * принимает адрес электронной почты в качестве аргумента и возвращает объект пользователя, если адрес электронной почты найден в базе данных.
     * 
     * @param string $email Адрес электронной почты для соответствия.
     * 
     * @return object объект пользователя из бд совпадающий с данной почтой.
     */
    public static function match($email)
    {
        global $dbh;
        $email = trim($email);
        if (empty($email)) {
            return;
        }
        $sth = $dbh->prepare("SELECT * FROM `users` WHERE `email` = ?");
        $sth->execute([$email]);
        return $sth->fetch();
    }

    /**
     * Если пользователь существует и хэш md5 адреса электронной почты и пароля совпадает с переданным хэшем md5, вернуть объект пользователя. Используется при восстановлении пароля пользователя
     * 
     * @param int $id Идентификатор пользователя
     * @param string $md5 Хэш md5 электронной почты и пароля пользователя.
     * 
     * @return object|null Пользовательский объект
     */
    public static function is_remind($id, $md5)
    {
        if (($user = self::get($id)) && $md5 == md5($user->email . $user->md5password)) {
            return $user;
        }
        return;
    }

    /**
     * Если пользователь существует и хэш md5 адреса электронной почты и пароля совпадает с переданным хэшем md5, вернуть объект пользователя. Используется при подтверждении email пользователя
     * 
     * @param int $id Идентификатор пользователя
     * @param string $md5 Хэш md5 электронной почты и пароля пользователя.
     * 
     * @return object|null Пользовательский объект в случае успешной проверки
     */
    public static function is_confirm($id, $md5)
    {
        if (($user = self::get($id)) && $md5 == md5($user->email . $user->md5password)) {
            return $user;
        }
        return;
    }

    /**
     * устанавливает для поля email_confirmed в таблице пользователей значение 1. Подтверждение почты.
     * 
     * @param int $email_confirmed 1 = подтверждено, 0 = не подтверждено
     */
    public function set_confirm($email_confirmed = 1)
    {
        global $dbh;
        $dbh->query("UPDATE `users` SET `email_confirmed` = $email_confirmed WHERE `id` = $this->id");
        $this->email_confirmed = $email_confirmed;
    }
}
