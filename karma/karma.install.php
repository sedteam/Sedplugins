<?php

/* ====================
Seditio - Website engine
Copyright Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/karma/karma.install.php
Version=180
Updated=2025-feb-23
Type=Plugin
Author=Amro
Description=
[END_SED]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

global $db_users;

$cfg['mysqlcollate'] = "utf8_unicode_ci";
$cfg['mysqlcharset'] = "utf8";
$cfg['mysqlengine'] = "MyISAM";

$sqlcheck = sed_sql_query("SHOW TABLES LIKE '" . $cfg['sqldbprefix'] . "karma'");
if (sed_sql_numrows($sqlcheck) != 1) {
	$sqlqr = "CREATE TABLE " . $cfg['sqldbprefix'] . "karma (
	  karma_id int NOT NULL AUTO_INCREMENT,
	  karma_recipient int NOT NULL DEFAULT '0',
	  karma_rater int NOT NULL DEFAULT '0',
	  karma_value char(2) NOT NULL DEFAULT '',
	  karma_text varchar(255) NOT NULL DEFAULT '',
	  karma_fp mediumint NOT NULL DEFAULT '0',
	  PRIMARY KEY (karma_id)
	) ENGINE=" . $cfg['mysqlengine'] . " DEFAULT CHARSET=" . $cfg['mysqlcharset'] . " COLLATE=" . $cfg['mysqlcollate'] . ";";
	$res .= sed_cc($sqlqr) . "<br />";
	$sql = sed_sql_query($sqlqr);
} else {
	$res .= "Table " . $cfg['sqldbprefix'] . "karma is exists<br />";
}

$sql_install = sed_sql_query("SHOW COLUMNS FROM $db_users LIKE 'user_karma'");
if (sed_sql_numrows($sql_install) == 0) {
	$res .= "Adding the 'user_karma' column to table users...<br />";
	$sqlqr = "ALTER TABLE " . $db_users . " ADD user_karma varchar(12) NOT NULL default '';";
	$res .= sed_cc($sqlqr) . "<br />";
	$sql = sed_sql_query($sqlqr);
} else {
	$res .= "Field user_karma is exists<br />";
}
