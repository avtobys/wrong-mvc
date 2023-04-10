<?php

/**
 * @file
 * @brief скрытие отключенных/недоступных по правам, элементов и возможностей(ссылок, кнопок вызова действий, форм, кнопок вызова модалок)
 * 
 */

namespace Wrong\Html;

use Wrong\Rights\Group;
use Wrong\Start\Env;
use Wrong\Database\Connect;

/**
 * @brief Hideout используется для скрытия элементов в HTML.
 * 
 */

class Hideout extends Assets
{
    /** статический массив всех действий отсортированный по наименьшему минимальному весу групп доступа */
    public static $actions = [];

    /** статический массив всех модалок отсортированный по наименьшему минимальному весу групп доступа */
    public static $modals = [];

    /** статический массив всех страниц отсортированный по наименьшему минимальному весу групп доступа */
    public static $pages = [];

    /**
     * скрывает все элементы, которые не разрешены для просмотра текущему пользователю.
     * 
     * @param string $str Строка для обработки - html код на выходе из ob_get_contents(), вызывается в register_shutdown_function() в include/session.php
     * 
     * @return строка, которая передается ему.
     */
    public static function hide($str)
    {
        global $user;

        if (defined('USE_ASSETS_PATH')) {
            $str = self::path($str);
        }

        if (Env::$e->HIDE_OUT_ACTIONS_MODALS) {
            self::$actions = Group::weightSort(Connect::$dbh->query("SELECT * FROM `actions`")->fetchAll());
            self::$modals = Group::weightSort(Connect::$dbh->query("SELECT * FROM `modals`")->fetchAll());
        }
        if (Env::$e->HIDE_OUT_LINKS) {
            self::$pages = Group::weightSort(Connect::$dbh->query("SELECT * FROM `pages`")->fetchAll());
        }

        $hide_rules = [];
        $requests = [];
        foreach (array_reverse(self::$actions) as $row) {
            if (in_array($row->request, $requests)) continue;
            $requests[] = $row->request;
            if ((!$row->act || !array_intersect($user->groups, json_decode($row->groups, true))) && !in_array($row->owner_group, $user->groups)) {
                if (strpos($str, 'data-action="' . basename($row->request) . '"') !== false || strpos($str, 'action="' . basename($row->request) . '"') !== false) {
                    $hide_rules[] = '[data-action="' . basename($row->request) . '"]{display:none!important}';
                    $hide_rules[] = '[action="' . basename($row->request) . '"]{display:none!important}';
                }
            }
        }
        $requests = [];
        foreach (array_reverse(self::$modals) as $row) {
            if (in_array($row->request, $requests)) continue;
            $requests[] = $row->request;
            if ((!$row->act || !array_intersect($user->groups, json_decode($row->groups, true))) && !in_array($row->owner_group, $user->groups)) {
                if (strpos($str, 'data-target="#' . basename($row->request) . '"') !== false) {
                    $hide_rules[] = '[data-target="#' . basename($row->request) . '"]{display:none!important}';
                }
            }
        }
        $requests = [];
        foreach (array_reverse(self::$pages) as $row) {
            if (in_array($row->request, $requests)) continue;
            $requests[] = $row->request;
            if ((!$row->act || !array_intersect($user->groups, json_decode($row->groups, true))) && !in_array($row->owner_group, $user->groups)) {
                if (strpos($str, 'href="' . $row->request . '"') !== false) {
                    $hide_rules[] = '[href="' . $row->request . '"]{display:none!important}';
                }
            }
        }
        if ($hide_rules) {
            $str = str_replace('</head>', '<style>' . implode(array_unique($hide_rules)) . '</style></head>', $str);
        }
        return $str;
    }

}
