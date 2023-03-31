<?php

/**
 * @file
 * @brief окно импорта модели
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Импорт модели</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-2">
                <form action="<?= Wrong\Models\Actions::find(38)->request ?>">
                    <div class="input-group input-group-sm">
                        <div class="custom-file">
                            <input type="file" name="file" accept="application/zip" class="custom-file-input" id="input-file">
                            <label class="custom-file-label" for="input-file">Выбрать .zip файл импорта</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-block btn-success mt-3">Импорт</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        $("#input-file").change(function() {
            $(this).next().html($("#input-file")[0].files[0]?.name || 'Выбрать .zip файл импорта');
        });
        $("#<?= $basename ?> form").submit(function(e) {
            lockSubmit($("#<?= $basename ?> form [type=submit]"));
            e.preventDefault();
            let formData = new FormData();
            formData.append('file', $("#input-file")[0].files[0]);
            formData.append('CSRF', window.CSRF);
            $.ajax({
                    type: "POST",
                    url: $(this).attr("action"),
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
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
                    if (location.pathname != response.location) {
                        setTimeout(() => {
                            location.href = response.location;
                        }, 1000);
                    }
                })
                .always(() => {
                    unlockSubmit($("#<?= $basename ?> form [type=submit]"));
                });
        });
    </script>
</div>
