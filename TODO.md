
- реорганизация классов, создание интерфейсов;
- подключение сторонних сервисов кеширования Memcached, Redis
- массовые действия над моделями;
- обновление системы без переустановки с нуля
- альтернативные способы авторизации/регистрации пользователей
- возможность установки и разворачивания всей системы из коробки docker-compose;
- ветки всей фс с редактированием любых файлов проекта для системного админа и ветки только доступных каталогов для всех остальных групп;
- шаблоны выборок на выбор, и действий с различными вариациями кодов;
- страницы + выборки по чекбоксам при создании, по аналогии реализации модалки + действие;
- персональная тикет поддержка для авторизованных в телегу ботом;
- возможность перегенерации x-auth-token
- создание/сборка готовых сайтов с готовой версткой в один клик из заранее подготовленных шаблонов - модулей групп
- экспорт/импорт групп как отдельного модуля со всеми принадлежащими файлами и моделями. Т.е. сайт с определенным шаблоном - это модуль группы, импортируем группу - получаем готовый сайт/проект с готовым шаблоном и готовыми моделями определённого функционала.
- возможность создания телеграм ботов
- возможность создания многопоточных парсеров
- подключение openai
- создание выделенных безопасных экосистем для правки любых файлов. Экосистема - подборка по принципу корзины из копий целевых файлов. Экосистема с уникальной ссылкой. Смотреть файлы можно, править по паролю. Накидываем в корзину файлов, сбрасываем ссылку теоретическому исполнителю. Берется - даём пароль. Исполнитель безопасно правит файлы в экосистеме(php не обрабатываются, файлы нигде не участвуют, только текст только код), тестируем, принимаем и применяем экосистему - файлы в проекте обновляются на файлы из экосистемы.
- встроенный терминал для администратора системы, для установки пакетов сразу из админ панели
- сохранение всех текущих фильтров после сброса сессии
- настройка/смена каталогов сборок в gulpfile.mjs из админ панели
- настройки смены тем для редактора кода
- инструментарий для работы с изображениями и видео, нарезка тумб, загрузка

