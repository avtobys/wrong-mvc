<?php

/**
 * @file
 * @brief обработчик сохранения системных настроек
 */

isset($user) or require $_SERVER['DOCUMENT_ROOT'] . '/page/404.php';

header("Content-type: application/json");

array_walk_recursive($_POST, function (&$item) {
    $item = trim(htmlspecialchars($item, ENT_QUOTES));
});


$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'HCAPTCHA_SITEKEY'");
$sth->bindValue(':value', $_POST['HCAPTCHA_SITEKEY']);
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'HCAPTCHA_SECRET'");
$sth->bindValue(':value', $_POST['HCAPTCHA_SECRET']);
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'GOOGLE_OAUTH_CLIENT_ID'");
$sth->bindValue(':value', $_POST['GOOGLE_OAUTH_CLIENT_ID']);
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'GOOGLE_OAUTH_CLIENT_SECRET'");
$sth->bindValue(':value', $_POST['GOOGLE_OAUTH_CLIENT_SECRET']);
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'YANDEX_OAUTH_CLIENT_ID'");
$sth->bindValue(':value', $_POST['YANDEX_OAUTH_CLIENT_ID']);
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'YANDEX_OAUTH_CLIENT_SECRET'");
$sth->bindValue(':value', $_POST['YANDEX_OAUTH_CLIENT_SECRET']);
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'ANYCOMMENT_SECRET'");
$sth->bindValue(':value', $_POST['ANYCOMMENT_SECRET']);
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'OWNER_GROUP_USERS'");
$sth->bindValue(':value', $_POST['OWNER_GROUP_USERS']);
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'GROUPS_USERS'");
$sth->bindValue(':value', json_encode(array_map('intval', array_keys($_POST['GROUPS_USERS']))));
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'RETURN_TO_REQUEST'");
$sth->bindValue(':value', intval(isset($_POST['RETURN_TO_REQUEST'])));
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'HIDE_OUT_LINKS'");
$sth->bindValue(':value', intval(isset($_POST['HIDE_OUT_LINKS'])));
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'HIDE_OUT_ACTIONS_MODALS'");
$sth->bindValue(':value', intval(isset($_POST['HIDE_OUT_ACTIONS_MODALS'])));
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'EMAIL_CONFIRMATION'");
$sth->bindValue(':value', intval(isset($_POST['EMAIL_CONFIRMATION'])));
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'EMAIL'");
$sth->bindValue(':value', intval(isset($_POST['EMAIL'])));
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'API'");
$sth->bindValue(':value', intval(isset($_POST['API'])));
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'USER_API'");
$sth->bindValue(':value', intval(isset($_POST['USER_API'])));
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'USER_ACT'");
$sth->bindValue(':value', intval(isset($_POST['USER_ACT'])));
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'CRON_ACT'");
$sth->bindValue(':value', intval(isset($_POST['CRON_ACT'])));
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'CRON_CLI'");
$sth->bindValue(':value', intval(isset($_POST['CRON_CLI'])));
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'SUBORDINATE_MODELS'");
$sth->bindValue(':value', intval(isset($_POST['SUBORDINATE_MODELS'])));
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'SYSTEM_CLOSED'");
$sth->bindValue(':value', intval(isset($_POST['SYSTEM_CLOSED'])));
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'DEVELOPER_MODE'");
$sth->bindValue(':value', intval(isset($_POST['DEVELOPER_MODE'])));
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'MAIL_USERNAME'");
$sth->bindValue(':value', $_POST['MAIL_USERNAME']);
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'SMTP'");
$sth->bindValue(':value', intval(isset($_POST['SMTP'])));
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'SMTP_HOST'");
$sth->bindValue(':value', $_POST['SMTP_HOST']);
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'SMTP_PORT'");
$sth->bindValue(':value', $_POST['SMTP_PORT']);
$sth->execute();

$sth = $dbh->prepare("UPDATE `settings` SET `value` = :value WHERE `name` = 'SMTP_PASSWORD'");
$sth->bindValue(':value', $_POST['SMTP_PASSWORD']);
$sth->execute();

$mem = new Wrong\Memory\Cache('env-cron');
$mem->delete('env-cron');

exit(json_encode(['result' => 'ok', 'message' => 'Настройки успешно сохранены!']));

