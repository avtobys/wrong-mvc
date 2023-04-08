<?php

/**
 * @file
 * @brief окно редактирования метода запроса для cron задачи
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

if (!($row = Wrong\Models\Crontabs::find($_GET['id']))) {
    exit('<script>errorToast("Ошибка!");</script>');
}

if ($row->method == 'CLI') {
    exit('<script>errorToast("У CLI задач нельзя менять метод!");</script>');
}

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Изменить метод</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= Wrong\Models\Actions::find(35)->request ?>">
                    <input type="hidden" name="id" value="<?= $row->id ?>">
                    <input type="hidden" name="table" value="<?= $_GET['table'] ?>">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Метод</span>
                        </div>
                        <select name="method" class="custom-select">
                            <option value="GET" <?= ($row->method == 'GET' ? 'selected' : '') ?>>HTTP GET</option>
                            <option value="POST" <?= ($row->method == 'POST' ? 'selected' : '') ?>>HTTP POST</option>
                            <option value="PUT" <?= ($row->method == 'PUT' ? 'selected' : '') ?>>HTTP PUT</option>
                            <option value="DELETE" <?= ($row->method == 'DELETE' ? 'selected' : '') ?>>HTTP DELETE</option>
                        </select>
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
