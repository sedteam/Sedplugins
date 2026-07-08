<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tpltags/tpltags.uninstall.php
Version=185
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

global $cfg;

$prefix = $cfg['sqldbprefix'];

if (!empty($sed_uninstall_drop_tables)) {
	sed_sql_query("DROP TABLE IF EXISTS {$prefix}tpltags");
	$res .= "Table {$prefix}tpltags dropped.<br />";
} else {
	$res .= "tpltags plugin uninstalled. Table {$prefix}tpltags preserved.<br />";
}
