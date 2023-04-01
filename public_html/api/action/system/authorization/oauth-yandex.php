<?php

/**
 * @file
 * @brief обработчик oauth авторизации через яндекс
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';


if (isset($_POST['action'])) {
    $params = array(
        'client_id'     => Wrong\Start\Env::$e->YANDEX_OAUTH_CLIENT_ID,
        'redirect_uri'  => 'https://' . Wrong\Start\Env::$e->HTTP_HOST . '/api/action/' . $basename,
        'response_type' => 'code',
        'state'         => '123'
    );

    $url = 'https://oauth.yandex.ru/authorize?' . urldecode(http_build_query($params));
    exit("
        if (!window.open('$url','oauth','left='+Math.ceil((window.screen.width - 800)/2)+', top='+Math.ceil((window.screen.height-600)/2)+', width=800, height=600')) {
            location.href='$url';
        }
    ");
}

if (!empty($_GET['code'])) {
    $params = array(
        'client_id'     => Wrong\Start\Env::$e->YANDEX_OAUTH_CLIENT_ID,
        'client_secret' => Wrong\Start\Env::$e->YANDEX_OAUTH_CLIENT_SECRET,
        'grant_type'    => 'authorization_code',
        'code'          => $_GET['code']
    );

    $ch = curl_init('https://oauth.yandex.ru/token');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $data = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($data, true);
    if (!empty($data['access_token'])) {
        $ch = curl_init('https://login.yandex.ru/info');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('format' => 'json'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $data['access_token']));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $info = curl_exec($ch);
        curl_close($ch);

        $info = json_decode($info, true);

        if (!empty($info['default_email'])) {
            if ($user = Wrong\Auth\User::match($info['default_email'])) {
                Wrong\Auth\User::session($user->id);
                $user = new Wrong\Auth\User($user->id);
                $user->set_confirm(1);
            } else {
                if ($id = Wrong\Auth\User::session(Wrong\Models\Users::create($info['default_email'], substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'), 0, 10), Wrong\Start\Env::$e->GROUPS_USERS, Wrong\Start\Env::$e->OWNER_GROUP_USERS))) {
                    $user = new Wrong\Auth\User($id);
                    $user->set_confirm(1);
                    Wrong\Task\stackJS::add('$(function(){successToast("Приятной работы в системе!");});', 2, 'sign-up');
                }
            }
            if (Wrong\Rights\Group::is_available_group(Wrong\Models\Pages::find('/wrong', 'request'))) {
                exit('<!DOCTYPE html><html lang="en"><head><title>...</title></head><body><script>if(window.opener){window.opener.location.href="/wrong";window.close();}else{location.href="/wrong";}</script></body></html>');
            } else {
                exit('<!DOCTYPE html><html lang="en"><head><title>...</title></head><body><script>if(window.opener){window.opener.location.reload();window.close();}else{location.href="/";}</script></body></html>');
            }
        }
    }
}
