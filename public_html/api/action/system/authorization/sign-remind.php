<?php

/**
 * @file
 * @brief обработчик формы восстановления пароля
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

if (empty($_POST['password']) || mb_strlen(trim($_POST['password']), 'utf-8') < 5) {
    exit(json_encode(['error' => 'password']));
}

if (empty($_POST['password2']) || $_POST['password'] != $_POST['password2']) {
    exit(json_encode(['error' => 'password2']));
}

if (empty($_POST['user_id']) || empty($_POST['md5'])) {
    exit;
}

if (!Wrong\Auth\Hcaptcha::check() && (empty($_POST['h-captcha-response']) || !Wrong\Auth\Hcaptcha::get($_POST['h-captcha-response']))) {
    exit(json_encode(['error' => 'hcaptcha']));
}

Wrong\Auth\Hcaptcha::attempt();

if ($user = Wrong\Auth\User::is_remind($_POST['user_id'], $_POST['md5'])) {
    if ($user = new Wrong\Auth\User($user->id)) {
        $user->set_password($_POST['password']);
        Wrong\Auth\User::session($user->id);
        exit(json_encode(['result' => 'ok']));
    }
}

