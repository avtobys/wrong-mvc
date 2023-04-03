<?php

/**
 * @file
 * @brief окно добавления нового шаблона
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-columns mr-2"></i>Добавить шаблон</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= Wrong\Models\Actions::find(18)->request ?>">
                    <div class="border px-2 py-1 rounded bg-light-info">
                        <small>Группы доступа <a onclick="if(~~this.dataset.checked){$(this).html('отметить все');this.dataset.checked=0;$(this).parents('form').find('[name^=groups]').prop('checked', false);}else{$(this).html('снять все');this.dataset.checked=1;$(this).parents('form').find('[name^=groups]').prop('checked', true);}return false;" href="#">отметить все</a></small>
                        <?php
                        foreach (Wrong\Rights\Group::$groups_not_system as $row) {
                            if ($row->id == 0) continue;
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
                            <span class="input-group-text w-100">Название</span>
                        </div>
                        <input type="text" name="name" class="form-control" value="" autocomplete="off" placeholder="Название">
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Тип шаблона</span>
                        </div>
                        <select name="type" class="custom-select">
                            <option value="page">Страница</option>
                            <option value="modal">Модальное окно</option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Файл шаблона</span>
                        </div>
                        <input title="Доступные каталоги: modal, page" type="text" name="file" class="form-control" value="/../templates/page/template.php" placeholder="/../templates/page/template.php" autocomplete="off" required>
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

        $(function() {
            function setPath() {
                let path = $('#<?= $basename ?> [name="type"]').val();
                let file = $("#<?= $basename ?> form [name=file]").val();
                file = file.replace(/^(\/\.\.\/templates\/)[^/]+/, '$1' + path);
                $("#<?= $basename ?> form [name=file]").val(file);
            }
            $('#<?= $basename ?> [name="type"]').on('change', setPath);
            $('#<?= $basename ?> [name="type"]').trigger('change');
        });
    </script>
</div>