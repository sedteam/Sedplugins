<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
https://www.seditio.org

[BEGIN_SED]
File=plugins/hybridauth/hybridauth.install.php
Version=180
Updated=2025-feb-07
Type=Plugin
Author=Amro
Description=
[END_SED]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }

global $db_users;
  
$sql_install = sed_sql_query("SHOW COLUMNS FROM $db_users LIKE 'user_token'");
if (sed_sql_numrows($sql_install) == 0) {	
	$res .= "Adding the 'user_token' column to table users...<br />";
	$sqlqr = "ALTER TABLE " . $db_users ." ADD user_token varchar(255) NOT NULL default '' AFTER user_auth";
	$res .= sed_cc($sqlqr) . "<br />";
	$sql = sed_sql_query($sqlqr);
} else {
    $res .= "Field user_token is exists<br />";
}

$sql_install = sed_sql_query("SHOW COLUMNS FROM $db_users LIKE 'user_oauth_provider'");
if (sed_sql_numrows($sql_install) == 0) {	
	$res .= "Adding the 'user_oauth_provider' column to table users...<br />";
	$sqlqr = "ALTER TABLE " . $db_users ." ADD user_oauth_provider varchar(50) NOT NULL default '' AFTER user_token";
	$res .= sed_cc($sqlqr) . "<br />";
	$sql = sed_sql_query($sqlqr);
} else {
    $res .= "Field user_oauth_provider is exists<br />";
}

$sql_install = sed_sql_query("SHOW COLUMNS FROM $db_users LIKE 'user_oauth_uid'");
if (sed_sql_numrows($sql_install) == 0) {	
	$res .= "Adding the 'user_oauth_uid' column to table users...<br />";
	$sqlqr = "ALTER TABLE " . $db_users ." ADD user_oauth_uid varchar(255) NOT NULL default '' AFTER user_oauth_provider";
	$res .= sed_cc($sqlqr) . "<br />";
	$sql = sed_sql_query($sqlqr);
} else {
    $res .= "Field user_oauth_uid is exists<br />";
}
