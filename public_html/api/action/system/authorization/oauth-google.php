<?php

/**
 * @file
 * @brief обрабочик oauth авторизации через google
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

if (isset($_POST['action'])) {
    $params = array(
        'client_id'     => Wrong\Start\Env::$e->GOOGLE_OAUTH_CLIENT_ID,
        'redirect_uri'  => 'https://' . Wrong\Start\Env::$e->HTTP_HOST . '/api/action/' . $basename,
        'response_type' => 'code',
        'scope'         => 'https://www.googleapis.com/auth/userinfo.email',
        'state'         => '123'
    );

    $url = 'https://accounts.google.com/o/oauth2/auth?' . urldecode(http_build_query($params));
    exit("
        if (!window.open('$url','oauth','left='+Math.ceil((window.screen.width - 800)/2)+', top='+Math.ceil((window.screen.height-600)/2)+', width=800, height=600')) {
            location.href='$url';
        }
    ");
}

if (!empty($_GET['code'])) {
    $params = array(
        'client_id'     => Wrong\Start\Env::$e->GOOGLE_OAUTH_CLIENT_ID,
        'client_secret' => Wrong\Start\Env::$e->GOOGLE_OAUTH_CLIENT_SECRET,
        'redirect_uri'  => 'https://' . Wrong\Start\Env::$e->HTTP_HOST . '/api/action/' . $basename,
        'grant_type'    => 'authorization_code',
        'code'          => $_GET['code']
    );

    $ch = curl_init('https://accounts.google.com/o/oauth2/token');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $data = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($data, true);
    if (!empty($data['access_token'])) {
        $params = array(
            'access_token' => $data['access_token'],
            'id_token'     => $data['id_token'],
            'token_type'   => 'Bearer',
            'expires_in'   => 3599
        );

        $info = file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?' . urldecode(http_build_query($params)));
        $info = json_decode($info, true);

        if (!empty($info['email'])) {
            if ($user = Wrong\Auth\User::match($info['email'])) {
                Wrong\Auth\User::session($user->id);
                $user = new Wrong\Auth\User($user->id);
                $user->set_confirm(1);
            } else {
                if ($id = Wrong\Auth\User::session(Wrong\Models\Users::create($info['email'], substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'), 0, 10), Wrong\Start\Env::$e->GROUPS_USERS, Wrong\Start\Env::$e->OWNER_GROUP_USERS))) {
                    $user = new Wrong\Auth\User($id);
                    $user->set_confirm(1);
                    Wrong\Task\stackJS::add('$(function(){successToast("Приятной работы в системе!");});', 2, 'sign-up');
                }
            }
            exit('<!DOCTYPE html><html lang="en"><head><title>...</title></head><body><script>if(window.opener){window.opener.location.reload();window.close();}else{location.href="/";}</script></body></html>');
        }
    }
}
