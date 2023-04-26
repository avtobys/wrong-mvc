<?php

/**
 * @file
 * @brief установочный файл. После установки можно и рекомендуется удалить вместе с каталогом install/
 * 
 * <a href="/docs/">Подробнее об установке Wrong MVC</a>
 * 
 * данный файл включен в index.php и после успешной установки его включение комментируется
 */


ob_start();
session_start();

header("Cache-Control: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT");

use Wrong\Start\Env;
use Wrong\File\Locker;
use Wrong\Database\Connect;
use Wrong\Auth\User;
use Wrong\Models\Users;

if (($dbh = Connect::start(true)) && $dbh->query("SHOW TABLES")->fetchAll() && $dbh->query("SELECT COUNT(*) FROM `users`")->fetchColumn()) {
    dd('Система установлена и есть зарегистрированные юзеры! Удалите в файле ' . $_SERVER['DOCUMENT_ROOT'] . '/index.php включение require \'../install/install.php\'; или очистите БД от таблиц и проведите установку заново');
}


if (!empty($_POST)) {

    if (empty($_POST['finish'])) {
        if (!Locker::lock(basename(__FILE__, '.php'))) {
            dd("Не удалось получить доступ к файлам блокировки\nУдалите вручную файлы:\n\n" . dirname(__DIR__) . "/temp/lock-dump.lock\n" . dirname(__DIR__) . "/temp/lock-install.lock\n\nи попробуйте <a href=\"#step-2\">заново</a>");
        }
        rd($_POST);
        rd('Записываем конфигурацию...');

        foreach ($_POST as $name => $value) {
            Env::$e->set($name, $value);
        }
        Env::$e->set('SERVER_ADDR', $_SERVER['SERVER_ADDR']);
        Env::$e->set('HTTP_HOST', $_SERVER['HTTP_HOST']);
        rd('Почти готово...');
        exit('<script>
                loading();
                setTimeout(function(){
                    $.ajax({
                        type: "POST",
                        url: "?",
                        data: "finish=1&ADMIN_MAIL=' . $_POST['ADMIN_MAIL'] . '&ADMIN_PASSWORD=' . $_POST['ADMIN_PASSWORD'] . '",
                        dataType: "html",
                        cache: false,
                        success: function(response) {
                            setTimeout(() => {
                                $(\'#step-3\').html(response);
                                loading(\'hide\');
                            }, 3000);
                        }
                    });
                }, 5000);
            </script>');
    }

    rd('Подключаемся к базе данных...');
    $dbh = Connect::start();

    try {
        if (round($dbh->query("SELECT VERSION()")->fetchColumn(), 2) < 10.3) {
            Locker::unlock(basename(__FILE__, '.php'), true);
            throw new \Error('Your mariadb version is less than 10.3');
        }
    } catch (\Throwable $th) {
        dd($th);
    }

    rd('Заливаем дамп таблиц...');
    require __DIR__ . '/dump.php';

    rd('Установка Env переменных среды из таблицы settings...');
    try {
        Env::add($dbh->query("SELECT `name`, `value` FROM `settings`")->fetchAll(\PDO::FETCH_KEY_PAIR));
    } catch (\Throwable $th) {
        dd($dbh->errorInfo());
    }

    rd('Регистрируем администратора...');
    Wrong\Auth\User::session_reset();
    if (!($id = Users::create($_POST['ADMIN_MAIL'], $_POST['ADMIN_PASSWORD'], [1, 2], 1))) {
        dd($dbh->errorInfo());
    }
    $user = new User($id);
    $user->set_confirm(1);

    rd('Отключение установочного скрипта...');
    try {
        $index = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/index.php');
    } catch (\Throwable $th) {
        dd($th);
    }
    $rx = "#(require '\.\./install/install\.php';).*#";
    $replacement = "// $1 // you can remove this line";
    $index = preg_replace($rx, $replacement, $index);
    try {
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/index.php', $index);
    } catch (\Throwable $th) {
        dd($th);
    }

    rd('Вы можете удалить каталог ' . __DIR__);
    rd('Установка завершена...');
    echo "Данные для входа администратора:\nEmail: <kbd contenteditable=\"true\">" . $user->email . "</kbd>\nПароль: <kbd contenteditable=\"true\">" . $_POST['ADMIN_PASSWORD'] . "</kbd>\n\n";
    Locker::unlock(basename(__FILE__, '.php'), true);
    Wrong\Task\Stackjs::add('$(function(){successToast("Система WRONG MVC успешно установлена!");$("#wrong").click();});', 0, 'install');
    exit('<a id="completed" class="btn btn-primary btn-lg" href="/">Готово!</a>');
}

?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/assets/system/css/main.min.css">
    <script src="/assets/system/js/main.min.js"></script>
    <title>Установка Wrong MVC</title>
</head>


<body>
    <div class="container">
        <div id="preloader" style="display:none;">
            <div class="position-fixed bg-secondary w-100 h-100 d-flex align-items-center justify-content-center text-white-50" style="top:0;left:0;z-index:999999;font-size:100px;opacity:0.5;"><i class="fa fa-circle-notch fa-spin" aria-hidden="true"></i></div>
        </div>
        <div id="step-1" class="jumbotron mt-3 pt-3 bg-secondary text-white rounded-lg">
            <h1 class="display-4">WRONG MVC - система для создания систем!</h1>
            <hr class="my-3">
            <div class="bg-light text-dark rounded p-3">
                <?= preg_replace('#.*?(<ul class="check-list">.*?</ul>).*#s', '$1', file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/page/system/main-guest.php')) ?>
                <script>
                    $('[data-target="#todo"]').replaceWith('И это не всё!');
                </script>
                <div class="lead mt-2">
                    <a class="btn btn-primary btn-lg" href="#step-2" role="button">Начнем установку на <?= $_SERVER['HTTP_HOST'] ?></a>
                </div>
            </div>
        </div>
        <div id="step-2" class="jumbotron mt-5 pt-5 bg-secondary text-white rounded-lg position-relative" style="display:none;">
            <a class="position-absolute text-white" style="left: 10px;top: 11px;font-size:30px;line-height: 30px;" href="#step-1"><i class="fa fa-arrow-circle-left"></i></a>
            <h1 class="display-4">WRONG среда почти установлена!</h1>
            <p class="lead">Быстро неправда ли?</p>
            <div>Осталось лишь указать доступы к базе данных и аккаунту администратора</div>
            <hr class="my-4">
            <form id="install" action="?">
                <div class="card text-black-50">
                    <div class="card-body">
                        <h6 class="card-title">База данных</h6>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend w-25">
                                <span class="input-group-text w-100" id="inputGroup-sizing-sm">Хост</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="DB_HOST" value="<?= Env::$e->DB_HOST ?>" autocomplete="off" required>
                        </div>
                        <div class="input-group input-group-sm mt-3">
                            <div class="input-group-prepend w-25">
                                <span class="input-group-text w-100" id="inputGroup-sizing-sm">Порт</span>
                            </div>
                            <input type="number" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="DB_PORT" value="<?= Env::$e->DB_PORT ?>" autocomplete="off" required>
                        </div>
                        <div class="input-group input-group-sm mt-3">
                            <div class="input-group-prepend w-25">
                                <span class="input-group-text w-100" id="inputGroup-sizing-sm">База данных</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="DB_DATABASE" value="<?= Env::$e->DB_DATABASE ?>" autocomplete="off" required>
                        </div>
                        <div class="input-group input-group-sm mt-3">
                            <div class="input-group-prepend w-25">
                                <span class="input-group-text w-100" id="inputGroup-sizing-sm">Пользователь</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="DB_USERNAME" value="<?= Env::$e->DB_USERNAME ?>" autocomplete="off" required>
                        </div>
                        <div class="input-group input-group-sm mt-3">
                            <div class="input-group-prepend w-25">
                                <span class="input-group-text w-100" id="inputGroup-sizing-sm">Пароль</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="DB_PASSWORD" value="<?= Env::$e->DB_PASSWORD ?>" autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <div class="card text-black-50 mt-3">
                    <div class="card-body">
                        <h6 class="card-title">Администратор</h6>
                        <div class="input-group input-group-sm mt-3">
                            <div class="input-group-prepend w-25">
                                <span class="input-group-text w-100" id="inputGroup-sizing-sm">Email</span>
                            </div>
                            <input type="email" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="ADMIN_MAIL" value="support@<?= $_SERVER['HTTP_HOST'] ?>" minlength="5" autocomplete="off" required>
                        </div>
                        <div class="input-group input-group-sm mt-3">
                            <div class="input-group-prepend w-25">
                                <span class="input-group-text w-100" id="inputGroup-sizing-sm">Пароль</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="ADMIN_PASSWORD" value="" minlength="5" autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <p class="lead mt-3">
                    <button class="btn btn-success btn-lg" type="submit">Закончим установку</button>
                </p>
            </form>
        </div>
        <pre id="step-3" class="jumbotron mt-5 pt-5 bg-secondary text-white rounded-lg pre" style="display: none;line-height:1.8;"></pre>
    </div>
</body>
<script>
    $(document).on('click', '[href^="#step"]', function(e) {
        e.preventDefault();
        $(this).parents('[id^="step"]').hide();
        $($(this).attr('href')).fadeIn();
        $('[name="ADMIN_PASSWORD"]').each(function() {
            !$(this).val() && $(this).val(Math.random().toString(36).slice(2, 10));
        });
    });

    $('#install').submit(function(e) {
        e.preventDefault();
        $("#install [type=submit]").attr("disabled", true);
        $(this).parents('[id^="step"]').hide();
        $('#step-3').fadeIn();
        $('#preloader').show();
        $.ajax({
            type: "POST",
            url: "?",
            data: $(this).serialize(),
            dataType: "html",
            cache: false,
            success: function(response) {
                $('#step-3').html(response);
            }
        }).always(() => {
            $('#preloader').hide();
            $("#install [type=submit]").removeAttr("disabled");
        });
    });

    $(document).on('click', 'kbd', function(e) {
        e.target.focus();
        const selection = window.getSelection();
        selection.removeAllRanges();
        let range = new Range();
        range.selectNodeContents(this);
        selection.addRange(range);
        document.execCommand('copy', false);
    });

    $(document).on('copy', 'kbd', function(e) {
        let text = this.innerText;
        e.clipboardData.setData('text/plain', text);
        e.preventDefault();
    });
</script>

</html>
<?php exit; ?>