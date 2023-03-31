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
                <th>Запрос</th>
                <th>ID исполнителя</th>
                <th>Расписание</th>
                <th>Метод</th>
                <th>Заголовки</th>
                <th>Данные</th>
                <th>Группа владелец</th>
                <th>Следующее выполнение</th>
                <th style="max-width:200px;">Комментарий</th>
                <th>Вкл / Выкл</th>
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
<div class="alert alert-secondary small p-1 px-2" role="alert">
    <i class="fa fa-exclamation-triangle text-danger mr-2"></i>Для выполнения задач от имени пользователей, пользователь должен быть активен(включен) и для пользователя должно быть включено API по заголовкам X-Auth-Token. Также API должно быть включено на уровне глобальных настроек системы.
</div>


<script>
    $(function() {
        window.dataTablesConfigs[0].ajax = '<?= Wrong\Models\Selects::find(9)->request ?>';
        window.dataTablesConfigs[0].columnDefs = [{
            orderable: false,
            targets: [11, 12, 13, 14]
        }];
        window.dataTablesConfigs[0].initComplete = function() {
            $('#table-crontabs_length label').append('<button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#add-crontab" style="font-size:12px;margin-left:7px;"><i class="fa fa-plus-circle"></i> Добавить</button><button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#hide-table-cols" title="Видимость колонок таблицы <b>'+$('title').text()+'</b>" style="font-size:12px;margin-left:5px;"><i class="fa fa-table"></i></button>');

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

    $(window).blur(function() {
        $('#shedule-next').popover('dispose');
    });
</script>
