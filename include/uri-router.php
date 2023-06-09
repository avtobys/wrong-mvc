<?php

/**
 * @file
 * @brief Маршрутизатор. uri контроллер, который отвечает за маршрутизацию http запроса к соответствующей модели.
 */

routing_start:

if ($request == '/forbidden') {
    ob_clean();
}

if (preg_match('#//#', $request)) { // убираем 2 и более слешей в url
    $uri = preg_replace('#[/]+$#', '', $request);
    $uri = preg_replace('#[/]{2,}#', '/', $uri);
    $uri = $uri ?: '/';
    if ($QUERY_STRING = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY)) {
        $uri .= '?' . $QUERY_STRING;
    }
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: $uri");
    exit;
}

if ($request != '/' && preg_match('#/$#', $request)) { // убираем крайний слеш если это не главная
    $uri = preg_replace('#[/]+$#', '', $request);
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $uri)) {
        if ($request == '/docs/') {
            require $_SERVER['DOCUMENT_ROOT'] . '/docs/index.html';
            exit;
        }
        require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';
    }
    $uri = $uri ?: '/';
    if ($QUERY_STRING = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY)) {
        $uri .= '?' . $QUERY_STRING;
    }
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: $uri");
    exit;
}

if (preg_match('#index\.(php|html)$#', $request)) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . dirname($request));
    exit;
}

if (Wrong\Start\Env::$e->SYSTEM_CLOSED && $user->main_group_id != 1) { // система закрыта всем кроме группы Система(основной администратор)
    $request = '/forbidden';
}

if (preg_match('#^/api/(modal|action|select)/[a-z0-9\-]+#', $request, $matches)) { // включение запросов к модалкам/дейсвиям/выборкам
    if ($arr = Wrong\Database\Controller::all($request, 'request', $matches[1] . 's')) {
        $arr = Wrong\Rights\Group::weightSort($arr);
        $arr = array_filter($arr, function ($row) use ($user) {
            return $user->access()->read($row);
        });
        if (!$arr) {
            $request = '/forbidden';
            goto routing_start;
        }
        foreach ($arr as $row) {
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $row->file)) {
                header("X-Robots-Tag: noindex");
                $basename = basename($request);

                if (strpos($request, '/api/select') !== false && $row->cache_time) { // если кешируется
                    $mem = new Wrong\Memory\Cache('api-select');
                    if ($data = $mem->get($_SERVER['REQUEST_URI'], $row->cache_time)) { // если есть в кеше отдаём из кеша
                        exit($data);
                    }
                    register_shutdown_function(function ($mem, $timeout) {
                        $mem->set($_SERVER['REQUEST_URI'], ob_get_contents(), $timeout);
                    }, $mem, $row->cache_time);
                }

                require $_SERVER['DOCUMENT_ROOT'] . $row->file;
                exit;
            }
        }
    }
}


if (isset($_GET['FROM_UID'])) { // переадресация на главную и сброс "гостевой" сессии
    !empty($_COOKIE['FROM_UID']) && Wrong\Auth\User::session_reset();
    $user = new Wrong\Auth\User(Wrong\Auth\User::session());
    if ($user->access()->page('/system')) {
        header("Location: /system");
    } else {
        header("Location: /");
    }
    exit;
}

if ($request == '/' && Wrong\Start\Env::$e->RETURN_TO_REQUEST && $user->id && $request != $user->request && $user->request && !isset($_GET['main'])) { // переадресация не предыдущий url
    header("Location: $user->request");
    exit;
}

