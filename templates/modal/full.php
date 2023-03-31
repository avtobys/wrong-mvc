<?php

/**
 * @file
 * @brief окно в полный экран
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable w-100 mw-100 h-100 p-0 m-0 position-fixed" style="max-width: 100%;max-height:100%;" role="document">
        <div class="modal-content w-100 h-100 rounded-0 border-0">
            <div class="modal-header rounded-0">
                <h5 class="modal-title">Окно на весь экран</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0"></div>
        </div>
    </div>
</div>
