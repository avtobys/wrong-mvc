<?php

/**
 * @file
 * @brief контроллер управления моделями выборок и форматирование вывода в таблицах
 * 
 */

namespace Wrong\Models;

use Wrong\Database\Controller;
use Wrong\Database\Connect;
use Wrong\File\Path;
use Wrong\Rights\Group;
use Wrong\Models\Actions;
use Wrong\Start\Env;
use Cron\CronExpression;
use Wrong\Models\Users;

/**
 * @brief Selects контроллер управления моделями выборок, расширяет Controller
 * 
 */

class Selects extends Controller implements ModelsInterface
{

    /**
     * создает в бд запись для новой модели типа "выборка" и копирует указанный файл шаблона
     * 
     * @param array $arr массив данных модели
     * @param array $replace_path массив путей для замены в файле и параметры запроса.
     * 
     * @return int Последний вставленный идентификатор.
     */
    public static function create($arr, $replace_path = [])
    {
        if ($replace_path) {
            $arr['file'] = strtr($arr['file'], $replace_path);
            $arr['request'] = strtr($arr['request'], $replace_path);
        }
        Path::mkdir($_SERVER['DOCUMENT_ROOT'] . $arr['file']);
        $arr['template_filename'] = Templates::all_available($arr['template_id'])[0]->file;
        if (copy($_SERVER['DOCUMENT_ROOT'] . $arr['template_filename'], $_SERVER['DOCUMENT_ROOT'] . $arr['file'])) {
            $sth = Connect::$dbh->prepare("INSERT INTO `selects` (`request`, `file`, `groups`, `owner_group`) VALUES (:request, :file, :groups, :owner_group)");
            $arr['groups'] = json_encode($arr['groups']);
            $sth->bindValue(':request', $arr['request']);
            $sth->bindValue(':file', $arr['file']);
            $sth->bindValue(':groups', $arr['groups']);
            $sth->bindValue(':owner_group', $arr['owner_group']);
            $sth->execute();
            return Connect::$dbh->lastInsertId();
        } else {
            Path::rmdir($_SERVER['DOCUMENT_ROOT'] . $arr['file']);
        }
    }

