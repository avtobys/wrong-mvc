<?php

/**
 * @file
 * @brief окно настройки потоков и нагрузки для cron задачи
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

if (!($row = Wrong\Models\Crontabs::find($_GET['id']))) {
    exit('<script>errorToast("Ошибка!");</script>');
}

$threads = json_decode($row->threads, true) ?: Wrong\Task\Cron::DEFAULT_THERADS_SET;

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Настройка потоков и нагрузки</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-2">
                <form action="<?= Wrong\Models\Actions::find(47)->request ?>">
                    <input type="hidden" name="id" value="<?= $row->id ?>">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend w-25">
                            <span class="input-group-text w-100">Минимум <b></b> потоков</span>
                        </div>
                        <div class="flex-fill bg-light-info border d-flex align-items-center px-2 rounded-right">
                            <input type="range" name="min" class="form-control-range" value="<?= $threads['min'] ?>" min="1" max="1000" required>
                        </div>
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="input-group-prepend w-25">
                            <span class="input-group-text w-100">Максимум <b></b> потоков</span>
                        </div>
                        <div class="flex-fill bg-light-info border d-flex align-items-center px-2 rounded-right">
                            <input type="range" name="max" class="form-control-range" value="<?= $threads['max'] ?>" min="1" max="1000" required>
                        </div>
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="input-group-prepend w-25">
                            <span class="input-group-text w-100">Предельная нагрузка сервера <b></b>%</span>
                        </div>
                        <div class="flex-fill bg-light-info border d-flex align-items-center px-2 rounded-right">
                            <input type="range" name="load" class="form-control-range" value="<?= $threads['load'] ?>" min="1" max="1000" required>
                        </div>
                    </div>
                    <div class="bg-light-info border mt-2 px-2 py-1 rounded">
                        <div class="custom-control custom-checkbox small">
                            <input type="checkbox" name="fixed" class="custom-control-input" id="fixed-threads" <?= $threads['fixed'] ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="fixed-threads">Держать <span id="fixed-min"></span> постоянно</label>
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

        $("#<?= $basename ?> [type=range]").on('input', function() {
            if ($(this).attr("name") == "min" && +this.value > +$("#<?= $basename ?> [name=max]").val()) {
                $("#<?= $basename ?> [name=max]").val(this.value).trigger('input');
            }
            if ($(this).attr("name") == "max" && +this.value < +$("#<?= $basename ?> [name=min]").val()) {
                $("#<?= $basename ?> [name=min]").val(this.value).trigger('input');
            }
            $("#fixed-min").html("<b>" + $("#<?= $basename ?> [name=min]").val() + "</b> поток" + (function(n) {
                let s = n + "";
                s = s.substr(-1);
                let arr = ["", "а", "ов"];
                return (s == 1 && n != 11) ? arr[0] : (s < 5 && s > 1 && n != 12 && n != 13 && n != 14 ? arr[1] : arr[2]);
            })($("#<?= $basename ?> [name=min]").val()));
            $(this).parent().prev().find('b').html('&nbsp;' + this.value + '&nbsp;');
        }).trigger('input');
    </script>
</div>