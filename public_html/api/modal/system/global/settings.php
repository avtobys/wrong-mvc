<?php

/**
 * @file
 * @brief окно системных настроек
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

if (!$user->access()->action('/api/action/settings')) { // скрываем некоторые данные для тех у кого недоступно само действие изменения настроек
    foreach (Wrong\Start\Env::$e as $key => $value) {
        if (stripos($key, 'secret') !== false || stripos($key, 'password') !== false) {
            Wrong\Start\Env::$e->$key = '******';
        }
    }
}

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable w-100 mw-100 h-100 p-0 m-0 position-fixed" style="max-width: 100%;max-height:100%;" role="document">
        <div class="modal-content w-100 h-100 rounded-0 border-0">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-cogs mr-2"></i>Настройки системы</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-2">
                <form id="form-system-settings" action="<?= Wrong\Models\Actions::find(25)->request ?>">
                    <div class="row row-cols-1 row-cols-xl-2">
                        <div class="col">
                            <div class="badge badge-warning px-2">API ключи:</div>
                            <div class="input-group input-group-sm mt-1">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100"><?= Wrong\Database\Controller::find('HCAPTCHA_SITEKEY', 'name', 'settings')->description ?></span>
                                </div>
                                <input type="text" name="HCAPTCHA_SITEKEY" class="form-control" value="<?= Wrong\Start\Env::$e->HCAPTCHA_SITEKEY ?>" placeholder="HCAPTCHA_SITEKEY" autocomplete="off">
                            </div>
                            <div class="input-group input-group-sm mt-2">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100"><?= Wrong\Database\Controller::find('HCAPTCHA_SECRET', 'name', 'settings')->description ?></span>
                                </div>
                                <input type="text" name="HCAPTCHA_SECRET" class="form-control" value="<?= Wrong\Start\Env::$e->HCAPTCHA_SECRET ?>" placeholder="HCAPTCHA_SECRET" autocomplete="off">
                            </div>
                            <div class="input-group input-group-sm mt-2">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100"><?= Wrong\Database\Controller::find('GOOGLE_OAUTH_CLIENT_ID', 'name', 'settings')->description ?></span>
                                </div>
                                <input type="text" name="GOOGLE_OAUTH_CLIENT_ID" class="form-control" value="<?= Wrong\Start\Env::$e->GOOGLE_OAUTH_CLIENT_ID ?>" placeholder="GOOGLE_OAUTH_CLIENT_ID" autocomplete="off">
                            </div>
                            <div class="input-group input-group-sm mt-2">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100"><?= Wrong\Database\Controller::find('GOOGLE_OAUTH_CLIENT_SECRET', 'name', 'settings')->description ?></span>
                                </div>
                                <input type="text" name="GOOGLE_OAUTH_CLIENT_SECRET" class="form-control" value="<?= Wrong\Start\Env::$e->GOOGLE_OAUTH_CLIENT_SECRET ?>" placeholder="GOOGLE_OAUTH_CLIENT_SECRET" autocomplete="off">
                            </div>
                            <div class="input-group input-group-sm mt-2">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100"><?= Wrong\Database\Controller::find('YANDEX_OAUTH_CLIENT_ID', 'name', 'settings')->description ?></span>
                                </div>
                                <input type="text" name="YANDEX_OAUTH_CLIENT_ID" class="form-control" value="<?= Wrong\Start\Env::$e->YANDEX_OAUTH_CLIENT_ID ?>" placeholder="YANDEX_OAUTH_CLIENT_ID" autocomplete="off">
                            </div>
                            <div class="input-group input-group-sm mt-2">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100"><?= Wrong\Database\Controller::find('YANDEX_OAUTH_CLIENT_SECRET', 'name', 'settings')->description ?></span>
                                </div>
                                <input type="text" name="YANDEX_OAUTH_CLIENT_SECRET" class="form-control" value="<?= Wrong\Start\Env::$e->YANDEX_OAUTH_CLIENT_SECRET ?>" placeholder="YANDEX_OAUTH_CLIENT_SECRET" autocomplete="off">
                            </div>
                            <div class="input-group input-group-sm mt-2">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100"><?= Wrong\Database\Controller::find('ANYCOMMENT_SECRET', 'name', 'settings')->description ?></span>
                                </div>
                                <input type="text" name="ANYCOMMENT_SECRET" class="form-control" value="<?= Wrong\Start\Env::$e->ANYCOMMENT_SECRET ?>" placeholder="ANYCOMMENT_SECRET" autocomplete="off">
                            </div>
                            <div class="badge badge-warning px-2 mt-2">Новые пользователи:</div>
                            <div class="input-group input-group-sm mt-1">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100"><?= Wrong\Database\Controller::find('OWNER_GROUP_USERS', 'name', 'settings')->description ?></span>
                                </div>
                                <select name="OWNER_GROUP_USERS" class="custom-select">
                                    <?php
                                    foreach ($user->subordinate_groups as $id) {
                                        $row = Wrong\Rights\Group::row($id);
                                        echo '<option value="' . $row->id . '" ' . ($row->id == Wrong\Start\Env::$e->OWNER_GROUP_USERS ? ' selected' : '') . '>' . Wrong\Rights\Group::text($row->id) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="border px-2 py-1 rounded bg-light-info mt-2">
                                <small><?= Wrong\Database\Controller::find('GROUPS_USERS', 'name', 'settings')->description ?> <a onclick="if(~~this.dataset.checked){$(this).html('отметить все');this.dataset.checked=0;$(this).parents('form').find('[name^=GROUPS_USERS]').prop('checked', false);}else{$(this).html('снять все');this.dataset.checked=1;$(this).parents('form').find('[name^=GROUPS_USERS]').prop('checked', true);}return false;" href="#">отметить все</a></small>
                                <?php
                                foreach ($user->subordinate_groups as $id) {
                                    $row = Wrong\Rights\Group::row($id);
                                    echo '<div class="custom-control custom-checkbox small">
                            <input type="checkbox" name="GROUPS_USERS[' . $row->id . ']" class="custom-control-input" id="check-group-' . $row->id . '" ' . (in_array($row->id, Wrong\Start\Env::$e->GROUPS_USERS) ? 'checked' : '') . '>
                            <label class="custom-control-label" for="check-group-' . $row->id . '">' . Wrong\Rights\Group::text($row->id) . '</label>
                            </div>';
                                }
                                ?>
                            </div>
                            <div class="bg-light-info border mt-2 px-2 py-1 rounded">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" name="USER_ACT" class="custom-control-input" id="USER_ACT" <?= Wrong\Database\Controller::find('USER_ACT', 'name', 'settings')->value ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="USER_ACT"><?= Wrong\Database\Controller::find('USER_ACT', 'name', 'settings')->description ?></label>
                                </div>
                            </div>
                            <div class="bg-light-info border mt-2 px-2 py-1 rounded">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" name="USER_API" class="custom-control-input" id="USER_API" <?= Wrong\Database\Controller::find('USER_API', 'name', 'settings')->value ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="USER_API"><?= Wrong\Database\Controller::find('USER_API', 'name', 'settings')->description ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div>
                                <div class="badge badge-warning px-2">Система:</div>
                                <a class="badge badge-danger px-2" title="Очистка всего кеша системы" data-action="cache-clean" data-confirm="true" data-header="Очистить кеш?" data-body="Очистить системный кеш полностью?" data-callback="afterCleanCache" href="#" role="button">Очистить кеш <span id="cache-size"><i class="fa fa-circle-o-notch fa-spin small"></i></span></a>
                            </div>
                            <div class="bg-light-info border mt-1 px-2 py-1 rounded">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" name="RETURN_TO_REQUEST" class="custom-control-input" id="RETURN_TO_REQUEST" <?= Wrong\Database\Controller::find('RETURN_TO_REQUEST', 'name', 'settings')->value ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="RETURN_TO_REQUEST"><?= Wrong\Database\Controller::find('RETURN_TO_REQUEST', 'name', 'settings')->description ?></label>
                                </div>
                            </div>
                            <div class="bg-light-info border mt-2 px-2 py-1 rounded">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" name="HIDE_OUT_LINKS" class="custom-control-input" id="HIDE_OUT_LINKS" <?= Wrong\Database\Controller::find('HIDE_OUT_LINKS', 'name', 'settings')->value ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="HIDE_OUT_LINKS"><?= Wrong\Database\Controller::find('HIDE_OUT_LINKS', 'name', 'settings')->description ?></label>
                                </div>
                            </div>
                            <div class="bg-light-info border mt-2 px-2 py-1 rounded">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" name="HIDE_OUT_ACTIONS_MODALS" class="custom-control-input" id="HIDE_OUT_ACTIONS_MODALS" <?= Wrong\Database\Controller::find('HIDE_OUT_ACTIONS_MODALS', 'name', 'settings')->value ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="HIDE_OUT_ACTIONS_MODALS"><?= Wrong\Database\Controller::find('HIDE_OUT_ACTIONS_MODALS', 'name', 'settings')->description ?></label>
                                </div>
                            </div>
                            <div class="bg-light-info border mt-2 px-2 py-1 rounded">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" name="API" class="custom-control-input" id="API" <?= Wrong\Database\Controller::find('API', 'name', 'settings')->value ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="API"><?= Wrong\Database\Controller::find('API', 'name', 'settings')->description ?></label>
                                </div>
                            </div>
                            <div class="bg-light-info border mt-2 px-2 py-1 rounded">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" name="CRON_ACT" class="custom-control-input" id="CRON_ACT" <?= Wrong\Database\Controller::find('CRON_ACT', 'name', 'settings')->value ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="CRON_ACT"><?= Wrong\Database\Controller::find('CRON_ACT', 'name', 'settings')->description ?></label>
                                </div>
                            </div>
                            <div class="bg-light-info border mt-2 px-2 py-1 rounded">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" name="CRON_CLI" class="custom-control-input" id="CRON_CLI" <?= Wrong\Database\Controller::find('CRON_CLI', 'name', 'settings')->value ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="CRON_CLI"><?= Wrong\Database\Controller::find('CRON_CLI', 'name', 'settings')->description ?></label>
                                </div>
                            </div>
                            <div class="bg-light-info border mt-2 px-2 py-1 rounded">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" name="SUBORDINATE_MODELS" class="custom-control-input" id="SUBORDINATE_MODELS" <?= Wrong\Database\Controller::find('SUBORDINATE_MODELS', 'name', 'settings')->value ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="SUBORDINATE_MODELS"><?= Wrong\Database\Controller::find('SUBORDINATE_MODELS', 'name', 'settings')->description ?></label>
                                </div>
                            </div>
                            <div class="bg-light-info border mt-2 px-2 py-1 rounded">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" name="SYSTEM_CLOSED" class="custom-control-input" id="SYSTEM_CLOSED" <?= Wrong\Database\Controller::find('SYSTEM_CLOSED', 'name', 'settings')->value ? 'checked' : '' ?>>
                                    <label class="custom-control-label text-danger" for="SYSTEM_CLOSED"><?= Wrong\Database\Controller::find('SYSTEM_CLOSED', 'name', 'settings')->description ?></label>
                                </div>
                            </div>
                            <div class="badge badge-warning px-2 mt-2">Отправка почты:</div>
                            <div class="bg-light-info border mt-1 px-2 py-1 rounded">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" name="EMAIL" class="custom-control-input" id="EMAIL" <?= Wrong\Database\Controller::find('EMAIL', 'name', 'settings')->value ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="EMAIL"><?= Wrong\Database\Controller::find('EMAIL', 'name', 'settings')->description ?></label>
                                </div>
                            </div>
                            <div class="input-group input-group-sm mt-2">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100"><?= Wrong\Database\Controller::find('MAIL_USERNAME', 'name', 'settings')->description ?></span>
                                </div>
                                <input type="text" name="MAIL_USERNAME" class="form-control" value="<?= Wrong\Start\Env::$e->MAIL_USERNAME ?>" placeholder="MAIL_USERNAME" autocomplete="off">
                            </div>
                            <div class="bg-light-info border mt-2 px-2 py-1 rounded">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" name="EMAIL_CONFIRMATION" class="custom-control-input" id="EMAIL_CONFIRMATION" <?= Wrong\Database\Controller::find('EMAIL_CONFIRMATION', 'name', 'settings')->value ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="EMAIL_CONFIRMATION"><?= Wrong\Database\Controller::find('EMAIL_CONFIRMATION', 'name', 'settings')->description ?></label>
                                </div>
                            </div>
                            <div class="bg-light-info border mt-2 px-2 py-1 rounded">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" name="SMTP" class="custom-control-input" id="SMTP" <?= Wrong\Database\Controller::find('SMTP', 'name', 'settings')->value ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="SMTP"><?= Wrong\Database\Controller::find('SMTP', 'name', 'settings')->description ?></label>
                                </div>
                            </div>
                            <div class="input-group input-group-sm mt-2">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100"><?= Wrong\Database\Controller::find('SMTP_HOST', 'name', 'settings')->description ?></span>
                                </div>
                                <input type="text" name="SMTP_HOST" class="form-control" value="<?= Wrong\Start\Env::$e->SMTP_HOST ?>" placeholder="SMTP_HOST" autocomplete="off">
                            </div>
                            <div class="input-group input-group-sm mt-2">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100"><?= Wrong\Database\Controller::find('SMTP_PORT', 'name', 'settings')->description ?></span>
                                </div>
                                <input type="number" name="SMTP_PORT" class="form-control" value="<?= Wrong\Start\Env::$e->SMTP_PORT ?>" placeholder="SMTP_PORT" autocomplete="off">
                            </div>
                            <div class="input-group input-group-sm mt-2">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100"><?= Wrong\Database\Controller::find('SMTP_PASSWORD', 'name', 'settings')->description ?></span>
                                </div>
                                <input type="text" name="SMTP_PASSWORD" class="form-control" value="<?= Wrong\Start\Env::$e->SMTP_PASSWORD ?>" placeholder="SMTP_PASSWORD" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <button form="form-system-settings" type="submit" class="btn btn-sm btn-block btn-success">Сохранить</button>
        </div>
    </div>
    <script>
        $("#<?= $basename ?> form").submit(function(e) {
            lockSubmit($("#<?= $basename ?> [type=submit]"));
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
                    unlockSubmit($("#<?= $basename ?> [type=submit]"));
                });
        });

        $("#<?= $basename ?> [name=CRON_ACT]").change(function() {
            if ($(this).is(':checked')) {
                $('#crontabs-alert').fadeOut();
            } else {
                $('#crontabs-alert').fadeIn();
            }
        });

        $("#<?= $basename ?> [name=EMAIL]").change(function() {
            if (!$(this).is(':checked')) {
                $("#<?= $basename ?> [name=EMAIL_CONFIRMATION]").prop("checked", false);
            }
        });

        $("#<?= $basename ?> [name=EMAIL_CONFIRMATION]").change(function() {
            if ($(this).is(':checked')) {
                $("#<?= $basename ?> [name=EMAIL]").prop("checked", true);
            }
        });

        $('#cache-size').load('/api/select/cache-size');

        function afterCleanCache(response) {
            if (response.error) {
                errorToast(response.error);
                return;
            }
            successToast(response.message);
            $('#cache-size').html(response.size);
        }
    </script>
</div>