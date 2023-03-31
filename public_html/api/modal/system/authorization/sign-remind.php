<?php

/**
 * @file
 * @brief окно восстановления пароля
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content text-white" style="background:#3B4346 url(/assets/system/img/bg01.jpg);">
            <div class="modal-header">
                <h5 class="modal-title">Новый пароль</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= Wrong\Models\Actions::find(4)->request ?>">
                    <input type="hidden" name="h-captcha-response">
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($_GET['user_id']) ?>">
                    <input type="hidden" name="md5" value="<?= htmlspecialchars($_GET['md5']) ?>">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-lock"></i>
                                </span>
                            </div>
                            <input type="password" class="form-control" name="password" placeholder="Пароль">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-lock"></i>
                                </span>
                            </div>
                            <input type="password" class="form-control" name="password2" placeholder="Повторите пароль">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Сохранить</button>
                    </div>
                </form>
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
                    if (response.error == 'password') {
                        $("#<?= $basename ?> [name='password']").addClass("is-invalid");
                        $("#<?= $basename ?> [name='password']").parent().after('<div class="invalid-feedback">В пароле должно быть минимум 5 символов</div>');
                        $("#<?= $basename ?> [name='password']").parent().next(".invalid-feedback").fadeIn();
                    } else if (response.error == 'password2') {
                        $("#<?= $basename ?> [name='password']").addClass("is-invalid");
                        $("#<?= $basename ?> [name='password2']").addClass("is-invalid");
                        $("#<?= $basename ?> [name='password2']").parent().after('<div class="invalid-feedback">Пароли не совпадают</div>');
                        $("#<?= $basename ?> [name='password2']").parent().next(".invalid-feedback").fadeIn();
                    } else if (response.error == 'hcaptcha') {
                        _modal("#hcaptcha");
                    } else if (response.result == 'ok') {
                        $("#<?= $basename ?> .modal-title").html("Отлично!");
                        $("#<?= $basename ?> form").replaceWith("<p>Новый пароль установлен! Автоматическая авторизация через 3 сек...</p>");
                        setTimeout(function() {
                            location.reload();
                            $('body').hide();
                            scrollTo(0, 0);
                        }, 3000);
                    } else {
                        errorToast();
                    }
                }).always(() => {
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