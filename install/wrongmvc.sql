-- MariaDB dump 10.19  Distrib 10.5.18-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: wrongmvc-production
-- ------------------------------------------------------
-- Server version	10.5.18-MariaDB-0+deb11u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `actions`
--

DROP TABLE IF EXISTS `actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `actions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `request` varchar(255) NOT NULL COMMENT 'запрос uri',
  `file` varchar(255) NOT NULL COMMENT 'файл обработчик',
  `groups` text DEFAULT NULL COMMENT 'группы юзеров которым включен доступ',
  `owner_group` int(11) unsigned NOT NULL DEFAULT 1 COMMENT 'группа владелец',
  `note` text DEFAULT NULL COMMENT 'комментарий',
  `act` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 - если включено, 0 - отключено полностью',
  PRIMARY KEY (`id`),
  KEY `request` (`request`) USING BTREE,
  KEY `act` (`act`),
  KEY `owner_group` (`owner_group`),
  KEY `file` (`file`),
  KEY `owner_group_2` (`owner_group`,`groups`(5)),
  FULLTEXT KEY `groups` (`groups`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Действие';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actions`
--

LOCK TABLES `actions` WRITE;
/*!40000 ALTER TABLE `actions` DISABLE KEYS */;
INSERT INTO `actions` VALUES (1,'/api/action/sign-in','/api/action/system/authorization/sign-in.php','[0]',1,'Обработчик формы авторизации',1),(2,'/api/action/sign-up','/api/action/system/authorization/sign-up.php','[0]',1,'Обработчик формы регистрации',1),(3,'/api/action/sign-forgot','/api/action/system/authorization/sign-forgot.php','[0]',1,'Обработчик формы отправки письма для восстановления пароля',1),(4,'/api/action/sign-remind','/api/action/system/authorization/sign-remind.php','[0]',1,'Обработчик формы восстановления пароля',1),(5,'/api/action/exit','/api/action/system/authorization/exit.php','[2,3,4,5,6]',1,'Выход из системы',1),(6,'/api/action/toggle','/api/action/system/global/toggle.php','[2,3,4,5,6]',1,'Включение/отключение моделей',1),(7,'/api/action/add-modal','/api/action/system/modals/add-modal.php','[2,5]',1,'Добавление модели &quot;модальное окно&quot;',1),(8,'/api/action/add-action','/api/action/system/actions/add-action.php','[2,5]',1,'Добавление модели &quot;действие&quot;',1),(9,'/api/action/rm','/api/action/system/global/rm.php','[2,5]',1,'Удаление моделей',1),(10,'/api/action/edit-groups','/api/action/system/groups/edit-groups.php','[2,5]',1,'Изменение групп доступа для моделей',1),(11,'/api/action/edit-owner','/api/action/system/groups/edit-owner.php','[2,5]',1,'Изменение группы владельца для моделей',1),(12,'/api/action/edit-file','/api/action/system/global/edit-file.php','[2,5]',1,'Изменение файла обработчика',1),(13,'/api/action/edit-request','/api/action/system/global/edit-request.php','[2,5]',1,'Изменение запроса request для моделей',1),(14,'/api/action/add-select','/api/action/system/selects/add-select.php','[2,5]',1,'Добавление новой выборки',1),(15,'/api/action/add-page','/api/action/system/pages/add-page.php','[2,5]',1,'Добавление новой страницы',1),(16,'/api/action/edit-template','/api/action/system/templates/edit-template.php','[2,5]',1,'Изменение шаблона для моделей страниц и модальных окон',1),(17,'/api/action/edit-name','/api/action/system/global/edit-name.php','[2,5]',1,'Изменение названия/имени моделей',1),(18,'/api/action/add-template','/api/action/system/templates/add-template.php','[2,5]',1,'Добавление нового шаблона страниц или модальных окон',1),(19,'/api/action/add-group','/api/action/system/groups/add-group.php','[2]',1,'Добавление новой группы из админ панели',1),(20,'/api/action/add-user','/api/action/system/users/add-user.php','[2,5]',1,'Добавление нового пользователя из админ панели',1),(21,'/api/action/edit-weight','/api/action/system/groups/edit-weight.php','[2,5]',1,'Изменение системного веса для групп',1),(22,'/api/action/toggle-log','/api/action/system/groups/toggle-log.php','[2,5]',1,'Включение/отключение записи логов действий для группы',1),(23,'/api/action/from-user','/api/action/system/authorization/from-user.php','[2,5]',1,'Вход под другим пользователем',1),(24,'/api/action/clean-logs','/api/action/system/global/clean-logs.php','[2]',1,'Очистка всех логов',1),(25,'/api/action/settings','/api/action/system/global/settings.php','[2]',1,'Настройки',1),(26,'/api/action/email-confirm','/api/action/system/authorization/email-confirm.php','[2,3,4,5,6]',1,'Обработчик формы подтверждения почты',1),(27,'/api/action/edit-models-limit','/api/action/system/groups/edit-models-limit.php','[2,5]',1,'Изменение лимита моделей для групп',1),(28,'/api/action/toggle-api','/api/action/system/users/toggle-api.php','[2,5]',1,'Включение/отключение api по x-auth-token для пользователя',1),(29,'/api/action/add-crontab','/api/action/system/crontabs/add-crontab.php','[2]',1,'Добавление cron задачи',1),(30,'/api/action/show-next-crontabs','/api/action/system/crontabs/show-next-crontabs.php','[2,3,4,5,6]',1,'Просмотр ближайшего расписания выполнения cron задачи',1),(31,'/api/action/edit-performer','/api/action/system/crontabs/edit-performer.php','[2,5]',1,'Изменение пользователя - исполнителя, от которого выполняется cron задача',1),(32,'/api/action/edit-shedule','/api/action/system/crontabs/edit-shedule.php','[2,5]',1,'Изменение расписания cron задачи',1),(33,'/api/action/edit-headers','/api/action/system/crontabs/edit-headers.php','[2,5]',1,'Изменение заголовков запроса в cron задачах',1),(34,'/api/action/edit-data','/api/action/system/crontabs/edit-data.php','[2,5]',1,'Изменение данных в запросе cron задачи',1),(35,'/api/action/edit-method','/api/action/system/crontabs/edit-method.php','[2,5]',1,'Изменение метода запроса к api из cron задач',1),(36,'/api/action/erase-group','/api/action/system/groups/erase-group.php','[2,5]',1,'Очистка групп от всех моделей',1),(37,'/api/action/export-model','/api/action/system/models/export-model.php','[2,5]',1,'Экспорт моделей',1),(38,'/api/action/import-model','/api/action/system/models/import-model.php','[2]',1,'Импорт моделей',1),(39,'/api/action/oauth-google','/api/action/system/authorization/oauth-google.php','[0]',1,'Oauth авторизация через google',1),(40,'/api/action/oauth-yandex','/api/action/system/authorization/oauth-yandex.php','[0]',1,'Oauth авторизация через яндекс',1),(41,'/api/action/filter','/api/action/system/global/filter.php','[2,5]',1,'Фильтр моделей по активности, группам доступа, группе владельцу',1),(42,'/api/action/edit-note','/api/action/system/global/edit-note.php','[2,3,4,5,6]',1,'Изменение комментария к любой модели',1),(43,'/api/action/edit-code','/api/action/system/global/edit-code.php','[2]',1,'Изменение кода из редактора кода моделей',1),(44,'/api/action/stackjs','/api/action/system/global/stackjs.php','[0,2,3,4,5,6]',1,'Выполнение javascript по стеку, асинхронный ответ на запрос по таймауту из api и установка нового таймаута, если в стеке остаются ещё задачи',1),(45,'/api/action/anycomment','/api/action/system/comments/anycomment.php','[2]',1,'Обновление данных о новых комментариях anycomment.io',1),(46,'/api/action/shedule-execute','/api/action/system/crontabs/shedule-execute.php','[2]',1,'Выполнение cron задачи по кнопке из таблицы задач',1),(47,'/api/action/edit-threads','/api/action/system/crontabs/edit-threads.php','[2]',1,'Настройки потоков для крон задач',1),(49,'/api/action/edit-cli','/api/action/system/crontabs/edit-cli.php','[2]',1,'Изменить cli команду для cron задачи',1),(50,'/api/action/cache-clean','/api/action/system/global/cache-clean.php','[2]',1,'Очистка кеша системы',1),(51,'/api/action/add-from-group','/api/action/system/groups/add-from-group.php','[2]',1,'Массовое добавление в группу пользователей другой группы',1),(52,'/api/action/remove-from-group','/api/action/system/groups/remove-from-group.php','[2]',1,'Массовое исключение из группы пользователей другой группы',1);
/*!40000 ALTER TABLE `actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crontabs`
--

DROP TABLE IF EXISTS `crontabs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crontabs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `threads` text DEFAULT NULL COMMENT 'настройки потоков',
  `cli` text DEFAULT NULL COMMENT 'cli команда',
  `request` varchar(255) NOT NULL COMMENT 'запрос uri',
  `user_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'пользователь от которого выполняется запрос',
  `shedule` varchar(255) NOT NULL DEFAULT '* * * * *' COMMENT 'расписание крон задачи',
  `method` enum('GET','POST','PUT','DELETE','CLI') NOT NULL DEFAULT 'GET' COMMENT 'метод запроса',
  `headers` text NOT NULL COMMENT 'заголовки запроса',
  `data` text NOT NULL COMMENT 'данные запроса',
  `owner_group` int(11) unsigned NOT NULL DEFAULT 1 COMMENT 'группа владелец',
  `run_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'следующее время выполнения',
  `note` text DEFAULT NULL COMMENT 'комментарий',
  `act` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 - если включено, 0 - отключено полностью',
  PRIMARY KEY (`id`),
  KEY `request` (`request`) USING BTREE,
  KEY `owner_group` (`owner_group`),
  KEY `run_at` (`run_at`,`act`),
  KEY `act` (`act`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Крон задача';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crontabs`
--

LOCK TABLES `crontabs` WRITE;
/*!40000 ALTER TABLE `crontabs` DISABLE KEYS */;
INSERT INTO `crontabs` VALUES (1,NULL,NULL,'/api/action/toggle',1,'* * * * *','POST','[]','{\"id\":\"3\",\"table\":\"groups\"}',2,'2023-04-11 19:54:00','Включает/отключает группу модераторы каждую минуту',1),(2,NULL,NULL,'/api/action/clean-logs',1,'0 0 */1 * *','POST','[]','[]',2,'2023-04-12 00:00:00','Периодическая очистка логов',1),(3,NULL,NULL,'/api/action/erase-group',1,'0 0 * * *','POST','[]','{\"table\":\"groups\",\"id\":\"5\"}',2,'2023-04-12 00:00:00','Очистка от всех демо моделей раз в сутки',1),(4,NULL,NULL,'/api/action/anycomment',1,'0 */1 * * *','GET','[]','[]',2,'2023-04-11 20:00:00','Обновление инфы о новых комментариях и запросах в тех поддержку',1),(5,'{\"min\":5,\"max\":5,\"load\":40,\"fixed\":1}','sleep 15','',0,'* * * * *','CLI','null','[]',2,'2023-04-11 19:54:00','Перманентно спит по 15 сек в 5 потоков одновременно, а если нагрузка более 40% даже не начинает спать',1);
/*!40000 ALTER TABLE `crontabs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `owner_group` int(11) unsigned NOT NULL DEFAULT 1 COMMENT 'группа владелец',
  `weight` int(11) NOT NULL DEFAULT 0 COMMENT 'вес, для запросов с одинаковыми url, а также вес прав пользователя по отношению к другим',
  `models_limit` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'лимимт моделей, 0 - безлимит',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT 'путь - только для подстановки url в формах при создании',
  `note` text DEFAULT NULL COMMENT 'комментарий',
  `logs` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'логгирование действий',
  `act` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '1 - группа включена',
  PRIMARY KEY (`id`),
  KEY `owner_group` (`owner_group`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Группа';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'Система',1,2147483647,0,'system','Системная, никому не подчиненная группа с наивысшими правами',1,1),(2,'Администраторы',1,2147483646,0,'admin','Администраторы с полными правами',1,1),(3,'Модераторы',2,1000,0,'moder','Для примера, в логике не участвует',0,0),(4,'Пользователи',2,10,0,'user','Для примера, в логике не участвует',0,1),(5,'Demo',2,10,50,'demo','Демо &quot;администраторы&quot;, доступна полностью админ панель и не критичные действия. Может посмотреть и &quot;пощупать&quot; почти всё.',1,1),(6,'Примеры шаблонов',2,99999,0,'examples','Примеры шаблонов простых сайтов/лендингов',0,0);
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `request` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `date_created` (`date_created`),
  KEY `user_id` (`user_id`),
  KEY `ip` (`ip`),
  KEY `request` (`request`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modals`
--

DROP TABLE IF EXISTS `modals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modals` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `request` varchar(255) NOT NULL COMMENT 'запрос uri',
  `file` varchar(255) NOT NULL COMMENT 'файл обработчкик',
  `groups` text DEFAULT NULL COMMENT 'группы юзеров которым включен доступ',
  `owner_group` int(11) unsigned NOT NULL DEFAULT 1 COMMENT 'группа владелец',
  `note` text DEFAULT NULL COMMENT 'комментарий',
  `act` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 - если включено, 0 - отключено полностью',
  PRIMARY KEY (`id`),
  KEY `request` (`request`) USING BTREE,
  KEY `act` (`act`),
  KEY `file` (`file`),
  KEY `owner_group` (`owner_group`),
  KEY `owner_group_2` (`owner_group`,`groups`(5)),
  FULLTEXT KEY `groups` (`groups`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Модальное окно';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modals`
--

LOCK TABLES `modals` WRITE;
/*!40000 ALTER TABLE `modals` DISABLE KEYS */;
INSERT INTO `modals` VALUES (1,'/api/modal/sign-in','/api/modal/system/authorization/sign-in.php','[0]',1,'Форма входа в систему',1),(2,'/api/modal/sign-up','/api/modal/system/authorization/sign-up.php','[0]',1,'Форма регистрации',1),(3,'/api/modal/sign-forgot','/api/modal/system/authorization/sign-forgot.php','[0]',1,'Форма запроса напоминания пароля',1),(4,'/api/modal/hcaptcha','/api/modal/system/authorization/hcaptcha.php','[0,2,3,4,5,6]',1,'Окно с каптчей',1),(5,'/api/modal/sign-remind','/api/modal/system/authorization/sign-remind.php','[0]',1,'Окно восстановления пароля',1),(6,'/api/modal/confirm','/api/modal/system/global/confirm.php','[2,3,4,5,6]',1,'Подтверждение любых действий',1),(7,'/api/modal/error','/api/modal/system/global/error.php','[0,2,3,4,5,6]',1,'Красное окно для показа ошибки(лучше используйте всплывающие сообщения-тосты)',1),(8,'/api/modal/add-modal','/api/modal/system/modals/add-modal.php','[2,5]',1,'Добавление модели &quot;модальное окно&quot;',1),(9,'/api/modal/add-action','/api/modal/system/actions/add-action.php','[2,5]',1,'Добавление модели &quot;действие&quot;',1),(10,'/api/modal/edit-groups','/api/modal/system/groups/edit-groups.php','[2,5]',1,'Изменение групп доступа для моделей',1),(11,'/api/modal/edit-owner','/api/modal/system/groups/edit-owner.php','[2,5]',1,'Изменение владельца для моделей',1),(12,'/api/modal/edit-file','/api/modal/system/global/edit-file.php','[2,5]',1,'Изменение файла обработчика моделей',1),(13,'/api/modal/edit-request','/api/modal/system/global/edit-request.php','[2,5]',1,'Изменение request запроса для моделей',1),(14,'/api/modal/add-select','/api/modal/system/selects/add-select.php','[2,5]',1,'Добавление модели &quot;выборка&quot;',1),(15,'/api/modal/add-page','/api/modal/system/pages/add-page.php','[2,5]',1,'Добавление модели &quot;страница&quot;',1),(16,'/api/modal/edit-template','/api/modal/system/templates/edit-template.php','[2,5]',1,'Изменения шаблона для страницы',1),(17,'/api/modal/edit-name','/api/modal/system/global/edit-name.php','[2,5]',1,'Изменение имени/названия для модели',1),(18,'/api/modal/add-template','/api/modal/system/templates/add-template.php','[2,5]',1,'Добавление модели &quot;шаблон&quot; страницы или модального окна',1),(19,'/api/modal/add-group','/api/modal/system/groups/add-group.php','[2,5]',1,'Добавление модели &quot;группа&quot;',1),(20,'/api/modal/add-user','/api/modal/system/users/add-user.php','[2,5]',1,'Добавление модели &quot;пользователь&quot;',1),(21,'/api/modal/edit-weight','/api/modal/system/groups/edit-weight.php','[2,5]',1,'Изменение системного веса для групп',1),(22,'/api/modal/settings','/api/modal/system/global/settings.php','[2,5]',1,'Окно настроек системы',1),(23,'/api/modal/email-confirm','/api/modal/system/authorization/email-confirm.php','[2,3,4,5,6]',1,'Окно отправки email для подтверждения почты',1),(24,'/api/modal/edit-models-limit','/api/modal/system/groups/edit-models-limit.php','[2,5]',1,'Изменение лимитов моделей для групп',1),(25,'/api/modal/construct-action','/api/modal/system/actions/construct-action.php','[2,5]',1,'Конструктор триггеров действий',1),(26,'/api/modal/construct-modal','/api/modal/system/modals/construct-modal.php','[2,5]',1,'Конструктор триггеров модальных окон',1),(27,'/api/modal/add-crontab','/api/modal/system/crontabs/add-crontab.php','[2,5]',1,'Добавление новой cron задачи',1),(28,'/api/modal/edit-performer','/api/modal/system/crontabs/edit-performer.php','[2,5]',1,'Изменение исполнителя cron задачи',1),(29,'/api/modal/edit-shedule','/api/modal/system/crontabs/edit-shedule.php','[2,5]',1,'Изменение расписания для cron задач',1),(30,'/api/modal/edit-headers','/api/modal/system/crontabs/edit-headers.php','[2,5]',1,'Изменение заголовков для cron задач',1),(31,'/api/modal/edit-data','/api/modal/system/crontabs/edit-data.php','[2,5]',1,'Изменение данных запроса для cron задач',1),(32,'/api/modal/edit-method','/api/modal/system/crontabs/edit-method.php','[2,5]',1,'Изменение метода запроса для cron задач',1),(33,'/api/modal/import-model','/api/modal/system/models/import-model.php','[2,5]',1,'Импорт моделей',1),(34,'/api/modal/hide-table-cols','/api/modal/system/global/hide-table-cols.php','[2,3,4,5,6]',1,'Настройки видимости колонок в таблицах',1),(35,'/api/modal/filter','/api/modal/system/global/filter.php','[2,5]',1,'Фильтр таблиц по активности, группам доступа, группе владельцу',1),(36,'/api/modal/edit-note','/api/modal/system/global/edit-note.php','[2,3,4,5,6]',1,'Изменение комментария для модели',1),(37,'/api/modal/edit-code','/api/modal/system/global/edit-code.php','[0,2,3,4,5,6]',1,'Редактор кода моделей, группы доступа все - для Demo админки, лучше убрать на рабочем проекте',1),(38,'/api/modal/comments','/api/modal/system/comments/comments.php','[0,2,3,4,5,6]',1,'Модальное окно с подгрузкой блока комментариев со страницы комментов',1),(39,'/api/modal/donates','/api/modal/system/global/donates.php','[0,2,3,4,5,6]',1,'Модалка поддержать проект',1),(40,'/api/modal/todo','/api/modal/system/global/todo.php','[0,2,3,4,5,6]',1,'Модалка с todo инфой',1),(41,'/api/modal/install','/api/modal/system/global/install.php','[0,2,3,4,5,6]',1,'Модалка с командами установки',1),(42,'/api/modal/edit-threads','/api/modal/system/crontabs/edit-threads.php','[2,5]',1,'Настройки потоков для крон задач',1),(43,'/api/modal/edit-cli','/api/modal/system/crontabs/edit-cli.php','[2,5]',1,'Окно изменения cli команды для cron задачи',1),(44,'/api/modal/add-from-group','/api/modal/system/groups/add-from-group.php','[2,5]',1,'Массовое добавление в группу пользователей другой группы',1),(45,'/api/modal/remove-from-group','/api/modal/system/groups/remove-from-group.php','[2,5]',1,'Массовое исключение из группы пользователей другой группы',1),(46,'/api/modal/view-page','/api/modal/system/pages/view-page.php','[0,2,3,4,5,6]',1,'Просмотр страниц в модальном окне в полноэкранном фрейме',1),(47,'/api/modal/view-page-mobile','/api/modal/system/pages/view-page-mobile.php','[0,2,3,4,5,6]',1,'Просмотр страниц в модальном окне в мобильном фрейме',1);
/*!40000 ALTER TABLE `modals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `request` varchar(255) NOT NULL COMMENT 'запрос uri',
  `file` varchar(255) NOT NULL COMMENT 'файл обработчик',
  `groups` text DEFAULT NULL COMMENT 'группы юзеров которым включен доступ',
  `owner_group` int(11) unsigned NOT NULL DEFAULT 1 COMMENT 'группа владелец',
  `template_id` int(11) unsigned NOT NULL DEFAULT 1 COMMENT 'id шаблона',
  `name` varchar(255) NOT NULL DEFAULT '''''' COMMENT 'имя, оно же тайтл страницы',
  `note` text DEFAULT NULL COMMENT 'комментарий',
  `act` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 - если включено, 0 - отключено полностью',
  PRIMARY KEY (`id`),
  KEY `request` (`request`) USING BTREE,
  KEY `owner_group` (`owner_group`),
  KEY `file` (`file`),
  KEY `name` (`name`),
  KEY `owner_group_2` (`owner_group`,`groups`(5)),
  FULLTEXT KEY `groups` (`groups`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Страница';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'/','/page/system/main-guest.php','[0,2,3,4,5,6]',1,1,'WRONG MVC - система для создания систем','Главная для не авторизованных',1),(2,'/system','/page/system/main.php','[2,5]',1,2,'Панель администратора','Главная панели администратора',1),(3,'/system/modals','/page/system/modals.php','[2,5]',1,2,'Модальные окна','Модальные окна',1),(4,'/system/actions','/page/system/actions.php','[2,5]',1,2,'Действия','Действия',1),(5,'/system/selects','/page/system/selects.php','[2,5]',1,2,'Выборки','Выборки',1),(6,'/system/pages','/page/system/pages.php','[2,5]',1,2,'Страницы','Страницы',1),(7,'/system/templates','/page/system/templates.php','[2,5]',1,2,'Шаблоны','Шаблоны страниц и модальных окон',1),(8,'/system/users','/page/system/users.php','[2,5]',1,2,'Пользователи','Пользователи',1),(9,'/system/groups','/page/system/groups.php','[2,5]',1,2,'Группы','Группы',1),(10,'/system/crontabs','/page/system/crontabs.php','[2,5]',1,2,'Задачи','Задачи',1),(11,'/system/logs','/page/system/logs.php','[2,5]',1,2,'Логи действий','Логи действий',1),(12,'/disabled','/page/system/disabled.php','[0,2,3,4,5,6]',1,3,'Пользователь отключен','Страница пользователь отключен, код 403',1),(13,'/documentation','/page/system/main.php','[0,2,3,4,5,6]',1,1,'Документация, установка wrong-mvc','Страница документации',1),(14,'/forbidden','/page/system/forbidden.php','[0,2,3,4,5,6]',1,3,'Доступ запрещён!','Страница - доступ запрещен, код 403. Если request существует, но недостаточно прав',1),(15,'/enter','/page/system/enter.php','[0]',1,1,'Вход в систему','Страница с автоматическим вызовом формы входа',1),(16,'/comments','/page/system/comments.php','[0,2,3,4,5,6]',1,1,'Отзывы о системе Wrong MVC','Страница с отзывами и комментариями, плагин',1),(17,'/examples/tivo/index','/page/examples/tivo/main.php','[0,2,3,4,5,6]',6,8,'Tivo - Landing Page','',1),(18,'/examples/tivo/article','/page/examples/tivo/article-details.php','[0,2,3,4,5,6]',6,8,'Tivo - Longer Project Title Should Definitely Go Here','',1),(19,'/examples/tivo/terms','/page/examples/tivo/terms-conditions.php','[0,2,3,4,5,6]',6,8,'Tivo - Terms &amp; Conditions','',1),(20,'/examples/tivo/privacy','/page/examples/tivo/privacy-policy.php','[0,2,3,4,5,6]',6,8,'Tivo - Privacy Policy','',1),(21,'/examples/tivo/log-in','/page/examples/tivo/log-in.php','[0,2,3,4,5,6]',6,8,'Tivo - Log In','',1),(22,'/examples/tivo/sign-up','/page/examples/tivo/sign-up.php','[0,2,3,4,5,6]',6,8,'Tivo - Sign Up','',1),(23,'/examples/seogram/index','/page/examples/seogram/main.php','[0,2,3,4,5,6]',6,9,'Seogram - The number #1 SEO Service Company','',1),(24,'/examples/seogram/about','/page/examples/seogram/about.php','[0,2,3,4,5,6]',6,9,'Seogram - About Us','',1),(25,'/examples/seogram/service','/page/examples/seogram/service.php','[0,2,3,4,5,6]',6,9,'Seogram - Our Services','',1),(26,'/examples/seogram/blog','/page/examples/seogram/blog.php','[0,2,3,4,5,6]',6,9,'Seogram - Blog','',1),(27,'/examples/seogram/contact','/page/examples/seogram/contact.php','[0,2,3,4,5,6]',6,9,'Seogram - Contact Us','',1),(28,'/examples/seogram/blog-details','/page/examples/seogram/blog-details.php','[0,2,3,4,5,6]',6,9,'Seogram - Blog Details','',1),(29,'/examples/delfood/index','/page/examples/delfood/main.php','[0,2,3,4,5,6]',6,10,'Delfood','',1),(30,'/examples/delfood/about','/page/examples/delfood/about.php','[0,2,3,4,5,6]',6,10,'Delfood - About Us','',1),(31,'/examples/delfood/blog','/page/examples/delfood/blog.php','[0,2,3,4,5,6]',6,10,'Delfood - Latest Blog','',1),(32,'/examples/delfood/testimonial','/page/examples/delfood/testimonial.php','[0,2,3,4,5,6]',6,10,'Delfood - Testimonial','',1),(33,'/examples/marshmallow/index','/page/examples/marshmallow/main.php','[0,2,3,4,5,6]',6,11,'Marshmallow - Landing Page','',1),(34,'/examples/orthoc/index','/page/examples/orthoc/main.php','[0,2,3,4,5,6]',6,12,'Orthoc','',1),(35,'/examples/orthoc/about','/page/examples/orthoc/about.php','[0,2,3,4,5,6]',6,12,'ABOUT US','',1),(36,'/examples/orthoc/departments','/page/examples/orthoc/departments.php','[0,2,3,4,5,6]',6,12,'OUR DEPARTMENTS','',1),(37,'/examples/orthoc/doctors','/page/examples/orthoc/doctors.php','[0,2,3,4,5,6]',6,12,'OUR DOCTORS','',1),(38,'/examples/orthoc/contact','/page/examples/orthoc/contact.php','[0,2,3,4,5,6]',6,12,'GET IN TOUCH','',1),(39,'/examples/eclipse-master/index','/page/examples/eclipse-master/main.php','[0,2,3,4,5,6]',6,13,'Eclipse Education','',1),(40,'/examples/eclipse-master/about','/page/examples/eclipse-master/about.php','[0,2,3,4,5,6]',6,13,'About Us','',1),(41,'/examples/eclipse-master/courses','/page/examples/eclipse-master/courses.php','[0,2,3,4,5,6]',6,13,'Courses','',1),(42,'/examples/eclipse-master/elements','/page/examples/eclipse-master/elements.php','[0,2,3,4,5,6]',6,13,'Elements','',1),(43,'/examples/eclipse-master/course-details','/page/examples/eclipse-master/course-details.php','[0,2,3,4,5,6]',6,13,'Course Details','',1),(44,'/examples/eclipse-master/blog-home','/page/examples/eclipse-master/blog-home.php','[0,2,3,4,5,6]',6,13,'Blog Home','',1),(45,'/examples/eclipse-master/blog-single','/page/examples/eclipse-master/blog-single.php','[0,2,3,4,5,6]',6,13,'Single Blog','',1),(46,'/examples/eclipse-master/contacts','/page/examples/eclipse-master/contacts.php','[0,2,3,4,5,6]',6,13,'Contacts','',1),(47,'/examples/aesthetic-master/index','/page/examples/aesthetic-master/main.php','[0,2,3,4,5,6]',6,14,'AESTHETIC','',1),(48,'/examples/aesthetic-master/about','/page/examples/aesthetic-master/about.php','[0,2,3,4,5,6]',6,14,'ABOUT US','',1),(49,'/examples/aesthetic-master/services','/page/examples/aesthetic-master/services.php','[0,2,3,4,5,6]',6,14,'OUR SERVICES','',1),(50,'/examples/aesthetic-master/pricing','/page/examples/aesthetic-master/pricing.php','[0,2,3,4,5,6]',6,14,'OUR PRICES','',1),(51,'/examples/aesthetic-master/doctor','/page/examples/aesthetic-master/doctor.php','[0,2,3,4,5,6]',6,14,'OUR TEAM','',1),(52,'/examples/aesthetic-master/blog-details','/page/examples/aesthetic-master/blog-details.php','[0,2,3,4,5,6]',6,14,'NEWS DETAILS','',1),(53,'/examples/aesthetic-master/blog','/page/examples/aesthetic-master/blog.php','[0,2,3,4,5,6]',6,14,'NEWS','',1),(54,'/examples/aesthetic-master/contact','/page/examples/aesthetic-master/contact.php','[0,2,3,4,5,6]',6,14,'CONTACT US','',1);
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `selects`
--

DROP TABLE IF EXISTS `selects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `selects` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `request` varchar(255) NOT NULL COMMENT 'запрос uri',
  `file` varchar(255) NOT NULL COMMENT 'файл обработчик',
  `groups` text DEFAULT NULL COMMENT 'группы юзеров которым включен доступ',
  `owner_group` int(11) unsigned NOT NULL DEFAULT 1 COMMENT 'группа владелец',
  `note` text DEFAULT NULL COMMENT 'комментарий',
  `act` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 - если включено, 0 - отключено полностью',
  PRIMARY KEY (`id`),
  KEY `request` (`request`) USING BTREE,
  KEY `owner_group` (`owner_group`),
  KEY `file` (`file`),
  KEY `owner_group_2` (`owner_group`,`groups`(5)),
  FULLTEXT KEY `groups` (`groups`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Выборка';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `selects`
--

LOCK TABLES `selects` WRITE;
/*!40000 ALTER TABLE `selects` DISABLE KEYS */;
INSERT INTO `selects` VALUES (1,'/api/select/modals','/api/select/system/modals.php','[2,5]',1,'Модальные окна',1),(2,'/api/select/actions','/api/select/system/actions.php','[2,5]',1,'Действия',1),(3,'/api/select/selects','/api/select/system/selects.php','[2,5]',1,'Выборки',1),(4,'/api/select/pages','/api/select/system/pages.php','[2,5]',1,'Страницы',1),(5,'/api/select/templates','/api/select/system/templates.php','[2,5]',1,'Шаблоны',1),(6,'/api/select/groups','/api/select/system/groups.php','[2,5]',1,'Группы',1),(7,'/api/select/users','/api/select/system/users.php','[2,5]',1,'Пользователи',1),(8,'/api/select/logs','/api/select/system/logs.php','[2,5]',1,'Логи действий',1),(9,'/api/select/crontabs','/api/select/system/crontabs.php','[2,5]',1,'Cron задачи',1),(10,'/api/select/cache-size','/api/select/system/cache-size.php','[2,5]',1,'Размер каталога с кешем',1);
/*!40000 ALTER TABLE `selects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Настройки';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'HCAPTCHA_SITEKEY','','Публичный hcaptcha ключ'),(2,'HCAPTCHA_SECRET','','Секретный hcapctha ключ'),(3,'OWNER_GROUP_USERS','2','Группа владелец новых пользователей по умолчанию'),(4,'GROUPS_USERS','[5]','Группы нового пользователя по умолчанию'),(5,'RETURN_TO_REQUEST','0','Запоминать url и возвращать на него авторизованных пользователей'),(6,'HIDE_OUT_LINKS','1','Скрывать на страницах ссылки на недоступные модели страниц'),(7,'EMAIL_CONFIRMATION','0','Включить подтверждение почты при регистрации'),(8,'API','1','Включить API с авторизацией по заголовкам X-Auth-Token на уровне системы'),(9,'USER_ACT','1','Включить аккаунт для новых пользователей по умолчанию'),(10,'USER_API','1','Включить API по заголовкам X-Auth-Token для новых пользователей по умолчанию'),(11,'HIDE_OUT_ACTIONS_MODALS','1','Скрывать на страницах триггеры вызова недоступных модальных окон и действий'),(12,'CRON_ACT','1','Включить выполнение cron задач'),(13,'SUBORDINATE_MODELS','0','Отображать только модели подчиненных групп'),(14,'EMAIL','0','Включить отправку писем с сервера'),(15,'GOOGLE_OAUTH_CLIENT_ID','','Публичный google oauth ключ'),(16,'GOOGLE_OAUTH_CLIENT_SECRET','','Секретный google oauth ключ'),(17,'YANDEX_OAUTH_CLIENT_ID','','Публичный яндекс oauth ключ'),(18,'YANDEX_OAUTH_CLIENT_SECRET','','Секретный яндекс oauth ключ'),(19,'ANYCOMMENT_SECRET','','API токен от anycomment.io'),(20,'SYSTEM_CLOSED','0','Закрыть систему для всех кроме группы \"Система\" (403 - доступ запрещён)'),(21,'SMTP','0','Отправлять письма через SMTP (используется библиотека phpmailer)'),(22,'SMTP_HOST','smtp.mail.ru','SMTP хост сервера'),(23,'MAIL_USERNAME','support@wrong-mvc.com','Почта отправителя писем'),(24,'SMTP_PASSWORD','','SMTP пароль'),(25,'SMTP_PORT','465','SMTP порт'),(26,'CRON_CLI','1','Поддержка CLI команд в CRON задачах'),(27,'DEVELOPER_MODE','0','Включить режим разработчика(отключает защиту на изменение системных моделей)');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `templates`
--

DROP TABLE IF EXISTS `templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `templates` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file` varchar(255) NOT NULL DEFAULT '' COMMENT 'файл шаблона из каталога template_full',
  `groups` text DEFAULT NULL COMMENT 'группы юзеров которым включен доступ',
  `owner_group` int(11) unsigned NOT NULL DEFAULT 1 COMMENT 'группа владелец',
  `name` varchar(255) NOT NULL COMMENT 'название (для таблицы в админке)',
  `type` enum('page','modal') NOT NULL DEFAULT 'page',
  `note` text DEFAULT NULL COMMENT 'комментарий',
  `act` tinyint(1) unsigned NOT NULL DEFAULT 1 COMMENT '1 - шаблон включен, в логике пока не участвует, все шаблоны включены по умолчанию',
  PRIMARY KEY (`id`),
  KEY `owner_group` (`owner_group`),
  KEY `file` (`file`),
  KEY `name` (`name`),
  KEY `owner_group_2` (`owner_group`,`groups`(5)),
  FULLTEXT KEY `groups` (`groups`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Шаблон страницы';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `templates`
--

LOCK TABLES `templates` WRITE;
/*!40000 ALTER TABLE `templates` DISABLE KEYS */;
INSERT INTO `templates` VALUES (1,'/../templates/page/system/quest.php','[0,2,3,4,5,6]',1,'Главная / гости','page','Шаблон главной для не авторизованных',1),(2,'/../templates/page/system/system-admin.php','[2,5]',1,'Главная / администратор системы','page','Шаблон главной для администраторов',1),(3,'/../templates/page/system/empty.php','[0,2,3,4,5,6]',1,'Пустая страница','page','Шаблон пустой страницы',1),(4,'/../templates/modal/system/empty.php','[2,3,4,5,6]',1,'Пустое окно','modal','Шаблон пустого окна',1),(5,'/../templates/modal/system/form.php','[2,3,4,5,6]',1,'Окно с формой','modal','Шаблон окна с формой',1),(6,'/../templates/modal/system/buttons.php','[2,3,4,5,6]',1,'Окно с кнопками','modal','Шаблон окна с кнопками',1),(7,'/../templates/modal/system/full.php','[2,3,4,5,6]',1,'Окно на весь экран','modal','Шаблон окна на весь экран',1),(8,'/../templates/page/examples/tivo.php','[2,3,4,5,6]',6,'tivo-1.0.0','page','tivo-1.0.0',1),(9,'/../templates/page/examples/seogram.php','[2,3,4,5,6]',6,'seogram-1.0.0','page','seogram-1.0.0',1),(10,'/../templates/page/examples/delfood.php','[2,3,4,5,6]',6,'delfood-1.0.0','page','delfood-1.0.0',1),(11,'/../templates/page/examples/marshmallow.php','[2,3,4,5,6]',6,'marshmallow','page','marshmallow',1),(12,'/../templates/page/examples/orthoc.php','[2,3,4,5,6]',6,'orthoc','page','orthoc',1),(13,'/../templates/page/examples/eclipse-master.php','[2,3,4,5,6]',6,'eclipse-master','page','eclipse-master',1),(14,'/../templates/page/examples/aesthetic-master.php','[2,3,4,5,6]',6,'aesthetic-master','page','aesthetic-master',1);
/*!40000 ALTER TABLE `templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `groups` text DEFAULT NULL COMMENT 'группы в которых состоит пользователь',
  `owner_group` int(11) unsigned NOT NULL DEFAULT 1 COMMENT 'группа владелец',
  `email` varchar(255) NOT NULL,
  `md5password` varchar(32) NOT NULL,
  `date_online` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'крайнее время онлайн',
  `date_created` datetime DEFAULT current_timestamp() COMMENT 'время создания аккаунта',
  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT 'крайний ip онлайна',
  `request` varchar(255) NOT NULL DEFAULT '' COMMENT 'крайний request',
  `email_confirmed` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'подтвержден ли email',
  `x_auth_token` varchar(255) DEFAULT NULL COMMENT 'X-Auth-Token токен для апи',
  `note` text DEFAULT NULL COMMENT 'комментарий',
  `api_act` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'включение и отключение апи на уровне пользователя',
  `act` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 - если включено, 0 - отключено полностью',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `x_auth_token` (`x_auth_token`),
  KEY `email_md5password` (`email`,`md5password`) USING BTREE,
  KEY `owner_group` (`owner_group`),
  KEY `id` (`id`,`act`),
  KEY `api_act` (`api_act`,`x_auth_token`),
  KEY `owner_group_2` (`owner_group`,`groups`(5)),
  FULLTEXT KEY `groups` (`groups`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Пользователь';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-04-11 19:54:13
