<?php

/**
 * @file
 * @brief кеширование
 * 
 */

namespace Wrong\Memory;

use Wrong\File\Path;

/**
 * @brief Cache класс, отвечающий за кеширование
 * 
 */

class Cache
{
    /** путь к каталогу, в котором будут храниться файлы и каталоги кеша. */
    const DIR_CACHE = __DIR__ . '/../../../temp/cache';

    /** время кеширования в секундах по умолчанию */
    const DEFAULT_TIMEOUT = 3600;


    /**
     * Конструктор
     * 
     * @param string $prefix - префикс к именам файлов, на случай установки идентичных ключей для разных хранимых сущностей
     * @param string $type - тип кеша, пока поддерживается только внутренний кеш системы
     * 
     */
    public function __construct($prefix = 'cache', $type = 'internal')
    {
        $this->prefix = $prefix;
        $this->type = $type;
        if ($type == 'internal') {
            file_exists(self::DIR_CACHE) or mkdir(self::DIR_CACHE, 0755, true) or exit('Path cache does not create');
        }
    }


    /**
     * устанавливает значение в файловом кеше с указанным ключом, строкой и временем
     * ожидания.
     * 
     * @param string|int key Ключ — это уникальный идентификатор данных, хранящихся в кэше. Он используется для
     * последующего извлечения данных.
     * @param mixed $value переменная, которая будет сериализована и записана в файл.
     * @param int timeout Параметр тайм-аута — это необязательный параметр, указывающий время в секундах, в
     * течение которого кэшированные данные должны быть действительными. По истечении этого времени
     * кэшированные данные будут считаться просроченными и будут удалены из кэша. Значение по умолчанию
     * для этого параметра устанавливается равным значению константы DEFAULT_TIMEOUT
     */
    public function set($key, $value, $timeout = self::DEFAULT_TIMEOUT)
    {
        $filename = $this->getFile($key);
        Path::mkdir($filename);
        $file = new \SplFileObject($filename, 'wb');
        $file->flock(LOCK_EX);
        $file->fwrite(serialize($value));
        touch($filename, time() + $timeout);
        $file->flock(LOCK_UN);
        $this->clean();
    }


    /**
     * извлекает содержимое файла, если он существует и таймаут кеширования не истек
     * 
     * @param string|int key Ключ — это уникальный идентификатор извлекаемых данных. Он используется для создания
     * имени файла, в котором хранятся данные.
     * 
     * @return string Если кеша таймаут не истек, то возвращает записанные данные. Иначе ничего не возвращается.
     */
    public function get($key)
    {
        $filename = $this->getFile($key);
        if (file_exists($filename) && filemtime($filename) >= time()) {
            return unserialize(file_get_contents($filename));
        }
    }


    /**
     * удаляет файл кеша на основе заданного ключа.
     * 
     * @param int|string key уникальный идентификатор или ключ, связанный с определенным
     * файлом или данными, которые необходимо удалить.
     * 
     * @return bool возвращает результат вызова функции `rm()` класса `Path`
     */
    public function delete($key)
    {
        return Path::rm($this->getFile($key));
    }


    /**
     * очищает файлы кеша по указанному префиксу
     * 
     * @param string $prefix строка с префиксом
     */
    public static function deleteByPrefix($prefix)
    {
        $di = new \RecursiveDirectoryIterator(self::DIR_CACHE, \FilesystemIterator::SKIP_DOTS);
        $ri = new \RecursiveIteratorIterator($di, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($ri as $file) {
            if ($file->isFile() && preg_match("#^$prefix#", $file->getFilename())) { // очистка файлов по префиксу
                Path::rm($file->getRealPath());
            }
        }
    }

    /**
     * очищает весь кеш полностью
     */
    public static function deleteAll()
    {
        exec('rm -rf ' . self::DIR_CACHE, $output, $code);
        file_exists(self::DIR_CACHE) or mkdir(self::DIR_CACHE, 0755, true);
        return !$code;
    }

    /**
     * возвращает общий размер каталога кеша
     */
    public static function getSize()
    {
        exec('du -sh ' . self::DIR_CACHE . ' | cut -f1', $out, $code);
        return $out[0] ?: 0;
    }


    /**
     * возвращает путь к файлу на основе заданного ключа с использованием алгоритма MD5.
     * 
     * @param key Параметр представляет собой строковое значение, которое используется для
     * создания хэша MD5. Затем этот хэш используется для создания пути к файлу, в котором будут
     * храниться кэшированные данные.
     * 
     * @return string путь к файлу, созданный на основе предоставленного ключа. Путь к файлу состоит из пути к
     * каталогу кэша, подкаталога, основанного на последних 8 символах хеша MD5 ключа, подкаталога,
     * основанного на последних 4 символах хеша MD5 ключа, и имени файла, который является префикс,
     * объединенный с хешем MD5 ключа
     */
    private function getFile($key)
    {
        $md5 = md5($key);
        return realpath(self::DIR_CACHE) . '/' . substr($md5, -8, 4) . '/' . substr($md5, -4) . '/' . $this->prefix . $md5;
    }


    /**
     * очищает устаревшие файлы кеша и периодически удаляет пустые каталоги кеша.
     */
    private function clean()
    {
        $di = new \RecursiveDirectoryIterator(self::DIR_CACHE, \FilesystemIterator::SKIP_DOTS);
        $ri = new \RecursiveIteratorIterator($di, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($ri as $file) {
            if ($file->isFile() && $file->getMTime() < time()) { // очистка устаревших файлов кеша вместе с их пустыми каталогами
                Path::rm($file->getRealPath());
            }
            if (mt_rand(1, 100) == 1 && $file->isDir()) { // периодическая очистка пустых каталогов(если их файлы были удалены как то иначе, чем блоком выше)
                Path::rmdir($file->getRealPath() . '/1');
            }
        }
    }
}
