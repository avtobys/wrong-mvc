<?php

/**
 * @file
 * @brief контроллер управления моделями пользователей
 * 
 */

namespace Wrong\Models;

use Wrong\Database\Controller;
use Wrong\Start\Env;

/**
 * @brief Groups контроллер управления моделями пользователей, расширяет Controller
 * 
 */

class Users extends Controller implements ModelsInterface
{
    /**
     * создает нового пользователя в базе данных
     * 
     * @param string $email Электронный адрес пользователя.
     * @param string $password Пароль для пользователя.
     * @param array $groups массив идентификаторов групп, к которым принадлежит пользователь
     * @param int $owner_group Группа, которой принадлежит пользователь.
     * 
     * @return int $id Последний вставленный идентификатор.
     */
    public static function create($email, $password = '', $groups = [], $owner_group = 1)
    {
        global $dbh;
        $email = mb_strtolower(trim($email), 'utf-8');
        $password = trim($password);
        $groups = json_encode($groups);
        if (empty($email) || empty($password)) {
            return;
        }
        $sth = $dbh->prepare("SELECT COUNT(*) FROM `users` WHERE `email` = ?");
        $sth->execute([$email]);
        if ($sth->fetchColumn()) {
            return;
        }
        $sth = $dbh->prepare("INSERT INTO `users` (`groups`, `owner_group`, `email`, `md5password`, `date_online`, `x_auth_token`, `api_act`, `act`, `ip`) VALUES (:groups, :owner_group, :email, :md5password, '0000-00-00 00:00:00', :x_auth_token, :api_act, :act, :ip)");
        $sth->bindValue(':groups', $groups);
        $sth->bindValue(':owner_group', $owner_group);
        $sth->bindValue(':email', $email);
        $sth->bindValue('md5password', md5($password));
        $sth->bindValue(':x_auth_token', md5($password . $email));
        $sth->bindValue(':api_act', Env::$e->USER_API);
        $sth->bindValue(':act', Env::$e->USER_ACT);
        $sth->bindValue(':ip', Env::$e->IP);
        $sth->execute();
        return $dbh->lastInsertId();
    }
    
}
