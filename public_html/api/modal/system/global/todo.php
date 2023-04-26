<?php

/**
 * @file
 * @brief окно что дальше
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered w-100 mw-100 h-100 p-0 m-0 position-fixed" role="document">
        <div class="modal-content w-100 h-100 rounded-0 border-0">
            <div class="modal-header rounded-0" style="background: #3b4346 url(/assets/system/img/bg01.jpg);
    border-bottom: solid 1px #272d30;
    box-shadow: inset 0 -1px 0 0 #51575a;
    text-shadow: -1px -1px 1px rgba(0,0,0,.75);color:#fff;">
                <h5 class="modal-title">Что планируется дальше?</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background: #f7f7f7 url(/assets/system/img/bg04.png);">
                <div id="jq-load-1">
                    <script>
                        $("#jq-load-1").load("/docs/md__t_o_d_o.html?" + Math.random() + " .textblock>ul", () => {
                            $("#jq-load-1 ul").addClass("check-list pl-2");
                        });
                    </script>
                </div>
                <div class="alert alert-secondary p-1 px-2"><i class="fa fa-exclamation-triangle text-danger mr-2"></i>Любая <a href="#donate">ваша поддержка развития проекта</a> будет весьма кстати, и ускорит реализацию данного списка! Возможно у Вас есть ещё <a data-toggle="modal" data-target="#comments" href="//wrong-mvc.com/comments">идеи или предложения</a>?</div>
            </div>
        </div>
    </div>
    <script>
        typeof ym === 'function' && ym(92932927,'reachGoal','todo');
    </script>
</div>