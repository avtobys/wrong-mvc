<?php

/**
 * @file
 * @brief окно для показа ошибки
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

$error = !empty($error) ? $error : (!empty($_GET['error']) ? htmlspecialchars($_GET['error'], ENT_QUOTES) : '');

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false" style="background: rgba(0, 0, 0, 0.7);font-size:14px;">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content bg-danger text-light">
            <div class="modal-header border-0 position-absolute w-100 p-1 pr-2" style="z-index:1;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center position-relative w-100 px-4"><?= (!empty($error) ? $error : '') ?></div>
        </div>
    </div>
</div>