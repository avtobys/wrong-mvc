<?php

/**
 * @file
 * @brief автозагрузка системных классов, установка переменных окружения
 */

/** автозагрузка классов проекта */
spl_autoload_register(function ($class) {
    require __DIR__ . '/' . str_replace('\\', '/', strtolower($class)) . '.class.php';
});


