<?php

/**
 * @file
 * @brief страница Вход в систему
 */

 if ($user->id) {
    header("Location: /?main");
    exit;
}


?>

<script>
    _modal('#sign-in');
    $(document).on('show.bs.modal', () => {
        $('.modal .close').hide();
    });
    $('body>*').hide();
</script>