<?php

/**
 * @file
 * @brief контроллер управления моделями страниц
 * 
 */

namespace Wrong\Models;

use Wrong\Database\Controller;
use Wrong\Database\Connect;
use Wrong\File\Path;

/**
 * @brief Pages контроллер управления моделями страниц, расширяет Controller
 * 
 */

class Pages extends Controller
{
    /**
     * создает в бд запись для новой модели типа "страница" и создаёт пустой файл страницы
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
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $arr['file'])) {
            $name = $arr['name'];
            $data = <<<EOF
<?php

/**
 * @file
 * @brief страница $name
 */

?>

<div class="container mt-3">Контент страницы $name</div>

EOF;
            $file = new \SplFileObject($_SERVER['DOCUMENT_ROOT'] . $arr['file'], 'a+b');
            $file->flock(LOCK_EX);
            $file->fwrite($data);
            $file->flock(LOCK_UN);
        }

        $sth = Connect::$dbh->prepare("INSERT INTO `pages` (`request`, `file`, `groups`, `owner_group`, `template_id`, `name`) VALUES (:request, :file, :groups, :owner_group, :template_id, :name)");
        $arr['groups'] = json_encode($arr['groups']);
        $sth->bindValue(':request', $arr['request']);
        $sth->bindValue(':file', $arr['file']);
        $sth->bindValue(':groups', $arr['groups']);
        $sth->bindValue(':owner_group', $arr['owner_group']);
        $sth->bindValue(':template_id', $arr['template_id']);
        $sth->bindValue(':name', $arr['name']);
        $sth->execute();
        return Connect::$dbh->lastInsertId();
    }
}
