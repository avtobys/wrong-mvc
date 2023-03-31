<?php

/**
 * @file
 * @brief обработчик удаления моделей и файлов моделей
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

set_time_limit(0);

header("Content-type: application/json");

$_POST = array_map(function ($item) {
    if (is_string($item)) {
        return trim($item);
    } else {
        return $item;
    }
}, $_POST);

if (!($table = Wrong\Database\Controller::table($_POST['table']))) {
    exit(json_encode(['error' => 'Ошибка!']));
}

$id = abs(intval($_POST['id']));

$sth = $dbh->prepare("SELECT * FROM `$table` WHERE `id` = ?");
$sth->execute([$id]);
$row = $sth->fetch();

if (!isset($row->owner_group) || (!in_array($row->owner_group, $user->groups) && !in_array($row->owner_group, $user->subordinate_groups))) {
    if (in_array($row->owner_group, array_column(Wrong\Rights\Group::$groups, 'id'))) {
        exit(json_encode(['error' => 'Недостаточно прав']));
    }
}

if ($row->owner_group == 1) {
    exit(json_encode(['error' => 'Системный функционал удалять нельзя!']));
}

$table_comment = $dbh->query("SHOW TABLE STATUS WHERE Name = '$table'")->fetch()->Comment;

$sth = $dbh->query("DELETE FROM `$table` WHERE `id` = $row->id LIMIT 1");
if ($sth->rowCount()) {
    if (Wrong\Rights\Group::is_one_owner_file($row->file)) { // файл не используется другими владельцами
        if ($row->file && !Wrong\Database\Controller::find($row->file, 'file', $table) && !Wrong\File\Path::rm($_SERVER['DOCUMENT_ROOT'] . $row->file)) {
            exit(json_encode(['error' => 'Неизвестная ошибка! Возможно что-то не так с правами на создание файлов и каталогов.']));
        }
    }
    if ($table == 'groups') {
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
    exit(json_encode(['id' => $id, 'message' => $table_comment . ' - успешно удалено!']));
}

exit(json_encode(['error' => 'Неизвестная ошибка!']));