if (preg_match('#^/remind/([0-9]+)/([a-z0-9]+)#i', $request, $matches) && Wrong\Auth\User::is_remind($matches[1], $matches[2])) { // восставновление пароля
    header("X-Robots-Tag: noindex");
    Wrong\Task\Stackjs::add('
        _modal("#sign-remind", null, "user_id=' . $matches[1] . '&md5=' . $matches[2] . '");
        history.pushState(null, null, "/");
    ', 0, 'sign-remind');
    $request = '/';
}

if (preg_match('#^/email-confirm/([0-9]+)/([a-z0-9]+)#i', $request, $matches) && Wrong\Auth\User::is_confirm($matches[1], $matches[2]) && !$user->email_confirmed) { // подтверждение email
    header("X-Robots-Tag: noindex");
    Wrong\Task\Stackjs::add('history.pushState(null, null, "/");setTimeout(()=>{successToast("Почта успешно подтверждена");},100)', 0, 'email-confirm');
    $user->set_confirm(1);
    if ($user->access()->page('/system')) {
        header("Location: /system");
    } else {
        header("Location: /");
    }
    exit;
}

if (Wrong\Start\Env::$e->EMAIL_CONFIRMATION && $user->id && !$user->email_confirmed) { // окно подтверждения email
    $request = '/disabled';
    Wrong\Task\Stackjs::add('_modal("#email-confirm");', 0, 'email-confirm');
}

if ($arr = Wrong\Models\Pages::all($request, 'request')) { // запросы к url страницам
    $arr = Wrong\Rights\Group::weightSort($arr);
    $arr = array_filter($arr, function ($row) use ($user) {
        return $user->access()->read($row);
    });
    if (!$arr && $request != '/forbidden') {
        $request = '/forbidden';
        goto routing_start;
    }
    foreach ($arr as $row) {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $row->file)) {
            if (
                ($template = Wrong\Models\Templates::find($row->template_id)) &&
                $user->access()->read($template) &&
                file_exists($_SERVER['DOCUMENT_ROOT'] . $template->file)
            ) { // шаблон доступен
                if ($row->cache_time) { // если страница кешируется
                    $mem = new Wrong\Memory\Cache('page-cache');
                    if ($data = $mem->get($_SERVER['REQUEST_URI'], $row->cache_time)) { // если есть в кеше отдаём из кеша
                        exit($data);
                    }
                    register_shutdown_function(function ($mem, $timeout) {
                        $mem->set($_SERVER['REQUEST_URI'], ob_get_contents(), $timeout);
                    }, $mem, $row->cache_time);
                }

                if ($template->cache_time) { // если шаблон кешируется
                    $mem = new Wrong\Memory\Cache('template-cache');
                    if ($data = $mem->get($_SERVER['REQUEST_URI'], $template->cache_time)) { // если есть в кеше отдаём из кеша
                        exit($data);
                    }
                    register_shutdown_function(function ($mem, $timeout) {
                        $mem->set($_SERVER['REQUEST_URI'], ob_get_contents(), $timeout);
                    }, $mem, $template->cache_time);
                }

                require $_SERVER['DOCUMENT_ROOT'] . $template->file;
            } else if ($request != '/forbidden') { // шаблон недоступен - 403
                $request = '/forbidden';
                goto routing_start;
            }
            $user->set_request($request);
            exit;
        }
    }
}




/**
 * пример запроса к динамическим страницам
 * my-categories - таблица в бд с вашими категориями
 * my-pages - таблица в бд с вашим контентом страниц
 * url - поля в бд ваших категорий и страниц
 * 
 * /request-dinamic-model-name - ваша модель страницы
 * 
 * $data_page - данные вашей страницы, которые будут доступны в контексте её файла
 * /any-category-url/any-page-url - запросы по которым будет доступна ваша динамическая модель
 */

// $rx = "#^/(" . implode('|', array_column(Wrong\Database\Controller::all('', 'id', 'my-categories'), 'url')) . ")/([^/]+)$#";
// if (
//     preg_match($rx, $request, $matches) &&
//     ($data_page = Wrong\Database\Controller::find($matches[2], 'url', 'my-pages')) && ($arr = Wrong\Models\Pages::all('/request-dinamic-model-name', 'request'))
// ) {
//     $arr = Wrong\Rights\Group::weightSort($arr);
//     $arr = array_filter($arr, function ($row) use ($user) {
//         return $user->access()->read($row);
//     });
//     if (!$arr) {
//         $request = '/forbidden';
//         goto routing_start;
//     }
//     foreach ($arr as $row) {
//         if (file_exists($_SERVER['DOCUMENT_ROOT'] . $row->file)) {
//             if (
//                 ($template = Wrong\Models\Templates::find($row->template_id)) &&
//                 $user->access()->read($template) &&
//                 file_exists($_SERVER['DOCUMENT_ROOT'] . $template->file)
//             ) { // шаблон доступен
//                 require $_SERVER['DOCUMENT_ROOT'] . $template->file;
//             } else if ($request != '/forbidden') { // шаблон недоступен - 403
//                 $request = '/forbidden';
//                 goto routing_start;
//             }
//             $user->set_request($request);
//             exit;
//         }
//     }
// }




require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php'; // контроллер не нашел подходящей модели
