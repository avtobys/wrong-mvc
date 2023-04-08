<?php

/**
 * @file
 * @brief обработчик очистка системного кеша
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

Wrong\Memory\Cache::deleteAll() && exit(json_encode(['result' => 'ok', 'message' => 'Кеш успешно очищен', 'size' => Wrong\Memory\Cache::getSize()]));

exit(json_encode(['error' => 'Ошибка']));
