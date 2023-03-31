<?php

/**
 * @file
 * @brief окно контсруктора триггера вызова модальных окон
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';
$name = htmlspecialchars($_GET['name'], ENT_QUOTES);

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-cubes"></i> Конструктор триггера модального окна</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return false;">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Тип тега</span>
                        </div>
                        <select name="tag" class="custom-select">
                            <option value="a">&lt;a&gt;</option>
                            <option value="button">&lt;button&gt;</option>
                            <option value="div">&lt;div&gt;</option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Анкор</span>
                        </div>
                        <input type="text" name="anchor" class="form-control" value="Кнопка" placeholder="Анкор" autocomplete="off">
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Целевое окно</span>
                        </div>
                        <input type="text" name="name" class="form-control" value="#<?= $name ?>" placeholder="Имя действия" autocomplete="off" readonly>
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100">Callback функция</span>
                        </div>
                        <input type="text" name="callback" class="form-control" value="" placeholder="" autocomplete="off">
                    </div>
                    <ul class="rounded bg-light-info border small mt-2 p-2 pl-4 mb-0">
                        <li>Можно добавить любые data-* аттрибуты, они все будут переданы в $_GET[*] переменных на скрипт обработчик</li>
                        <li>Если задана callback функция - она будет вызвана после добавления модального окна в DOM</li>
                    </ul>
                    <div class="position-relative">
                        <textarea name="result-button" class="mt-2 form-control p-1" rows="1" style="font-size:14px;"></textarea>
                        <a style="position:absolute;top:0;right:4px;" title="Копировать код" href="#"><i class="fa fa-copy" aria-hidden="true"></i></a>
                    </div>
                    <div class="position-relative" style="display:none;">
                        <textarea name="result-function" class="mt-2 form-control p-1" rows="7" style="font-size:14px;"></textarea>
                        <a style="position:absolute;top:0;right:4px;" title="Копировать код" href="#"><i class="fa fa-copy" aria-hidden="true"></i></a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script>
        $(function() {
            function construct() {
                let action = $('#<?= $basename ?> [name=name]').val().trim();
                let code = $('<a class="btn btn-primary" data-toggle="modal" data-target="' + action + '"></a>');
                let anchor = $('#<?= $basename ?> [name=anchor]').val().trim();
                code.html(anchor);
                let callback = $('#<?= $basename ?> [name=callback]').val().trim();
                $('#<?= $basename ?> [name=result-function]').val('');
                if (callback) {
                    code.attr('data-callback', callback);
                    $('#<?= $basename ?> [name=result-function]').val('function ' + callback + '() {\n    $("#<?= $name ?> .modal-header").html("Replaced header");\n}');
                    $('#<?= $basename ?> [name=result-function]').parent().slideDown();
                } else {
                    $('#<?= $basename ?> [name=result-function]').parent().slideUp();
                }

                code = code[0].outerHTML;
                let tag = $('#<?= $basename ?> [name=tag]').val();
                code = code.replace(/^<[a-z]+/, '<' + tag);
                code = code.replace(/[a-z]+>$/, tag + '>');

                $('#<?= $basename ?> [name=result-button]').val(code);
                $('#<?= $basename ?> [name=result-button]').css({
                    'height': 'auto'
                });
                $('#<?= $basename ?> [name=result-button]').css({
                    'height': ($('#<?= $basename ?> [name=result-button]')[0].scrollHeight + 3) + 'px'
                });

            }

            $('#<?= $basename ?> form').on('input keyup', construct);
            setTimeout(() => {
                $('#<?= $basename ?> form').trigger('keyup');
            }, 300);

            $('#<?= $basename ?> textarea').next().click(function(e) {
                e.preventDefault();
                $(this).prev().focus();
                $(this).prev().select();
                document.execCommand('copy', false);
                successToast('Скопировано в буфер');
                setTimeout(() => {
                    window.getSelection().removeAllRanges();
                }, 500);
            });
        });
    </script>
</div>