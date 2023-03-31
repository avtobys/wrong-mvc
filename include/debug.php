<?php

/**
 * @file
 * @brief отладочные функции dd/rd/ld
 */


/**
 * отладочная функция, которая принимает любое количество аргументов и выводит их дамп на экран, а затем
 * завершает работу всей программы.
 * 
 * @param mixed ...$vars 
 */
function dd(...$vars)
{
    rd(...$vars);
    exit(1);
}


/**
 * выполяет ту же функцию что и rd() но не завершает работу программы
 * Это var_dump() с отображением текущего времени выполнения программы и компактным форматированием pre тегами
 */
function rd(...$vars)
{
    if (empty($vars)) {
        $vars[0] = null;
    }
    foreach ($vars as $v) {
        echo '<pre style="display:block;font-size:12px;color:#dcdcaa;background:#1e1e1e;line-height:16px;padding:10px;font-family:monospace;margin:8px 0;overflow:auto;position:relative;">';
        var_dump($v);
        echo '<pre style="display:inline-block;font-size:9px;color:#000000;background:rgba(255, 255, 255, 0.6);padding:2px;font-family:monospace;margin:0;overflow:auto;position:absolute;top:0;right:0;">exec time: ' . round((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']), 5) . '</pre>';
        echo '</pre>';
    }
}


/**
 * отладочная функция, которая принимает любое количество аргументов и записывает их дамп в лог файл.
 * Запись прекращается если файл превысил размер 50 мб.
 * 
 * Вызов функции без аргументов, прекращает работу программы и выводит все записанные дампы на экран
 * 
 * Вызов функции с аргументом string == 'rm', прекращает работу программы, выводит все записанные дампы на экран, и удаляет файл с дампами.
 * 
 * @param mixed ...$vars 
 */
function ld(...$vars)
{
    $filename = $_SERVER['DOCUMENT_ROOT'] . '/../temp/ld';
    if (empty($vars) || $vars[0] == 'rm') {
        echo file_get_contents($filename);
        if ($vars[0] == 'rm') {
            unlink($filename);
        }
        exit;
    }
    if (file_exists($filename) && filesize($filename) > 52428800) {
        return;
    }
    $str = '';
    foreach ($vars as $v) {
        $str .= '<pre style="display:block;font-size:12px;color:#dcdcaa;background:#1e1e1e;line-height:16px;padding:10px;font-family:monospace;margin:8px 0;margin-top:50px;soverflow:auto;position:relative;">';
        $str .= var_export($v, true);
        $str .= '<pre style="display:inline-block;font-size:9px;color:#000000;background:rgba(255, 255, 255, 0.6);padding:2px;font-family:monospace;margin:0;overflow:auto;position:absolute;top:0;right:0;">' . (date('r')) . '; exec time: ' . round((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']), 5) . '</pre>';
        $str .= '</pre>';
    }

    $file = new \SplFileObject($filename, 'a+b');
    $file->flock(LOCK_EX);
    $file->fwrite($str);
    $file->flock(LOCK_UN);
}
