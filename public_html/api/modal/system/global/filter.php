<?php

/**
 * @file
 * @brief окно фильтров моделей
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

if (!in_array($_GET['table'], Wrong\Database\Controller::$tables)) {
    exit('<script>errorToast("Ошибка!");</script>');
}

$initial = [
    'act' => ['0', '1'],
    'owner_group' => array_map('strval', array_column(Wrong\Rights\Group::$groups_owners, 'id')),
    'groups' => array_map('strval', array_column(Wrong\Rights\Group::$groups_not_system, 'id'))
];

if ($_GET['table'] == 'templates') {
    $template_types = ['page', 'incode', 'modal', 'select', 'action'];
    $initial['type'] = array_map('strval', array_keys($template_types));
}
// ksort($initial);

$filter = isset($_SESSION['filter'][$_GET['table']]) ? $_SESSION['filter'][$_GET['table']] : $initial;

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-filter mr-2"></i>Фильтр "<?= $dbh->query("SHOW TABLE STATUS WHERE Name = '{$_GET['table']}'")->fetch()->Comment ?>"</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-2">
                <form action="<?= Wrong\Models\Actions::find(41)->request ?>">
                    <input type="hidden" name="table" value="<?= $_GET['table'] ?>">
                    <div class="border px-2 py-1 rounded bg-light-info">
                        <div class="custom-control custom-checkbox small">
                            <input type="checkbox" name="act[0]" value="0" class="custom-control-input" id="check-group-0">
                            <label class="custom-control-label" for="check-group-0">Не активные</label>
                        </div>
                        <div class="custom-control custom-checkbox small">
                            <input type="checkbox" name="act[1]" value="1" class="custom-control-input" id="check-group-1">
                            <label class="custom-control-label" for="check-group-1">Активные</label>
                        </div>
                    </div>
                    <?php if ($_GET['table'] == 'templates') : ?>
                        <div class="border px-2 py-1 rounded bg-light-info masscheck mt-2">
                            <small>Типы шаблонов <a onclick="if(~~this.dataset.checked){$(this).html('отметить все');this.dataset.checked=0;$(this).parents('form').find('[name^=type]').prop('checked', false);}else{$(this).html('снять все');this.dataset.checked=1;$(this).parents('form').find('[name^=type]').prop('checked', true);}$('#<?= $basename ?> form').trigger('change');return false;" href="#"></a></small>
                            <?php
                            foreach ($initial['type'] as $key) {
                                echo '<div class="custom-control custom-checkbox small">
                                <input type="checkbox" name="type[' . $key . ']" value="' . $key . '" class="custom-control-input" id="check-type-' . $key . '">
                                <label class="custom-control-label" for="check-type-' . $key . '">' . $template_types[$key] . '</label>
                                </div>';
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                    <div class="border px-2 py-1 rounded bg-light-info masscheck mt-2">
                        <small>Группа владелец <a onclick="if(~~this.dataset.checked){$(this).html('отметить все');this.dataset.checked=0;$(this).parents('form').find('[name^=owner_group]').prop('checked', false);}else{$(this).html('снять все');this.dataset.checked=1;$(this).parents('form').find('[name^=owner_group]').prop('checked', true);}$('#<?= $basename ?> form').trigger('change');return false;" href="#"></a></small>
                        <?php
                        foreach (Wrong\Rights\Group::$groups_owners as $row) {
                            echo '<div class="custom-control custom-checkbox small">
                            <input type="checkbox" name="owner_group[' . $row->id . ']" value="' . $row->id . '" class="custom-control-input" id="check-owner_group-' . $row->id . '">
                            <label class="custom-control-label" for="check-owner_group-' . $row->id . '">' . Wrong\Rights\Group::text($row->id) . '</label>
                            </div>';
                        }
                        ?>
                    </div>
                    <div class="border px-2 py-1 rounded bg-light-info masscheck mt-2 <?= in_array($_GET['table'], ['crontabs', 'groups']) ? 'd-none' : '' ?>">
                        <small>Группы доступа <a onclick="if(~~this.dataset.checked){$(this).html('отметить все');this.dataset.checked=0;$(this).parents('form').find('[name^=groups]').prop('checked', false);}else{$(this).html('снять все');this.dataset.checked=1;$(this).parents('form').find('[name^=groups]').prop('checked', true);}$('#<?= $basename ?> form').trigger('change');return false;" href="#"></a></small>
                        <?php
                        foreach (Wrong\Rights\Group::$groups_not_system as $row) {
                            echo '<div class="custom-control custom-checkbox small">
                            <input type="checkbox" name="groups[' . $row->id . ']" value="' . $row->id . '" class="custom-control-input" id="check-groups-' . $row->id . '">
                            <label class="custom-control-label" for="check-groups-' . $row->id . '">' . Wrong\Rights\Group::text($row->id) . '</label>
                            </div>';
                        }
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            let filter = <?= json_encode($filter) ?>;
            for (let key in filter) {
                filter[key].forEach(el => {
                    $('#<?= $basename ?> [name="' + key + '[' + el + ']"]').prop('checked', true);
                });
            }

            $("#<?= $basename ?> .masscheck").each(function() {
                if ($(this).find('input[type=checkbox]').not(':checked').length) {
                    $(this).find('a').html('отметить все');
                    $(this).find('a')[0].dataset.checked = 0;
                } else {
                    $(this).find('a').html('снять все');
                    $(this).find('a')[0].dataset.checked = 1;
                }
            });
        });

        $("#<?= $basename ?> form").change(function() {
            $(this).trigger('submit');
        });

        function isEqual(object1, object2) {
            const props1 = Object.getOwnPropertyNames(object1);
            const props2 = Object.getOwnPropertyNames(object2);

            if (props1.length !== props2.length) {
                return false;
            }

            for (let i = 0; i < props1.length; i += 1) {
                const prop = props1[i];
                const bothAreObjects = typeof(object1[prop]) === 'object' && typeof(object2[prop]) === 'object';

                if ((!bothAreObjects && (object1[prop] !== object2[prop])) ||
                    (bothAreObjects && !isEqual(object1[prop], object2[prop]))) {
                    return false;
                }
            }

            return true;
        }

        $("#<?= $basename ?> form").submit(function(e) {
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
                    if (!isEqual(response.filter, <?= json_encode($initial) ?>)) {
                        $('#reset-filter').show();
                        $('.toast').toast('hide');
                        successToast(response.message);
                    } else {
                        $('#reset-filter').trigger('click');
                        $('#reset-filter').hide();
                    }
                    $('.dataTable').DataTable().ajax.reload(null, false);
                });
        });
    </script>
</div>