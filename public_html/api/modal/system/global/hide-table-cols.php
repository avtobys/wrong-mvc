<?php

/**
 * @file
 * @brief окно настройки видимости колонок в таблицах
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-2">
                <form></form>
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
    </script>
</div>
