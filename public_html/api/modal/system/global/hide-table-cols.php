<?php

/**
 * @file
 * @brief окно настройки видимости колонок в таблицах
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-2">
                <div class="d-flex">
                    <form class="flex-fill"></form>
                    <div class="align-items-center d-flex flex-column ml-2 mt-2 pb-1 pt-2 px-1">
                        <span style="font-size:10px;top:15px;position:absolute;text-align:center;"></span>
                        <input title="Максимальная высота скролла таблицы" data-placement="right" type="range" orient="vertical" class="form-control-range mt-2" name="vh" min="30" max="500" value="80" step="5">
                        <a onclick="$('#<?= $basename ?> [name=vh]').val(80).trigger('input');return false;" title="Вернуть значение по умолчанию" class="mt-2" style="line-height:1;" href="#"><i class="fa fa-reply-all"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $("#<?= $basename ?> .modal-title").html("Видимость колонок: <b>" + $("title").text() + "</b>");

        try {
            window.table_data = JSON.parse(window.localStorage.table_data) || {};
        } catch (error) {
            window.table_data = {};
            console.log(error);
        }

        $('.dataTable thead:first th').each((i, th) => {
            let name = $(th).attr('data-name') || $(th).text();
            let table = location.pathname.split('/').pop();
            let visible = true;
            if (table_data[table] && table_data[table][i] === false) {
                visible = false;
            }
            $("#<?= $basename ?> form").append('<div class="input-group mt-2 py-1 px-2 rounded ' + (visible ? 'bg-success' : 'bg-info') + ' text-white" style="cursor:pointer;">\
                        <div class="align-items-center input-group-prepend w-50">\
                            <div class="custom-control custom-switch">\
                                <input data-index="' + i + '" type="checkbox" name="index[]" class="custom-control-input" id="swith-cols-' + i + '" ' + (visible ? 'checked' : '') + '>\
                                <label class="custom-control-label" for="swith-cols-' + i + '">' + (visible ? 'Видимо' : 'Скрыто') + '</label>\
                            </div>\
                        </div>\
                        <div class="input-group-append w-50">\
                            ' + name + '\
                        </div>\
                    </div>');
        });

        $("#<?= $basename ?> form .input-group-prepend, #<?= $basename ?> form .input-group-append").click(function() {
            let checked = $(this).parent().find('[type=checkbox]').prop('checked');
            $(this).parent().find('[type=checkbox]').prop('checked', !checked).trigger('change');
        });

        $("#<?= $basename ?> [type=checkbox]").change(function() {
            if ($(this).is(':checked')) {
                $(this).next().html('Видимо');
            } else {
                $(this).next().html('Скрыто');
            }
            $(this).parents('.input-group').toggleClass(['bg-success', 'bg-info']);
            let table = location.pathname.split('/').pop();
            table_data[table] = table_data[table] || {};
            table_data[table][+$(this).attr('data-index')] = $(this).is(':checked');
            window.localStorage.table_data = JSON.stringify(table_data);
            tableCss();
        });

        try {
            window.table_data_scroll = JSON.parse(window.localStorage.table_data_scroll) || {};
            let table = location.pathname.split('/').pop();
            if (table_data_scroll[table]) {
                $("#<?= $basename ?> [name=vh]").val(table_data_scroll[table]).trigger('change');
            }
        } catch (error) {
            window.table_data_scroll = {};
            console.log(error);
        }

        $("#<?= $basename ?> [type=range]").on('input', function() {
            $(this).prev().html(+this.value == 500 ? '∞' : this.value + 'vh');
            let table = location.pathname.split('/').pop();
            table_data_scroll[table] = table_data_scroll[table] || {};
            table_data_scroll[table] = this.value;
            window.localStorage.table_data_scroll = JSON.stringify(table_data_scroll);
            tableCss();
        }).trigger('input');

    </script>
</div>