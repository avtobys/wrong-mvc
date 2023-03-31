<?php

/**
 * @file
 * @brief выборка моделей типа "модальное окно"
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

$table_name = basename($request);

$response['recordsFiltered'] = $response['recordsTotal'];

$columns = ['id', 'request', 'file', 'groups', 'owner_group', 'note', 'act'];

$order_column = isset($_GET['order'][0]['column']) && isset($columns[$_GET['order'][0]['column']]) ? $columns[$_GET['order'][0]['column']] : $columns[0];
$order_dir = isset($_GET['order'][0]['dir']) && $_GET['order'][0]['dir'] == 'asc' ? 'ASC' : 'DESC';
$start = abs(intval($_GET['start']));
$length = intval($_GET['length']);

$query = "SELECT " . implode(', ', $columns) . " FROM `$table_name` ORDER BY `$order_column` $order_dir";

$sth = $dbh->prepare($query);
$sth->execute();
$arr = $sth->fetchAll(PDO::FETCH_NUM);

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

exit(json_encode($response, JSON_UNESCAPED_UNICODE));
