<?php

/**
 * @file
 * @brief окно изменения названия/имени для модели
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

if (!($row = Wrong\Database\Controller::find($_GET['id'], 'id', $_GET['table']))) {
    exit('<script>errorToast("Ошибка!");</script>');
}

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Название</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= Wrong\Models\Actions::find(17)->request ?>">
                    <input type="hidden" name="id" value="<?= $row->id ?>">
                    <input type="hidden" name="table" value="<?= $_GET['table'] ?>">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Название</span>
                        </div>
                        <input type="text" name="name" class="form-control" value="<?= $row->name ?>" autocomplete="off" required>
                    </div>
                    <button type="submit" class="btn btn-sm btn-block btn-success mt-3">Сохранить</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            setTimeout(() => {
                let el = $("#<?= $basename ?> [name='name']")[0];
                el.setSelectionRange(el.value.length, el.value.length);
                el.focus();
            }, 600);
        });

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
