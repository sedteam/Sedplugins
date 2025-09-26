<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumlock/forumlock.forums.newtopic.newtopic.first.php
Version=180
Updated=2025-sep-25
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=forumlock
Part=main
File=forumlock.forums.newtopic.newtopic.first
Hooks=forums.newtopic.newtopic.first
Tags=
Order=11
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$forumlock_nominpost = $cfg['plugin']['forumlock']['nominpost'];
$forumlock_timehold = $cfg['plugin']['forumlock']['timehold'];
$forumlock_maxtopicksday = $cfg['plugin']['forumlock']['maxtopicksday'];

$time_passed_reg = $sys['now_offset'] - $usr['profile']['user_regdate'];
$time_passed_creation = $sys['now_offset'] - 24 * 3600;
$passed_hours = floor($time_passed_reg / 3600);

require_once("plugins/forumlock/lang/forumlock." . $usr['lang'] . ".lang.php");

$error_string .= ($passed_hours < $forumlock_timehold) ? $L['forumlock_timehold'] . "<br />" : '';
$error_string .= ($usr['profile']['user_postcount'] < $forumlock_nominpost) ? $L['forumlock_nominpost'] . "<br />" : '';

$sqltmp = sed_sql_query("SELECT COUNT(*) FROM $db_forum_topics WHERE ft_firstposterid = " . (int)$usr['id'] . " AND ft_creationdate > " . $time_passed_creation);
if (sed_sql_numrows($sqltmp) > 0) {
	$creation_topicks_day = sed_sql_result($sqltmp, 0, "COUNT(*)");
	$error_string .= ($creation_topicks_day >= $forumlock_maxtopicksday) ? $L['forumlock_maxtopicksday'] . "<br />" : '';
}
