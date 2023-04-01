<?php

/**
 * @file
 * @brief страница 403 доступ запрещен - модель существует, но недоступна
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

http_response_code(403);

?>

<main class="position-fixed h-100 w-100" role="main" style="top: 0; left: 0;">
    <div class="d-flex justify-content-center align-items-center flex-column h-100 text-danger text-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 512 512">
            <path d="M367.2 412.5L99.5 144.8C77.1 176.1 64 214.5 64 256c0 106 86 192 192 192c41.5 0 79.9-13.1 111.2-35.5zm45.3-45.3C434.9 335.9 448 297.5 448 256c0-106-86-192-192-192c-41.5 0-79.9 13.1-111.2 35.5L412.5 367.2zM512 256c0 141.4-114.6 256-256 256S0 397.4 0 256S114.6 0 256 0S512 114.6 512 256z" fill="#f15e5e" />
        </svg>
        <div class="mt-3" style="font-size:100px;font-weight: 700;line-height: 1;">403</div>
        <div class="mt-3" style="font-size:35px;font-weight: 700;line-height: 1;">Доступ запрещён</div>
        <a class="mt-3" href="/?main"><?= $_SERVER['HTTP_HOST'] ?></a>
    </div>
</main>
<script>
    setTimeout(() => {
        _modal("#sign-in");
    }, 2000);
</script>