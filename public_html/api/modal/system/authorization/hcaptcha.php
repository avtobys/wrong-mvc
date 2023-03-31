<?php

/**
 * @file
 * @brief окно с капчей
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>
<div class="modal fade bg-light" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <a data-dismiss="modal" class="position-absolute close" style="right:20px;top:10px;" href="#">&times;</a>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content d-flex align-items-center justify-content-center border-0 shadow-none bg-light">
            <div id="hcaptcha-render"></div>
        </div>
    </div>
    <script>
        ! function() {
            window.hcaptchaRender = function() {
                hcaptcha.render('hcaptcha-render', {
                    sitekey: '<?= Wrong\Start\Env::$e->HCAPTCHA_SITEKEY ?>',
                    'callback': verifyCallback
                });
            }

            if ($("script[src*=hcaptcha]").length) {
                hcaptchaRender();
            } else {
                var s = document.createElement("script");
                s.src = "//js.hcaptcha.com/1/api.js?hl=ru&onload=hcaptchaRender&render=explicit";
                document.head.appendChild(s);
            }
        }();
    </script>
</div>
