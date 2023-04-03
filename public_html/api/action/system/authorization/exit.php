<?php

/**
 * @file
 * @brief обработчик сброса сессии и выхода из системы
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

Wrong\Auth\User::session_reset();
if (empty($_COOKIE['FROM_UID'])) {
    session_destroy();
} elseif (Wrong\Rights\Group::is_available_group(Wrong\Models\Pages::find('/system', 'request'))) {
    exit('location.href="/system";');
}
exit('window.localStorage.clear();location.href="/";');