    /**
     * форматирует данные для таблиц выборок.
     * 
     * @param array $arr Массив данных таблицы для форматирования.
     * @param array $columns массив имен столбцов
     * @param string $table Имя таблицы для редактирования.
     * 
     * @return array $arr отформатированный массив данных
     */
    public static function formatter($arr, $columns, $table)
    {
        global $user;

        $arr = self::filter($arr, $columns, $table);

        $key_column = array_search('owner_group', $columns);
        if (Env::$e->SUBORDINATE_MODELS) {
            foreach ($arr as $key => $row) {
                $owner_group = $row[$key_column];
                if (!in_array($owner_group, $user->subordinate_groups)) {
                    unset($arr[$key]);
                }
            }
        }

        if (Env::$e->HIDE_NON_ACTIVE_GROUP_MODELS) {
            foreach ($arr as $key => $row) {
                $owner_group = $row[$key_column];
                if (!Group::is_active($owner_group)) {
                    unset($arr[$key]);
                }
            }
        }

        if (in_array($table, ['actions', 'modals', 'selects', 'templates', 'pages'])) {
            $key_file = array_search('file', $columns);
            foreach ($arr as $key => $row) {
                $arr[$key][] = '<a title="<div class=\'text-left small\'>Файл: ' . $arr[$key][$key_file] . '<br>Последнее изменение: ' . date('Y-m-d H:i:s', filemtime($_SERVER['DOCUMENT_ROOT'] . $arr[$key][$key_file])) . '<div>" data-id="' . $row[0] . '" data-table="' . $table . '" data-target="#edit-code" data-toggle="modal" class="d-block text-center editable-act px-1" href="#"><i class="fa fa-file-code-o"></i></a>';
            }
        }

        if (in_array($table, ['actions', 'modals', 'selects', 'templates', 'pages', 'crontabs'])) {
            if ($table == 'crontabs') {
                foreach ($arr as $key => $row) {
                    $arr[$key][] = '<a title="Выполнить задачу сейчас" data-id="' . $row[0] . '" data-table="' . $table . '" data-copy="true" data-precallback="precallbackShedule" data-callback="callbackAction" data-action="' . Actions::name(46) . '" data-confirm="true" data-header="Выполнить сейчас <b>ID ' . $row[0] . '</b>?" data-body="Выполнить задачу <b>ID ' . $row[0] . '</b> сейчас? Задача будет выполнена в одном потоке, вне зависимости от настроек потоков." class="d-block text-center editable-act px-1" href="#"><i class="fa fa-play"></i></a>';
                }
            }
            foreach ($arr as $key => $row) {
                $arr[$key][] = '<a title="Копировать модель" data-id="' . $row[0] . '" data-table="' . $table . '" data-copy="true" data-callback="callbackAction" data-action="' . Actions::name(37) . '" data-confirm="true" data-header="Копировать <b>ID ' . $row[0] . '</b>?" data-body="Копировать модель <b>ID ' . $row[0] . '</b> и добавить копию?" class="d-block text-center editable-act px-1" href="#"><i class="fa fa-copy"></i></a>';
                $arr[$key][] = '<a title="Экспорт модели" data-id="' . $row[0] . '" data-table="' . $table . '" data-action="' . Actions::name(37) . '" data-confirm="false" data-header="Экспорт <b>ID ' . $row[0] . '</b>?" data-body="Экспортировать модель <b>ID ' . $row[0] . '</b>?" data-response="script" class="d-block text-center editable-act px-1" href="#"><i class="fa fa-download"></i></a>';
            }
        }

        if ($table == 'groups') {
            foreach ($arr as $key => $row) {
                if ($key_column && $arr[$key][$key_column] == 1) {
                    $arr[$key][] = '<a title="Очистить от моделей" onclick="errorToast(\'Системный функционал удалять нельзя!\');setTimeout(()=>{$(\'.editable\').removeClass(\'editable\');},100);return false;" class="text-danger d-block text-center editable-act px-1" href="#"><i class="fa fa-eraser"></i></a>';
                } else {
                    $arr[$key][] = '<a title="Очистить от моделей" data-id="' . $row[0] . '" data-table="' . $table . '" data-action="' . Actions::name(36) . '" data-confirm="true" data-header="Очистить <b>ID ' . $row[0] . '</b>?" data-body="Очистить <b>ID ' . $row[0] . '</b> от всех принадлежащих группе моделей? ' . ($table == 'groups' ? '<b>Внимание!</b> Все модели, файлы, группы, пользователи и функционал принадлежащий данной группе будут удалены!' : '') . '" data-callback="afterRemoved" class="text-danger d-block text-center editable-act px-1" href="#"><i class="fa fa-eraser"></i></a>';
                }
            }
        }

        foreach ($arr as $key => $row) {
            if ($key_column && $arr[$key][$key_column] == 1) {
                $arr[$key][] = '<a title="Удалить" onclick="errorToast(\'Системный функционал удалять нельзя!\');setTimeout(()=>{$(\'.editable\').removeClass(\'editable\');},100);return false;" class="text-danger d-block text-center editable-act px-1" href="#"><i class="fa fa-trash"></i></a>';
            } else {
                $arr[$key][] = '<a title="Удалить" data-id="' . $row[0] . '" data-table="' . $table . '" data-action="' . Actions::name(9) . '" data-confirm="true" data-header="Удалить <b>ID ' . $row[0] . '</b>?" data-body="Удалить <b>ID ' . $row[0] . '</b> навсегда из системы? ' . ($table == 'groups' ? '<b>Внимание!</b> Все модели, файлы, группы, пользователи и функционал принадлежащий данной группе будут тоже удалены!' : '') . '" data-callback="afterRemoved" class="text-danger d-block text-center editable-act px-1" href="#"><i class="fa fa-trash"></i></a>';
            }
        }

        if ($table == 'users') {
            $owner_column = array_search('owner_group', $columns);
            $key_column = array_search('x_auth_token', $columns);
            foreach ($arr as $key => $item) {
                $owner_group = $item[$owner_column];
                if (in_array($owner_group, $user->subordinate_groups) || $owner_group == $user->main_group_id || $item[$key_column] == $user->x_auth_token) {
                    $arr[$key][$key_column] = '<div class="copy-text">' . $item[$key_column] . '</div>';
                } else {
                    $arr[$key][$key_column] = '***';
                }
            }

            $key_column = array_search('email', $columns);
            foreach ($arr as $key => $item) {
                $owner_group = $item[$owner_column];
                if (in_array($owner_group, $user->subordinate_groups) || $owner_group == $user->main_group_id || $item[$key_column] == $user->email) {
                    $arr[$key][$key_column] = '<div class="copy-text">' . $item[$key_column] . '</div>';
                } else {
                    $arr[$key][$key_column] = '***';
                }
            }

            $key_column = array_search('ip', $columns);
            foreach ($arr as $key => $item) {
                $owner_group = $item[$owner_column];
                if (!in_array($owner_group, $user->subordinate_groups) && $owner_group != $user->main_group_id && $item[$key_column] != $user->ip) {
                    $arr[$key][$key_column] = '***';
                }
            }
        }

        if (($key_column = array_search('act', $columns)) && ($key_owner = array_search('owner_group', $columns))) {
            foreach ($arr as $key => $item) {
                $data_system = $item[$key_owner] == 1 ? 'onclick="$(this).data(\'confirm\', $(this).prev().is(\':checked\'));" data-confirm="false" data-header="Предупреждение" data-body="Владелец Система. Отключение системного функционала может привести к нежелательным последствиям! Отключить всё равно?"' : '';
                $arr[$key][$key_column] = '<input class="tgl tgl-flip" id="tgl-' . $item[0] . '" type="checkbox" ' . ($item[$key_column] ? 'checked' : '') . '>
                    <label title="' . ($item[$key_column] ? 'Выключить' : 'Включить') . '" data-owner="' . intval(in_array($item[$key_owner], $user->groups)) . '" data-action="' . Actions::name(6) . '" ' . $data_system . ' data-table="' . $table . '" data-id="' . $item[0] . '" data-callback="toggled" class="tgl-btn mx-auto" data-tg-off="Выкл" data-tg-on="Вкл" for="tgl-' . $item[0] . '"></label>';
            }
        }

        if ($key_column = array_search('groups', $columns)) {
            foreach ($arr as $key => $item) {
                $arr[$key][$key_column] = Group::groupNamesText($item[$key_column]);
                $arr[$key][$key_column] = '<div class="edit-wrapper editable-act edit-wrapper-text" data-id="' . $item[0] . '" data-target="#edit-groups" data-toggle="modal" data-table="' . $table . '" title="' . $arr[$key][$key_column] . '">' . $arr[$key][$key_column] . '<i class="fa fa-edit"></i></div>';
            }
        }

        if ($key_column = array_search('owner_group', $columns)) {
            foreach ($arr as $key => $item) {
                $arr[$key][$key_column] = Group::text($item[$key_column]);
                $arr[$key][$key_column] = '<div class="edit-wrapper editable-act" data-id="' . $item[0] . '" data-target="#edit-owner" data-toggle="modal" data-table="' . $table . '">' . $arr[$key][$key_column] . '<i class="fa fa-edit"></i></div>';
            }
        }

        if ($key_column = array_search('file', $columns)) {
            foreach ($arr as $key => $item) {
                $arr[$key][$key_column] = '<div title="' . $item[$key_column] . '" class="edit-wrapper editable-act px-1" data-id="' . $item[0] . '" data-target="#edit-file" data-toggle="modal" data-table="' . $table . '">' . $item[$key_column] . '<i class="fa fa-edit"></i></div>';
            }
        }

        if (($key_column = array_search('request', $columns)) && $table != 'users') {
            foreach ($arr as $key => $item) {
                $arr[$key][$key_column] = '<div title="' . $item[$key_column] . '" class="edit-wrapper edit-wrapper-text editable-act" data-id="' . $item[0] . '" data-target="#edit-request" data-toggle="modal" data-table="' . $table . '">' . $item[$key_column] . '<i class="fa fa-edit"></i></div>';
            }
        }


        if ($key_column = array_search('name', $columns)) {
            foreach ($arr as $key => $item) {
                $arr[$key][$key_column] = '<div class="edit-wrapper edit-wrapper-text editable-act" data-id="' . $item[0] . '" data-target="#edit-name" data-toggle="modal" data-table="' . $table . '" title="' . $arr[$key][$key_column] . '">' . $item[$key_column] . '<i class="fa fa-edit"></i></div>';
            }
        }

        if ($key_column = array_search('note', $columns)) {
            foreach ($arr as $key => $item) {
                $arr[$key][$key_column] = '<div title="' . nl2br($item[$key_column]) . '" class="edit-wrapper edit-wrapper-text editable-act" data-id="' . $item[0] . '" data-target="#edit-note" data-toggle="modal" data-table="' . $table . '">' . nl2br($item[$key_column]) . '<i class="fa fa-edit"></i></div>';
            }
        }

        if ($key_column = array_search('cache_time', $columns)) {
            foreach ($arr as $key => $item) {
                $arr[$key][$key_column] = '<div title="Время кеширования: ' . $item[$key_column] . ' сек." class="edit-wrapper edit-wrapper-text editable-act text-nowrap" data-id="' . $item[0] . '" data-target="#edit-cache-time" data-toggle="modal" data-table="' . $table . '">' . $item[$key_column] . ' сек.<i class="fa fa-edit"></i></div>';
            }
        }

        if ($table == 'groups') {
            if ($key_column = array_search('weight', $columns)) {
                foreach ($arr as $key => $item) {
                    $arr[$key][$key_column] = '<div class="edit-wrapper editable-act" data-id="' . $item[0] . '" data-target="#edit-weight" data-toggle="modal" data-table="' . $table . '">' . $item[$key_column] . '<i class="fa fa-edit"></i></div>';
                }
            }

            if ($key_column = array_search('models_limit', $columns)) {
                foreach ($arr as $key => $item) {
                    $arr[$key][$key_column] = '<div class="edit-wrapper editable-act" data-id="' . $item[0] . '" data-target="#edit-models-limit" data-toggle="modal" data-table="' . $table . '">' . ($item[$key_column] ? $item[$key_column] : 'Без лимита') . '<i class="fa fa-edit"></i></div>';
                }
            }

            if ($key_column = array_search('\'count_active_models\'', $columns)) {
                foreach ($arr as $key => $item) {
                    $arr[$key][$key_column] = '<div class="text-center" style="margin-left:-25px;">' . Group::count_all_owner_models($item[0]) . ' / ' . Group::count_all_owner_models($item[0], true) . '</div>';
                }
            }

            if ($key_column = array_search('\'count_available_models\'', $columns)) {
                foreach ($arr as $key => $item) {
                    $arr[$key][$key_column] = Group::count_all_available_models($item[0]);
                }
            }
        }

        if ($table == 'pages') {
            if ($key_column = array_search('template_id', $columns)) {
                foreach ($arr as $key => $item) {
                    $arr[$key][$key_column] = '<div class="edit-wrapper editable-act" data-id="' . $item[0] . '" data-target="#edit-template" data-toggle="modal" data-table="' . $table . '">' . Templates::find($item[$key_column])->name . '<i class="fa fa-edit"></i></div>';
                }
            }

            $key_column = array_search('id', $columns);
            foreach ($arr as $key => $item) {
                $arr[$key][$key_column] = '<a title="Посмотреть страницу" class="link-wrapper" data-toggle="modal" data-target="#view-page" data-uri="' . Pages::find($item[$key_column])->request . '" href="#">' . $item[$key_column] . '<i class="fa fa-external-link"></i></a>';
            }
        }

        if ($table == 'actions') {
            $key_column = array_search('id', $columns);
            foreach ($arr as $key => $item) {
                $arr[$key][$key_column] = '<a title="Конструктор триггера" class="link-wrapper" data-toggle="modal" data-name="' . Actions::name($item[$key_column]) . '" data-target="#construct-action" href="#">' . $item[$key_column] . '<i class="fa fa-cubes"></i></a>';
            }
        }

        if ($table == 'modals') {
            $key_column = array_search('id', $columns);
            foreach ($arr as $key => $item) {
                $arr[$key][$key_column] = '<a title="Конструктор триггера" class="link-wrapper" data-toggle="modal" data-name="' . Modals::name($item[$key_column]) . '" data-target="#construct-modal" href="#">' . $item[$key_column] . '<i class="fa fa-cubes"></i></a>';
            }
        }

        if (($key_column = array_search('logs', $columns))) {
            foreach ($arr as $key => $item) {
                $arr[$key][$key_column] = '<input class="tgl tgl-flip" id="tgl-log-' . $item[0] . '" type="checkbox" ' . ($item[$key_column] ? 'checked' : '') . '>
                    <label title="' . ($item[$key_column] ? 'Выключить' : 'Включить') . '" data-action="' . Actions::name(22) . '" data-id="' . $item[0] . '" data-callback="toggledLogs" class="tgl-btn mx-auto" data-tg-off="Выкл" data-tg-on="Вкл" for="tgl-log-' . $item[0] . '"></label>';
            }
        }

        if ($table == 'users') {
            $key_column = array_search('api_act', $columns);
            foreach ($arr as $key => $item) {
                $arr[$key][$key_column] = '<input class="tgl tgl-flip" id="tgl-api-' . $item[0] . '" type="checkbox" ' . ($item[$key_column] ? 'checked' : '') . '>
                        <label title="' . ($item[$key_column] ? 'Выключить' : 'Включить') . '" data-action="' . Actions::name(28) . '" data-id="' . $item[0] . '" data-callback="toggledApi" class="tgl-btn mx-auto" data-tg-off="Выкл" data-tg-on="Вкл" for="tgl-log-' . $item[0] . '"></label>';
            }

            $key_column = array_search('id', $columns);
            foreach ($arr as $key => $item) {
                $arr[$key][$key_column] = '<a title="Вход от имени: ' . Users::find($item[0])->email . '" data-action="' . Actions::name(23) . '" data-id="' . $item[0] . '" data-callback="fromUser" class="link-wrapper" href="#">' . $item[$key_column] . '<i class="fa fa-user"></i></a>';
            }

            $key_column = array_search('email_confirmed', $columns);
            foreach ($arr as $key => $item) {
                $arr[$key][$key_column] = '<div class="text-center px-1">' . ($item[$key_column] ? '<i title="Email подтверждён" class="fa fa-check-circle text-success"></i>' : '<i title="Email не подтверждён" class="fa fa-times-circle text-secondary"></i>') . '</div>';
            }
        }

        if ($table == 'crontabs') {
            $shedule_column = array_search('shedule', $columns);
            $key_column = array_search('run_at', $columns);
            foreach ($arr as $key => $item) {
                $cron = CronExpression::factory($item[$shedule_column]);
                $shedules = [];
                for ($i = 0; $i < 10; $i++) {
                    $shedules[] = $cron->getNextRunDate(null, $i)->format('Y-m-d H:i:s');
                }

                $arr[$key][$key_column] = '<div data-placement="left" title="<div class=\'text-left font-weight-bold\'>Расписание:</div><small style=\'line-height:1;\'>' . implode("<br>", $shedules) . '</small>">' . substr($shedules[0], 0, -3) . '</div>';
            }

            $key_column = array_search('user_id', $columns);
            foreach ($arr as $key => $item) {
                $arr[$key][$key_column] = '<div class="edit-wrapper editable-act" data-id="' . $item[0] . '" data-target="#edit-performer" data-toggle="modal" data-table="' . $table . '">' . $item[$key_column] . '<i class="fa fa-edit"></i></div>';
            }

            $key_column = array_search('shedule', $columns);
            foreach ($arr as $key => $item) {
                $arr[$key][$key_column] = '<div class="edit-wrapper editable-act" data-id="' . $item[0] . '" data-target="#edit-shedule" data-toggle="modal" data-table="' . $table . '">' . $item[$key_column] . '<i class="fa fa-edit"></i></div>';
            }

            $key_column = array_search('headers', $columns);
            foreach ($arr as $key => $item) {
                $arr[$key][$key_column] = '<div class="edit-wrapper editable-act" data-id="' . $item[0] . '" data-target="#edit-headers" data-toggle="modal" data-table="' . $table . '">' . $item[$key_column] . '<i class="fa fa-edit"></i></div>';
            }

            $key_column = array_search('data', $columns);
            foreach ($arr as $key => $item) {
                $arr[$key][$key_column] = '<div class="edit-wrapper editable-act" data-id="' . $item[0] . '" data-target="#edit-data" data-toggle="modal" data-table="' . $table . '">' . $item[$key_column] . '<i class="fa fa-edit"></i></div>';
            }

            $key_column = array_search('method', $columns);
            foreach ($arr as $key => $item) {
                $arr[$key][$key_column] = '<div class="edit-wrapper editable-act" data-id="' . $item[0] . '" data-target="#edit-method" data-toggle="modal" data-table="' . $table . '">' . $item[$key_column] . '<i class="fa fa-edit"></i></div>';
            }
        }

        return $arr;
    }

