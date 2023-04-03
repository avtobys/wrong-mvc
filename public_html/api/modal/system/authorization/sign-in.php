<?php

/**
 * @file
 * @brief окно авторизации
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

if (Wrong\Rights\Group::is_available_group(Wrong\Models\Pages::find('/system', 'request'))) {
    exit('<script>location.href="/system";</script>');
}

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content text-white" style="background:#3B4346 url(/assets/system/img/bg01.jpg);">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-sign-in mr-2"></i>Вход</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= Wrong\Models\Actions::find(1)->request ?>">
                    <input type="hidden" name="h-captcha-response">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <span class="fa fa-at"></span>
                                </span>
                            </div>
                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-key"></i>
                                </span>
                            </div>
                            <input type="password" class="form-control" name="password" placeholder="Пароль" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Войти</button>
                    </div>
                    <div class="text-right small">
                        <a data-dismiss="modal" data-toggle="modal" data-target="#sign-forgot" href="#">Забыли пароль?</a>
                    </div>
                    <p class="text-center">Или войдите при помощи:</p>
                    <div class="btn-group w-100">
                        <a data-action="oauth-google" data-response="script" href="#" class="btn btn-outline-info w-50"><i class="fa fa-google"></i>&nbsp; Google</a>
                        <a data-action="oauth-yandex" data-response="script" href="#" class="btn btn-outline-info w-50"><i class="fa-brands fa-yandex"></i>&nbsp; Yandex</a>
                    </div>
                </form>
                <p class="text-center text-muted small mt-3">Ещё нет аккаунта? <a data-dismiss="modal" data-toggle="modal" data-target="#sign-up" href="#">Регистрируйтесь!</a></p>
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
                    } else if (response.error == 'password') {
                        $("#<?= $basename ?> [name='password']").addClass("is-invalid");
                        $("#<?= $basename ?> [name='password']").parent().after('<div class="invalid-feedback">Пароль указан некорректно</div>');
                        $("#<?= $basename ?> [name='password']").parent().next(".invalid-feedback").fadeIn();
                    } else if (response.error == 'hcaptcha') {
                        _modal("#hcaptcha");
                    } else if (response.error == 'auth') {
                        $("#<?= $basename ?> [name='password']").parent().after('<div class="invalid-feedback">Ошибка авторизации</div>');
                        $("#<?= $basename ?> [name='password']").parent().next(".invalid-feedback").fadeIn();
                    } else if (response.result == 'ok') {
                        $.getScript('/api/action/stackjs');
                        $('body').hide();
                        scrollTo(0, 0);
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