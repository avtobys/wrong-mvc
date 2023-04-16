<?php

/**
 * @file
 * @brief шаблон главной страницы для неавторизованных
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';
$CONTENT_PAGE_FILE = $_SERVER['DOCUMENT_ROOT'] . $row->file;

?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $row->name ?></title>
    <meta name="title" content="<?= $row->name ?>">
    <meta name="description" content="Унифицированная среда WEB разработки Wrong MVC с визуализацией компонентов и автоматизацией действий">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://wrong-mvc.com/">
    <meta property="og:title" content="<?= $row->name ?>">
    <meta property="og:description" content="Унифицированная среда WEB разработки Wrong MVC с визуализацией компонентов и автоматизацией действий">
    <meta property="og:image" content="https://wrong-mvc.com/assets/system/img/wrong.png">
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://wrong-mvc.com/">
    <meta property="twitter:title" content="<?= $row->name ?>">
    <meta property="twitter:description" content="Унифицированная среда WEB разработки Wrong MVC с визуализацией компонентов и автоматизацией действий">
    <meta property="twitter:image" content="https://wrong-mvc.com/assets/system/img/wrong.png">
    <meta name="description" content="Унифицированная среда WEB разработки Wrong MVC с визуализацией компонентов и автоматизацией действий">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="64x64" href="/assets/system/img/favicon-64.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/system/img/favicon-32.png">
    <?= Wrong\Html\Get::stylesrc($_SERVER['DOCUMENT_ROOT'] . '/assets/system/css/guest.min.css') ?>
    <?= Wrong\Html\Get::scriptsrc($_SERVER['DOCUMENT_ROOT'] . '/assets/system/js/main.min.js') ?>
</head>


<body>
    <div id="page-wrapper">
        <?php require $CONTENT_PAGE_FILE; ?>
    </div>
</body>
<?= Wrong\Html\Get::scriptsrc($_SERVER['DOCUMENT_ROOT'] . '/assets/system/js/guest.min.js') ?>
<script>
    $(document).on('click', '[data-fancybox]', function(e) {
        if ($(this).data('fancybox') == 'video') {
            typeof ym === 'function' && ym(92932927, 'reachGoal', 'video');
        }
        if (typeof Fancybox !== 'function') {
            e.preventDefault();
            _this = this;
            loadLibs(<?= Wrong\Html\Get::pathArrayJSON(['/assets/system/css/fancybox.min.css']) ?>, <?= Wrong\Html\Get::pathArrayJSON(['/assets/system/js/fancybox.min.js']) ?>, 'Fancybox')
                .then(() => {
                    Fancybox.bind('[data-fancybox]', {
                        on: {
                            close: () => {
                                $('.tooltip').tooltip('hide');
                            }
                        }
                    });
                    _this.click();
                });
        }
    });
</script>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function(m, e, t, r, i, k, a) {
        m[i] = m[i] || function() {
            (m[i].a = m[i].a || []).push(arguments)
        };
        m[i].l = 1 * new Date();
        for (var j = 0; j < document.scripts.length; j++) {
            if (document.scripts[j].src === r) {
                return;
            }
        }
        k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
    })
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(92932927, "init", {
        clickmap: true,
        trackLinks: true,
        accurateTrackBounce: true,
        webvisor: true
    });
</script>
<noscript>
    <div><img src="https://mc.yandex.ru/watch/92932927" style="position:absolute; left:-9999px;" alt="" /></div>
</noscript>
<!-- /Yandex.Metrika counter -->

</html>