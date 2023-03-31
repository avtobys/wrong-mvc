<?php

/**
 * @file
 * @brief обработчик сброса сессии и выхода из системы
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

Wrong\Auth\User::session_reset();
if (empty($_COOKIE['FROM_UID'])) {
    session_destroy();
}
exit('window.localStorage.clear();location.href="/";');
