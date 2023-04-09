<?php

/**
 * @file
 * @brief минификация css и js
 * 
 */

namespace Wrong\Html;

use MatthiasMullie\Minify;

/**
 * @brief Get методы класса минифицируют и возвращают минифицированные css и js имена файлов
 * 
 */

class Min
{
    /**
     * минифицирует файл CSS и возвращает его полный путь.
     * 
     * @param string $filename Путь к файлу CSS, который вы хотите минимизировать.
     * 
     * @return string путь к файлу уменьшенного CSS.
     */
    public static function style($filename)
    {

        if (!file_exists($filename)) return;
        if (preg_match('#\.min\.css$#', $filename)) {
            $minifed_path = dirname($filename) . '/' . basename($filename);
        } else {
            $minifed_path = dirname($filename) . '/' . basename($filename, '.css') . '.min.css';
        }

        if (!file_exists($minifed_path) || filemtime($minifed_path) != filemtime($filename)) {
            $minifier = new Minify\CSS($filename);
            $minifier->minify($minifed_path);
            touch($minifed_path);
            touch($filename);
            clearstatcache(true, $minifed_path);
            clearstatcache(true, $filename);
        }
        return $minifed_path;
    }


    
    /**
     * минифицирует файл Javascript и возвращает его полный путь.
     * 
     * @param string $filename Путь к файлу, который вы хотите минимизировать.
     * 
     * @return string путь к файлу уменьшенного javascript.
     */
    public static function script($filename)
    {
        if (!file_exists($filename)) return;
        if (preg_match('#\.min\.js$#', $filename)) {
            $minifed_path = dirname($filename) . '/' . basename($filename);
        } else {
            $minifed_path = dirname($filename) . '/' . basename($filename, '.js') . '.min.js';
        }

        if (!file_exists($minifed_path) || filemtime($minifed_path) != filemtime($filename)) {
            $minifier = new Minify\JS($filename);
            $minifier->minify($minifed_path);
            touch($minifed_path);
            touch($filename);
            clearstatcache(true, $minifed_path);
            clearstatcache(true, $filename);
        }
        return $minifed_path;
    }
}
