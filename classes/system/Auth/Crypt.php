<?php

/**
 * @file
 * @brief шифрование и расшифровка идентификатора пользователя
 * 
 * шифрование используется для авторизации по $_COOKIE['UID] и $_COOKIE['FROM_UID], для создания уникального ключа шифрования используется пароль базы данных, имя базы данных и имя хоста.
 */


namespace Wrong\Auth;

use Wrong\Start\Env;

/**
 * @brief Crypt отвечает за шифрование и расшифровку идентификатора пользователя
 * 
 */
class Crypt
{
    /**
     * шифрует идентификатор.
     * 
     * @param int $id Идентификатор для шифрования
     * 
     * @return string $ciphertext Строка символов, представляющая собой зашифрованную версию идентификатора.
     */
    public static function idEncrypt($id)
    {
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($id, $cipher, Env::$e->SYSTEM_SECRET_KEY, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, Env::$e->SYSTEM_SECRET_KEY, $as_binary = true);
        $ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);
        return $ciphertext;
    }

    /**
     * расшифровывает идентификатор.
     * 
     * @param string $hash Зашифрованная строка
     * 
     * @return int $id Идентификатор пользователя.
     */
    public static function idDecrypt($hash)
    {
        $c = base64_decode($hash);
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len = 32);
        $ciphertext_raw = substr($c, $ivlen + $sha2len);
        $plaintext = openssl_decrypt($ciphertext_raw, $cipher, Env::$e->SYSTEM_SECRET_KEY, $options = OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, Env::$e->SYSTEM_SECRET_KEY, $as_binary = true);
        if (hash_equals($hmac, $calcmac)) {
            return intval($plaintext);
        }
        return 0;
    }
}
