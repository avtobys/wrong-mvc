<?php

/**
 * @file
 * @brief окно
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>
<div class="modal fade bg-white" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false" data-noremove="true" style="z-index:1055;">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="border-0 modal-content bg-white">
            <div class="modal-header border-0 position-absolute w-100" style="z-index:777;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center p-4">
                <script>
                    ! function(e) {
                        var l = function(l) {
                                return e.cookie.match(new RegExp("(?:^|; )digiseller-" + l + "=([^;]*)"))
                            },
                            i = l("lang"),
                            s = l("cart_uid"),
                            t = i ? "?lang=" + i[1] : "",
                            d = s ? "&cart_uid=" + s[1] : "",
                            r = e.getElementsByTagName("head")[0] || e.documentElement,
                            n = e.createElement("link"),
                            a = e.createElement("script");
                        n.type = "text/css", n.rel = "stylesheet", n.id = "digiseller-css", n.href = "//shop.digiseller.ru/xml/store2_css.asp?seller_id=341980", a.async = !0, a.id = "digiseller-js", a.src = "//www.digiseller.ru/store2/digiseller-api.js.asp?seller_id=341980" + t + d, !e.getElementById(n.id) && r.appendChild(n), !e.getElementById(a.id) && r.appendChild(a)
                    }(document);
                </script>
                <div class="border-0 digiseller-buy-standalone shadow-none" data-id="3685327" data-owner="0" data-lang="" data-img="1" data-img-size="180" data-name="1" data-price="1" data-no-price="0">
                    <div class="h-100 w-100 d-flex justify-content-center align-items-center flex-column my-5"><svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="128px" height="128px" viewBox="0 0 128 128" xml:space="preserve">
                            <g>
                                <path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#a4a4a4" />
                                <path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(45 64 64)" />
                                <path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(90 64 64)" />
                                <path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(135 64 64)" />
                                <path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(180 64 64)" />
                                <path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(225 64 64)" />
                                <path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(270 64 64)" />
                                <path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(315 64 64)" />
                                <animateTransform attributeName="transform" type="rotate" values="0 64 64;45 64 64;90 64 64;135 64 64;180 64 64;225 64 64;270 64 64;315 64 64" calcMode="discrete" dur="1040ms" repeatCount="indefinite"></animateTransform>
                            </g>
                        </svg></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('[data-dismiss="modal"]').attr('onclick', '$(this).parents(".modal").modal("hide");'); // хак, digiseller что то порет с эвентами
        $('.aos-init').removeClass('aos-init');
        $('[data-aos]').removeAttr('data-aos');
        typeof ym === 'function' && ym(92932927, 'reachGoal', 'donate');
        new Promise((resolve, reject) => {
            let id = setInterval(() => {
                if ($('#donates .digiseller-button').length) {
                    clearInterval(id);
                    resolve($('#donates .digiseller-button'));
                }
            }, 50);
        }).then((button) => {
            button.text('Поддержать!');
            $('.digiseller-calc-0-product_image').css({
                "height": "180px"
            });
        });
    </script>
</div>