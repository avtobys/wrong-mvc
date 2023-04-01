<?php


/**
 * @file
 * @brief работа с hcaptcha
 * 
 * каптчей защищены формы авторизизации, регистрации, восстановления пароля. Если количество попыток(взаимодействий с формами) не превышает лимитов ATTEMPTS_NUM
 * допустимых за период ATTEMPTS_TIME секунд, то допустимо взаимодействие без капчи, в целях удобства пользователей.
 */


namespace Wrong\Auth;

use Wrong\Start\Env;

/**
 * @brief Hcaptcha класс отвечает за работу с hcaptcha
 * 
 */
class Hcaptcha
{
    /** путь к временному файлу */
    const ATTEMPTS_FILE = __DIR__ . '/../../../temp/hcaptcha-attempts.json';

    /** Время в секундах, для подсчета попыток */
    const ATTEMPTS_TIME = 3600;
    
    /** Количество попыток, которое может сделать пользователь, прежде чем ему потребуется решить капчу. */
    const ATTEMPTS_NUM  = 5;

    /**
     * отправляет запрос POST в API hCaptcha с секретным ключом и токеном ответа и возвращает true, если ответ действителен.
     * 
     * @param string $response Токен ответа, предоставленный hCaptcha
     * 
     * @return bool Логическое значение.
     */
    public static function get($response)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://hcaptcha.com/siteverify',
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'secret=' . Env::$e->HCAPTCHA_SECRET . '&response=' . $response,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $result = curl_exec($curl);
        curl_close($curl);
        if ($result && json_decode($result)->success === true) {
            return true;
        }
        return false;
    }

    /**
     * проверяет, разрешен ли IP-адресу доступ к сайту без ввода каптчи.
     * 
     * @return int Количество оставшихся попыток.
     */
    public static function check()
    {
        if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') { // для локалки проверка всегда валидна
            return self::ATTEMPTS_NUM;
        }
        if (!Env::$e->HCAPTCHA_SECRET) { // если не указан ключ проверка валидна всегда
            return self::ATTEMPTS_NUM;
        }
        try {
            file_exists(dirname(self::ATTEMPTS_FILE)) or mkdir(dirname(self::ATTEMPTS_FILE), 0755);
            if (!file_exists(dirname(self::ATTEMPTS_FILE))) {
                throw new \Error('Path does not create');
            }
        } catch (\Throwable $th) {
            exit($th);
        }
        $file = new \SplFileObject(self::ATTEMPTS_FILE, 'a+b');
        $file->flock(LOCK_EX);
        $file->rewind();
        $data = $file->fread($file->getSize());
        $data = empty($data) ? [] : json_decode($data, true);
        $data = array_filter($data, function ($time) {
            if ($time + self::ATTEMPTS_TIME < time()) {
                return false;
            }
            return true;
        }, ARRAY_FILTER_USE_KEY);
        $file->ftruncate(0);
        $file->fwrite(json_encode($data));
        $file->flock(LOCK_UN);
        return self::ATTEMPTS_NUM - count(array_keys($data, $_SERVER['REMOTE_ADDR'])) > 0;
    }

    /**
     * записывает очередное взаимодействие(попытку)
     */
    public static function attempt()
    {
        try {
            file_exists(dirname(self::ATTEMPTS_FILE)) or mkdir(dirname(self::ATTEMPTS_FILE), 0755);
            if (!file_exists(dirname(self::ATTEMPTS_FILE))) {
                throw new \Error('Path does not create');
            }
        } catch (\Throwable $th) {
            exit($th);
        }
        $file = new \SplFileObject(self::ATTEMPTS_FILE, 'a+b');
        $file->flock(LOCK_EX);
        $file->rewind();
        $data = $file->fread($file->getSize());
        $data = empty($data) ? [] : json_decode($data, true);
        $data = array_filter($data, function ($time) {
            if ($time + self::ATTEMPTS_TIME < time()) {
                return false;
            }
            return true;
        }, ARRAY_FILTER_USE_KEY);
        $data[(string) microtime(true)] = $_SERVER['REMOTE_ADDR'];
        $file->ftruncate(0);
        $file->fwrite(json_encode($data));
        $file->flock(LOCK_UN);
    }
}
