<?php

/**
 * @file
 * @brief обрабочик гостевого входа в систему из под другого пользователя
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});

if (!($row = Wrong\Models\Users::find($_POST['id']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if ($row->id == $user->id) {
    exit(json_encode(['error' => 'Нельзя зайти из под самого себя']));
}

if (!$user->access()->write($row)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

$uid = Wrong\Auth\Crypt::idEncrypt($row->id);
setcookie('FROM_UID', $uid, [
    'expires' => time() + 31536000,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => Wrong\Start\Env::$e->IS_SECURE,
    'httponly' => false,
    'samesite' => Wrong\Start\Env::$e->IS_SECURE ? 'None' : 'Lax'
]) or setcookie('FROM_UID', $uid, time() + 31536000, '/', $_SERVER['HTTP_HOST'], Wrong\Start\Env::$e->IS_SECURE);

$user = new Wrong\Auth\User($row->id);
if ($user->access()->page('/system')) {
    Wrong\Task\stackJS::add('location.href="/system";', 0, 'location');
} else {
    Wrong\Task\stackJS::add('location.href="/";', 0, 'location');
}

exit(json_encode(['result' => 'ok']));
