<?php

/**
 * @file
 * @brief очистка и удаление каталогов
 * 
 */

namespace Wrong\File;

/**
 * @brief Path удаляет и очищает каталоги
 * 
 */

class Path
{
    /**
     * Если каталог не существует, создает его
     * 
     * @param string $filename Полный путь к файлу, каталог под который нужно создать.
     */
    public static function mkdir($filename)
    {
        if (!file_exists(dirname($filename))) {
            mkdir(dirname($filename), 0755, true);
        }
    }

    /**
     * Рекурсивно удалить файл и его родительские каталоги, если они пусты
     * 
     * @param string $filename Файл для удаления.
     */
    public static function rm($filename)
    {
        $result = false;
        if (is_file($filename) && unlink($filename)) {
            $result = true;
        }
        $dir = dirname($filename);
        if (is_dir($dir) && file_exists($dir) && !(new \FilesystemIterator($dir))->valid() && rmdir($dir)) {
            $result = true;
            self::rm($dir);
        }
        return $result;
    }

    /**
     * если каталог пуст, удаляет его, а затем рекурсивно вызывает функцию в родительском каталоге.
     * 
     * @param string $filename уже несуществующий файл каталога.
     */
    public static function rmdir($filename)
    {
        $result = false;
        $dir = dirname($filename);
        if (!(new \FilesystemIterator($dir))->valid() && rmdir($dir)) {
            $result = true;
            self::rmdir($dir);
        }
        return $result;
    }

}
