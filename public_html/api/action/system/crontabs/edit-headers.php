<?php

/**
 * @file
 * @brief обработчик редактирования заголовков cron задачи
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});


if (!($row = Wrong\Models\Crontabs::find($_POST['id']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if (!$user->access()->write($row)) {
    exit(json_encode(['error' => 'Недостаточно прав!']));
}

$_POST['headers'] = array_map('trim', $_POST['headers']);
$_POST['headers'] = array_filter($_POST['headers']);
foreach ($_POST['headers'] as $key => $item) {
    $arr = explode(':', $item, 2);
    $_POST['headers'][$key] = trim($arr[0]) . ': ' . trim($arr[1]);
}
$_POST['headers'] = json_encode($_POST['headers']);

$sth = $dbh->prepare("UPDATE `crontabs` SET `headers` = :headers WHERE `id` = :id");
$sth->bindValue(':headers', $_POST['headers']);
$sth->bindValue(':id', $row->id);
$sth->execute();

if ($sth->errorCode() == '00000') {
    $mem = new Wrong\Memory\Cache('cron');
    $mem->delete($row->id);
    exit(json_encode(['result' => 'ok', 'message' => 'Заголовки успешно установлены']));
}

exit(json_encode(['error' => 'Ошибка']));