    /**
     * фильтрация массива для таблиц выборок.
     * 
     * @param array $arr Массив данных таблицы для форматирования.
     * @param array $columns массив имен столбцов
     * @param string $table Имя таблицы для редактирования.
     * 
     * @return array $arr отфильтрованный массив данных
     */
    private static function filter($arr, $columns, $table)
    {
        if (!isset($_SESSION['filter'][$table])) {
            return $arr;
        }

        if ($key_column = array_search('act', $columns)) {
            if (empty($_SESSION['filter'][$table]['act'])) {
                return [];
            }

            foreach ($arr as $key => $item) {
                if (!in_array($arr[$key][$key_column], $_SESSION['filter'][$table]['act'])) {
                    unset($arr[$key]);
                }
            }
        }

        if ($key_column = array_search('owner_group', $columns)) {
            if (empty($_SESSION['filter'][$table]['owner_group'])) {
                return [];
            }

            foreach ($arr as $key => $item) {
                if (!in_array($arr[$key][$key_column], $_SESSION['filter'][$table]['owner_group'])) {
                    unset($arr[$key]);
                }
            }
        }

        if ($key_column = array_search('groups', $columns)) {
            if (empty($_SESSION['filter'][$table]['groups'])) {
                foreach ($arr as $key => $item) {
                    if (json_decode($arr[$key][$key_column], true)) {
                        unset($arr[$key]);
                    }
                }
            } else {
                foreach ($arr as $key => $item) {
                    if (!array_intersect(json_decode($arr[$key][$key_column], true), $_SESSION['filter'][$table]['groups'])) {
                        unset($arr[$key]);
                    }
                }
            }
        }

        if ($table == 'templates' && ($key_column = array_search('type', $columns))) {
            $template_types = ['page', 'incode', 'modal', 'select', 'action'];
            if (empty($_SESSION['filter'][$table]['type'])) {
                return [];
            }

            foreach ($arr as $key => $item) {
                if (!in_array(array_search($arr[$key][$key_column], $template_types), $_SESSION['filter'][$table]['type'])) {
                    unset($arr[$key]);
                }
            }
        }

        return $arr;
    }
}
