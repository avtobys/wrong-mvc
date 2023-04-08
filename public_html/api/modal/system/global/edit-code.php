<?php

/**
 * @file
 * @brief окно редактирования кода моделей
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

if (!($row = Wrong\Database\Controller::find($_GET['id'], 'id', $_GET['table']))) {
    exit('<script>errorToast("Ошибка!");</script>');
}


?>
<div class="modal fade p-0" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable w-100 mw-100 h-100 p-0 m-0" style="max-width: 100%;max-height:100%;" role="document">
        <div class="modal-content w-100 h-100 rounded-0 border-0">
            <div class="bg-secondary modal-header py-0 text-white rounded-0 border-0 pl-1 pr-0 align-items-center">
                <h6 class="modal-title"><?= $row->file ?><small style="top:-3px;left:5px;position:relative;"><span class="badge badge-light badge-pill slide-in-elliptic-left-fwd" style="padding:3px 6px 2px 6px;font-weight:300;">Изменён: <span id="last-modified"><?= date('Y-m-d H:i:s', filemtime($_SERVER['DOCUMENT_ROOT'] . $row->file)) ?></span></span></small></h6>
                <div class="btn-group h-100">
                    <a id="edit-code-help" class="btn btn-info px-3 py-0 rounded-0 d-flex align-items-center" href="#" data-trigger="focus" tabindex="0" data-placement="bottom" data-html="true" data-title="<i class='fa fa-keyboard-o mr-2'></i>Сочетания клавиш" data-content="<div class='m-0 mt-2 w-100 text-nowrap'>
                        <div class='m-0 small'>
                            <div class='d-flex justify-content-between pb-1'><div class='pr-5'>Сохранить:</div><kbd>Ctrl + S</kbd></div>
                            <div class='d-flex justify-content-between py-1 border-top'><div class='pr-5'>Автодополнения:</div><kbd>Ctrl + Space</kbd></div>
                            <div class='d-flex justify-content-between py-1 border-top'><div class='pr-5'>Развернуть:</div><kbd>F11</kbd></div>
                            <div class='d-flex justify-content-between py-1 border-top'><div class='pr-5'>Свернуть:</div><kbd>Esc</kbd></div>
                            <div class='d-flex justify-content-between py-1 border-top'><div class='pr-5'>К закрывающему тегу:</div><kbd>Ctrl + J</kbd></div>
                            <div class='d-flex justify-content-between py-1 border-top'><div class='pr-5'>Поиск:</div><kbd>Alt + F</kbd></div>
                            <div class='d-flex justify-content-between py-1 border-top'><div class='pr-5'>Следующий результат:</div><kbd>Enter</kbd></div>
                            <div class='d-flex justify-content-between py-1 border-top'><div class='pr-5'>Заменить:</div><kbd>Shift + Ctrl + F</kbd></div>
                            <div class='d-flex justify-content-between py-1 border-top'><div class='pr-5'>Заменить всё:</div><kbd>Shift + Ctrl + R</kbd></div>
                            <div class='d-flex justify-content-between py-1 border-top'><div class='pr-5'>Закомментировать:</div><kbd>Ctrl + /</kbd></div>
                        </div>
                    </div>"><i class="fa fa-question-circle"></i></a>
                    <a class="btn btn-primary px-3 py-0 rounded-0 d-flex align-items-center" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>

            </div>
            <div class="modal-body p-0" style="background:#0f0f0f;">
                <form id="edit-code-form" action="<?= Wrong\Models\Actions::find(43)->request ?>" class="h-100">
                    <input type="hidden" name="id" value="<?= $row->id ?>">
                    <input type="hidden" name="table" value="<?= $_GET['table'] ?>">
                    <textarea id="code" name="code" style="width:100%;display:none;"><?= htmlentities(file_get_contents($_SERVER['DOCUMENT_ROOT'] . $row->file)) ?></textarea>
                </form>
            </div>
            <div class="border-0 m-0 p-0 rounded-0" <?= !$user->access()->action('/api/action/edit-code') ? 'hidden' : '' ?>>
                <button style="display:none;" form="edit-code-form" type="submit" class="btn btn-sm btn-block btn-success rounded-0 border-0">Сохранить и закрыть</button>
            </div>
        </div>
    </div>
    <script>
        $('#edit-code-help').click(function(e) {
            e.preventDefault();
            if ($(this).is('.active')) {
                $(this).popover('hide');
            } else {
                $(this).popover('show');
            }
        });
        $('#edit-code-help').on('hidden.bs.popover', function(e) {
            $('#edit-code-help').removeClass('active btn-success').addClass('btn-info');
        });
        $('#edit-code-help').on('shown.bs.popover', function() {
            $('#edit-code-help').addClass('active btn-success').removeClass('btn-info');
        });
        $('#<?= $basename ?>').on('shown.bs.modal', () => {
            loading();
            loadLibs(<?= Wrong\Html\Get::pathArrayJSON(['/assets/system/css/codemirror.min.css']) ?>, <?= Wrong\Html\Get::pathArrayJSON(['/assets/system/js/codemirror.min.js']) ?>, 'CodeMirror')
                .then(() => {
                    loading();
                    setTimeout(() => {
                        var editor = CodeMirror.fromTextArea($('#<?= $basename ?> #code')[0], {
                            lineNumbers: true,
                            matchBrackets: true,
                            styleActiveLine: true,
                            extraKeys: {
                                "Ctrl-Space": "autocomplete",
                                "F11": function(cm) {
                                    cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                                },
                                "Esc": function(cm) {
                                    if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                                },
                                "Ctrl-J": "toMatchingTag",
                                "Alt-F": "findPersistent"
                            },
                            mode: "application/x-httpd-php",
                            indentUnit: 4,
                            indentWithTabs: true,
                            autoCloseBrackets: true,
                            autoCloseTags: true,
                            foldGutter: true,
                            gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter", "breakpoints"],
                            highlightSelectionMatches: {
                                showToken: /\w/,
                                annotateScrollbar: true
                            },
                            matchTags: {
                                bothTags: true
                            },
                            keyMap: "sublime",
                            tabSize: 4

                        });
                        editor.setOption("theme", 'abcdef');
                        editor.on("gutterClick", function(cm, n) {
                            var info = cm.lineInfo(n);
                            cm.setGutterMarker(n, "breakpoints", info.gutterMarkers ? null : makeMarker());
                        });

                        function makeMarker() {
                            var marker = document.createElement("div");
                            marker.style.color = "#822";
                            marker.style.marginLeft = "-8px";
                            marker.innerHTML = "●";
                            return marker;
                        }

                        $('#<?= $basename ?> [type=submit]').slideDown();

                        $("#<?= $basename ?> form").submit(function(e) {
                            let origin = e.originalEvent;
                            editor.save();
                            lockSubmit($("#<?= $basename ?> button[form=edit-code-form]"));
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
                                    if (response.modified) {
                                        $('#last-modified').html(response.modified);
                                        $('#last-modified').parent().removeClass('slide-in-elliptic-left-fwd');
                                        $('#last-modified').parent().addClass('blink-1');
                                        setTimeout(() => {
                                            $('#last-modified').parent().removeClass('blink-1');
                                        }, 3000);
                                    }
                                    successToast(response.message, 2000);
                                    if (origin) {
                                        $("#<?= $basename ?>").modal("hide");
                                    }
                                })
                                .always(() => {
                                    unlockSubmit($("#<?= $basename ?> button[form=edit-code-form]"));
                                });
                        });
                        loading('hide');
                    }, 500);

                });
        });
    </script>
</div>