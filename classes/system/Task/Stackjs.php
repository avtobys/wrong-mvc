<?php

/**
 * @file
 * @brief javascript стеки
 * 
 */

namespace Wrong\Task;

/**
 * @brief Stackjs класс добавляющий и вызывающий javascript стеки
 * 
 */

class Stackjs
{
    /**
     * Добавляет js код в стек
     * 
     * @param string $code строка с javascript кодом
     * @param int $timeout таймаут в секундах спустя который код будет добавлен в html страницу
     * @param string $key ключ в массиве во избежание дублирования кодов
     */
    public static function add($code, $timeout = 0, $key = '')
    {
        $_SESSION['Stackjs'] = empty($_SESSION['Stackjs']) ? [] : $_SESSION['Stackjs'];
        if ($key) {
            $_SESSION['Stackjs'][$key] = [
                'code' => $code,
                'timeout' => $timeout,
                'timestamp' => time()
            ];
        } else {
            $_SESSION['Stackjs'][] = [
                'code' => $code,
                'timeout' => $timeout,
                'timestamp' => time()
            ];
        }
    }

    /**
     * возвращает строки кода javascript, которые были добавлены в стек, и должны быть исполнена по таймауту(время вышло)
     * удаляет возвращенные элементы из массива, и возвращает функцию с очередным js таймаутом если есть в стеке ещё задачи
     */
    public static function execute()
    {
        if (empty($_SESSION['Stackjs'])) {
            return '';
        }
        $code = '';
        foreach ($_SESSION['Stackjs'] as $key => $value) {
            if ($value['timestamp'] + $value['timeout'] <= time()) {
                $code .= $value['code'];
                unset($_SESSION['Stackjs'][$key]);
            }
        }
        $code .= self::set();
        return $code;
    }

    /**
     * возвращает javascript код с таймаутом, спустя который будет запрошен js код исполнения из апи.
     */
    public static function set()
    {
        if (empty($_SESSION['Stackjs'])) {
            return '';
        }
        $arr = [];
        foreach ($_SESSION['Stackjs'] as $key => $value) {
            if ($value['timestamp'] + $value['timeout'] <= time()) {
                $arr[] = 0;
            } else {
                $arr[] = $value['timestamp'] + $value['timeout'] - time();
            }
        }
        if ($arr) {
            $timeout = min($arr) * 1000;
            return "setTimeout(()=>{\$.getScript('/api/action/stackjs');}, $timeout);";
        }
        return '';
    }
}
