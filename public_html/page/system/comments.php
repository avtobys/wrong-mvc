<?php

/**
 * @file
 * @brief страница Отзывы о системе Wrong MVC
 */

?>
<section id="header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="logo"><a onclick="return wronginfo();" href="/<?= $user->id ? '?main' : '' ?>" id="wrong">Wrong MVC</a></div>
                <nav id="nav">
                    <a title="Документация" href="/documentation">Документация</a>
                    <?php if (!$user->id) : ?>
                        <a title="Вход, демо версия" data-toggle="modal" data-target="#sign-in" href="/enter">Вход</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </div>
</section>

<section id="content">
    <div class="container px-0">
        <div class="description px-3 px-lg-4">
            <?php if (!$user->id) : ?>
                <h2>Мнения, комментарии, техподдержка в среде разработки Wrong MVC</h2>
                <h3 class="text-muted">Откровенно, что думаете? Мы имеем право налево слева направо?</h3>
            <?php endif; ?>
            <div id="anycomment-app">
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
            <script>
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

                $(function() {
                    typeof ym === 'function' && ym(92932927, 'reachGoal', 'comments');
                });
            </script>
        </div>
    </div>
</section>

<section id="footer">
    <div class="container">
        <div class="row">
            <div class="col-12 col-12-medium">
                <section>
                    <h2>Ссылки на документацию</h2>
                    <div>
                        <div class="row">
                            <div class="col-4 col-12-small">
                                <ul class="link-list last-child">
                                    <li><a href="/docs/">Установка и системные требования</a></li>
                                    <li><a href="/docs/castomization.html">Кастомизация</a></li>
                                    <li><a href="/docs/groups_users.html">Группы и пользователи</a></li>
                                    <li><a href="/docs/weight.html">Системный вес</a></li>
                                </ul>
                            </div>
                            <div class="col-4 col-12-small">
                                <ul class="link-list last-child">
                                    <li><a href="/docs/models.html">Модели</a></li>
                                    <li><a href="/docs/userlandnaming.html">Руководство по именованию</a></li>
                                    <li><a href="/docs/routing.html">Роутинг и URI контроллеры</a></li>
                                    <li><a href="/docs/layout.html">Шаблонизация</a></li>
                                </ul>
                            </div>
                            <div class="col-4 col-12-small">
                                <ul class="link-list last-child">
                                    <li><a href="/docs/cron.html">Встроенные CRON задачи</a></li>
                                    <li><a href="/docs/stackjs.html">Javascript-PHP стеки</a></li>
                                    <li><a href="/docs/triggers.html">Триггеры действий и окон</a></li>
                                    <li><a href="/docs/code_editor.html">Встроенный редактор</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
<div class="container my-md-2 pt-2 border-top" style="position: relative;z-index:4;">
    <div class="row">
        <div class="col-6 col-md">
            <small class="d-block text-muted pt-2">© <?= $_SERVER['HTTP_HOST'] ?> - неправильным для правильных!</small>
        </div>
        <div class="col-6 col-md d-flex justify-content-end">
            <a title="Telegram" target="_blank" class="d-block text-decoration-none pt-2 mr-3" href="https://t.me/tdsdm"><i class="fa fa-telegram text-muted" style="font-size: 26px;"></i></a>
            <a title="Whatsapp" target="_blank" class="d-block text-decoration-none pt-2" href="https://api.whatsapp.com/send?phone=+79494717522"><i class="fa fa-whatsapp text-muted" style="font-size: 26px;"></i></a>
        </div>
    </div>
</div>