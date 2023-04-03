<?php

/**
 * @file
 * @brief окно добавления новой cron задачи
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-clock"></i> Добавить <span>HTTP</span> задачу</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-2">
                <form action="<?= Wrong\Models\Actions::find(29)->request ?>">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Группа владелец</span>
                        </div>
                        <select name="owner_group" class="custom-select">
                            <?php
                            foreach ($user->subordinate_groups as $id) {
                                $row = Wrong\Rights\Group::row($id);
                                echo '<option data-path="' . $row->path . '" value="' . $row->id . '" ' . ($row->id == $user->main_group_id ? ' selected' : '') . '>' . Wrong\Rights\Group::text($row->id) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Метод</span>
                        </div>
                        <select name="method" class="custom-select">
                            <option value="GET">HTTP GET</option>
                            <option value="POST">HTTP POST</option>
                            <option value="PUT">HTTP PUT</option>
                            <option value="DELETE">HTTP DELETE</option>
                            <option value="CLI">CLI COMMAND</option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm mt-2" data-cli="false" hidden>
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Запрос</span>
                        </div>
                        <input type="text" name="request" class="form-control" value="/request" placeholder="/request" autocomplete="off">
                    </div>
                    <div class="input-group input-group-sm mt-2" data-cli="true" hidden>
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">CLI команда</span>
                        </div>
                        <input type="text" name="cli" class="form-control" value="" placeholder="CLI команда" autocomplete="off">
                    </div>
                    <div class="input-group input-group-sm mt-2" data-cli="false" hidden>
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">ID исполнителя(опционально)</span>
                        </div>
                        <input title="Если не указано, задача будет выполняться без авторизации" type="number" name="user_id" class="form-control" value="" placeholder="ID от кого выполнять задачу">
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Расписание cron<a tabindex="0" id="shedule-next" role="button" href="#"></a></span>
                        </div>
                        <input title="Минуты Часы День Месяц День недели" type="text" name="shedule" class="form-control" value="* * * * *" placeholder="* * * * *" autocomplete="off">
                        <div class="input-group-append">
                            <a onclick="$(this).data({'shedule':$(this).parents('.input-group').find('[name=shedule]').val(),'id':Date.now()});initSheduleNextPopover();" data-action="show-next-crontabs" data-callback="showNextCrontabs" title="Посмотреть расписание ближайших выполнений" href="#" class="btn btn-primary" role="button"><i class="fa fa-clock-o"></i></a>
                        </div>
                    </div>
                    <div class="input-group input-group-sm mt-2" data-cli="false" hidden>
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Content-Type</span>
                        </div>
                        <select name="headers[]" class="custom-select">
                            <option value="">none</option>
                            <option value="Content-Type: application/x-www-form-urlencoded">application/x-www-form-urlencoded</option>
                            <option value="Content-Type: application/json; charset=utf-8">application/json; charset=utf-8</option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm mt-2" data-cli="false" hidden>
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Дополнительные заголовки(опционально)</span>
                        </div>
                        <div class="flex-fill">
                            <input type="text" name="headers[]" class="form-control form-control-sm" value="" placeholder="Key: value" autocomplete="off">
                        </div>
                        <div class="input-group-append">
                            <button title="Убрать крайнее поле" type="button" class="btn btn-danger rm-header" style="display:none;"><i class="fa fa-minus"></i></button>
                            <button title="Добавить ещё поле" type="button" class="btn btn-primary add-header"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="input-group input-group-sm mt-2" data-cli="false" hidden>
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Данные json объекта или post формы(опционально)</span>
                        </div>
                        <div class="flex-fill">
                            <input type="text" name="data[]" class="form-control form-control-sm" value="" placeholder="Key: value" autocomplete="off">
                        </div>
                        <div class="input-group-append">
                            <button title="Убрать крайнее поле" type="button" class="btn btn-danger rm-data" style="display:none;"><i class="fa fa-minus"></i></button>
                            <button title="Добавить ещё поле" type="button" class="btn btn-primary add-data"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Комментарий</span>
                        </div>
                        <input type="text" name="note" class="form-control" value="" placeholder="Необязательный комментарий к задаче" autocomplete="off">
                    </div>
                    <button type="submit" class="btn btn-sm btn-block btn-success mt-3">Сохранить</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        $("#<?= $basename ?> form").submit(function(e) {
            lockSubmit($("#<?= $basename ?> form [type=submit]"));
            e.preventDefault();
            $.ajax({
                    type: "POST",
                    url: $(this).attr("action"),
                    data: $(this).serialize(),
                    dataType: "json",
                    statusCode: {
                        404: errorToast,
                        403: errorToast
                    }
                })
                .done(response => {
                    if (response.error) {
                        errorToast(response.error);
                        return;
                    }
                    $('.dataTable').DataTable().ajax.reload(null, false);
                    $('.toast').toast('hide');
                    successToast(response.message);
                    $("#<?= $basename ?>").modal("hide");
                })
                .always(() => {
                    unlockSubmit($("#<?= $basename ?> form [type=submit]"));
                });
        });

        $("#<?= $basename ?> .add-header").click(() => {
            let input = $("#<?= $basename ?> [type='text'][name='headers[]']:last");
            let clone = input.clone();
            clone.val("");
            input.after(clone);
            $("#<?= $basename ?> .rm-header").show();
        });

        $("#<?= $basename ?> .rm-header").click(() => {
            if ($("#<?= $basename ?> [type='text'][name='headers[]']").length > 1) {
                $("#<?= $basename ?> [type='text'][name='headers[]']:last").remove();
            }
            if ($("#<?= $basename ?> [type='text'][name='headers[]']").length == 1) {
                $("#<?= $basename ?> .rm-header").hide();
            }
        });

        $("#<?= $basename ?> .add-data").click(() => {
            let input = $("#<?= $basename ?> [type='text'][name='data[]']:last");
            let clone = input.clone();
            clone.val("");
            input.after(clone);
            $("#<?= $basename ?> .rm-data").show();
        });

        $("#<?= $basename ?> .rm-data").click(() => {
            if ($("#<?= $basename ?> [type='text'][name='data[]']").length > 1) {
                $("#<?= $basename ?> [type='text'][name='data[]']:last").remove();
            }
            if ($("#<?= $basename ?> [type='text'][name='data[]']").length == 1) {
                $("#<?= $basename ?> .rm-data").hide();
            }
        });

        $("#<?= $basename ?> [name=method]").change(function() {
            if (this.value == "CLI") {
                $("#<?= $basename ?> [data-cli='false']").attr("hidden", true);
                $("#<?= $basename ?> [data-cli='true']").attr("hidden", false);
                $('#<?= $basename ?> .modal-title span').html('CLI');
            } else {
                $("#<?= $basename ?> [data-cli='false']").attr("hidden", false);
                $("#<?= $basename ?> [data-cli='true']").attr("hidden", true);
                $('#<?= $basename ?> .modal-title span').html('HTTP');
            }
        }).trigger("change");
    </script>
</div>