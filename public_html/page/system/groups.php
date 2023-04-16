<?php

/**
 * @file
 * @brief страница админки с моделями типа "группы"
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>

<div class="table-responsive p-2">
    <table id="table-groups" class="table table-hover table-striped table-bordered table-sm text-nowrap small" style="width:100%">
        <thead class="bg-info text-center">
            <tr>
                <th>ID</th>
                <th style="min-width:130px;">Название</th>
                <th>Группа владелец</th>
                <th>Системный вес</th>
                <th>Лимит моделей</th>
                <th>Моделей / Активно</th>
                <th>Доступно моделей</th>
                <th>Каталог по умолчанию</th>
                <th>Пользователей</th>
                <th style="max-width:200px;">Комментарий</th>
                <th>Лог действий</th>
                <th data-name="Вкл / выкл" style="width:60px;"><i class="fa fa-power-off"></i></th>
                <th data-name="Очистить" style="width:25px;"><i class="fa fa-eraser"></i></th>
                <th data-name="Удалить" style="width:25px;"><i class="fa fa-trash"></i></th>
            </tr>
        </thead>
    </table>
</div>

<script>
    $(function() {
        window.dataTablesConfigs[0].ajax = '<?= Wrong\Models\Selects::find(6)->request ?>';
        window.dataTablesConfigs[0].columnDefs = [{
            orderable: false,
            targets: [11, 12, 13]
        }];
        window.dataTablesConfigs[0].initComplete = function() {
            $('#table-groups_length label').append('<button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#add-group" style="font-size:12px;margin-left:7px;"><i class="fa fa-plus-circle"></i> Добавить</button><button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#hide-table-cols" title="Видимость колонок таблицы <b>' + $('title').text() + '</b>" style="font-size:12px;margin-left:5px;"><i class="fa fa-table"></i></button><button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#view-page" data-uri="//wrong-mvc.com/docs/models.html#groups" title="Документация" style="font-size:12px;margin-left:5px;"><i class="fa fa-question-circle"></i></button>');

            $('#table-groups_filter label').append('<button title="Фильтр" data-target="#filter" data-table="groups" data-toggle="modal" class="btn btn-outline-primary btn-sm text-nowrap" style="font-size:12px;margin-left:5px;"><i class="fa fa-filter"></i></button><button id="reset-filter" data-action="filter" data-reset="true" data-table="groups" data-callback="afterResetFilter" title="Сбросить фильтр" class="btn btn-warning btn-sm text-nowrap" style="font-size:12px;margin-left:5px;display:<?= isset($_SESSION['filter']['groups']) ? 'inline-block' : 'none' ?>;"><i class="fa fa-close"></i></button>');
        }
        $('#table-groups').DataTable(window.dataTablesConfigs[0]);
    });

    function toggledLogs(response) {
        if (response.error) {
            errorToast(response.error);
            return;
        }
        $('#tgl-log-' + response.id).prop({
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
</script>