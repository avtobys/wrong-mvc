<?php

/**
 * @file
 * @brief окно изменения cli команды для cron задачи
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

if (!($row = Wrong\Models\Crontabs::find($_GET['id']))) {
    exit('<script>errorToast("Ошибка!");</script>');
}

if ($row->method != 'CLI') {
    exit('<script>errorToast("Это HTTP задача, ей нельзя установить команду!");</script>');
}

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Изменить CLI команду</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-2">
                <form action="<?= Wrong\Models\Actions::find(49)->request ?>">
                    <input type="hidden" name="id" value="<?= $row->id ?>">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend w-25">
                            <span class="input-group-text w-100">Команда</span>
                        </div>
                        <input type="text" name="cli" class="form-control" value="<?= $row->cli ?>" placeholder="Команда CLI" autocomplete="off" required>
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
    </script>
</div>