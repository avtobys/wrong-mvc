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
use Wrong\File\Locker;

/**
 * @brief Cron класс управляющий выполнением крон задач
 * 
 */

class Cron
{
    /** настройки потоков по умолчанию */
    const DEFAULT_THERADS_SET = ['curr' => 0, 'min' => 1, 'max' => 1, 'load' => 300, 'fixed' => 0];


    /**
     * проверяет, включено ли выполнение cron, и если это так, получает все задания cron из базы данных,
     * которые должны быть запущены, и запускает их.
     */
    public static function load()
    {

        if (!Env::$e->CRON_ACT) return;
        $dbh = Connect::start();
        $sth = $dbh->query("SELECT * FROM `crontabs` WHERE `run_at` BETWEEN NOW() - INTERVAL 1 YEAR AND NOW() AND `act` = 1");
        self::set_run_at();
        while ($row = $sth->fetch()) {
            $threads = json_decode($row->threads, true) ?: self::DEFAULT_THERADS_SET;
            self::run_stack($row->id, self::available_threads($row->id, $threads), $threads);
        }
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
        if (!Env::$e->CRON_ACT) return;
        if ((!$row->request && $row->method != 'CLI') || (!$row->cli && $row->method == 'CLI')) return;
        $threads = json_decode($row->threads, true) ?: self::DEFAULT_THERADS_SET;
        if (intval(shell_exec("echo $(nproc) $(cat /proc/loadavg | awk '{print $1}') | awk '$2>$1/100*" . $threads['load'] . " {print 1}'"))) exit;

        if ($row->method == 'CLI' && $row->cli && Env::$e->CRON_CLI) {
            Connect::close();
            exec($row->cli);
            exit;
        }

        if (!$row->request) exit;
        $x_auth_token = false;
        if ($row->user_id && !($x_auth_token = self::get_token($row->user_id))) return;
        $headers = json_decode($row->headers, true);
        Connect::close();
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


    /**
     * Возвращает текущее количество запущенных потоков с id задачи
     * 
     * @param int $id - идентификатор задачи
     */
    private static function get_curr_therads($id)
    {
        return intval(shell_exec("ps aux | grep '" . addcslashes('php -f ' . dirname(__DIR__, 3) . '/public_html/cron.php ' . $id, '.') . "' | wc -l"));
    }


    /**
     * Возвращает количество требуемых для запуска потоков
     * 
     * @param int $id - идентификатор задачи
     * @param array $threads - настройки потоков
     */
    private static function available_threads($id, $threads)
    {
        if (intval(shell_exec("echo $(nproc) $(cat /proc/loadavg | awk '{print $1}') | awk '$2>$1/100*" . $threads['load'] . " {print 1}'"))) return 0;
        $threads['curr'] = self::get_curr_therads($id);
        if ($threads['curr'] > $threads['max']) return 0;
        return $threads['min'] - $threads['curr'];
    }


    /**
     * Запускает указанное количество потоков
     * 
     * @param int $id - идентификатор задачи
     * @param int $n - количество потоков
     * @param array $threads - настройки потоков
     * 
     */
    private static function run_stack($id, $n, $threads)
    {
        if (!Locker::lock("cron-stack-$id", 10)) return;
        for ($i = 0; $i < $n; $i++) {
            self::run_thread($id);
            if ($i != $n) {
                usleep(10000);
            }
        }

        if ($threads['fixed']) {
            exec('(sleep 0.5 && php -f ' . dirname(__DIR__, 3) . '/public_html/cron.php fork ' . $id . ' &) > /dev/null 2>&1');
        }
        
        Locker::unlock("cron-stack-$id", 10);
    }

    /**
     * Запускает поток по идентификатору задачи
     * 
     * @param int $id - идентификатор задачи
     */
    private static function run_thread($id)
    {
        exec('(php -f ' . dirname(__DIR__, 3) . '/public_html/cron.php ' . $id . ' &) > /dev/null 2>&1');
    }

    /**
     * запуск стека из форка
     * 
     * @param object $row объект задачи из строки бд
     */
    public static function fork($row)
    {
        if (!$row->act || !Env::$e->CRON_ACT) {
            exit;
        }
        Connect::close();
        $threads = json_decode($row->threads, true) ?: self::DEFAULT_THERADS_SET;
        self::run_stack($row->id, self::available_threads($row->id, $threads), $threads);
    }
}
