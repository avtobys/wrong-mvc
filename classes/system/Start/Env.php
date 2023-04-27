<?php

/**
 * @file
 * @brief установка переменных среды приложения
 * 
 */

namespace Wrong\Start;

/**
 * @brief Env класс управляющий, добавляющий или записывающий переменные среды
 * 
 */

class Env
{
    /** путь к файлу переменных среды .env */
    const PATH = __DIR__ . '/../../../.env';

    /** статическое свойство, которое используется для хранения экземпляра класса. */
    public static $e;

    /**
     * читает файл .env и устанавливает свойства класса в значения в файле .env
     */
    public function __construct()
    {
        file_exists(self::PATH) or exit('No such .env file');
        foreach (parse_ini_file(self::PATH) as $prop => $value) {
            $this->$prop = $value;
        }
        $this->IP                 = $this->ip();
        $this->IS_SECURE          = $this->is_secure();
        $this->SYSTEM_SECRET_KEY  = md5($this->DB_PASSWORD . $this->DB_DATABASE . $this->HTTP_HOST . __FILE__);
        $this->IS_CLI             = in_array(PHP_SAPI, array('cli', 'cli-server', 'phpdbg'));
        $this->TEMP_PATH          = realpath(__DIR__ .'/../../../temp');
        self::$e                  = $this;
    }

    /**
     * Если пользователь находится за прокси, получить IP-адрес прокси, в противном случае получить
     * IP-адрес пользователя
     * 
     * @return string IP-адрес пользователя.
     */
    private function ip()
    {
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') > 0) {
                $addr = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
                return trim($addr[0]);
            } else {
                return htmlspecialchars($_SERVER['HTTP_X_FORWARDED_FOR'], ENT_QUOTES);
            }
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * Если сервер использует HTTPS, или если порт сервера — 443, или если для заголовка
     * X-Forwarded-Proto установлено значение HTTPS, или если для заголовка посетителя Cloudflare
     * установлено значение HTTPS, то вернуть истину
     * 
     * @return bool Используется ли https
     */
    private function is_secure()
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || $_SERVER['SERVER_PORT'] == 443
            || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
            || (!empty($_SERVER['HTTP_CF_VISITOR']) && $_SERVER['HTTP_CF_VISITOR'] == '{"scheme":"https"}');
    }

    /**
     * записывает в .env файл переменные среды
     * 
     * @param string $name Имя параметра, который вы хотите изменить.
     * @param string|int $value Значение для установки параметра.
     * 
     */
    public function set($name, $value)
    {
        if (self::$e->$name == $value) return;
        file_exists(self::PATH) && clearstatcache(true, self::PATH);
        $file = new \SplFileObject(self::PATH, 'a+b');
        $file->flock(LOCK_EX);
        $file->rewind();
        $data = $file->fread($file->getSize());
        $arr = explode("\n", $data);
        $rx = '#^' . $name . '=#i';
        foreach ($arr as $k => $v) {
            if (preg_match($rx, $v)) {
                $arr[$k] = $name . '=' . $value;
            }
        }
        $data = trim(implode("\n", $arr)) . "\n";
        $file->ftruncate(0);
        $file->fwrite($data);
        $file->flock(LOCK_UN);
        self::$e->$name = $value;
    }

    /**
     * принимает массив значений и добавляет их к объекту переменных среды, без записи в файл
     * 
     * @param array $arr Массив параметров для добавления к объекту.
     */
    public static function add($arr)
    {
        foreach ($arr as $key => $value) {
            self::$e->$key = $value;
        }
        if (!is_array(self::$e->GROUPS_USERS)) {
            self::$e->GROUPS_USERS = json_decode(self::$e->GROUPS_USERS, true);
        }
    }
}
