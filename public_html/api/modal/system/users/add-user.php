<?php

/**
 * @file
 * @brief окно добавления нового пользователя
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-user mr-2"></i>Добавить пользователя</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-2">
                <form action="<?= Wrong\Models\Actions::find(20)->request ?>">
                    <div class="border px-2 py-1 rounded bg-light-info">
                        <small>Группы доступа <a onclick="if(~~this.dataset.checked){$(this).html('отметить все');this.dataset.checked=0;$(this).parents('form').find('[name^=groups]').prop('checked', false);}else{$(this).html('снять все');this.dataset.checked=1;$(this).parents('form').find('[name^=groups]').prop('checked', true);}return false;" href="#">отметить все</a></small>
                        <?php
                        foreach ($user->subordinate_groups as $id) {
                            $row = Wrong\Rights\Group::row($id);
                            echo '<div class="custom-control custom-checkbox small">
                            <input type="checkbox" name="groups[' . $row->id . ']" class="custom-control-input" id="check-group-' . $row->id . '" ' . (in_array($row->id, Wrong\Start\Env::$e->GROUPS_USERS) ? 'checked' : '') . '>
                            <label class="custom-control-label" for="check-group-' . $row->id . '">' . Wrong\Rights\Group::text($row->id) . '</label>
                            </div>';
                        }
                        ?>
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Группа владелец</span>
                        </div>
                        <select name="owner_group" class="custom-select">
                            <?php
                            foreach ($user->subordinate_groups as $id) {
                                $row = Wrong\Rights\Group::row($id);
                                echo '<option value="' . $row->id . '" ' . ($row->id == Wrong\Start\Env::$e->OWNER_GROUP_USERS ? ' selected' : '') . '>' . Wrong\Rights\Group::text($row->id) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Email</span>
                        </div>
                        <input type="email" name="email" class="form-control" value="" placeholder="Email" autocomplete="off" required>
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Пароль</span>
                        </div>
                        <input type="text" name="password" class="form-control" value="" placeholder="Пароль" autocomplete="off" required>
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

        $('#<?= $basename ?> [name="password"]').val(Math.random().toString(36).slice(2, 10));

        $('#<?= $basename ?> [name="password"]').click(function(e) {
            $(this).select();
            document.execCommand('copy', false);
        });

        $('#<?= $basename ?> [name="password"]').on('copy', function(e) {
            successToast('Пароль скопирован');
        });

        $('#<?= $basename ?> [name="email"]').val(Math.random().toString(36).slice(2, 10) + '@wrong-mvc.com');
    </script>
</div>