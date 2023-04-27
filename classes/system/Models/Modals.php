<?php

/**
 * @file
 * @brief контроллер управления моделями модальных окон
 * 
 */

namespace Wrong\Models;

use Wrong\Database\Controller;
use Wrong\Database\Connect;
use Wrong\File\Path;
use Wrong\Models\Templates;

/**
 * @brief Modals контроллер управления моделями групп пользователей, расширяет Controller
 * 
 */

class Modals extends Controller implements ModelsInterface
{

    /**
     * создает в бд запись для новой модели типа "модальное окно" и копирует указанный файл шаблона
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
            $sth = $dbh->prepare("INSERT INTO `modals` (`request`, `file`, `groups`, `owner_group`) VALUES (:request, :file, :groups, :owner_group)");
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
     * если в модальном окне есть форма с action аттрибутом Wrong\Models\Actions::find(0)->request
     * то исправляет в файле 0 на переданный $action_id
     * 
     * @param $action_id идентификатор модели действия
     * @param string $modal_id идентификатор модели модального окна
     * 
     */
    public static function set_action($action_id, $modal_id)
    {
        $file = $_SERVER['DOCUMENT_ROOT'] . self::find($modal_id)->file;
        if (file_exists($file) && ($data = file_get_contents($file)) && strpos($data, 'Wrong\Models\Actions::find(0)') !== false) {
            $data = str_replace('Wrong\Models\Actions::find(0)', 'Wrong\Models\Actions::find(' . $action_id . ')', $data);
            $file = new \SplFileObject($file, 'w+b');
            $file->flock(LOCK_EX);
            $file->ftruncate(0);
            $file->rewind();
            $file->fwrite($data);
            $file->flock(LOCK_UN);
        }
    }
}
