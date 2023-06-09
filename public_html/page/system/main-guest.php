<?php

/**
 * @file
 * @brief главная страница для не авторизованных
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

?>
<section id="header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="logo"><a onclick="return wronginfo();" href="/<?= $user->id ? '?main' : '' ?>" id="wrong">Wrong MVC</a></div>
                <nav id="nav">
                    <a title="Отзывы и техподдержка" data-toggle="modal" data-target="#comments" href="/comments">Отзывы и техподдержка</a>
                    <a title="Документация" href="/documentation">Документация</a>
                    <a title="Вход, демо версия" data-toggle="modal" data-target="#sign-in" href="/enter">Вход</a>
                </nav>
            </div>
        </div>
    </div>
    <div id="banner">
        <div class="container">
            <div class="row">
                <div class="col-6 col-12-medium">
                    <h1>Wrong MVC - система созданная для создания других систем...</h1>
                    <a data-toggle="modal" data-target="#install" data-aos="zoom-in" href="/docs/" class="button-large text-decoration-none shadow-lg mt-4"><i class="fa fa-download mr-3"></i>Установить Wrong MVC</a>
                    <div class="small mt-3 mt-xl-4" data-aos="zoom-in-left" data-aos-duration="1000">Текущая актуальная версия: <?= Wrong\Start\Env::$e->VERSION ?></div>
                </div>
                <div class="col-6 col-12-medium imp-medium">
                    <a data-fancybox="video" href="//wrong-mvc.com/assets/system/video/wrong-mvc.mp4" class="w-100 rounded-lg position-relative text-decoration-none d-block">
                        <img class="w-100" src="/assets/system/img/wrong.png" alt="wrong mvc" data-aos="flip-up" data-aos-duration="1500">
                        <span class="position-absolute text-white px-3 py-1 small" style="left:0;bottom:0px;" data-aos="zoom-in-left" data-aos-duration="1000"><i class="fa fa-video-camera mr-2"></i>Посмотреть видео презентацию</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="features">
    <div class="container">
        <div class="row">
            <div class="col-3 col-6-medium col-12-small" data-aos="flip-left">
                <section>
                    <a data-fancybox="gallery" class="bordered-feature-image" href="/assets/system/img/groups.png"><img src="/assets/system/img/groups.png"></a>
                    <h2>Гибкость и системность</h2>
                    <p>
                        Совмещенная несовместимость - системататизация моделей WEB разработки, в сочетании с такой гибкостью,
                        которую вы не получите нигде более.
                    </p>
                </section>
            </div>
            <div class="col-3 col-6-medium col-12-small" data-aos="flip-right">
                <section>
                    <a data-fancybox="gallery" class="bordered-feature-image" href="/assets/system/img/page.png"><img src="/assets/system/img/page.png"></a>
                    <h2>Полный контроль</h2>
                    <p>
                        Групповые политики доступа с полным контролем над всей системой и её отдельными компонентами. Создание и отключение функционала в один клик.
                    </p>
                </section>

            </div>
            <div class="col-3 col-6-medium col-12-small" data-aos="flip-left">
                <section>
                    <a data-fancybox="gallery" class="bordered-feature-image" href="/assets/system/img/code.png"><img src="/assets/system/img/code.png"></a>
                    <h2>Простота разработки</h2>
                    <p>
                        Всего пару шагов от натяжки вёрстки до запуска простейших проектов, удобство и скорость разработки многоуровневых сложных систем.
                    </p>
                </section>

            </div>
            <div class="col-3 col-6-medium col-12-small" data-aos="flip-right">
                <section>
                    <a data-fancybox="gallery" class="bordered-feature-image" href="/assets/system/img/uri.png"><img src="/assets/system/img/uri.png"></a>
                    <h2>Умный URI роутинг</h2>
                    <p>
                        Распределяющий http запросы к моделям с учётом установленных доступов и системного веса как владельцев моделей так и запрашивающих пользователей.
                    </p>
                </section>
            </div>
        </div>
    </div>
</section>
<section id="content">
    <div class="container">
        <div class="row aln-center">
            <div class="col-6 col-12-medium" data-aos="zoom-in-right" data-aos-offset="-10">
                <section class="h-100">
                    <header>
                        <h2>Концепт Wrong MVC</h2>
                        <h3>Просто как 5 копеек</h3>
                    </header>
                    <p>
                        <strong>Wrong MVC</strong> - это среда для WEB разработки, включающая в себя множество необходимых компонентов, систематизированных в единое пространство и иерархию. Это тот фундамент, позволяющий очень гибко создавать уникальные системы с функционалом действий и страниц, назначенным определенным группам пользователей. Это система для создания на её базе любых WEB проектов, от простейших лендингов в несколько страниц, до сложнейших с многоуровневым разграничением прав доступов и пользовательского функционала, групповыми политиками доступов к моделям функционала, готовым умным uri роутингом, собственной cron реализацией, визуализацией всех моделей и их удобным управлением.
                    </p>
                    <p class="mt-4">
                        Это не Laravel в плане концепции MVC как таковых, и это не Wordpress в плане концепции сайтовых движков, это <b>WRONG MVC</b>! Система рассчитана на использование опытными Full-stack разработчиками, здесь реализованы все базовые нюансы, которые существенно упрощают и систематизируют разработку. Разобравшись в логике работы данной системы и её возможностях, вы гарантированно и навсегда забудете про php фреймворки или движки! Потому что сборка и реализация любых сложных проектов превратится в простое удовольствие.
                    </p>
                    <p class="mt-4">
                        ПО "Wrong MVC" имеет открытый исходный код и распространяется под лицензией свободного ПО - <a data-toggle="modal" data-target="#view-page" data-uri="//wrong-mvc.com/docs/_l_i_c_e_n_s_e_source.html" href="/docs/_l_i_c_e_n_s_e_source.html">Apache License 2.0</a>. Если Вы решите отблагодарить автора за труд чашечкой кофе и внести вклад в развитие этого бесплатного продукта - это можно сделать <a data-toggle="modal" data-target="#donates" href="#">здесь</a>. Также автор будет благодарен за Ваш вклад в безопасность системы - её взлом, все <a data-id="2" data-table="templates" data-target="#edit-code" data-toggle="modal" href="#">открытые исходники</a> вам в помощь. Приветствуются также отзывы и комментарии в <a data-toggle="modal" data-target="#comments" href="/comments">чате техподдержки Wrong MVC</a>.
                    </p>
                    <a data-toggle="modal" data-target="#sign-up" data-callback="demoStart" data-aos="zoom-in" data-aos-duration="1000" href="#" class="button-large text-decoration-none shadow-lg d-block text-center mt-5"><i class="fa fa-eye mr-3"></i>Демо Wrong MVC</a>
                </section>

            </div>
            <div class="col-6 col-12-medium col-12-small" data-aos="zoom-in-left" data-aos-offset="-10">
                <section class="h-100">
                    <header>
                        <h2>А под капотом?</h2>
                        <h3>Всё как в нормальном самолёте</h3>
                    </header>
                    <ul class="check-list">
                        <li>Визуально понятная система компонентов</li>
                        <li>Неограниченная вложенность уровней доступа</li>
                        <li>Автоматический роутинг страниц и запросов</li>
                        <li>Настраиваемые шаблоны страниц и действий</li>
                        <li>Конструктор триггеров действий</li>
                        <li>Встроенный редактор кода с подсветкой и горячими клавишами</li>
                        <li>Управление группами доступа, пользователями и их функционалом</li>
                        <li>Умные стеки PHP - JavaScript кода с отложенным выполнением</li>
                        <li>Модальные окна запрашиваемые автоматически из API по AJAX</li>
                        <li>Умная подгрузка js библиотек по мере их применения</li>
                        <li>API действий, выборок и модальных окон</li>
                        <li>X-Auth-Token авторизация в API</li>
                        <li>Копирование, экспорт, импорт моделей</li>
                        <li>Логгирование любых действий</li>
                        <li>Встроенные CRON задачи с выполнением от имени пользователей</li>
                        <li>Многопоточность выполняемых CRON задач с контролем нагрузки</li>
                        <li>Собственная система кеширования</li>
                        <li>Готовый gulp комбайн для вёрсток и кастомизации</li>
                        <li>Создание простых сайтов в пару кликов из готовых шаблонов</li>
                        <li>Автоматическая защита всех форм от CSRF</li>
                        <li>Бессрочная авторизация по COOKIE с криптованием UID</li>
                        <li>Hcaptcha по лимиту Brute force попыток</li>
                        <li>Oauth google + yandex авторизации</li>
                        <li>Backend: PDO MySQL + PHP</li>
                        <li>Frontend: Bootstrap v4.6.1 + JQuery</li>
                        <li>Установка в пару кликов</li>
                        <li><a title="Что дальше?" data-toggle="modal" data-target="#todo" href="/docs/md__t_o_d_o.html">Что будет дальше?</a></li>
                    </ul>
                </section>

            </div>
        </div>
    </div>
    <div class="container mt-4" data-aos="zoom-in-left">
        <div class="description">
            <h2>Зачем это нужно Вам?</h2>
            <h3>Управление бизнес логикой</h3>
            <p>Для разделения и распределения абсолютно любой Вашей бизнес-логики! Менеджмент, пользователи, покупатели, заказчики, доставщики, руководители, водители и их родители, могильщики и похоронщики - благодаря групповым политикам доступов ко всем моделям, легко организовать и построить абсолютно любую архитектуру и подчиненность процессов в любых Ваших приложениях и управлять ею онлайн! А в сочетании со встроенной автоматизацией по расписаниям - это пушка бомба. Это не CRM система, это среда разработки на базе которой можно построить любую свою систему, включая CRM!</p>
        </div>
    </div>
    <div class="container mt-4" data-aos="fade-up">
        <div class="description">
            <h2>Нет ничего проще сайта в два клика?</h2>
            <h3>Тогда запускайте лендинги в 1 клик</h3>
            <div class="owl-carousel-projects owl-carousel owl-theme mt-3">
                <div class="item">
                    <a data-toggle="modal" data-target="#view-page" data-uri="/examples/tivo/index" href="#">
                        <img src="/assets/system/img/tivo.png" alt="slider" loading="lazy">
                    </a>
                </div>
                <div class="item">
                    <a data-toggle="modal" data-target="#view-page" data-uri="/examples/seogram/index" href="#">
                        <img src="/assets/system/img/seogram.png" alt="slider" loading="lazy">
                    </a>
                </div>
                <div class="item">
                    <a data-toggle="modal" data-target="#view-page" data-uri="/examples/aesthetic-master/index" href="#">
                        <img src="/assets/system/img/aesthetic.png" alt="slider" loading="lazy">
                    </a>
                </div>
                <div class="item">
                    <a data-toggle="modal" data-target="#view-page" data-uri="/examples/delfood/index" href="#">
                        <img src="/assets/system/img/delfood.png" alt="slider" loading="lazy">
                    </a>
                </div>
                <div class="item">
                    <a data-toggle="modal" data-target="#view-page" data-uri="/examples/marshmallow/index" href="#">
                        <img src="/assets/system/img/marshmallow.png" alt="slider" loading="lazy">
                    </a>
                </div>
                <div class="item">
                    <a data-toggle="modal" data-target="#view-page" data-uri="/examples/orthoc/index" href="#">
                        <img src="/assets/system/img/orthoc.png" alt="slider" loading="lazy">
                    </a>
                </div>
                <div class="item">
                    <a data-toggle="modal" data-target="#view-page" data-uri="/examples/eclipse-master/index" href="#">
                        <img src="/assets/system/img/eclipse.png" alt="slider" loading="lazy">
                    </a>
                </div>
                <div class="item">
                    <a data-toggle="modal" data-target="#view-page" data-uri="/examples/tivo/index" href="#">
                        <img src="/assets/system/img/tivo.png" alt="slider" loading="lazy">
                    </a>
                </div>
                <div class="item">
                    <a data-toggle="modal" data-target="#view-page" data-uri="/examples/seogram/index" href="#">
                        <img src="/assets/system/img/seogram.png" alt="slider" loading="lazy">
                    </a>
                </div>
                <div class="item">
                    <a data-toggle="modal" data-target="#view-page" data-uri="/examples/orthoc/index" href="#">
                        <img src="/assets/system/img/orthoc.png" alt="slider" loading="lazy">
                    </a>
                </div>
            </div>
        </div>
        <script>
            $(function() {
                $('.owl-carousel-projects').owlCarousel({
                    loop: true,
                    stagePadding: 100,
                    margin: 20,
                    nav: false,
                    autoplay: true,
                    autoplayTimeout: 3000,
                    autoplayHoverPause: true,
                    smartSpeed: 2500,
                    dots: false,
                    responsive: {
                        0: {
                            items: 1
                        },
                        500: {
                            items: 2
                        },
                        767: {
                            items: 3
                        },
                        1000: {
                            items: 4
                        }
                    }
                });
            });
        </script>
    </div>
    <div class="container mt-4" data-aos="zoom-in-right">
        <div class="description">
            <h2>Что за зверь такой?</h2>
            <h3>Чуточку подробнее про Wrong MVC</h3>
            <p>Вложенность назначаемых прав доступа не ограничена. Совсем! Ничем! Что это значит? Это значит возможность создания любого функционала многоуровневой вложенности в проекте и простота корректировки его логики работы в дальнейшем. Администратор с наивысшими правами системы может делигировать определенные функции другим пользователям, создавать группы администраторов, модераторов, с разными доступами и функционалом, включать и отключать их. Аналогично по цепочке вниз, пользователи могут управлять делегированным им функционалом других пользователей.</p>

            <p>Архитектура WRONG в плане разграничения прав пользователей относительно похожа на доступы в системе Linux. Существуют группы пользователей и даже специальная группа "Система". Только здесь - это реализация на PHP + MySQL, роутинг страниц, архитектура прав, это всё подтягивается из записанных в БД данных.</p>
            <p>Действия, выборки, страницы, всё это доступно определенной группе(группы доступа) и имеет группу-владельца(владелец имеет права на изменение или удаление, добавление групп и на сам доступ). У групп могут быть подчиненные группы.</p>
            <p>У каждого действия или модели(компонента), будь то api-http запрос/выборки/страницы/модалки, есть массив "Группы доступа" и единственная "Группа владелец"</p>
            <p>Если брать аналогию с Linux то "Группы доступа" - имеют право на чтение и выполнение, а "Группа владелец" имеет плюс к этому ещё право на запись.</p>
            <p>Например, "Модератор" создает страницу с REQUEST_URI = /example-page и соответствующий ей файл - обработчик /example-page.php , с правами доступа группам "Гости", "Пользователи". Указанные группы имееют доступ к странице(она "читается" и "выполняется"), а вот включать, отключать, удалять, изменять группы которым она доступна, это уже "запись", это может сам владелец страницы - группа "Модератор" или пользователи с правами выше(группа "Администраторы"). Естественно что владелец всегда имеет право и на доступ.</p>
            <p>Так же со всеми действиями, выборками, модальными окнами, группами пользователей, всё имеет свои права и владельца. Даже функционал и страницы главного Администратора подчинены группе "Система", чтобы админ не смог "выключить" самого себя или критически важный функционал - начальные параметры системы.</p>
            <p>Тем не менее пользователь создаваемый при установке состоит не только в группе "Администраторы" но и в группе "Система", но функционал данной группы защищен от отключения и удаления, а вот вносить правки в "Группы доступа" можно.</p>
            <p>Как и в Linux пользователь может принадлежать одновременно нескольким группам, причем для этих групп могут быть заданы страницы/запросы с одинаковыми REQUEST_URI, но различными файлами-обработчиками. Как же мы определяем какую страницу отдать по одному и тому же REQUEST_URI? Всё просто. Каждой группе пользователей назначается "вес"(weight) и запрашивается страница с запросом соответствующим группе с большим весом. Тоже самое с действиями/модалками и прочими запросами к api(они тоже имеют REQUEST_URI и файлы-обработчики)</p>
            <p>Например, Администратор состоит в группах "Администраторы", "Модераторы", у тех и других групп есть одинаковые REQUEST_URI. Но вес группы "Администраторы" больший чем вес "Модераторов", и будет отдан обработчик именно "Администраторов", в то время как группе ниже может быть отдан другой обработчик по тому же URI запросу. Можно создавать любое количество групп с различными названиями и весом! Чем больше вес - тем больше прав. Пользователь группы с меньшим весом не может управлять пользователями(и их функционалом) с большим весом(удалять, выключать, изменять и т.д.)</p>
            <p><strong>Модели(компоненты).</strong> Всё что имеет группу-владельца является моделью, будем называть компоненты так - модель. Таких типов моделей в системе 8:</p>
            <ul class="check-list">
                <li>Группы пользователей</li>
                <li>Пользователи</li>
                <li>Шаблоны</li>
                <li>Страницы</li>
                <li>Выборки</li>
                <li>Модальные окна</li>
                <li>Действия</li>
                <li>Cron задачи</li>
            </ul>
            <p>Есть возможность устанавливать лимиты моделей для групп, очищать все модели полностью вместе с записями бд и файлами, удалять группы вместе с принадлежащими моделями, экпортировать, импортировать и копировать модели.</p>
            <p><strong>Группы пользователей.</strong> У каждой группы есть свойство - системный вес. От веса зависит приоритет одного пользователя над другим. Пользователь может состоять одновременно в разных группах с разным весом, в данном случае для расчета приоритета(веса) пользователя берется максимальный вес из групп в которых он находится.</p>
            <p><strong>Вес в системе.</strong> Что такое приоритет одного пользователя над другим? Это значит что пользователи из "подчиненной группы"(с меньшим весом) могут управляться пользователем с большим приоритетом(макс. вес его групп больше). Т.е. он может отключать, удалять таких пользователей и создавать для них различные действия, модальные окна, страницы, назначать их владельцами данного функционала, отключать им определенный функционал. Естественно это всё в рамках того функционала, который включен и доступен данному пользователю.</p>
            <p><strong>Управление по кнопке.</strong> Пользователи, группы пользователей, страницы, действия, модальные окна, здесь всё отключается и включается кнопочками вкл/выкл! Кроме того, ссылки на вызов отключенных окон, линки на такие REQUEST_URI, не показываются на других даже доступных пользователю страницах. Тоже самое если юзер вне "Группы доступа", он просто не увидит таких ссылок, если они будут у него. И конечно получит 403 если запросит url вручную. Это всё - ав-то-ма-том! Что это значит? Это значит мы выключили страницу или модалку для пользователя - нам даже не нужно менять верстку шаблонов, у него автоматически будут скрыты все ссылки/кнопки на этот функционал(средствами CSS), а обработчик запроса перестанет работать.</p>
            <p><strong>Роутинг uri</strong> запросов к моделям автоматический и учитывает вес. У пользователей могут быть страницы с одинаковым REQUEST_URI, но с разными файлами обработчиками и контентом в них. При этом пользователи могут находится в одних группах. Например пользователь 1 состоит в группе "администраторы" и "пользователи", а пользователь 2 состоит в группе "пользователи". Вес групп пользователя 1 больше. И есть 2 разных модели типа "страница" с одинаковым REQUEST_URI. Но для пользователя 1 доступны обе страницы, а для пользователя 2 только одна. В результате <a data-toggle="modal" data-target="#view-page" data-uri="//wrong-mvc.com/docs/routing.html" href="/docs/routing.html">URI контроллер</a> отдаст пользователям разные страницы, т.к. пользователю 1 будет отдана страница наиболее подходящая по его весу групп.</p>
            <p><strong>Страницы</strong> могут иметь одинаковый основной контент, но различные шаблоны. Например это страница со списком документации для не авторизованных и главная страница админки. Как уже сказано выше они могут иметь и одинаковый url, а шаблон будет зависеть от группы пользователя.</p>
            <p><strong>Модальные окна</strong> - их полно, но их просто нет!:) Обычные бутстраповские модальные окна, на первый взгляд, но их html кода на страницах нет. Окна подтягиваются автоматически из api по ajax и уничтожаются из DOM при их закрытии. Это позволяет очень гибко реализовать в них js логику и менять их как перчатки, перемещаясь "внутри" логики окон. Для примера - страницы регистрации/авторизации/восстановления пароля. Окна и действия создаются в админке из готового шаблона, а дальше правим на свой вкус. Но это не всё! Все data-* атрибуты с кнопки триггера модального окна, добавятся переменными к GET запросу api отдающему модалку. А в data-callback="MyFynction" можно указать даже имя callback функции которая будет вызвана после добавления в DOM кода модалки. Конечно реализован и javascript программный вызов окон функцией, с callback обработчиком. Взгляните как просто <a data-id="1" data-table="pages" data-target="#edit-code" data-toggle="modal" href="#">на этой странице вызывается модальное окно</a> входа в систему. Обычный bootstrap триггер: data-toggle="modal" data-target="#sign-in", но <a data-id="1" data-table="modals" data-target="#edit-code" data-toggle="modal" href="#">сам код модального окна</a> запрашивается из api и добавляется в DOM автоматически.</p>
            <p><strong>Действия.</strong> Всего лишь data-action="my-action" атрибут к любой ссылке и любая ссылка/кнопка/элемент <a data-toggle="modal" data-target="#view-page" data-uri="//wrong-mvc.com/docs/triggers.html" href="/docs/triggers.html">автоматически превращается в триггер</a> при клике связанный с api, автоматом отправляющий POST request на апи /api/action/my-action, при этом все данные data-* атрибутов переменными POST массива летят на этот запрос, имеется автоматическая модалка(прокладка) подтверждение с кастомизацией текстов да/нет(если нужна - добавляем data-confirm="true"), callback функция с response от api обработчика(добавляем data-callback="MyFunction"). По умолчанию в response от обработчика приходит json формат, но и это легко меняется data-response="script|html" и вуаля - любой формат в апи ответе. И всё это, ав-то-ма-том! data-* атрибуты рулят! Естественно действие(апи запрос) заранее должно быть добавлено в админке, с требуемым ему разграничением прав. Там же есть конструктор кнопок с data-* атрибутами. То есть фактически, любая кнопка автоматом превращается в "мини" форму, отправляющую данные с возможностью получить ответ от api. Обычные формы по сути запросов не отличаются. Это легко и просто! Также есть <a data-toggle="modal" data-target="#view-page" data-uri="//wrong-mvc.com/docs/js_actions_modals.html" href="/docs/js_actions_modals.html">программный вызов действий javascript функцией</a> с передачей параметров и callback обработчиком.</p>
            <p><strong>Выборки</strong> в таблицах моделей реализованы плагином DataTable и имеют все необходимые сортировки по столбцам, постраничную навигацию и поиск по таблицам, все состояния таблиц сохраняются. Есть быстрое редактирование всех необходимых параметров в таблицах. Имеется удобный лог действий, с подсветкой ошибок, который отключается для групп пользователей. В таблицах имеется настройка скрытия ненужных столбцов таблиц, а также фильтрация моделей по группам доступа, владельцам и активности. Таблицы - лишь визуализация моделей системы, в вашем проекте это могут быть любые иные обработчики и визуализация, но работать они будут в той же глобальной api структуре выборок /api/select/.. и будут относиться к моделям типа "выборка".</p>
            <p><strong>API.</strong> Абсолютно все действия в системе от пользователей выполняются при помощи api запросов. Запросы авторизуются как посредством сессии пользователей, так и посредством X-Auth-Token. Авторизованные X-Auth-Token запросы отключаются как на уровне настроек всей системы, так и на уровне настроек для каждого отдельного пользователя. Практически неограниченные возможности для расширения! Даже эта система может являться частью другой системы или проекта, быть управляема ею извне по api, или же сама стать проектом со своим собственным api.</p>
            <p><strong>CRON задачи</strong> настраиваются в админ панели с абсолютно идентичным синтаксисом crontab расписания в Linux. Имеется удобный предпросмотр расписания ближайших выполнений. Но в данной логике по крону выполняются не файлы, а выполняются любые внутрисистемные http запросы, которые могут быть авторизованы X-Auth-Token любого пользователя(если у него включено api). Указывается лишь id исполнителя задачи. С http запросами можно отправлять любые заголовки и POST/PUT/DELETE данные, опять же для взаимодействия с api системы. Это позволяет работать с функционалом по расписанию, например включать и отключать определенные сервисы-модели(действия, страницы, пользователей, группы, настройки и т.д.) в определенное расписанием время. Cron задачи являются такой же моделью как и все остальные модели системы и подчинены общей логике групповой политики доступов.</p>
            <p><strong>Импорт, экспорт, копирование моделей.</strong> Любую модель можно легко <a data-toggle="modal" data-target="#view-page" data-uri="//wrong-mvc.com/docs/import_export.html" href="/docs/import_export.html">экcпортировать и импортировать</a> как внутри системы, так и между системами на базе WRONG MVC. Экспорт отдает архив модели со всеми потрохами = строка в бд + файл обработчик(если он есть у модели) При импорте будут созданы файлы и записи в необходимых таблицах моделей. А если делается импорт уже существующей в системе модели - к именам файлов, записям, запросам, добавится автоматически префикс copy. Т.е. если у нас есть некий функционал(действие, страница, модалка) и нам нужно создать такой же, но с "перламутровыми пуговицами". В этом поможет быстрый экcпорт/импорт/копирование.</p>
            <p><strong>Вёрстка и её шаблоны.</strong> В проекте предусмотрен <a data-toggle="modal" data-target="#view-page" data-uri="//wrong-mvc.com/docs/castomization.html" href="/docs/castomization.html">gulp сборщик фронтенда</a>, вы можете собирать любые собственные модификации фронтендов. Javascript работает с вёрсткой Bootstrap v4.6.1, поэтому его верстка нужна(в любой модификации). Если же все фронт js плюшки(модалки и прочее) вам не нужны - то не нужен(оставляем рабочим лишь бекенд - контроллеры, крон, роутинг, а встроенный js не подключаем в проекты). Можно создать любую страницу для входа в панель, отключив эту, а в шаблонах проектов использовать любой свой бутстраповский css по вкусу, или даже стек файлов для разных шаблонов, меняя бутстраповские дизайны хоть каждый день или отдельным пользователям или под любые странички или по cron задачам на праздники. Для системной панели же, лучше оставить встроенный дизайн. Вне панели, javascript можно подключать внизу шаблона, это не критично. Необходимые js/css плагины можно подгружать в шаблоны по их путям даже напрямую из любого каталога фс, при этом они будут автоматически минифицированы. Вы можете создавать любое количество сборок и вариаций различных фронтендов. Для примера посмотрите на <a data-id="1" data-table="templates" data-target="#edit-code" data-toggle="modal" href="#">файл шаблона этой страницы</a> и на <a data-id="1" data-table="pages" data-target="#edit-code" data-toggle="modal" href="#">файл контента этой страницы</a>. Как видите контент и шаблон разделены и практически не зависят друг от друга.</p>
            <p>Выше, изложено лишь сумбурное описание системы. В <a target="_blank" href="/documentation">документации</a> изложено уже систематизированное описание методов, свойств, разделов, api и возможностей. На базе Wrong MVC можно создать практически любой расширяемый проект, будь то движок многостраничного портала, интернет магазина, или движок многофункционального web сервиса.</p>
        </div>
    </div>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru-RU&apikey=00661efd-13d4-4773-8046-8f6473ce6e34" type="text/javascript"></script>
    <div class="container mt-4" data-aos="fade-zoom-in" data-aos-offset="-10">
        <div class="description">
            <h2>Откуда взялся?</h2>
            <h3>Начинающий нелюбитель, учусь систематизировать и автоматизировать пушки бомбы</h3>
            <div id="map" class="mt-3" data-aos="zoom-in" data-aos-duration="1000"></div>
        </div>
    </div>

</section>



<section id="footer">
    <div class="container">
        <div class="row">
            <div class="col-8 col-12-medium">
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
                                    <li><a href="/docs/code_editor.html">Встроенный редактор кода</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-4 col-12-medium imp-medium">
                <section>
                    <h2>Сотрудничество</h2>
                    <p>
                        Рассматриваются предложения относительно создания на базе продукта коммерческих проектов. Есть масса не реализованных идей соотвествующего уровня оптимизации системы и совершенствования! Коротко - продажа готовых программных решений любой бизнес-логики.
                    </p>
                </section>

            </div>
        </div>
    </div>
</section>
<div class="container pb-5 pb-lg-0 my-md-2 pt-2 border-top" style="position: relative;z-index:4;">
    <div class="row">
        <div class="col-6 col-md">
            <small class="d-block text-muted pt-2">© wrong-mvc.com - неправильным для правильных!</small>
        </div>
        <div class="col-6 col-md d-flex justify-content-end">
            <a title="Telegram" target="_blank" class="d-block text-decoration-none pt-2 mr-3" href="https://t.me/tdsdm"><i class="fa fa-telegram text-muted" style="font-size: 26px;"></i></a>
            <a title="Whatsapp" target="_blank" class="d-block text-decoration-none pt-2" href="https://api.whatsapp.com/send?phone=+79494717522"><i class="fa fa-whatsapp text-muted" style="font-size: 26px;"></i></a>
        </div>
    </div>
</div>
<div id="arrowTop" hidden><i class="fa fa-chevron-circle-up"></i></div>
<script>
    arrowTop.onclick = () => {
        $('body,html').animate({
            scrollTop: 0
        }, 1000);
    };
    window.addEventListener('scroll', function() {
        arrowTop.hidden = (pageYOffset < document.documentElement.clientHeight);
    });
    $(function() {
        AOS.init();
    });
    ymaps.ready(init);

    function init() {
        window.myMap = new ymaps.Map('map', {
            center: [48.335712, 38.054908],
            zoom: 12,
            controls: ['zoomControl', 'typeSelector', 'fullscreenControl'],
            behaviors: []
        });

        window.myPlacemark = new ymaps.Placemark([48.335712, 38.054908], {
            balloonContentHeader: "Меня зовут Димыч",
            balloonContentBody: "<div class=\"text-nowrap small w-100 mt-3\"><a class=\"btn btn-sm rounded-pill text-primary py-0 px-3 btn-block bg-light-info text-left\" data-aos=\"zoom-in-right\" data-aos-duration=\"1000\" href=\"tel:+79494717522\"><i class=\"fa fa-phone\"></i> +7 (949) 471-75-22</a><a class=\"btn btn-sm rounded-pill text-primary py-0 px-3 btn-block bg-light-info text-left\" target=\"_blank\" data-aos=\"zoom-in-left\" data-aos-duration=\"1000\" href=\"https://t.me/tdsdm\"><i class=\"fa fa-telegram\"></i> Telegram</a><a class=\"btn btn-sm rounded-pill text-primary py-0 px-3 btn-block bg-light-info text-left\" target=\"_blank\" data-aos=\"zoom-in-right\" data-aos-duration=\"1000\" href=\"https://api.whatsapp.com/send?phone=+79494717522\"><i class=\"fa fa-whatsapp\"></i> Whatsapp</a><a class=\"btn btn-sm rounded-pill text-primary py-0 px-3 btn-block bg-light-info text-left\" target=\"_blank\" data-aos=\"zoom-in-left\" data-aos-duration=\"1000\" href=\"mailto:support@wrong-mvc.com\"><i class=\"fa fa-at\"></i> support@wrong-mvc.com</a></div>",
            balloonContentFooter: 'Контакты <?= $_SERVER['HTTP_HOST'] ?>',
            hintContent: "<strong>hintContent</strong>",
            iconContent: "<div class='d-flex align-items-center'><img class='rounded-circle mr-1' width='15' height='15' src='/assets/system/img/tux.jpg' alt='wrong-mvc'> <strong>Контакты Wrong-mvc</strong></div>"
        }, {
            preset: 'islands#redStretchyIcon'
        });

        myMap.geoObjects.add(myPlacemark);
        myPlacemark.balloon.open(null, null, {
            minWidth: 230
        });
    }
</script>
<?= Wrong\Html\Get::scriptsrc($_SERVER['DOCUMENT_ROOT'] . '/assets/system/vendors/owl.carousel/js/owl.carousel.js') ?>