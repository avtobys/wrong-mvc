<?php

/**
 * @file
 * @brief выборка логов действий
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

$table_name = basename($request);

$columns = ['user_id', 'request', 'text', 'date_created', 'ip'];

$order_column = isset($_GET['order'][0]['column']) && isset($columns[$_GET['order'][0]['column']]) ? $columns[$_GET['order'][0]['column']] : $columns[0];
$order_dir = isset($_GET['order'][0]['dir']) && $_GET['order'][0]['dir'] == 'asc' ? 'ASC' : 'DESC';
$start = abs(intval($_GET['start']));
$length = intval($_GET['length']);

$query = "SELECT " . implode(', ', $columns) . " FROM `$table_name` ORDER BY `$order_column` $order_dir";

$sth = $dbh->prepare($query);
$sth->execute();
$arr = $sth->fetchAll(PDO::FETCH_NUM);

$hide_ip = !Wrong\Rights\Group::is_available_group(Wrong\Models\Actions::find(24));

foreach ($arr as $key => $item) {
    $arr[$key][0] = Wrong\Models\Users::find($item[0])->email . ' (ID: ' . $item[0] . ')';
    $arr[$key][2] = '<pre class="log-show" style="max-width:1000px;white-space:normal;margin:0;display:none;-webkit-line-clamp: 1;-webkit-box-orient:vertical;overflow: hidden;text-overflow: ellipsis;">' . strip_tags($item[2]) . '</pre>';
    if ($hide_ip) {
        $arr[$key][4] = '******';
    }
}

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
