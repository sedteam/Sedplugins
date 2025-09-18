<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumlock/forumlock.forums.posts.newpost.first.php
Version=180
Updated=2025-sep-13
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=forumlock
Part=main
File=forumlock.forums.posts.newpost.first
Hooks=forums.posts.newpost.first
Tags=
Order=11
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$forumlock_nominpost = $cfg['plugin']['forumlock']['nominpost'];
$forumlock_timehold = $cfg['plugin']['forumlock']['timehold'];

$time_passed_reg = $sys['now_offset'] - $usr['profile']['user_regdate'];
$passed_hours = floor($time_passed_reg / 3600);

require_once("plugins/forumlock/lang/forumlock.".$usr['lang'].".lang.php");

$error_string .= ($passed_hours < $forumlock_timehold) ? $L['forumlock_timehold'] . "<br />" : '';
