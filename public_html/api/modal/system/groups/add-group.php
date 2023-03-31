<?php

/**
 * @file
 * @brief окно добавления новой группы
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-group mr-2"></i>Добавить группу</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= Wrong\Models\Actions::find(19)->request ?>">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Название</span>
                        </div>
                        <input type="text" name="name" class="form-control" value="" placeholder="Название группы" autocomplete="off" required>
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Группа владелец</span>
                        </div>
                        <select name="owner_group" class="custom-select">
                            <?php
                            foreach ($user->subordinate_groups as $id) {
                                $row = Wrong\Rights\Group::row($id);
                                echo '<option value="' . $row->id . '" ' . ($row->id == $user->main_group_id ? ' selected' : '') . '>' . Wrong\Rights\Group::text($row->id) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Системный вес</span>
                        </div>
                        <input type="number" name="weight" class="form-control" value="1" min="0" max="<?= $user->weight_subordinate ?>" placeholder="Системный вес" required>
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Каталог по умолчанию</span>
                        </div>
                        <input type="text" name="path" pattern="^[a-zA-Z0-9]{1,20}$" class="form-control" value="" placeholder="Каталог по умолчанию" autocomplete="off" required>
                    </div>
                    <div class="mt-2">
                        <div title="Во всех моделях где доступ назначен всем/всем авторизованным эта группа будет также добавлена в группы доступа" class="custom-control custom-checkbox small">
                            <input type="checkbox" name="add-groups" class="custom-control-input" id="add-groups" checked>
                            <label class="custom-control-label" for="add-groups">Доступы к моделям с доступами "Все" и "Все авторизованные"</label>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div title="Во всех моделях доступных группе владельцу новая группа будет также добавлена в группы доступа" class="custom-control custom-checkbox small">
                            <input type="checkbox" name="add-groups-owner" class="custom-control-input" id="add-groups-owner">
                            <label class="custom-control-label" for="add-groups-owner">Доступы к моделям доступным группе владельцу</label>
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
    </script>
</div>
