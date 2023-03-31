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
        $crypt_key = md5(Env::$e->DB_PASSWORD . Env::$e->DB_DATABASE . Env::$e->HTTP_HOST . __FILE__);
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($id, $cipher, $crypt_key, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $crypt_key, $as_binary = true);
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
        $crypt_key = md5(Env::$e->DB_PASSWORD . Env::$e->DB_DATABASE . Env::$e->HTTP_HOST . __FILE__);
        $c = base64_decode($hash);
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len = 32);
        $ciphertext_raw = substr($c, $ivlen + $sha2len);
        $plaintext = openssl_decrypt($ciphertext_raw, $cipher, $crypt_key, $options = OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $crypt_key, $as_binary = true);
        if (hash_equals($hmac, $calcmac)) {
            return intval($plaintext);
        }
        return 0;
    }
}
