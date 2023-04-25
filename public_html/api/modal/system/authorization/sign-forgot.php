<?php

/**
 * @file
 * @brief окно для запроса напоминания пароля
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

if ($user->access()->page('/system')) {
    exit('<script>location.href="/system";</script>');
}

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content text-white" style="background:#3B4346 url(/assets/system/img/bg01.jpg);">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-question-circle mr-2"></i>Восстановление пароля</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= Wrong\Models\Actions::find(3)->request ?>">
                    <input type="hidden" name="h-captcha-response">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <span class="fa fa-at"></span>
                                </span>
                            </div>
                            <input type="email" class="form-control" name="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Напомнить</button>
                    </div>
                </form>
                <p class="text-center text-muted small mt-3">Помните пароль? <a data-dismiss="modal" data-toggle="modal" data-target="#sign-in" href="#">Войдите!</a></p>
            </div>
        </div>
    </div>
    <script>
        $("#<?= $basename ?> form").submit(function(e) {
            lockSubmit($("#<?= $basename ?> form [type=submit]"));
            $("#<?= $basename ?> .is-invalid").removeClass("is-invalid");
            $("#<?= $basename ?> .invalid-feedback").remove();
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
                    if (response.error == 'email') {
                        $("#<?= $basename ?> [name='email']").addClass("is-invalid");
                        $("#<?= $basename ?> [name='email']").parent().after('<div class="invalid-feedback">Email указан некорректно</div>');
                        $("#<?= $basename ?> [name='email']").parent().next(".invalid-feedback").fadeIn();
                    } else if (response.error == 'auth') {
                        $("#<?= $basename ?> [name='email']").addClass("is-invalid");
                        $("#<?= $basename ?> [name='email']").parent().after('<div class="invalid-feedback">Пользователь не существует</div>');
                        $("#<?= $basename ?> [name='email']").parent().next(".invalid-feedback").fadeIn();
                    } else if (response.error == 'hcaptcha') {
                        _modal("#hcaptcha");
                    } else if (response.message) {
                        $("#<?= $basename ?> form").replaceWith(response.message);
                    } else {
                        errorToast();
                    }
                })
                .always(() => {
                    unlockSubmit($("#<?= $basename ?> form [type=submit]"));
                });
        });

        $("#<?= $basename ?> form input").focus(function() {
            $("#<?= $basename ?> .is-invalid").removeClass("is-invalid");
            $("#<?= $basename ?> .invalid-feedback").remove();
        });

        window.verifyCallback = function(token) {
            $("#<?= $basename ?> input[name=h-captcha-response]").val(token);
            $("#hcaptcha").modal("hide");
            $("#<?= $basename ?> form").submit();
            $("#<?= $basename ?> input[name=h-captcha-response]").val("");
        }
    </script>
</div>
