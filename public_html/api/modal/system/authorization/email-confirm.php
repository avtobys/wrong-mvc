<?php

/**
 * @file
 * @brief окно отправки email подтверждения почты
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Подтверждение Email</h5>
            </div>
            <div class="modal-body pt-2">
                <form action="<?= Wrong\Models\Actions::find(26)->request ?>">
                    <input type="hidden" name="h-captcha-response">
                    <div class="border px-2 py-1 rounded bg-light-info">
                        Вам отправлено письмо с кодом подтверждения, если оно не доставлено, проверьте папку "спам" или отправьте заново.
                    </div>
                    <div class="input-group input-group mt-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <span class="fa fa-at"></span>
                            </span>
                        </div>
                        <input type="email" class="form-control" name="email" value="<?= $user->email ?>" placeholder="Email">
                    </div>
                    <button type="submit" class="btn btn-block btn-success mt-3">Отправить ещё раз</button>
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
                    if (response.error == 'hcaptcha') {
                        _modal("#hcaptcha");
                        return;
                    } else if (response.error) {
                        errorToast(response.error);
                        return;
                    }
                    $('.toast').toast('hide');
                    successToast(response.message);
                    $("#<?= $basename ?>").modal("hide");
                })
                .always(() => {
                    unlockSubmit($("#<?= $basename ?> form [type=submit]"));
                });
        });

        window.verifyCallback = function(token) {
            $("#<?= $basename ?> input[name=h-captcha-response]").val(token);
            $("#hcaptcha").modal("hide");
            $("#<?= $basename ?> form").submit();
            $("#<?= $basename ?> input[name=h-captcha-response]").val("");
        }
    </script>
</div>
