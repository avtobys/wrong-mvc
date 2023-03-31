<?php

/**
 * @file
 * @brief обработчик импорта модели
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

if (empty($_FILES) || !preg_match('#\.zip$#', $_FILES['file']['name'])) {
    exit(json_encode(['error' => 'Ошибка, zip архив не найден']));
}

$zip = new ZipArchive;
$res = $zip->open($_FILES['file']['tmp_name']);
if (!$res) {
    exit(json_encode(['error' => 'Ошибка открытия архива']));
}

isset($zipname) && unlink($zipname);

$table = $zip->getFromName('name.txt');

if (in_array(!$table, ['actions', 'modals', 'selects', 'templates', 'pages', 'crontabs'])) {
    exit(json_encode(['error' => 'Ошибка, таблица не существует']));
}

$model = json_decode($zip->getFromName('model.json'));

if (!$model) {
    exit(json_encode(['error' => 'Ошибка парсинга модели']));
}

$fields = $dbh->query("SHOW COLUMNS FROM $table")->fetchAll(PDO::FETCH_COLUMN);
$model_fields = array_keys(get_object_vars($model));

if ($fields != $model_fields) {
    exit(json_encode(['error' => 'Ошибка парсинга модели, ключи объектов не совпадают!']));
}

if (empty($model->owner_group) || (!in_array($model->owner_group, $user->subordinate_groups) && $model->owner_group != $user->main_group_id)) {
    exit(json_encode(['error' => 'Недостаточно прав для импорта модели с данной группой владельцем']));
}

if ($model->owner_group == 1) {
    $model->owner_group = 2;
}

if (($models_limit = Wrong\Database\Controller::find($model->owner_group, 'id', 'groups')->models_limit) && $models_limit <= Wrong\Rights\Group::count_all_owner_models($model->owner_group)) {
    exit(json_encode(['error' => 'Лимит моделей для данной группы исчерпан']));
}

if ($model->request) {
    do {
        $model->request .= '-copy';
    } while (Wrong\Database\Controller::count($model->request, 'request', $table));
}

if ($model->file) {
    $file_data = $zip->getFromName(basename($model->file));
    if (!$file_data) {
        exit(json_encode(['error' => 'Ошибка парсинга php файла']));
    }

    do {
        $model->file = dirname($model->file) . '/' . basename($model->file, '.php') . '-copy.php';
    } while (Wrong\Database\Controller::count($model->file, 'file', $table));

    $dir = $_SERVER['DOCUMENT_ROOT'] . dirname($model->file);
    if (!file_exists($dir) && !mkdir($dir, 0755, true)) {
        exit(json_encode(['error' => 'Ошибка при создании каталога ' . $dir]));
    }
}

$zip->close();

if ($model->name) {
    do {
        $model->name .= ' - копия';
    } while (Wrong\Database\Controller::count($model->name, 'name', $table));
}

unset($model->id);

if ($model->act && $table != 'templates') {
    $model->act = 0;
}

$sql = "INSERT INTO $table (" . implode(', ', array_keys(get_object_vars($model))) . ") VALUES (" . implode(', ', array_map(function ($item) {
    return ':' . $item;
}, array_keys(get_object_vars($model)))) . ")";

$sth = $dbh->prepare($sql);
foreach ($model as $key => $value) {
    $sth->bindValue(":$key", $value);
}
$sth->execute();
if ($id = $dbh->lastInsertId()) {
    if ($model->file && !file_put_contents($_SERVER['DOCUMENT_ROOT'] . $model->file, $file_data)) {
        $dbh->query("DELETE FROM $table WHERE `id` = $id");
        exit(json_encode(['error' => 'Ошибка импорта']));
    }
    exit(json_encode(['result' => 'ok', 'message' => 'Импорт успешно завершен!', 'location' => '/' . $table]));
}

exit(json_encode(['error' => 'Ошибка импорта']));
