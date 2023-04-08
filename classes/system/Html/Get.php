<?php

/**
 * @file
 * @brief минификация css и js для встраивания в код html
 * 
 */

namespace Wrong\Html;

use MatthiasMullie\Minify;

/**
 * @brief Get методы класса возвращают минифицированные css и js коды
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

        if (!file_exists($filename)) return '';
        if (preg_match('#\.min\.css$#', $filename)) {
            $minifed_path = dirname($filename) . '/' . basename($filename);
        } else {
            $minifed_path = dirname($filename) . '/' . basename($filename, '.css') . '.min.css';
        }

        if (!file_exists($minifed_path) || filemtime($minifed_path) != filemtime($filename)) {
            $minifier = new Minify\CSS($filename);
            $data = $minifier->minify($minifed_path);
            touch($minifed_path);
            touch($filename);
            clearstatcache(true, $minifed_path);
            clearstatcache(true, $filename);
        } else {
            $data = file_get_contents($minifed_path);
        }
        $data = preg_replace('#\/\*\# sourceMappingURL=[^/]+\/#', '', $data);
        return '<style>' . trim($data) . '</style>';
    }

    /**
     * возвращает строку в виде тега link <link rel="stylesheet" href="style.css?123456789"> добавляя время модификации файла.
     * 
     * @param string $filename Путь к файлу CSS, который вы хотите подключить.
     * 
     * @return string Строка с тегом.
     * 
     * TODO: добавить генерацию мин версий
     */
    public static function stylesrc($filename)
    {

        return '<link rel="stylesheet" href="' . str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath($filename)) . '?' . filemtime($filename) . '">';
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
        if (!file_exists($filename)) return '';
        if (preg_match('#\.min\.js$#', $filename)) {
            $minifed_path = dirname($filename) . '/' . basename($filename);
        } else {
            $minifed_path = dirname($filename) . '/' . basename($filename, '.js') . '.min.js';
        }

        if (!file_exists($minifed_path) || filemtime($minifed_path) != filemtime($filename)) {
            $minifier = new Minify\JS($filename);
            $data = $minifier->minify($minifed_path);
            touch($minifed_path);
            touch($filename);
            clearstatcache(true, $minifed_path);
            clearstatcache(true, $filename);
        } else {
            $data = file_get_contents($minifed_path);
        }
        return '<script>' . trim($data) . '</script>';
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

        return '<script src="' . str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath($filename)) . '?' . filemtime($filename) . '"></script>';
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
