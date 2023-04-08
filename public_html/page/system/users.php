<?php

/**
 * @file
 * @brief страница админки с моделями типа "пользователи"
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>

<div class="table-responsive p-2">
    <table id="table-users" class="table table-hover table-striped table-bordered table-sm text-nowrap small" style="width:100%">
        <thead class="bg-info text-center">
            <tr>
                <th>ID</th>
                <th style="max-width:250px;">Группы доступа</th>
                <th>Группа владелец</th>
                <th>Email</th>
                <th>Время онлайна</th>
                <th>Время регистрации</th>
                <th>Крайний IP</th>
                <th>Крайний Request</th>
                <th data-name="Email"><span class="fa fa-at"></span></th>
                <th>X-Auth-Token</th>
                <th style="max-width:200px;">Комментарий</th>
                <th>API</th>
                <th data-name="Вкл / выкл" style="width:60px;"><i class="fa fa-power-off"></i></th>
                <th data-name="Удалить" style="width: 25px;"><i class="fa fa-trash"></i></th>
            </tr>
        </thead>
    </table>
</div>

<script>
    $(function() {
        window.dataTablesConfigs[0].ajax = '<?= Wrong\Models\Selects::find(7)->request ?>';
        window.dataTablesConfigs[0].columnDefs = [{
            orderable: false,
            targets: [8, 11, 12, 13]
        }];
        window.dataTablesConfigs[0].initComplete = function() {
            $('#table-users_length label').append('<button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#add-user" style="font-size:12px;margin-left:7px;"><i class="fa fa-plus-circle"></i> Добавить</button><button class="btn btn-outline-primary btn-sm autoupdate" data-toggle="button" title="Автообновление таблицы" style="font-size:12px;margin-left:5px;"><i class="fa fa-play"></i></button><button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#hide-table-cols" title="Видимость колонок таблицы <b>' + $('title').text() + '</b>" style="font-size:12px;margin-left:5px;"><i class="fa fa-table"></i></button>');

            $('#table-users_filter label').append('<button title="Фильтр" data-target="#filter" data-table="users" data-toggle="modal" class="btn btn-outline-primary btn-sm text-nowrap" style="font-size:12px;margin-left:5px;"><i class="fa fa-filter"></i></button><button id="reset-filter" data-action="filter" data-reset="true" data-table="users" data-callback="afterResetFilter" title="Сбросить фильтр" class="btn btn-warning btn-sm text-nowrap" style="font-size:12px;margin-left:5px;display:<?= isset($_SESSION['filter']['users']) ? 'inline-block' : 'none' ?>;"><i class="fa fa-close"></i></button>');

            window.autoupdate = window.localStorage.autoupdate ? JSON.parse(window.localStorage.autoupdate) : {};
            if (window.autoupdate[location.pathname]) {
                $('.autoupdate').trigger('click');
            }
        }
        $('#table-users').DataTable(window.dataTablesConfigs[0]);
    });

    function fromUser(response) {
        if (response.error) {
            errorToast(response.error);
            return;
        }
        $.getScript('/api/action/stackjs');
    }

    function toggledApi(response) {
        if (response.error) {
            errorToast(response.error);
            return;
        }
        $('#tgl-api-' + response.id).prop({
            'checked': response.act
        });
        $('.toast').toast('hide');
        if (!response.act) {
            dangerToast(response.message);
        } else {
            successToast(response.message);
        }
        setTimeout(() => {
            $('.dataTable').DataTable().ajax.reload(null, false);
        }, 350);
    }

    $(document).on('click', '.autoupdate', function() {
        $(this).find('.fa').toggleClass(['fa-play', 'fa-stop']);
        window.autoupdate[location.pathname] = $('.autoupdate').is('.active');
        window.localStorage.autoupdate = JSON.stringify(window.autoupdate);
    });

    setInterval(() => {
        $('.autoupdate').is('.active') && $('.dataTable').DataTable().ajax.reload(null, false);
    }, 5000);
</script>
