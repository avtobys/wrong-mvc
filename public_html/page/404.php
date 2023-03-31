<?php

/**
 * @file
 * @brief страница 404
 */

ob_clean();
http_response_code(404);

?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/system/css/main.min.css?<?= filemtime(__DIR__ . '/assets/system/css/main.min.css') ?>">
    <script src="/assets/system/js/main.min.js?<?= filemtime(__DIR__ . '/assets/system/js/main.min.js') ?>"></script>
    <title>404 Not Found</title>
</head>

<body>
    <main class="position-fixed h-100 w-100" role="main" style="top: 0; left: 0;">
        <div class="d-flex justify-content-center align-items-center flex-column h-100 text-danger text-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 512 512">
                <path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224c0-17.7-14.3-32-32-32s-32 14.3-32 32s14.3 32 32 32s32-14.3 32-32z" fill="#f15e5e" />
            </svg>
            <div class="mt-3" style="font-size:100px;font-weight: 700;line-height: 1;">404</div>
            <a class="mt-3" href="/?main"><?= $_SERVER['HTTP_HOST'] ?></a>
        </div>
    </main>
</body>

</html>
<?php exit; ?>