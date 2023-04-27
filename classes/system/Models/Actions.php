<?php

/**
 * @file
 * @brief контроллер управления моделями действий
 * 
 */

namespace Wrong\Models;

use Wrong\Database\Controller;
use Wrong\Database\Connect;
use Wrong\File\Path;

/**
 * @brief Actions контроллер управления моделями действий, расширяет Controller
 * 
 */

class Actions extends Controller implements ModelsInterface
{

    /**
     * создает в бд запись для новой модели типа "действие" и копирует файл шаблона
     * 
     * @param array $arr массив данных модели
     * @param array $replace_path массив путей для замены в файле и параметры запроса.
     * 
     * @return int Последний вставленный идентификатор.
     */
    public static function create($arr, $replace_path = [])
    {
        $dbh = Connect::getInstance()->dbh;
        if ($replace_path) {
            $arr['file'] = strtr($arr['file'], $replace_path);
            $arr['request'] = strtr($arr['request'], $replace_path);
        }
        Path::mkdir($_SERVER['DOCUMENT_ROOT'] . $arr['file']);
        $arr['template_filename'] = Templates::all_available($arr['template_id'])[0]->file;
        if (copy($_SERVER['DOCUMENT_ROOT'] . $arr['template_filename'], $_SERVER['DOCUMENT_ROOT'] . $arr['file'])) {
            $sth = $dbh->prepare("INSERT INTO `actions` (`request`, `file`, `groups`, `owner_group`) VALUES (:request, :file, :groups, :owner_group)");
            $arr['groups'] = json_encode($arr['groups']);
            $sth->bindValue(':request', $arr['request']);
            $sth->bindValue(':file', $arr['file']);
            $sth->bindValue(':groups', $arr['groups']);
            $sth->bindValue(':owner_group', $arr['owner_group']);
            $sth->execute();
            return $dbh->lastInsertId();
        } else {
            Path::rmdir($_SERVER['DOCUMENT_ROOT'] . $arr['file']);
        }
    }

    /**
     * возвращает имя запроса по id строки
     * 
     * @param int $id Идентификатор, имя для которого вы хотите получить.
     * 
     * @return string имя запроса.
     */
    public static function name($id)
    {
        return preg_replace('#^/api/action/#', '', self::find($id)->request);
    }

}
