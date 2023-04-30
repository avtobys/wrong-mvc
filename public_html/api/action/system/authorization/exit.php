<?php

/**
 * @file
 * @brief обработчик сброса сессии и выхода из системы
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

Wrong\Auth\User::session_reset();
if ($user->access()->page('/system')) {
    exit('location.href="/system";');
}
session_destroy();
exit('window.localStorage.clear();location.href="/";');
