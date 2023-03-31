<?php

/**
 * @file
 * @brief окно пустое
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-white" style="background:#3B4346 url(/assets/system/img/bg01.jpg);">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-download mr-2"></i>Версия: <?= Wrong\Start\Env::$e->VERSION ?></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-1" style="background: #f7f7f7 url(/assets/system/img/bg04.png);color:#212529;">
                <div>
                    <b>Системные требования:</b>
                    <ul class="check-list small mb-0">
                        <li>MariaDB >= 10.3</li>
                        <li>PHP >= 7.0</li>
                        <li>Composer >= 2.4</li>
                        <li>nodejs >= 16 (если нужна сборка фронтендов)</li>
                    </ul>
                    <code class="d-flex flex-column mt-3">
                        <kbd class="d-flex justify-content-start text-nowrap overflow-auto"><span class="text-muted mr-1">$</span><span class="copy-text">git clone https://github.com/avtobys/wrong-mvc.git</span><a class="text-muted ml-auto" href="#"><i class="fa fa-copy" aria-hidden="true"></i></a></kbd>
                        <kbd class="d-flex justify-content-start text-nowrap overflow-auto mt-1"><span class="text-muted mr-1">$</span><span class="copy-text">cd wrong-mvc/</span><a class="text-muted ml-auto" href="#"><i class="fa fa-copy" aria-hidden="true"></i></a></kbd>
                        <kbd class="d-flex justify-content-start text-nowrap overflow-auto mt-1"><span class="text-muted mr-1">$</span><span class="copy-text">composer install</span><a class="text-muted ml-auto" href="#"><i class="fa fa-copy" aria-hidden="true"></i></a></kbd>
                        <kbd class="d-flex justify-content-start text-nowrap overflow-auto mt-1"><span class="text-muted mr-1">$</span><span class="copy-text">npm install</span><a class="text-muted ml-auto" href="#"><i class="fa fa-copy" aria-hidden="true"></i></a></kbd>
                    </code>
                    <div class="alert alert-secondary small p-1 px-2 mb-0 mt-3" role="alert">
                        <i class="fa fa-info-circle text-muted mr-2"></i>Подробности установки в <a target="_blank" href="/docs/">вашей структуре каталогов</a>
                    </div>
                    <div class="alert alert-secondary small p-1 px-2 mb-0 mt-1" role="alert">
                        <i class="fa-solid fa-eye text-muted mr-2"></i>Посмотреть как работает <a data-toggle="modal" data-target="#sign-up" data-dismiss="modal" data-callback="demoStart" href="#">демо версия</a>
                    </div>
                    <div class="alert alert-secondary small p-1 px-2 mb-0 mt-1" role="alert">
                        <i class="fa fa-question-circle text-muted mr-2"></i>Идеи, вопросы и пожелания оставляйте в <a data-toggle="modal" data-target="#comments" href="/comments">чате техподдержки</a>
                    </div>
                    <div class="alert alert-secondary small p-1 px-2 mb-0 mt-1" role="alert">
                        <i class="fa fa-coffee text-muted mr-2"></i>Ваш вклад в <a href="#donate">развитие проекта</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#<?= $basename ?> code kbd a').click(function(e) {
            e.preventDefault();
            $(this).prev().click();
        });
        typeof ym === 'function' && ym(92932927,'reachGoal','install');
    </script>
</div>