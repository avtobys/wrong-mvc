<?php

/**
 * @file
 * @brief блокировка файлов
 * 
 */

namespace Wrong\File;


/**
 * @brief Locker блокировщик файлов
 * 
 * отвечает за блокировку файлов для крон задач, может использоваться при необходимости любых иных блокировок потоков и их ограничения
 * usage example:
 *
 *  if (Locker::lock(basename(__FILE__, '.php'))) {
 *       Locker::unlock(basename(__FILE__, '.php'));
 *  }
 * 
 */

class Locker
{
    /** путь к каталогу, в котором будут храниться файлы блокировки. */
    const LOCK_PATH = __DIR__ . '/../../../temp';

    /** префикс файла блокировки. */
    const LOCK_PREFIX = 'lock';

    /** время блокировки в секундах */
    const CLEAN_TIME = 3600;

    /** Переменная класса, используется для хранения пути к файлу блокировки. */
    private static $path;

    /** Используется, чтобы определить, заблокировал ли текущий процесс файл. */
    private static $lock = false;

    /**
     * создает файл с именем блокировки и записывает текущее время плюс максимальное время, в
     * течение которого блокировка должна удерживаться в файле.
     * 
     * Если файл уже существует, проверяет, истек ли срок действия блокировки, и если да, то удаляет
     * файл и возвращает значение true.
     * 
     * Если срок действия блокировки не истек, возвращается false.
     * 
     * Если файл не существует, создает файл и возвращает true
     * 
     * @param int $id Имя файла блокировки.
     * @param int $max_time Максимальное время удержания блокировки в секундах.
     */
    public static function lock($id, $max_time = self::CLEAN_TIME)
    {
        self::setPath($id);
        if (file_exists(self::$path) && self::cleaner()) {
            return false;
        }
        $file = new \SplFileObject(self::$path, 'a+b');
        $file->flock(LOCK_EX);
        $file->rewind();
        $time = $file->fgets();
        $file->ftruncate(0);
        $file->fwrite(time() + $max_time);
        if (!$time) {
            touch(self::$path, time() + $max_time);
            $file->flock(LOCK_UN);
            self::$lock = true;
            return true; // блокировка получена
        }
        $file->flock(LOCK_UN);
        return false; // блокировка не получена
    }


    /**
     * создает каталог, если он не существует, а затем устанавливает в self::$path имя файла блокировки
     * 
     * @param Уникальный идентификатор блокировки, который используется для создания имени файла
     * блокировки.
     */
    private static function setPath($id)
    {
        try {
            file_exists(self::LOCK_PATH) or mkdir(self::LOCK_PATH, 0755);
            if (!file_exists(self::LOCK_PATH)) {
                throw new \Error('Path does not create');
            }
        } catch (\Throwable $th) {
            exit($th);
        }
        self::$path = self::LOCK_PATH . '/' . self::LOCK_PREFIX . '-' . $id . '.lock';
        file_exists(self::$path) && clearstatcache(true, self::$path);
    }

    /**
     * Удаляет файл блокировки
     * 
     * @param int $id Имя файла блокировки.
     * @param bool $forse истинное означает что блокировка будет снята, даже если она не была создана текущим процессом.
     */
    public static function unlock($id, $forse = false)
    {
        self::setPath($id);
        if (file_exists(self::$path) && (self::$lock || $forse)) {
            unlink(self::$path);
            return true;
        }
        return false;
    }

    /**
     * удаляет любые файлы блокировки, которые старше текущего времени.
     * 
     * @return bool Логическое значение, истинное означает что текущий файл блокировки по прежнему существует.
     */
    private static function cleaner()
    {
        foreach (glob(self::LOCK_PATH . '/' . self::LOCK_PREFIX . '-*.lock') as $path) {
            if (filemtime($path) < time() && intval(file_get_contents($path)) < time()) {
                unlink($path);
            }
        }
        return file_exists(self::$path);
    }
}
