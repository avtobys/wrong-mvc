<?php

/**
 * @file
 * @brief обработчик удаления моделей и файлов моделей
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

set_time_limit(0);

header("Content-type: application/json");

if (!($row = Wrong\Database\Controller::find($_POST['id'], 'id', $_POST['table']))) {
    exit(json_encode(['error' => 'Ошибка']));
}

if (!$user->access()->write($row)) {
    exit(json_encode(['error' => 'Недостаточно прав']));
}

if ($user->access()->is_system($row)) {
    exit(json_encode(['error' => 'Системный функционал удалять нельзя!']));
}

$sth = $dbh->query("DELETE FROM `{$_POST['table']}` WHERE `id` = $row->id LIMIT 1");
if ($sth->rowCount()) {
    if ($_POST['table'] == 'crontabs') {
        $mem = new Wrong\Memory\Cache('cron');
        $mem->delete($row->id);
    }
    if (Wrong\Rights\Group::is_one_owner_file($row->file)) { // файл не используется другими владельцами
        if ($row->file && !Wrong\Database\Controller::find($row->file, 'file', $table) && !Wrong\File\Path::rm($_SERVER['DOCUMENT_ROOT'] . $row->file)) {
            exit(json_encode(['error' => 'Неизвестная ошибка! Возможно что-то не так с правами на создание файлов и каталогов.']));
        }
    }
    if ($_POST['table'] == 'groups') {
        Wrong\Rights\Group::delete_all_owner_models($id);
        foreach (['actions', 'modals', 'selects', 'pages', 'users', 'templates'] as $table) {
            foreach ($dbh->query("SELECT * FROM `$table`") as $row) {
                $arr = json_decode($row->groups);
                if (($key = array_search($id, $arr)) !== false) {
                    unset($arr[$key]);
                    $arr = array_values(array_unique(array_map('intval', $arr)));
                    $dbh->query("UPDATE `$table` SET `groups` = '" . json_encode($arr) . "' WHERE `id` = $row->id");
                }
            }
        }
    }
    exit(json_encode(['id' => $id, 'message' => $dbh->query("SHOW TABLE STATUS WHERE Name = '{$_POST['table']}'")->fetch()->Comment . ' - успешно удалено!']));
}

exit(json_encode(['error' => 'Неизвестная ошибка!']));
