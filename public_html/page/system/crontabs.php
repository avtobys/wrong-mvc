<?php

/**
 * @file
 * @brief страница админки с моделями типа "cron задачи"
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>
<div class="table-responsive p-2">
    <table id="table-crontabs" class="table table-hover table-striped table-bordered table-sm text-nowrap small" style="width:100%">
        <thead class="bg-info text-center">
            <tr>
                <th>ID</th>
                <th>Потоки</th>
                <th style="max-width:240px;">CLI команда</th>
                <th style="max-width:240px;">Запрос</th>
                <th>ID исполнителя</th>
                <th>Расписание</th>
                <th>Метод</th>
                <th>Заголовки</th>
                <th>Данные</th>
                <th>Группа владелец</th>
                <th>Будет выполнено</th>
                <th style="max-width:200px;">Комментарий</th>
                <th data-name="Вкл / выкл" style="width:60px;"><i class="fa fa-power-off"></i></th>
                <th data-name="Выполнить" style="width:25px;"><i class="fa fa-play"></i></th>
                <th data-name="Копия" style="width:25px;"><i class="fa fa-copy"></i></th>
                <th data-name="Экспорт" style="width:25px;"><i class="fa fa-download"></i></th>
                <th data-name="Удалить" style="width:25px;"><i class="fa fa-trash"></i></th>
            </tr>
        </thead>
    </table>
</div>
<?php if (!Wrong\Start\Env::$e->CRON_ACT) : ?>
    <div id="crontabs-alert" class="alert alert-secondary small p-1 px-2 my-2" role="alert">
        <i class="fa fa-exclamation-triangle text-danger mr-2"></i>Для выполнения задач они должны быть активированы в общих настройках системы, в данный момент выполнение отключено.
    </div>
<?php endif; ?>
<div class="alert alert-secondary small p-1 px-2 my-2" role="alert">
    <i class="fa fa-exclamation-triangle text-danger mr-2"></i>Для выполнения http задач от имени пользователей, пользователь должен быть активен(включен) и для пользователя должно быть включено API по заголовкам X-Auth-Token. Также API должно быть включено на уровне глобальных настроек системы.
</div>
<div class="alert alert-secondary alert-dismissible fade show small p-1 px-2" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <i class="fa fa-info-circle text-black-50 mr-2"></i> Потоки: в процессе / минимум / максимум / держать потоки / предел нагрузки
    <ul>
        <li>В процессе - текущее количество исполняемых потоков задачи</li>
        <li>Минимум - минимальное количество запускаемых по расписанию задачей потоков её выполнения</li>
        <li>Максимум - масксимальное количество выполняемых потоков задачи при котором остальным запускам(потокам) этой задачи будет отказано</li>
        <li>Держать потоки - если установлено "да", то всегда будет поддерживаться минимальное установленное количество потоков, вне зависимости от периодичности запуска задачи. Каждый поток при запуске будет создавать необходимое количество дополнительных независимых форков.</li>
        <li>Предел нагрузки - устанавливается и рассчитывается в процентах от 1% до 1000% по формуле: текущий load average / кол-во логических ядер сервера * 100. Например 4/12*100 = 33% нагрузка. Где 4 это текущий la на 12 логических процессорах. 12 la из 12 ядер = 100% нагрузка сервера. 1000% - если сервер ещё работает, значит он крепыш. Если значение превышает установленное - в запуске очередного потока будет отказано. Вы устанавливаете лишь процент допустимой нагрузки при котором выполению данной задачи(любого её потока) будет отказано.</li>
        <li>Если вы не понимате зачем это вам, не настраивайте потоки, оставьте их по умолчанию и пользуйтесь cron задачами в их классическом варианте.</li>
        <li>При использовании большого количества потоков позаботьтесь о бд настройках сервера max_connections и max_user_connections. Запросы к бд максимально короткие.</li>
    </ul>

</div>


<script>
    $(function() {
        window.dataTablesConfigs[0].ajax = '<?= Wrong\Models\Selects::find(9)->request ?>';
        window.dataTablesConfigs[0].columnDefs = [{
            orderable: false,
            targets: [12, 13, 14, 15, 16]
        }];
        window.dataTablesConfigs[0].initComplete = function() {
            $('#table-crontabs_length label').append('<button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#add-crontab" style="font-size:12px;margin-left:7px;"><i class="fa fa-plus-circle"></i> Добавить</button><button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#hide-table-cols" title="Видимость колонок таблицы <b>' + $('title').text() + '</b>" style="font-size:12px;margin-left:5px;"><i class="fa fa-table"></i></button>');

            $('#table-crontabs_filter label').append('<button title="Фильтр" data-target="#filter" data-table="crontabs" data-toggle="modal" class="btn btn-outline-primary btn-sm text-nowrap" style="font-size:12px;margin-left:5px;"><i class="fa fa-filter"></i></button><button id="reset-filter" data-action="filter" data-reset="true" data-table="crontabs" data-callback="afterResetFilter" title="Сбросить фильтр" class="btn btn-warning btn-sm text-nowrap" style="font-size:12px;margin-left:5px;display:<?= isset($_SESSION['filter']['crontabs']) ? 'inline-block' : 'none' ?>;"><i class="fa fa-close"></i></button>');
        }
        $('#table-crontabs').DataTable(window.dataTablesConfigs[0]);
        setInterval(() => {
            !$(".modal:visible").length && $('.dataTable').DataTable().ajax.reload(null, false);
        }, 20000);
    });

    function initSheduleNextPopover() {
        $('#shedule-next').popover('dispose');
        $('#shedule-next')[0].focus();
        $('#shedule-next').popover({
            trigger: 'focus',
            title: '<div class="d-flex align-items-center"><i class="fa fa-clock-o mr-2"></i><div>Ближайшее расписание:</div></div>',
            content: '<div class="text-center mb-2 shedule-next-list"><i class="fa fa-circle-o-notch fa-spin text-gray-500 display-4"></i></div>',
            html: true,
            placement: 'right'

        });
        $('#shedule-next').popover('show');
    }

    function showNextCrontabs(response) {
        if (response.id != $('[data-action="show-next-crontabs"]').data('id')) {
            return;
        }
        if (response.error) {
            errorToast(response.error);
            $('#shedule-next').popover('dispose');
            return;
        }
        $('.shedule-next-list').empty();
        response.shedules.forEach(el => {
            $('.shedule-next-list').append('<div class="text-left">' + el + '</div>');
        });
        $('#shedule-next').popover('update');
    }

    function precallbackShedule() {
        setTimeout(() => {
            $.getScript('/api/action/stackjs');
        }, 100);
    }

    $(window).blur(function() {
        $('#shedule-next').popover('dispose');
    });
</script>