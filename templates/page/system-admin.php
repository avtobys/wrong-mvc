<?php

/**
 * @file
 * @brief шаблон главной страницы администратора системы
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';
$TEMPLATE_DATA = ob_get_contents();
ob_clean();

?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $row->name ?></title>
    <link rel="icon" type="image/png" sizes="64x64" href="/assets/system/img/favicon-64.png">
    <?= Wrong\Html\Get::style($_SERVER['DOCUMENT_ROOT'] . '/assets/system/css/system-admin.min.css') ?>
    <?= Wrong\Html\Get::style($_SERVER['DOCUMENT_ROOT'] . '/assets/system/css/main.min.css') ?>
    <?= Wrong\Html\Get::script($_SERVER['DOCUMENT_ROOT'] . '/assets/system/js/main.min.js') ?>
    <?= Wrong\Html\Get::script($_SERVER['DOCUMENT_ROOT'] . '/assets/system/js/system-admin.min.js') ?>
    <style id="table-css"></style>
</head>


<body>
    <nav id="admin-navbar" class="navbar navbar-expand-lg navbar-dark fixed-top p-0 small" style="background:#3B4346 url(/assets/system/img/bg02.jpg);">
        <a title="Установка, документация" class="btn btn-secondary rounded-circle p-0 mx-2 overflow-hidden" href="/?main"><img style="height:25px;" src="/assets/system/img/tux.jpg" alt="."></a>
        <a id="main-link" class="text-gray-500 mr-auto text-decoration-none font-weight-bold d-inline-block d-lg-none" href="/?main">Wrong MVC</a>
        <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav w-100 text-left text-lg-center">
                <li class="nav-item flex-fill">
                    <a class="nav-link border-secondary px-3 px-lg-2" href="<?= Wrong\Models\Pages::find(11)->request ?>"><i class="fa fa-bug d-lg-none d-xl-inline-block mr-2"></i>Логи</a>
                </li>
                <li class="nav-item flex-fill">
                    <a class="nav-link border-secondary px-3 px-lg-2" href="<?= Wrong\Models\Pages::find(10)->request ?>"><i class="fa fa-clock d-lg-none d-xl-inline-block mr-2"></i>Задачи</a>
                </li>
                <li class="nav-item flex-fill">
                    <a class="nav-link border-secondary px-3 px-lg-2" href="<?= Wrong\Models\Pages::find(4)->request ?>"><i class="fa fa-flash d-lg-none d-xl-inline-block mr-2"></i>Действия</a>
                </li>
                <li class="nav-item flex-fill">
                    <a class="nav-link border-secondary px-3 px-lg-2" href="<?= Wrong\Models\Pages::find(3)->request ?>"><i class="fa fa-window-restore d-lg-none d-xl-inline-block mr-2"></i>Модальные окна</a>
                </li>
                <li class="nav-item flex-fill">
                    <a class="nav-link border-secondary px-3 px-lg-2" href="<?= Wrong\Models\Pages::find(5)->request ?>"><i class="fa fa-database d-lg-none d-xl-inline-block mr-2"></i>Выборки</a>
                </li>
                <li class="nav-item flex-fill">
                    <a class="nav-link border-secondary px-3 px-lg-2" href="<?= Wrong\Models\Pages::find(6)->request ?>"><i class="fa fa-window-maximize d-lg-none d-xl-inline-block mr-2"></i>Страницы</a>
                </li>
                <li class="nav-item flex-fill">
                    <a class="nav-link border-secondary px-3 px-lg-2" href="<?= Wrong\Models\Pages::find(7)->request ?>"><i class="fa fa-columns d-lg-none d-xl-inline-block mr-2"></i>Шаблоны</a>
                </li>
                <li class="nav-item flex-fill">
                    <a class="nav-link border-secondary px-3 px-lg-2" href="<?= Wrong\Models\Pages::find(8)->request ?>"><i class="fa fa-user d-lg-none d-xl-inline-block mr-2"></i>Пользователи</a>
                </li>
                <li class="nav-item flex-fill">
                    <a class="nav-link border-secondary px-3 px-lg-2" href="<?= Wrong\Models\Pages::find(9)->request ?>"><i class="fa fa-group d-lg-none d-xl-inline-block mr-2"></i>Группы</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link border-secondary px-3" title="Импорт модели" data-toggle="modal" data-target="#import-model" href=""><i class="fa fa-upload mr-2 mr-lg-0"></i><span class="d-lg-none">Импорт модели</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link border-secondary px-3" title="Настройки" data-toggle="modal" data-target="#settings" href=""><i class="fa fa-cogs mr-2 mr-lg-0"></i><span class="d-lg-none">Настройки</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link border-secondary px-3" title="Выход <?= $user->email ?>" data-action="<?= Wrong\Models\Actions::name(5) ?>" data-confirm="true" data-header="<i class='fa fa-sign-out mr-2'></i>Выход" data-body="Выйти из аккаунта: <div class='font-weight-bold text-right'><?= $user->email ?>?</div>" data-response="script" href="#"><i class="fa fa-power-off mr-2 mr-lg-0"></i><span class="d-lg-none">Выход</span></a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container-fluid py-2" style="margin-top:35px;">
        <?= $TEMPLATE_DATA ?>
        <footer class="border-top mt-2 pt-2">
            <div class="row">
                <div class="col-12 col-md">
                    <small class="d-block mb-3 text-muted">&copy; <a class="text-muted" href="//wrong-mvc.com" target="_blank">wrong-mvc.com</a> <?= Wrong\Start\Env::$e->VERSION ?></small>
                </div>
            </div>
        </footer>
    </div>
    <div id="arrowTop" hidden><i class="fa fa-chevron-circle-up"></i></div>
    <script>
        arrowTop.onclick = () => {
            $('body,html').animate({
                scrollTop: 0
            }, 1000);
        };
        window.addEventListener('scroll', function() {
            arrowTop.hidden = (pageYOffset < document.documentElement.clientHeight);
        });
    </script>
</body>

</html>