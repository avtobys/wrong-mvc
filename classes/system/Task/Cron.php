<?php

/**
 * @file
 * @brief выполение крон задач
 * 
 */

namespace Wrong\Task;

use Wrong\Database\Connect;
use Wrong\Start\Env;
use Wrong\Curl\API;
use Cron\CronExpression;

/**
 * @brief Cron класс управляющий выполнением крон задач
 * 
 */

class Cron
{
    /**
     * проверяет, включено ли выполнение cron, и если это так, получает все задания cron из базы данных,
     * которые должны быть запущены, и запускает их.
     */
    public static function load()
    {

        if (!Env::$e->CRON_ACT) return;
        $dbh = Connect::start();
        $sth = $dbh->query("SELECT * FROM `crontabs` WHERE `run_at` BETWEEN NOW() - INTERVAL 1 YEAR AND NOW() AND `act` = 1");
        while ($row = $sth->fetch()) {
            self::execute($row);
        }

        self::set_run_at();
        $sth = null;
        $dbh = null;
        Connect::close();
    }

    /**
     * выполняет cron задачу
     * 
     * @param object $row объект строки задачи из базы
     * 
     */
    public static function execute($row)
    {

        $x_auth_token = false;
        if ($row->user_id && !($x_auth_token = self::get_token($row->user_id))) return;

        $headers = json_decode($row->headers, true);
        if ($x_auth_token) {
            $headers[] = 'X-Auth-Token: ' . $x_auth_token;
        }

        if (!in_array('Content-Type: application/json; charset=utf-8', $headers)) {
            $data = json_decode($row->data, true);
        } else {
            $data = $row->data;
        }

        API::req($row->request, $row->method, $data, $headers);
    }

    /**
     * возвращает x_auth_token пользователя, если пользователь имеет активную учетную
     * запись с включенным API и имеет x_auth_token.
     * 
     * @param int $user_id Идентификатор пользователя в базе данных.
     * 
     * @return string x_auth_token из пользовательской таблицы.
     */
    private static function get_token($user_id)
    {
        if (!Env::$e->API) return;
        $user = Connect::$dbh->query("SELECT * FROM `users` WHERE `id` = $user_id")->fetch();
        if (!$user->act || !$user->api_act || !$user->x_auth_token) return;
        return $user->x_auth_token;
    }

    /**
     * устанавливает время следующих выполнений для всех крон задач из бд соответственно расписанию для каждой записи
     */
    public static function set_run_at()
    {

        $sth = Connect::$dbh->query("SELECT * FROM `crontabs`");
        while ($row = $sth->fetch()) {
            $cron = CronExpression::factory($row->shedule);
            $run_at = $cron->getNextRunDate(null, 0)->format('Y-m-d H:i:s');
            Connect::$dbh->query("UPDATE `crontabs` SET `run_at` = '$run_at' WHERE `id` = $row->id");
        }
    }
}
