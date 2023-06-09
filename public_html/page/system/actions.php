<?php

/**
 * @file
 * @brief страница админки с моделями типа "действие"
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>

<div class="table-responsive p-2">
    <table id="table-actions" class="table table-hover table-striped table-bordered table-sm text-nowrap small" style="width:100%">
        <thead class="bg-info text-center">
            <tr>
                <th>ID</th>
                <th>Запрос</th>
                <th>Файл обработчик</th>
                <th>Группы доступа</th>
                <th>Группа владелец</th>
                <th style="max-width:200px;">Комментарий</th>
                <th data-name="Вкл / выкл" style="width:60px;"><i class="fa fa-power-off"></i></th>
                <th data-name="Редактор кода" style="width:25px;"><i class="fa fa-file-code-o"></i></th>
                <th data-name="Копия" style="width:25px;"><i class="fa fa-copy"></i></th>
                <th data-name="Экспорт" style="width:25px;"><i class="fa fa-download"></i></th>
                <th data-name="Удалить" style="width:25px;"><i class="fa fa-trash"></i></th>
            </tr>
        </thead>
    </table>
</div>
<script>
    $(function() {
        window.dataTablesConfigs[0].ajax = '<?= Wrong\Models\Selects::find(2)->request ?>';
        window.dataTablesConfigs[0].columnDefs = [{
            orderable: false,
            targets: [6, 7, 8, 9, 10]
        }];
        window.dataTablesConfigs[0].initComplete = function() {
            $('#table-actions_length label').append('<button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#add-action" style="font-size:12px;margin-left:7px;"><i class="fa fa-plus-circle"></i> Добавить</button><button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#hide-table-cols" title="Видимость колонок таблицы <b>'+$('title').text()+'</b>" style="font-size:12px;margin-left:5px;"><i class="fa fa-table"></i></button><button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#view-page" data-uri="//wrong-mvc.com/docs/models.html#actions" title="Документация" style="font-size:12px;margin-left:5px;"><i class="fa fa-question-circle"></i></button>');

            $('#table-actions_filter label').append('<button title="Фильтр" data-target="#filter" data-table="actions" data-toggle="modal" class="btn btn-outline-primary btn-sm text-nowrap" style="font-size:12px;margin-left:5px;"><i class="fa fa-filter"></i></button><button id="reset-filter" data-action="filter" data-reset="true" data-table="actions" data-callback="afterResetFilter" title="Сбросить фильтр" class="btn btn-warning btn-sm text-nowrap" style="font-size:12px;margin-left:5px;display:<?= isset($_SESSION['filter']['actions']) ? 'inline-block' : 'none' ?>;"><i class="fa fa-close"></i></button>');
        }
        $('#table-actions').DataTable(window.dataTablesConfigs[0]);
    });
</script>
