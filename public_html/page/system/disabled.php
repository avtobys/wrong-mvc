<?php

/**
 * @file
 * @brief страница 403 пользователь отключен
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

http_response_code(403);

?>

<main class="position-fixed h-100 w-100" role="main" style="top: 0; left: 0;">
    <div class="d-flex justify-content-center align-items-center flex-column h-100 text-danger text-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 512 512">
            <path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224c0-17.7-14.3-32-32-32s-32 14.3-32 32s14.3 32 32 32s32-14.3 32-32z" fill="#f15e5e" />
        </svg>
        <div class="mt-3" style="font-size:35px;font-weight: 700;line-height: 1;">Пользователь отключен</div>
        <a class="mt-3" href="/?main"><?= $_SERVER['HTTP_HOST'] ?></a>
    </div>
</main>
