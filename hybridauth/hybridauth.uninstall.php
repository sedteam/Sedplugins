<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
https://www.seditio.org

[BEGIN_SED]
File=plugins/hybridauth/hybridauth.uninstall.php
Version=186
Updated=2026-jul-28
Type=Plugin
Author=Amro
Description=Uninstall: drop social_accounts table
[END_SED]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }

global $cfg;

$db_social_accounts = $cfg['sqldbprefix'] . 'social_accounts';

$res .= "Dropping table social_accounts...<br />";
$sqlqr = "DROP TABLE IF EXISTS `" . $db_social_accounts . "`";
$res .= sed_cc($sqlqr) . "<br />";
sed_sql_query($sqlqr);
