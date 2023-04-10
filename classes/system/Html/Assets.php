<?php

/**
 * @file
 * @brief преобразование относительных путей в шаблонах
 * 
 */

namespace Wrong\Html;

/**
 * @brief Assets класс преобразует относительные пути в шаблонах
 * 
 */

class Assets
{
    /**
     * если в шаблоне была указана константа ASSETS_PATH то добавляет её ко всем атрибутам src тегов img, script, и атрибутам href тегов link на странице
     * преобразуя тем самым относительные пути к ресурсам в абсолютные
     * @param string $str код страницы на выходе
     * 
     * @return string $str код с заменами атрибутов
     */
    public static function path($str)
    {
        $assets_path = '/' . trim(USE_ASSETS_PATH, '/') . '/';
        $str = preg_replace_callback('#<script[^<>]+src\s*=\s*(\'|")([^/]+[^\'"]+)(\'|")[^>]*>#', function ($matches) use ($assets_path) {
            if (preg_match('#^http(s|):#', $matches[2]) || strlen($matches[0]) > 500) return $matches[0];
            return str_replace($matches[2], $assets_path . $matches[2] . '?' . filemtime($_SERVER['DOCUMENT_ROOT'] . $assets_path . $matches[2]), $matches[0]);
        }, $str);
        $str = preg_replace_callback('#<img[^<>]+src\s*=\s*(\'|")([^/]+[^\'"]+)(\'|")[^>]*>#', function ($matches) use ($assets_path) {
            if (preg_match('#^http(s|):#', $matches[2]) || strlen($matches[0]) > 500) return $matches[0];
            return str_replace($matches[2], $assets_path . $matches[2] . '?' . filemtime($_SERVER['DOCUMENT_ROOT'] . $assets_path . $matches[2]), $matches[0]);
        }, $str);
        $str = preg_replace_callback('#<link[^<>]+href\s*=\s*(\'|")([^/]+[^\'"]+)(\'|")[^>]*>#', function ($matches) use ($assets_path) {
            if (preg_match('#^http(s|):#', $matches[2]) || strlen($matches[0]) > 500) return $matches[0];
            return str_replace($matches[2], $assets_path . $matches[2] . '?' . filemtime($_SERVER['DOCUMENT_ROOT'] . $assets_path . $matches[2]), $matches[0]);
        }, $str);
        $str = preg_replace_callback('#<a[^<>]+href\s*=\s*(\'|")([^/]+[^\'"]+\.(jpg|png|jpeg))(\'|")[^>]*>#', function ($matches) use ($assets_path) {
            if (preg_match('#^http(s|):#', $matches[2]) || strlen($matches[0]) > 500) return $matches[0];
            return str_replace($matches[2], $assets_path . $matches[2] . '?' . filemtime($_SERVER['DOCUMENT_ROOT'] . $assets_path . $matches[2]), $matches[0]);
        }, $str);
        $str = preg_replace_callback('#<[^<>]+background-image:\s*url\(([^\(\)]+)\);[^<>]*>#', function ($matches) use ($assets_path) {
            if (preg_match('#^http(s|):#', $matches[1]) || strlen($matches[0]) > 500) return $matches[0];
            return str_replace($matches[1], $assets_path . $matches[1] . '?' . filemtime($_SERVER['DOCUMENT_ROOT'] . $assets_path . $matches[1]), $matches[0]);
        }, $str);
        $str = preg_replace_callback('#<[^<>]+background-image:\s*url\("([^\(\)"]+)"\);[^<>]*>#', function ($matches) use ($assets_path) {
            if (preg_match('#^http(s|):#', $matches[1]) || strlen($matches[0]) > 500) return $matches[0];
            return str_replace($matches[1], $assets_path . $matches[1] . '?' . filemtime($_SERVER['DOCUMENT_ROOT'] . $assets_path . $matches[1]), $matches[0]);
        }, $str);
        $str = preg_replace_callback('#<[^<>]+background-image:\s*url\(\'([^\(\)\']+)\'\);[^<>]*>#', function ($matches) use ($assets_path) {
            if (preg_match('#^http(s|):#', $matches[1]) || strlen($matches[0]) > 500) return $matches[0];
            return str_replace($matches[1], $assets_path . $matches[1] . '?' . filemtime($_SERVER['DOCUMENT_ROOT'] . $assets_path . $matches[1]), $matches[0]);
        }, $str);
        $str = preg_replace_callback('#<[^<>]+data-setbg="([^\(\)"]+)"[^<>]*>#', function ($matches) use ($assets_path) {
            if (preg_match('#^http(s|):#', $matches[1]) || strlen($matches[0]) > 500) return $matches[0];
            return str_replace($matches[1], $assets_path . $matches[1] . '?' . filemtime($_SERVER['DOCUMENT_ROOT'] . $assets_path . $matches[1]), $matches[0]);
        }, $str);
        return $str;
    }
}
