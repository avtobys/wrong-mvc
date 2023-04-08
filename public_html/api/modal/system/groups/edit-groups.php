<?php

/**
 * @file
 * @brief окно установки групп доступа для модели
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

if (!($row = Wrong\Database\Controller::find($_GET['id'], 'id', $_GET['table']))) {
    exit('<script>errorToast("Ошибка!");</script>');
}

$groups = json_decode($row->groups, true);
$owner_group = $row->owner_group;

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Группы доступа</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-2">
                <form action="<?= Wrong\Models\Actions::find(10)->request ?>">
                    <input type="hidden" name="id" value="<?= $row->id ?>">
                    <input type="hidden" name="table" value="<?= $_GET['table'] ?>">
                    <div class="border px-2 py-1 rounded bg-light-info">
                        <small><a onclick="if(~~this.dataset.checked){$(this).html('Отметить все');this.dataset.checked=0;$(this).parents('form').find('[name^=groups]').prop('checked', false);}else{$(this).html('Снять все');this.dataset.checked=1;$(this).parents('form').find('[name^=groups]').prop('checked', true);}return false;" href="#">Отметить все</a></small>
                        <?php
                        foreach (Wrong\Rights\Group::$groups_not_system as $row) {
                            if ($_GET['table'] == 'templates' && $row->id == 0) continue;
                            if ($_GET['table'] == 'users' && !in_array($row->id, $user->subordinate_groups)) continue;
                            echo '<div class="custom-control custom-checkbox small">
                            <input type="checkbox" name="groups[' . $row->id . ']" class="custom-control-input" id="check-group-' . $row->id . '" ' . (in_array($row->id, $groups) ? 'checked' : '') . '>
                            <label class="custom-control-label" for="check-group-' . $row->id . '">' . Wrong\Rights\Group::text($row->id) . '</label>
                            </div>';
                        }
                        ?>
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
        <?php if ($owner_group == 1) : ?>
            $('.toast').toast('hide');
            dangerToast('Владелец Система. Изменение групп доступа для системного функционала может привести к нежелательным последствиям!', 15000);
        <?php endif; ?>
    </script>
</div>