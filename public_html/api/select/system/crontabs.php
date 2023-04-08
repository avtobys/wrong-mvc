<?php

/**
 * @file
 * @brief выборка моделей типа "cron задача"
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

$table_name = basename($request);

$columns = ['id', 'threads', 'cli', 'request', 'user_id', 'shedule', 'method', 'headers', 'data', 'owner_group', 'run_at', 'note', 'act'];

$order_column = isset($_GET['order'][0]['column']) && isset($columns[$_GET['order'][0]['column']]) ? $columns[$_GET['order'][0]['column']] : $columns[0];
$order_dir = isset($_GET['order'][0]['dir']) && $_GET['order'][0]['dir'] == 'asc' ? 'ASC' : 'DESC';
$start = abs(intval($_GET['start']));
$length = intval($_GET['length']);

$query = "SELECT " . implode(', ', $columns) . " FROM `$table_name` ORDER BY `$order_column` $order_dir";

$sth = $dbh->prepare($query);
$sth->execute();
$arr = $sth->fetchAll(PDO::FETCH_NUM);

foreach ($arr as $key => $item) {
    $threads = json_decode($item[1], true) ?: Wrong\Task\Cron::DEFAULT_THERADS_SET;
    $threads['curr'] = intval(shell_exec("ps aux | grep 'php -f " . dirname(__DIR__, 3) . "/cron\.php " . $item[0] . "' | wc -l"));
    $text = "{$threads['curr']} / {$threads['min']} / {$threads['max']} / {$threads['load']}% / " . ($threads['fixed'] ? '+' : '-');
    $title = '<div class=\'text-left small\'>Потоков в работе: <b>' . $threads['curr'] . '</b><br>Минимум потоков: <b>' . $threads['min'] . '</b><br>Максимум потоков: <b>' . $threads['max'] . '</b><br>Предел нагрузки: <b>' . $threads['load'] . '%</b><br>Держать потоки: <b>' . ($threads['fixed'] ? 'да' : 'нет') . '</b></div>';
    $arr[$key][1] = '<div title="' . $title . '" class="edit-wrapper editable-act" data-toggle="modal" data-target="#edit-threads" data-id="' . $item[0] . '">' . $text . '<i class="fa fa-edit"></i></div>';
    $arr[$key][2] = htmlspecialchars($item[2], ENT_QUOTES);
    $arr[$key][2] = '<div title="' . $item[2] . '" class="edit-wrapper editable-act" data-id="' . $item[0] . '" data-target="#edit-cli" data-toggle="modal" data-table="' . $table_name . '">' . $item[2] . '<i class="fa fa-edit"></i></div>';
}

$arr = Wrong\Models\Selects::formatter($arr, $columns, $table_name);


$arr_filtered = $arr;

if (!empty($_GET['search']['value'])) {
    $searchable_columns = [];
    $arr_filtered = [];
    foreach ($_GET['columns'] as $key => $item) {
        if ($item['searchable']) {
            $searchable_columns[] = $key;
        }
    }

    if ($searchable_columns) {
        foreach ($arr as $key => $item) {
            $arr_search = array_intersect_key($item, $searchable_columns);
            foreach ($arr_search as $word) {
                if (mb_stripos(strip_tags($word), $_GET['search']['value']) !== false) {
                    $arr_filtered[] = $arr[$key];
                    continue 2;
                }
            }
        }
    }
}

$response = [];
$response['recordsTotal'] = count($arr);
$response['recordsFiltered'] = $response['recordsTotal'];
$length = $length == -1 ? $response['recordsTotal'] : $length;
$response['recordsFiltered'] = count($arr_filtered);
$response['data'] = array_slice($arr_filtered, $start, $length);
$response['draw'] = abs(intval($_GET['draw']));
if ($user->id == 1) {
    $response['uptime'] = shell_exec('uptime');
}


exit(json_encode($response, JSON_UNESCAPED_UNICODE));
