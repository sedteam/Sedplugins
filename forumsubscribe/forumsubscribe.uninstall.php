<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumsubscribe/forumsubscribe.uninstall.php
Version=186
Updated=2026-aug-24
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

global $cfg;

if (!isset($res)) {
	$res = '';
}

$prefix = $cfg['sqldbprefix'];
$db_forum_subscribed = $prefix . 'forum_subscribed';

$check = sed_sql_query("SHOW TABLES LIKE '{$prefix}forum_subscribed'");
if (sed_sql_numrows($check) > 0) {
	$res .= "Dropping forum_subscribed table...<br />";
	sed_sql_query("DROP TABLE IF EXISTS {$prefix}forum_subscribed;");
}

$res .= "Forum Subscribe plugin table uninstalled.<br />";
