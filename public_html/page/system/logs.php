<?php

/**
 * @file
 * @brief страница админки с логами действий
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>

<div class="table-responsive p-2">
    <table id="table-logs" class="table table-hover table-striped table-bordered table-sm small" style="width:100%">
        <thead class="bg-info text-center">
            <tr>
                <th>Пользователь</th>
                <th>Запрос</th>
                <th style="max-width:50%;">Данные</th>
                <th>Время</th>
                <th>IP</th>
            </tr>
        </thead>
    </table>
</div>

<script>
    $(function() {
        window.dataTablesConfigs[0].ajax = '<?= Wrong\Models\Selects::find(8)->request ?>';
        window.dataTablesConfigs[0].initComplete = function() {
            $('#table-logs_length label').append('<button class="btn btn-outline-primary btn-sm" data-action="clean-logs" data-confirm="true" data-body="Очистить лог?" data-callback="logsCleaned" style="font-size:12px;margin-left:7px;"><i class="fa fa-trash"></i> Очистить</button> <button id="toggle-show" class="btn btn-outline-primary btn-sm" style="font-size:12px;margin-left:7px;"><i class="fa fa-eye-slash"></i> <span>Показать</span></button><button class="btn btn-outline-primary btn-sm autoupdate" data-toggle="button" title="Автообновление таблицы" style="font-size:12px;margin-left:5px;"><i class="fa fa-play"></i></button><button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#hide-table-cols" title="Видимость колонок таблицы <b>' + $('title').text() + '</b>" style="font-size:12px;margin-left:5px;"><i class="fa fa-table"></i></button>');

            window.autoupdate = window.localStorage.autoupdate ? JSON.parse(window.localStorage.autoupdate) : {};
            if (window.autoupdate[location.pathname]) {
                $('.autoupdate').trigger('click');
            }
        }
        window.dataTablesConfigs[0].drawCallback = function(e) {
            $(window).trigger('interaction');
        }
        window.dataTablesConfigs[0].order = [
            [3, 'desc']
        ];
        $('#table-logs').DataTable(window.dataTablesConfigs[0]);

        $(document).on('click', '#toggle-show', function() {
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            $(window).trigger('interaction');
            if ($('#toggle-show').find('i').is('.fa-eye')) {
                $('.log-show').removeClass('show');
            }
        });
    });

    $(window).on("interaction", function() {
        $('.log-show').not('.show').removeAttr('style');
        if ($('#toggle-show').find('i').is('.fa-eye')) {
            $('#toggle-show').find('span').html('Скрыть');
            $('.log-show').not('.show').css({
                margin: '0',
                maxWidth: '850px'
            });
        } else {
            $('#toggle-show').find('span').html('Показать');
            $('.log-show').not('.show').css({
                margin: '0',
                display: '-webkit-box',
                WebkitLineClamp: '1',
                WebkitBoxOrient: 'vertical',
                overflow: 'hidden',
                textOverflow: 'ellipsis',
                whiteSpace: 'normal',
                maxWidth: '850px'
            });
        }
        $('.log-show').each((i, el) => {
            if ($(el).text().match(/"error"/) || $(el).text().match(/404 Not Found/)) {
                $(el).addClass('bg-danger text-white');
                $(el).parents('tr').addClass('bg-danger text-white');
            }
        });
    });

    $(document).on('click', '#table-logs tbody tr', function() {
        $(this).find('.log-show').removeAttr('style');
        if ($(this).find('.log-show').is('.show')) {
            $(this).find('.log-show').css({
                margin: '0',
                display: '-webkit-box',
                WebkitLineClamp: '1',
                WebkitBoxOrient: 'vertical',
                overflow: 'hidden',
                textOverflow: 'ellipsis',
                whiteSpace: 'normal',
                maxWidth: '850px'
            });
        } else {
            $(this).find('.log-show').css({
                margin: '0',
                maxWidth: '850px'
            });
        }
        $(this).find('.log-show').toggleClass('show');
    });

    function logsCleaned(response) {
        if (response.error) {
            errorToast(response.error);
            return;
        }
        successToast(response.message);
        $('.dataTable').DataTable().ajax.reload(null, false);
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