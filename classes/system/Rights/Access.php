<?php

/**
 * @file
 * @brief проверка прав доступа пользователей на "чтение"/"запись"
 * 
 */


namespace Wrong\Rights;

use Wrong\Database\Controller;
use Wrong\Start\Env;
use Wrong\Rights\Group;

/**
 * @brief Access класс, проверки прав доступов
 * 
 */

class Access
{

    /**
     * конструктор:
     * 
     * @param object $user пользователь для которого делается проверка
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /** 
     * Проверяет доступность модели для пользователя на доступ
     * (юзер состоит в группах доступа модели и она включена, либо юзер состоит в группе - владельца модели) группа владелец модели должна быть также включена
     * функция не сравнивает наличие прав по весу подчиненных групп, проверяются только группы доступа модели и его владелец
     * 
     * @param object $row проверяемая модель
     * 
     * @return bool истинное означает наличие доступа к модели
     */

    public function read($row)
    {
        if (!$row) return false;
        return (bool) ((array_intersect($this->user->groups, json_decode($row->groups, true)) && $row->act && Group::is_active($row->owner_group)) || ($this->user->id && in_array($row->owner_group, $this->user->groups)));
    }

    /** 
     * Проверяет доступность модели для пользователя на её изменение
     * (группа владелец входит в подчиненные группы)
     * 
     * @param object $row проверяемая модель
     * @param bool $extended опционально расширяет права для системной группы, которая не входит в подчиненные админу группы
     * 
     * @return bool истинное означает наличие доступа на изменение модели
     */
    public function write($row, $extended = false)
    {
        if (!$row) return false;
        if ($extended || Env::$e->DEVELOPER_MODE) { // расширенные права на изменение своих системных моделей при включенном режиме разработчика
            return in_array($row->owner_group, $this->user->subordinate_groups) || in_array($row->owner_group, $this->user->groups);
        }
        return in_array($row->owner_group, $this->user->subordinate_groups);
    }

    /**
     * Проверяет принадлежность данной модели к системной группе
     * 
     * @param object $row проверяемая модель
     */
    public function is_system($row)
    {
        return $row->owner_group == 1 && !Env::$e->DEVELOPER_MODE;
    }

    /**
     * Проверяет доступность модели страницы с указанным request или id
     * 
     * @param mixed $value - request или id модели
     */
    public function page($value)
    {
        $arr = Controller::all($value, is_int($value) ? 'id' : 'request', 'pages');
        $arr = array_filter($arr, function ($row) {
            return $this->read($row);
        });
        return boolval($arr);
    }

    /**
     * Проверяет доступность модели модального окна с указанным request или id
     * 
     * @param mixed $value - request или id модели
     */
    public function modal($value)
    {
        $arr = Controller::all($value, is_int($value) ? 'id' : 'request', 'modals');
        $arr = array_filter($arr, function ($row) {
            return $this->read($row);
        });
        return boolval($arr);
    }

    /**
     * Проверяет доступность модели действия с указанным request или id
     * 
     * @param mixed $value - request или id модели
     */
    public function action($value)
    {
        $arr = Controller::all($value, is_int($value) ? 'id' : 'request', 'actions');
        $arr = array_filter($arr, function ($row) {
            return $this->read($row);
        });
        return boolval($arr);
    }

    /**
     * Проверяет доступность модели выборки с указанным request или id
     * 
     * @param mixed $value - request или id модели
     */
    public function select($value)
    {
        $arr = Controller::all($value, is_int($value) ? 'id' : 'request', 'selects');
        $arr = array_filter($arr, function ($row) {
            return $this->read($row);
        });
        return boolval($arr);
    }

    /**
     * Проверяет доступность модели шаблона с указанным id
     * 
     * @param mixed $value - id модели
     */
    public function template($value)
    {
        $arr = Controller::all($value, 'id', 'templates');
        $arr = array_filter($arr, function ($row) {
            return $this->read($row);
        });
        return boolval($arr);
    }
}
