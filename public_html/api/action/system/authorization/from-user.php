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

if (!($row = Wrong\Database\Controller::find($_POST['id'], 'id', 'users'))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if ($row->id == $user->id) {
    exit(json_encode(['error' => 'Нельзя зайти из под самого себя']));
}

if (!in_array($row->owner_group, $user->subordinate_groups)) {
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
]);

exit(json_encode(['result' => 'ok']));
