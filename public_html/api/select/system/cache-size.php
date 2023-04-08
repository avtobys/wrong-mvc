<?php

/**
 * @file
 * @brief выборка возвращает размера каталога с кешем
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

session_write_close();

echo Wrong\Memory\Cache::getSize();
