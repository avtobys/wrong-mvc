<?php

/**
 * @file
 * @brief окно с просмотром страницы в мобильном фрейме
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>
<div id="view-page-mobile" class="modal fade" data-backdrop="static" data-keyboard="false" style="background:rgba(255, 255, 255,.8);">
    <div class="modal-dialog" style="width:411px;height:836px;">
        <div class="modal-content" style="background: transparent;border:none;">
            <div class="modal-body d-flex justify-content-center position-relative p-0" style="overflow:hidden;background:transparent;">
                <div style="width:411px;height:836px;background-image:url(/assets/system/img/mobile.png);position:relative;z-index:5;"></div>
                <iframe src="" frameborder="0" style="border:0;top: 99px;left: 26px;bottom:0px;right:0px;width: 360px;height: 638px;position:absolute;z-index:10;" allowfullscreen></iframe>
                <div class="btn-group btn-group-toggle position-absolute" data-toggle="buttons" style="z-index:100;bottom:22px;">
                    <label title="Позвонить в поддержку" class="btn btn-outline-success">
                        <input type="radio" name="act" id="radio-1" value="1"> <i class="fa fa-volume-control-phone"></i>
                    </label>
                    <label title="Повесить трубку" class="btn btn-outline-danger">
                        <input type="radio" name="act" id="radio-2" value="2"> <i class="fa fa-phone"></i>
                    </label>
                </div>
                <a title="Перезагрузить" class="ex-reload" href="#"><i class="fas fa-redo-alt"></i></a>
            </div>
        </div>
    </div>
    <script>
        $('#view-page-mobile').on('show.bs.modal', function() {
            $('#view-page-mobile iframe').attr('src', $('#view-page iframe')[0].contentDocument.URL);
        });

        $('#view-page-mobile iframe').on('load', function() {
            this.contentWindow.$('head').append('<style>body::-webkit-scrollbar{width:0;}</style>');
        });

        $(document).on("click", "#view-page-mobile .ex-reload", function(e) {
            e.preventDefault();
            let URL = $('#view-page-mobile iframe')[0].contentDocument.URL;
            $("#view-page-mobile iframe").attr("src", "");
            setTimeout(() => {
                $("#view-page-mobile iframe").attr("src", URL);
            }, 100);
        });

        $(document).on("change", "#view-page-mobile input[name=act]", function() {
            if (this.value == 1) {
                _modal('#comments');
            } else {
                $('#view-page-mobile').modal('hide');
            }
            $(this).prop('checked', false);
            $(this).parent().removeClass('active');
        });
    </script>
</div>