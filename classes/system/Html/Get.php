<?php

/**
 * @file
 * @brief минификация css и js для встраивания в код html
 * 
 */

namespace Wrong\Html;

use Wrong\Html\Min;

/**
 * @brief Get методы класса возвращают минифицированные css и js коды для встраивания в html
 * 
 */

class Get
{

    /**
     * минифицирует файл CSS и возвращает его в виде строки в тегах <style>.
     * 
     * @param string $filename Путь к файлу CSS, который вы хотите минимизировать.
     * 
     * @return string Строка уменьшенного файла CSS.
     */
    public static function style($filename)
    {

        if (!file_exists($filename) || !($minifed_path = Min::style($filename)) || !file_exists($minifed_path)) return;
        $data = file_get_contents($minifed_path);
        $data = preg_replace('#\/\*\# sourceMappingURL=[^/]+\/#', '', $data);
        return '<style>' . trim($data) . '</style>' . PHP_EOL;
    }

    /**
     * возвращает строку в виде тега link <link rel="stylesheet" href="style.css?123456789"> добавляя время модификации файла.
     * 
     * @param string $filename Путь к файлу CSS, который вы хотите подключить.
     * 
     * @return string Строка с тегом.
     * 
     */
    public static function stylesrc($filename)
    {
        if (!file_exists($filename) || !($minifed_path = Min::style($filename)) || !file_exists($minifed_path)) return;
        return '<link rel="stylesheet" href="' . str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath($minifed_path)) . '?' . filemtime($minifed_path) . '">' . PHP_EOL;
    }

    /**
     * минифицирует файл Javascript и возвращает его в виде строки в тегах <script>.
     * 
     * @param string $filename Путь к файлу, который вы хотите минимизировать.
     * 
     * @return string Строка уменьшенного файла javascript.
     */
    public static function script($filename)
    {
        if (!file_exists($filename) || !($minifed_path = Min::script($filename)) || !file_exists($minifed_path)) return;
        $data = file_get_contents($minifed_path);
        return '<script>' . trim($data) . '</script>' . PHP_EOL;
    }

    /**
     * возвращает строку в виде тега <script src="script.js?123456789"></script> добавляя время модификации файла.
     * 
     * @param string $filename Путь к файлу js, который вы хотите подключить.
     * 
     * @return string Строка с тегом.
     */
    public static function scriptsrc($filename)
    {

        if (!file_exists($filename) || !($minifed_path = Min::script($filename)) || !file_exists($minifed_path)) return;
        return '<script src="' . str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath($minifed_path)) . '?' . filemtime($minifed_path) . '"></script>' . PHP_EOL;
    }

    /**
     * из массива путей файлов преобразует массив с теми же путями, добавляя к строке запроса время модификации файла для обхода кешей
     * и возвращает массив в json формате. Используется впоследствии для подгрузки различных библиотек
     * 
     * @param array $arr массив путей
     * @return string преобразованный в JSON массив данных
     */
    public static function pathArrayJSON($arr)
    {
        return json_encode(array_map(function ($item) {
            return $item . '?' . filemtime($_SERVER['DOCUMENT_ROOT'] . $item);
        }, $arr));
    }
}
