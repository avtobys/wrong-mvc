<?php

/**
 * @file
 * @brief обработчик формы входа в систему
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

if (empty($_POST['email']) || !filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
    exit(json_encode(['error' => 'email']));
}

if (empty($_POST['password'])) {
    exit(json_encode(['error' => 'password']));
}

if (!Wrong\Auth\Hcaptcha::check() && (empty($_POST['h-captcha-response']) || !Wrong\Auth\Hcaptcha::get($_POST['h-captcha-response']))) {
    exit(json_encode(['error' => 'hcaptcha']));
}

$user = Wrong\Auth\User::match($_POST['email']);

if (!$user || $user->md5password != md5(trim($_POST['password']))) {
    Wrong\Auth\Hcaptcha::attempt();
    exit(json_encode(['error' => 'auth']));
}

if (!$user->act) {
    exit(json_encode(['error' => 'unknown']));
}

if (Wrong\Auth\User::session($user->id)) {
    if (Wrong\Rights\Group::is_available_group(Wrong\Models\Pages::find('/wrong', 'request'), $user->id)) {
        Wrong\Task\stackJS::add('location.href="/wrong";', 0, 'location');
    } else {
        Wrong\Task\stackJS::add('location.reload();', 0, 'location');
    }
    exit(json_encode(['result' => 'ok']));
}
