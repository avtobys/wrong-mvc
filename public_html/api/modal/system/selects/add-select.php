<?php

/**
 * @file
 * @brief окно добавления новой выборки
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-database mr-2"></i>Добавить выборку</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-2">
                <form action="<?= Wrong\Models\Actions::find(14)->request ?>">
                    <div class="border px-2 py-1 rounded bg-light-info">
                        <small>Группы доступа <a onclick="if(~~this.dataset.checked){$(this).html('отметить все');this.dataset.checked=0;$(this).parents('form').find('[name^=groups]').prop('checked', false);}else{$(this).html('снять все');this.dataset.checked=1;$(this).parents('form').find('[name^=groups]').prop('checked', true);}return false;" href="#">отметить все</a></small>
                        <?php
                        foreach (Wrong\Rights\Group::$groups_not_system as $row) {
                            echo '<div class="custom-control custom-checkbox small">
                            <input type="checkbox" name="groups[' . $row->id . ']" class="custom-control-input" id="check-group-' . $row->id . '">
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
                                echo '<option data-path="' . $row->path . '" value="' . $row->id . '" ' . ($row->id == $user->main_group_id ? ' selected' : '') . '>' . Wrong\Rights\Group::text($row->id) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Запрос</span>
                        </div>
                        <input type="text" name="request" class="form-control" value="/api/select/request" placeholder="/api/select/request" autocomplete="off" required>
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Файл обработчик</span>
                        </div>
                        <input title="Доступные каталоги: <?= implode(', ', $user->writeble_paths) ?>" type="text" name="file" class="form-control" value="/api/select/<?= Wrong\Rights\Group::row($user->main_group_id)->path ?>/request.php" placeholder="/api/select/<?= Wrong\Rights\Group::row($user->main_group_id)->path ?>/request.php" autocomplete="off" required>
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

        $("#<?= $basename ?> form [name=request]").keyup(function() {
            let filename = ($(this).val().trim().match(/[^\/]+$/)?. [0] || '') + '.php';
            let replace = $("#<?= $basename ?> form [name=file]").val().trim().replace(/[^\/]+$/, filename);
            $("#<?= $basename ?> form [name=file]").val(replace);
        });

        $(function() {
            function setPath() {
                let path = $('#<?= $basename ?> [name="owner_group"] option:selected').data('path');
                let file = $("#<?= $basename ?> form [name=file]").val();
                file = file.replace(/^(\/api\/[^/]+\/)[^/]+/, '$1' + path);
                $("#<?= $basename ?> form [name=file]").val(file);
            }
            $('#<?= $basename ?> [name="owner_group"]').on('change', setPath);
            $('#<?= $basename ?> [name="owner_group"]').trigger('change');
        });
    </script>
</div>
