<?php

/**
 * @file
 * @brief запись логов действий
 * 
 */

namespace Wrong\Logs;

use Wrong\Database\Connect;
use Wrong\Start\Env;

/**
 * @brief Write класс отвечает за запись логов действий
 * 
 */

class Write
{
    /**
     * записывает все запросы API действий /api/action в базу данных.
     */
    public static function action()
    {
        global $user;
        if ($user->id && $user->write_log_actions && preg_match('#^/api/action#', $_SERVER['REQUEST_URI'])) {
            register_shutdown_function(function ($user) {
                $output = ob_get_contents();
                $output = json_decode(ob_get_contents(), true) ?: $output;
                $input = file_get_contents('php://input');
                if (mb_parse_str($input, $arr) || ($arr = json_decode($input, true))) {
                    $input = $arr;
                }
                if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'PUT') {
                    $input = $input ?: $_POST;
                }
                $arr = [
                    "method" => $_SERVER['REQUEST_METHOD'],
                    "input" => $input,
                    "output" => $output
                ];
                $text = json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                $dbh = Connect::getInstance()->dbh;
                $sth = $dbh->prepare("INSERT INTO `logs` (`user_id`, `request`, `text`, `ip`) VALUES (:user_id, :request, :text, :ip)");
                $sth->bindValue(':user_id', $user->id);
                $sth->bindValue(':request', $_SERVER['REQUEST_URI']);
                $sth->bindValue(':text', $text);
                $sth->bindValue(':ip', Env::$e->IP);
                $sth->execute();
            }, $user);
        }
    }
}
