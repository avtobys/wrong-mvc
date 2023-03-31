<?php

/**
 * @file
 * @brief обновление данных о новых комментариях anycomment.io
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

$response = Wrong\Curl\API::req_external('https://anycomment.io/v1/client/comment?token=' . Wrong\Start\Env::$e->ANYCOMMENT_SECRET . '&url=https://' . Wrong\Start\Env::$e->HTTP_HOST . '/comments');

if (!$response->_meta) {
    exit(json_encode(['error' => 'Ошибка запроса']));
}

$data = file_exists($_SERVER['DOCUMENT_ROOT'] . '/../temp/comments') ? json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/../temp/comments'), true) : ['new' => 0, 'old' => 0];
if (isset($_POST['readed'])) {
    $data['new'] = 0;
    $data['old'] = $response->_meta->totalCount;
} else {
    $data['new'] = $response->_meta->totalCount - $data['old'];
    $data['new'] = $data['new'] > 0 ? $data['new'] : 0;
}
file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/../temp/comments', json_encode($data));
exit(json_encode(['result' => 'ok', 'message' => 'Успешно обновлено']));
