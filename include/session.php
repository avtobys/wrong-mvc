<?php

/**
 * @file
 * @brief файл стартующий логику авторизации в системе и старт сессии, внедрение дополнительных js и html кодов в страницу
 * 
 * подключение к бд, установка дополнительных переменных среды, старт сессии
 */

use Wrong\Auth\User;
use Wrong\Rights\Group;
use Wrong\Task\stackJS;
use Wrong\Database\Connect;
use Wrong\Start\Env;
use Wrong\Html\Hideout;
use Wrong\Logs\Write;

/** Статический метод, который подключается к базе данных и возвращает объект подключения. */
$dbh = Connect::start();

try {
    Env::add($dbh->query("SELECT `name`, `value` FROM `settings`")->fetchAll(\PDO::FETCH_KEY_PAIR));
} catch (\Throwable $th) {
    dd($dbh->errorInfo(), 'Возможно система не установлена и в index.php не включен файл install/install.php');
}

/** Строка path запроса */
$request = strtok($_SERVER['REQUEST_URI'], '?');

ob_start();
session_start();
/** Установка адреса сервера, хоста и порта в переменные среды в случае изменения. */
Env::$e->set('SERVER_ADDR', $_SERVER['SERVER_ADDR']);
Env::$e->set('HTTP_HOST', $_SERVER['HTTP_HOST']);
Env::$e->set('SERVER_PORT', $_SERVER['SERVER_PORT']);
Env::add(['CSRF' => md5(session_id())]);

register_shutdown_function(function () {
    $out = ob_get_contents();
    if (stripos($out, '</body>') === false) return; // значит это не html страница, а api запрос
    ob_clean();
    $script = '<script>window.CSRF = "' . Env::$e->CSRF . '";' . stackJS::execute() . '</script>';
    $out = str_replace('</body>', '<div class="position-fixed" id="toast" style="top:0;left:0;z-index:1051;"></div>' . $script . '</body>', $out);
    if (!empty($_COOKIE['FROM_UID'])) {
        $out = str_replace('</body>', '<div class="position-fixed" style="bottom:10px;right:10px;z-index:1051;"><a title="Вернуться в свой аккаунт" class="btn btn-secondary border-0 rounded-circle" href="/?FROM_UID"><i class="fa fa-user"></i></a></div></body>', $out);
    }
    $out = Hideout::hide($out);
    echo $out;
});

header("Cache-Control: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT");

/** Это статический метод, который загружает все группы из базы данных и сохраняет их в массиве `Group::`. */
Group::groups();

if (!empty($_SERVER['HTTP_X_AUTH_TOKEN'])) { /// Авторизация по токену апи
    if (!Env::$e->API) {
        exit(json_encode(['error' => 'API is disabled']));
    }
    $sth = $dbh->prepare("SELECT `id` FROM `users` WHERE `api_act` = 1 AND `x_auth_token` = :x_auth_token");
    $sth->bindValue(':x_auth_token', $_SERVER['HTTP_X_AUTH_TOKEN']);
    $sth->execute();
    if ($id = $sth->fetchColumn()) {
        $user = new User($id);
        $user->is_api = true;
    } else {
        exit(json_encode(['error' => 'Wrong token or API is disabled']));
    }
} else { /// авторизация по сессии или кукам
    $user = new User(User::session());
}


/** защита от CSRF-атаки для POST/PUT/DELETE запросов. */
if ($user->id && !$user->is_api && ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'DELETE') && (empty($_REQUEST['CSRF']) || $_REQUEST['CSRF'] != Env::$e->CSRF)) {
    exit('Invalid CSRF!');
}

/** Метод, обновляющий время последней активности пользователя в базе данных. */
$user->set_online();
/** записывает действия пользователя в базу данных. */
Write::action();

if (Wrong\Start\Env::$e->CRON_ACT && (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/../temp/lock-cron.lock') || filemtime($_SERVER['DOCUMENT_ROOT'] . '/../temp/lock-cron.lock') + 100 < time())) {
    Wrong\Task\stackJS::add('(function(){let im=new Image();im.src="/cron.php?"+Math.random();})();', 0, 'cron'); /// активация самозапускающегося скрипта выполнения крон задач
}
