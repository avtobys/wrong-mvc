<?php

/**
 * @file
 * @brief обработчик формы подтверждения почты
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});

if (empty($_POST['email']) || !filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
    exit(json_encode(['error' => 'Email указан некорректно']));
}

if (!Wrong\Auth\Hcaptcha::check() && (empty($_POST['h-captcha-response']) || !Wrong\Auth\Hcaptcha::get($_POST['h-captcha-response']))) {
    exit(json_encode(['error' => 'hcaptcha']));
}

Wrong\Auth\Hcaptcha::attempt();
if ($_POST['email'] != $user->email && Wrong\Database\Controller::find($_POST['email'], 'email', 'users')) {
    exit(json_encode(['error' => 'Этот email уже зарегистрирован другим пользователем']));
}


$user->set_email($_POST['email']);

Wrong\Mail\Send::confirm($user);

exit(json_encode(['result' => 'ok', 'message' => 'Письмо на <b>' . $_POST['email'] . '</b> успешно отправлено! Проверьте почту.']));
