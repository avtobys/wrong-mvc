<?php

/**
 * @file
 * @brief окно
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

if (Wrong\Start\Env::$e->HTTP_HOST != 'wrong-mvc.com') {
    exit('<script>location.href="//wrong-mvc.com/comments";</script>');
}

?>
<div class="modal fade" id="<?= $basename ?>" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable w-100 mw-100 h-100 p-0 m-0 position-fixed" style="max-width: 100%;max-height:100%;" role="document">
        <div class="modal-content w-100 h-100 rounded-0 border-0">
            <div class="border-0 modal-header position-absolute rounded-0 w-100 pb-0" style="z-index:1;">
                <button title="Закрыть" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0 <?= $user->id ? 'pt-5' : '' ?>"></div>
        </div>
    </div>
    <script>
        $('#<?= $basename ?> .modal-body').load('/comments #content', () => {
            AnyComment = window.AnyComment || [];
            AnyComment.Comments = [];
            AnyComment.Comments.push({
                "root": "anycomment-app",
                "app_id": 5219,
                "language": "ru",
                "i18n": {
                    "ru": {
                        "add_comment": "Добавить сообщение..."
                    }
                },
                "title": "Отзывы о системе Wrong MVC",
                "author": "Wrong MVC",
                "page_url": "https://wrong-mvc.com/comments"
            })
            var s = document.createElement("script");
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://widget.anycomment.io/comment/embed.js";
            var sa = document.getElementsByTagName("script")[0];
            sa.parentNode.insertBefore(s, s.nextSibling);

            <?php if (Wrong\Rights\Group::is_available_group(Wrong\Models\Actions::find(45))) : ?>
                _action({
                    action: 'anycomment',
                    readed: true,
                }, response => {
                    $('#new-comments').remove();
                });
            <?php endif; ?>
        });

        $(document).off('click', '#comments button[class^="Button__StyledButton"]');
        $(document).on('click', '#comments button[class^="Button__StyledButton"]', function(e) {
            window.localStorage.commented = 1;
        });

        $('.toast').toast('hide');
        $('#comments').on('hidden.bs.modal', () => {
            if (!window.localStorage.commented) {
                dangerToast([
                    ['Постой!', 'Погоди!', 'Не уходи!', 'Куда же ты?'][Math.floor(Math.random() * 4)],
                    ['Может', 'Давай', 'Возможно'][Math.floor(Math.random() * 3)],
                    ['вернешься', 'передумаешь', 'надумаешь'][Math.floor(Math.random() * 3)],
                    ['и'],
                    ['скажешь', 'напишешь', 'выскажешь', 'расскажешь'][Math.floor(Math.random() * 4)],
                    ['чего', 'что'][Math.floor(Math.random() * 2)],
                    ['нить?', 'нибудь?', 'либо?'][Math.floor(Math.random() * 3)],
                    ['А?', 'Пожалуйста!', 'Прошу тебя!', 'Вернись пожалуйста!'][Math.floor(Math.random() * 4)]
                ].join(' '), 10000);
            }
        });

        typeof ym === 'function' && ym(92932927, 'reachGoal', 'comments');
    </script>
</div>