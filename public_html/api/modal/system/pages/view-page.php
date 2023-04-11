<?php

/**
 * @file
 * @brief окно с просмотром страницы в полный экран во фрейме
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable w-100 mw-100 h-100 p-0 m-0 position-fixed" style="max-width: 100%;max-height:100%;" role="document">
        <div class="modal-content w-100 h-100 rounded-0 border-0">
            <div id="drag-menu" class="position-absolute d-flex flex-column" style="height:150px;justify-content:space-between;z-index:11111111;left:15px;top:20px;">
                <a class="btn btn-primary btn-sm" title="Закрыть" data-dismiss="modal" href=""><i class="fa fa-times-circle"></i></a>
                <a class="btn btn-primary btn-sm" title="Открыть вне фрейма" target="_blank" href="<?= htmlspecialchars($_GET['uri']) ?>"><i class="fa fa-external-link"></i></a>
                <a data-toggle="modal" data-target="#view-page-mobile" class="btn btn-primary btn-sm" title="В мобильном" href="#"><i class="fa fa-mobile"></i></a>
                <span id="drag-menu-button" class="btn btn-primary btn-sm" title="Переместить меню" style="cursor:move;user-select:none;"><i class="fa fa-arrows"></i></span>
            </div>
            <div class="modal-body p-0">
                <iframe src="<?= htmlspecialchars($_GET['uri']) ?>" frameborder="0" class="w-100"></iframe>
            </div>
        </div>
    </div>
    <script>
        $('#<?= $basename ?> iframe').on('load', function() {
            $(this).css({
                height: (this.contentDocument.documentElement.scrollHeight + 2) + 'px'
            });
        });

        $(function() {
            // The current position of mouse
            let x = 0;
            let y = 0;

            // Query the element
            const ele = document.getElementById('drag-menu');
            const drag = document.getElementById('drag-menu-button');

            // Handle the mousedown event
            // that's triggered when user drags the element
            const mouseDownHandler = function(e) {
                // Get the current mouse position
                x = e.clientX;
                y = e.clientY;

                // Attach the listeners to `document`
                document.addEventListener('mousemove', mouseMoveHandler);
                document.addEventListener('mouseup', mouseUpHandler);
            };

            const mouseMoveHandler = function(e) {
                // How far the mouse has been moved
                const dx = e.clientX - x;
                const dy = e.clientY - y;

                // Set the position of element
                ele.style.top = `${ele.offsetTop + dy}px`;
                ele.style.left = `${ele.offsetLeft + dx}px`;

                // Reassign the position of mouse
                x = e.clientX;
                y = e.clientY;
            };

            const mouseUpHandler = function() {
                // Remove the handlers of `mousemove` and `mouseup`
                document.removeEventListener('mousemove', mouseMoveHandler);
                document.removeEventListener('mouseup', mouseUpHandler);
            };

            drag.addEventListener('mousedown', mouseDownHandler);
        });
    </script>
</div>