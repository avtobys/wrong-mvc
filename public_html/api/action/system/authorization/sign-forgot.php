<?php

/**
 * @file
 * @brief обработчик формы отправки письма для восстановления пароля
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

if (empty($_POST['email']) || !filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
    exit(json_encode(['error' => 'email']));
}


if (!Wrong\Auth\Hcaptcha::check() && (empty($_POST['h-captcha-response']) || !Wrong\Auth\Hcaptcha::get($_POST['h-captcha-response']))) {
    exit(json_encode(['error' => 'hcaptcha']));
}

Wrong\Auth\Hcaptcha::attempt();
$user = Wrong\Auth\User::match($_POST['email']);

if (!$user) {
    exit(json_encode(['error' => 'auth']));
}

Wrong\Mail\Send::forgot($user);

exit(json_encode(['message' => '<p>Инструкция отправлена на <strong>' . $user->email . '</strong>, проверьте <a target="_blank" href="//' . explode('@', $user->email)[1] . '">почту</a></p>']));
