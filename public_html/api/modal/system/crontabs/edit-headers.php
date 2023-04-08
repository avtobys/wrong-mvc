<?php

/**
 * @file
 * @brief окно редактирования заголовков cron задачи
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

if (!($row = Wrong\Models\Crontabs::find($_GET['id']))) {
    exit('<script>errorToast("Ошибка!");</script>');
}

if ($row->method == 'CLI') {
    exit('<script>errorToast("У CLI задач нельзя менять заголовки!");</script>');
}

$headers = json_decode($row->headers);
$content_types = [];
foreach ($headers as $item) {
    if (stripos($item, 'Content-Type:') !== false) {
        $content_types[] = $item;
    }
}

$headers = array_filter($headers, function ($item) {
    return stripos($item, 'Content-Type:') === false;
});

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Изменить заголовки</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= Wrong\Models\Actions::find(33)->request ?>">
                    <input type="hidden" name="id" value="<?= $row->id ?>">
                    <input type="hidden" name="table" value="<?= $_GET['table'] ?>">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Content-Type</span>
                        </div>
                        <select name="headers[]" class="custom-select">
                            <option value="">none</option>
                            <option value="Content-Type: application/x-www-form-urlencoded" <?= in_array('Content-Type: application/x-www-form-urlencoded', $content_types) ? 'selected' : '' ?>>application/x-www-form-urlencoded</option>
                            <option value="Content-Type: application/json; charset=utf-8" <?= in_array('Content-Type: application/json; charset=utf-8', $content_types) ? 'selected' : '' ?>>application/json; charset=utf-8</option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Заголовки(опционально)</span>
                        </div>
                        <div class="flex-fill">
                            <?php foreach ($headers as $item) : ?>
                                <input type="text" name="headers[]" class="form-control form-control-sm" value="<?= $item ?>" placeholder="Key: value" autocomplete="off">
                            <?php endforeach; ?>
                            <?php if (!$headers) : ?>
                                <input type="text" name="headers[]" class="form-control form-control-sm" value="" placeholder="Key: value" autocomplete="off">
                            <?php endif; ?>
                        </div>
                        <div class="input-group-append">
                            <button title="Убрать крайнее поле" type="button" class="btn btn-danger rm-header" style="display:none;"><i class="fa fa-minus"></i></button>
                            <button title="Добавить ещё поле" type="button" class="btn btn-primary add-header"><i class="fa fa-plus"></i></button>
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

        if ($("#<?= $basename ?> [type='text'][name='headers[]']").length > 1) {
            $("#<?= $basename ?> .rm-header").show();
        }
    </script>
</div>
