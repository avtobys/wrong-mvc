<?php

/**
 * @file
 * @brief обработчик очистки группы от всех моделей
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

set_time_limit(0);

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});

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

Wrong\Rights\Group::delete_all_owner_models($id);
exit(json_encode(['id' => $id, 'message' => $table_comment . ' - успешно очищено!']));

