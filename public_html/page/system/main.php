<?php

/**
 * @file
 * @brief главная страница админки со списком линков на документацию + страница документации для не авторизованных
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

if ($user->id && $request == '/documentation') {
    header("Location: /system?main");
    exit;
}

?>
<?php if (!$user->id) : ?>
    <section id="header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="logo"><a onclick="return wronginfo();" href="/<?= $user->id ? 'system?main' : '' ?>" id="wrong">Wrong MVC</a></div>
                    <nav id="nav">
                        <a title="Что дальше?" data-toggle="modal" data-target="#todo" href="/docs/md__t_o_d_o.html">Что дальше?</a>
                        <a title="Отзывы и техподдержка" data-toggle="modal" data-target="#comments" href="/comments">Отзывы и техподдержка</a>
                        <a title="Вход, демо версия" data-toggle="modal" data-target="#sign-in" href="/enter">Вход</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
<div class="<?= $user->id ? '' : 'container' ?>">
    <div class="description pt-0 <?= $user->id ? '' : 'mt-4' ?>">
        <ul class="text-dark rounded-lg py-4 pl-1 check-list">
            <li><a href="//wrong-mvc.com/docs/md__r_e_a_d_m_e.html">README</a></li>
            <li><a data-toggle="modal" data-target="#todo" href="//wrong-mvc.com/docs/md__t_o_d_o.html">TODO</a></li>
            <li><a href="//wrong-mvc.com/docs">Установка</a></li>
            <li><a href="//wrong-mvc.com/docs/castomization.html">Frontend кастомизация</a></li>
            <li><a href="//wrong-mvc.com/docs/settings.html">Настройки, переменные среды</a></li>
            <li><a href="//wrong-mvc.com/docs/groups_users.html">Группы и пользователи</a></li>
            <li><a href="//wrong-mvc.com/docs/weight.html">Системный вес</a></li>
            <li><a href="//wrong-mvc.com/docs/access.html">Проверка прав доступов пользователя</a></li>
            <li><a href="//wrong-mvc.com/docs/from_uid.html">Вход из под другого пользователя</a></li>
            <li><a href="//wrong-mvc.com/docs/models.html">Модели</a></li>
            <li><a href="//wrong-mvc.com/docs/userlandnaming.html">Руководство по именованию</a></li>
            <li><a href="//wrong-mvc.com/docs/dinamic.html">Динамические модели страниц</a></li>
            <li><a href="//wrong-mvc.com/docs/routing.html">Роутинг, контроллеры URI</a></li>
            <li><a href="//wrong-mvc.com/docs/your_first_model.html">Ваша первая модель</a></li>
            <li><a href="//wrong-mvc.com/docs/layout.html">Шаблонизация, своя вёрстка, подключение стилей</a></li>
            <li><a href="//wrong-mvc.com/docs/import_export.html">Экспорт, импорт, копирование моделей</a></li>
            <li><a href="//wrong-mvc.com/docs/logs.html">Логи действий</a></li>
            <li><a href="//wrong-mvc.com/docs/http_api.html">HTTP API запросы, X-Auth-Token</a></li>
            <li><a href="//wrong-mvc.com/docs/internal_api.html">Cистемные curl API запросы, X-Auth-Token</a></li>
            <li><a href="//wrong-mvc.com/docs/external_api.html">Внешние curl API запросы к любым сервисам</a></li>
            <li><a href="//wrong-mvc.com/docs/packages.html">Подключение node и composer пакетов</a></li>
            <li><a href="//wrong-mvc.com/docs/triggers.html">Триггеры действий и модальных окон</a></li>
            <li><a href="//wrong-mvc.com/docs/js_actions_modals.html">Javascript вызовы действий и окон</a></li>
            <li><a href="//wrong-mvc.com/docs/toasts.html">Всплывающие сообщения</a></li>
            <li><a href="//wrong-mvc.com/docs/ajaxforms.html">AJAX формы, блокировка submit кнопок</a></li>
            <li><a href="//wrong-mvc.com/docs/csrf.html">Автоматическая CSRF защита POST/PUT/DELETE</a></li>
            <li><a href="//wrong-mvc.com/docs/interaction.html">Событие взаимодействия interaction</a></li>
            <li><a href="//wrong-mvc.com/docs/textarea_counters.html">Счётчики символов в textarea</a></li>
            <li><a href="//wrong-mvc.com/docs/hcaptcha.html">Защита форм с внедрением Hcaptcha</a></li>
            <li><a href="//wrong-mvc.com/docs/loadlibs.html">Подгрузка javascript библиотек по факту применения</a></li>
            <li><a href="//wrong-mvc.com/docs/stackjs.html">Javascript-PHP стеки, отложенное выполнение</a></li>
            <li><a href="//wrong-mvc.com/docs/cron.html">Встроенные CRON задачи, многопотоки, автоматизация</a></li>
            <li><a href="//wrong-mvc.com/docs/cache.html">Система кеширования</a></li>
            <li><a href="//wrong-mvc.com/docs/code_editor.html">Встроенный редактор кода</a></li>
            <li><a href="//wrong-mvc.com/docs/files.html">Список файлов</a></li>
            <li><a href="//wrong-mvc.com/docs/hierarchy.html">Иерархия классов</a></li>
            <li><a href="//wrong-mvc.com/docs/annotated.html">Структуры данных</a></li>
            <li><a href="//wrong-mvc.com/docs/functions.html">Поля структур</a></li>
            <li><a href="//wrong-mvc.com/docs/debug_8php.html#details">Отладочные функции dd(), rd(), ld()</a></li>
            <li><a href="//wrong-mvc.com/docs/_l_i_c_e_n_s_e_source.html">LICENSE</a></li>
            <li><a data-toggle="modal" data-target="#comments" href="//wrong-mvc.com/comments">Техническая поддержка</a><?= Wrong\Rights\Group::is_available_group(Wrong\Models\Actions::find(45)) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/../temp/comments') && ($data = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/../temp/comments'), true)) && $data['new'] > 0 ? '<small id="new-comments" style="top:-3px;left:5px;position:relative;"><span class="badge badge-success badge-pill slide-in-elliptic-left-fwd" style="padding:3px 6px 2px 6px;font-weight:300;">Новые сообщения: ' . $data['new'] . '</span></small><script>$(function(){successToast("Новые сообщения в техподдержку: <b>' . $data['new'] . '</b>");})</script>' : '' ?></li>

            <li><a target="_blank" title="График работы над проектом" href="https://wakatime.com/@avtobys/projects/yebubsozfj"><img src="https://wakatime.com/badge/user/bc703945-8673-421d-a0a4-b33295014658/project/b450665a-946a-4a02-9629-56122f1754cc.svg" alt="wakatime"></a></li>
        </ul>
    </div>
</div>