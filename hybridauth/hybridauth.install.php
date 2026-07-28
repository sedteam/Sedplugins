<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
https://www.seditio.org

[BEGIN_SED]
File=plugins/hybridauth/hybridauth.install.php
Version=186
Updated=2026-jul-28
Type=Plugin
Author=Amro
Description=Install: social_accounts table + legacy user columns
[END_SED]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }

global $cfg;

$db_social_accounts = $cfg['sqldbprefix'] . 'social_accounts';

// --- Create social_accounts table ---

$res .= "Creating table social_accounts...<br />";
$sqlqr = "CREATE TABLE IF NOT EXISTS `" . $db_social_accounts . "` (
    `sa_id`       INT(11)      NOT NULL AUTO_INCREMENT,
    `sa_userid`   INT(11)      NOT NULL DEFAULT 0,
    `sa_provider` VARCHAR(50)  NOT NULL DEFAULT '',
    `sa_uid`      VARCHAR(255) NOT NULL DEFAULT '',
    `sa_email`    VARCHAR(255) NOT NULL DEFAULT '',
    `sa_name`     VARCHAR(255) NOT NULL DEFAULT '',
    `sa_photo`    TEXT,
    `sa_token`    VARCHAR(512) NOT NULL DEFAULT '',
    `sa_created`  INT(11)      NOT NULL DEFAULT 0,
    PRIMARY KEY (`sa_id`),
    UNIQUE KEY `uk_provider_uid` (`sa_provider`, `sa_uid`),
    KEY `idx_userid` (`sa_userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
$res .= sed_cc($sqlqr) . "<br />";
sed_sql_query($sqlqr);
