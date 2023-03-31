<?php

/**
 * @file
 * @brief обработчик добавления нового модального окна
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});

header("Content-type: application/json");

$_POST['groups'] = array_map('intval', array_values(array_intersect(array_column(Wrong\Rights\Group::$groups_not_system, 'id'), empty($_POST['groups']) ? [] : array_keys($_POST['groups']))));

Wrong\Check\Model::create($_POST, 'modals');

$action_id = null;
if (!empty($_POST['add-action'])) {
    Wrong\Check\Model::create($_POST, 'actions', ['/api/modal' => '/api/action']);
    $action_id = Wrong\Models\Actions::create($_POST, ['/api/modal' => '/api/action']);
}

if ($modal_id = Wrong\Models\Modals::create($_POST)) {
    if (!empty($action_id)) {
        Wrong\Models\Modals::set_action($action_id, $modal_id);
    }
    exit(json_encode(['result' => 'ok', 'message' => 'Модальное окно успешно создано' . (!empty($action_id) ? ', дополнительно создано действие' : '')]));
}

exit(json_encode(['error' => 'Неизвестная ошибка! Возможно что-то не так с правами на создание файлов и каталогов.']));
