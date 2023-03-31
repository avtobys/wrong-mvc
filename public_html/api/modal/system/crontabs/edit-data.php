<?php

/**
 * @file
 * @brief окно редактирования тела запроса для cron задачи
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

if (!($row = Wrong\Database\Controller::find($_GET['id'], 'id', $_GET['table']))) {
    exit('<script>errorToast("Ошибка!");</script>');
}

if (!in_array($row->owner_group, $user->subordinate_groups)) {
    exit('<script>errorToast("Недостаточно прав!");</script>');
}

$data = json_decode($row->data, true);
foreach ($data as $key => $value) {
    $data[$key] = "$key: $value";
}

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Изменить данные</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= Wrong\Models\Actions::find(34)->request ?>">
                    <input type="hidden" name="id" value="<?= $row->id ?>">
                    <input type="hidden" name="table" value="<?= $_GET['table'] ?>">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Данные json объекта или post формы(опционально)</span>
                        </div>
                        <div class="flex-fill">
                            <?php foreach ($data as $item) : ?>
                                <input type="text" name="data[]" class="form-control form-control-sm" value="<?= $item ?>" placeholder="Key: value" autocomplete="off">
                            <?php endforeach; ?>
                            <?php if (!$data) : ?>
                                <input type="text" name="data[]" class="form-control form-control-sm" value="" placeholder="Key: value" autocomplete="off">
                            <?php endif; ?>
                        </div>
                        <div class="input-group-append">
                            <button title="Убрать крайнее поле" type="button" class="btn btn-danger rm-data" style="display:none;"><i class="fa fa-minus"></i></button>
                            <button title="Добавить ещё поле" type="button" class="btn btn-primary add-data"><i class="fa fa-plus"></i></button>
                        </div>
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

        if ($("#<?= $basename ?> [type='text'][name='data[]']").length > 1) {
            $("#<?= $basename ?> .rm-data").show();
        }
    </script>
</div>
