<?php

/**
 * @file
 * @brief серверные api запросы
 * 
 */

namespace Wrong\Curl;

use Wrong\Start\Env;

/**
 * @brief API отвечает за http запросы к серверу
 * 
 */

class API
{
    /**
     * делает запрос на сервер и возвращает json раскодированный ответ в виде объекта либо строки в случае если раскодировка не удалась
     * 
     * @param string $request Запрос, который вы хотите сделать.
     * @param string $method Используемый HTTP-метод.
     * @param string $data urlencode данные для отправки на сервер.
     * @param headers массив заголовков для отправки с запросом
     * @param float $timeout таймаут выполнения в секундах, допускается использование дробных чисел 0.001 если запросу требуется отвалиться сразу же, не дожидаясь ответа
     * 
     * @return object|string Ответ на запрос.
     */
    public static function req($request, $method = 'GET', $data = '', $headers = [], $timeout = 0)
    {

        switch (Env::$e->SERVER_PORT) {
            case 80:
                $CURLOPT_URL = 'http://' . Env::$e->SERVER_ADDR . $request;
                break;
            case 443:
                $CURLOPT_URL = 'https://' . Env::$e->HTTP_HOST . $request;
                break;
            default:
                $CURLOPT_URL = 'http://' . Env::$e->HTTP_HOST . $request;
                break;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $CURLOPT_URL,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT_MS => $timeout * 1000,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array_merge(['Host: ' . Env::$e->HTTP_HOST], $headers)
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($response);
        return $json ?: $response;
    }

    /**
     * делает запрос на любое внешнее API и возвращает json раскодированный ответ в виде объекта либо строки в случае если раскодировка не удалась
     * 
     * @param string $url Запрос на который вы хотите сделать.
     * @param string $method Используемый HTTP-метод.
     * @param string $data urlencode данные для отправки на сервер.
     * @param headers массив заголовков для отправки с запросом
     * @param float $timeout таймаут выполнения в секундах, допускается использование дробных чисел 0.001 если запросу требуется отвалиться сразу же, не дожидаясь ответа
     * 
     * @return object|string Ответ на запрос.
     */
    public static function req_external($url, $method = 'GET', $data = '', $headers = [], $timeout = 0)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT_MS => $timeout * 1000,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($response);
        return $json ?: $response;
    }
}
