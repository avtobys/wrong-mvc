<?php

/**
 * @file
 * @brief отправка email с сайта
 * 
 */

namespace Wrong\Mail;

use Wrong\Start\Env;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * @brief Send класс отвечает за отправку почты
 * 
 */

class Send
{
    /**
     * отправляет электронное письмо пользователю со ссылкой для сброса пароля.
     * 
     * @param object $user пользовательский объект
     */
    public static function forgot($user)
    {
        if (!Env::$e->EMAIL) return;
        $subject = 'Восстановление пароля ' . Env::$e->HTTP_HOST;
        $body = "Вы запросили восстановление пароля. Для этого перейдите по ссылке: " . (Env::$e->IS_SECURE ? 'https' : 'http') . "://" . Env::$e->HTTP_HOST . "/remind/{$user->id}/" . md5($user->email . $user->md5password);
        self::is_smtp() ? self::mail_smtp($user->email, $subject, $body) : self::mail($user->email, $subject, $body);
    }

    /**
     * отправляет электронное письмо пользователю со ссылкой для подтверждения своего адреса
     * электронной почты.
     * 
     * @param object $user пользовательский объект
     */
    public static function confirm($user)
    {
        $subject = 'Подтверждение почты ' . Env::$e->HTTP_HOST;
        $body = "Для подтверждения почты перейдите по ссылке: " . (Env::$e->IS_SECURE ? 'https' : 'http') . "://" . Env::$e->HTTP_HOST . "/email-confirm/{$user->id}/" . md5($user->email . $user->md5password);
        self::is_smtp() ? self::mail_smtp($user->email, $subject, $body) : self::mail($user->email, $subject, $body);
    }

    /**
     * Если установлены переменные среды SMTP, SMTP_HOST, SMTP_PORT, MAIL_USERNAME и SMTP_PASSWORD,
     * возвращает значение true. Проверка на отправку через smtp
     * 
     * @return bool
     */
    private static function is_smtp()
    {
        return Env::$e->SMTP && Env::$e->SMTP_HOST && Env::$e->SMTP_PORT && Env::$e->MAIL_USERNAME && Env::$e->SMTP_PASSWORD;
    }

    /**
     * Отправка почты стандартными функциями
     */
    private static function mail($email, $subject, $body)
    {
        if (!Env::$e->EMAIL) return;
        $subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $headers = "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: " . Env::$e->MAIL_USERNAME . "\r\n";
        mail(
            $email,
            $subject,
            $body,
            $headers,
            '-f ' . Env::$e->MAIL_USERNAME
        );
    }

    /**
     * отправка при помощи smtp
     */
    private static function mail_smtp($email, $subject, $body)
    {
        if (!Env::$e->EMAIL) return;
        $mail = new PHPMailer(true);
        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = Env::$e->SMTP_HOST;                         //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = Env::$e->MAIL_USERNAME;                     //SMTP username 
            $mail->Password   = Env::$e->SMTP_PASSWORD;                 //SMTP password пароль самой почты UPLuRu28*tuu
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = Env::$e->SMTP_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            $mail->CharSet = "utf-8";
            //Recipients
            $mail->setFrom(Env::$e->MAIL_USERNAME);
            $mail->addAddress($email);                                  //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->send();
        } catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
