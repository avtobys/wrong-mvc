<?php

/**
 * @file
 * @brief выполнение javascript по стеку, асинхронный ответ на запрос по таймауту из api и установка нового таймаута
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

exit(Wrong\Task\Stackjs::execute());
