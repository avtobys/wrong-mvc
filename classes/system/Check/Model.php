<?php

/**
 * @file
 * @brief проверка создания моделей
 * 
 */

namespace Wrong\Check;

use Wrong\Database\Controller;
use Wrong\Rights\Group;
use Wrong\Models\Templates;

/**
 * @brief Model отвечает за проверку создания моделей
 * 
 */

class Model
{
    /**
     * проверка возможности создания новой модели
     * 
     * @param array $arr массив данных
     * @param string $table имя таблицы в базе данных
     * @param array $replace_path массив путей для замены в файлах и полях запроса.
     */
    public static function create($arr, $table, $replace_path = [])
    {
        global $user;

        if ($replace_path) {
            $arr['file'] = strtr($arr['file'], $replace_path);
            $arr['request'] = strtr($arr['request'], $replace_path);
        }

        if ($table == 'modals') {
            $arr['template_id'] = $arr['template_id'] ?? 4;
            if (!($arr['template_filename'] = Templates::all_available($arr['template_id'])[0]->file) || !file_exists($_SERVER['DOCUMENT_ROOT'] . $arr['template_filename'])) {
                exit(json_encode(['error' => 'Файл шаблона с таким именем не существует!']));
            }
        }

        self::request($arr, $table);
        self::file($arr, $table);

        if (empty($arr['owner_group']) || !in_array($arr['owner_group'], $user->subordinate_groups)) {
            exit(json_encode(['error' => '"Группа владелец" не найдена среди подчиненных групп']));
        }

        if (($models_limit = Controller::find($arr['owner_group'], 'id', 'groups')->models_limit) && $models_limit <= Group::count_all_owner_models($arr['owner_group'])) {
            exit(json_encode(['error' => 'Лимит моделей для данной группы исчерпан']));
        }
    }

    /**
     * проверяет, возможность создания файла обработчика
     * 
     * @param array $arr массив данных
     * @param string $table имя таблицы в базе данных
     * @param bool $format_only если true, то функция будет проверять только формат, а не существование файла
     * и наличие файла в базе.
     */
    public static function file($arr, $table, $format_only = false)
    {

        $path = self::pattern($table)->file;

        $rx = '#^' . $path . '/([/a-z0-9\-\.]+)\.php$#';

        if (!preg_match($rx, $arr['file'], $matches) || preg_match('#//#', $matches[2]) || preg_match('#^/#', $matches[2]) || preg_match('#/$#', $matches[2])) {
            exit(json_encode(['error' => 'Неверный формат для "Файл обработчик"']));
        }

        if (!$format_only && $table != 'pages') {
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $arr['file'])) {
                exit(json_encode(['error' => 'Файл обработчик с таким именем уже существует!']));
            }

            if (Controller::find($arr['file'], 'file', $table)) {
                exit(json_encode(['error' => 'Файл обработчик с таким именем уже зарегистрирован в БД!']));
            }
        }
    }

    /**
     * проверяет формат запроса для создания модели
     * 
     * @param array $arr массив данных
     * @param string $table имя таблицы в базе данных
     */
    public static function request($arr, $table)
    {

        if ($table == 'templates') return;

        $path = self::pattern($table)->request;

        if (in_array($table, ['pages', 'actions'])) {
            $rx = '#^' . $path . '/([/a-z0-9\-\.]*)$#i';
        } else if ($table == 'crontabs') {
            $rx = '#^' . $path . '/([a-z0-9]*)#i';
        } else {
            $rx = '#^' . $path . '/([a-z0-9\-]+)$#i';
        }

        if (!preg_match($rx, $arr['request'], $matches) || preg_match('#//#', $matches[1]) || preg_match('#^/#', $matches[1]) || preg_match('#/$#', $matches[1])) {
            exit(json_encode(['error' => 'Неверный формат для "Запрос"']));
        }
    }

    /**
     * возвращает объект с двумя свойствами: паттерн формата файла и запроса для таблицы модели.
     * 
     * @param string $table имя таблицы в базе данных
     * 
     * @return object Объект с двумя свойствами: паттерн файла и запроса.
     */
    public static function pattern($table)
    {
        global $user;

        switch ($table) {
            case 'actions':
                return (object) ['file' => '/api/action/(' . implode('|', $user->writeble_paths) . ')', 'request' => '/api/action'];
                break;
            case 'modals':
                return (object) ['file' => '/api/modal/(' . implode('|', $user->writeble_paths) . ')', 'request' => '/api/modal'];
                break;
            case 'selects':
                return (object) ['file' => '/api/select/(' . implode('|', $user->writeble_paths) . ')', 'request' => '/api/select'];
                break;
            case 'pages':
                return (object) ['file' => '/page/(' . implode('|', $user->writeble_paths) . ')', 'request' => ''];
            case 'templates':
                return (object) ['file' => '/\.\./templates/(page|modal|action|incode|select)', 'request' => ''];
                break;
            case 'crontabs':
                return (object) ['file' => '', 'request' => ''];
                break;
        }
    }
}
