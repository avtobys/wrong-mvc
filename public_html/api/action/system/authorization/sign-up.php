<?php

/**
 * @file
 * @brief обработчик формы регистрации
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

if (empty($_POST['email']) || !filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
    exit(json_encode(['error' => 'email']));
}

if (empty($_POST['password']) || mb_strlen(trim($_POST['password']), 'utf-8') < 5) {
    exit(json_encode(['error' => 'password']));
}

if (empty($_POST['password2']) || $_POST['password'] != $_POST['password2']) {
    exit(json_encode(['error' => 'password2']));
}

if (!Wrong\Auth\Hcaptcha::check() && (empty($_POST['h-captcha-response']) || !Wrong\Auth\Hcaptcha::get($_POST['h-captcha-response']))) {
    exit(json_encode(['error' => 'hcaptcha']));
}

Wrong\Auth\Hcaptcha::attempt();

if ($user = Wrong\Auth\User::match($_POST['email'])) {
    exit(json_encode(['error' => 'auth']));
}

if ($id = Wrong\Auth\User::session(Wrong\Models\Users::create($_POST['email'], $_POST['password'], Wrong\Start\Env::$e->GROUPS_USERS, Wrong\Start\Env::$e->OWNER_GROUP_USERS))) {
    $user = new Wrong\Auth\User($id);
    Wrong\Mail\Send::confirm($user);
    if ($user->access()->page('/system')) {
        Wrong\Task\Stackjs::add('location.href="/system";', 0, 'location');
    } else {
        Wrong\Task\Stackjs::add('location.reload();', 0, 'location');
    }
    Wrong\Task\Stackjs::add('$(function(){successToast("Приятной работы в системе!");});', 1, 'sign-up');
    exit(json_encode(['result' => 'ok']));
}
