<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumsubscribe/forumsubscribe.install.php
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

$mysqlengine = isset($cfg['mysqlengine']) ? $cfg['mysqlengine'] : 'InnoDB';
$mysqlcharset = isset($cfg['mysqlcharset']) ? $cfg['mysqlcharset'] : 'utf8mb4';
$mysqlcollate = isset($cfg['mysqlcollate']) ? $cfg['mysqlcollate'] : 'utf8mb4_unicode_ci';
$prefix = $cfg['sqldbprefix'];
$db_forum_subscribed = $prefix . 'forum_subscribed';

$check = sed_sql_query("SHOW TABLES LIKE '{$prefix}forum_subscribed'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating forum_subscribed table...<br />";
	sed_sql_query("CREATE TABLE {$prefix}forum_subscribed (
		sfs_id int(11) NOT NULL AUTO_INCREMENT,
		sfs_userid int(11) NOT NULL DEFAULT 0,
		sfs_topicid int(11) NOT NULL DEFAULT 0,
		sfs_sectionid int(11) NOT NULL DEFAULT 0,
		sfs_date int(11) NOT NULL DEFAULT 0,
		PRIMARY KEY (sfs_id),
		UNIQUE KEY sfs_user_topic (sfs_userid, sfs_topicid),
		KEY sfs_topicid (sfs_topicid),
		KEY sfs_userid (sfs_userid)
	) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate};");
}

$res .= "Forum Subscribe plugin table installed.<br />";
