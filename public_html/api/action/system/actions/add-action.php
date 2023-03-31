<?php

/**
 * @file
 * @brief обработчик создания действия
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});

$_POST['groups'] = array_map('intval', array_values(array_intersect(array_column(Wrong\Rights\Group::$groups_not_system, 'id'), empty($_POST['groups']) ? [] : array_keys($_POST['groups']))));

Wrong\Check\Model::create($_POST, 'actions');

$modal_id = null;
if (!empty($_POST['add-modal'])) {
    Wrong\Check\Model::create($_POST, 'modals', ['/api/action' => '/api/modal']);
    $_POST['template_id'] = 5;
    $modal_id = Wrong\Models\Modals::create($_POST, ['/api/action' => '/api/modal']);
}

if ($action_id = Wrong\Models\Actions::create($_POST)) {
    if (!empty($modal_id)) {
        Wrong\Models\Modals::set_action($action_id, $modal_id);
    }
    exit(json_encode(['result' => 'ok', 'message' => 'Действие успешно создано' . (!empty($modal_id) ? ', дополнительно создано модальное окно' : '')]));
}

exit(json_encode(['error' => 'Неизвестная ошибка! Возможно что-то не так с правами на создание файлов и каталогов.']));
