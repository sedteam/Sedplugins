<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumsubscribe/forumsubscribe.common.php
Version=186
Updated=2026-aug-26
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=forumsubscribe
Part=common
File=forumsubscribe.common
Hooks=common
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $cfg, $db_forum_subscribed;
$db_forum_subscribed = $cfg['sqldbprefix'] . 'forum_subscribed';

require_once(SED_ROOT . '/plugins/forumsubscribe/inc/forumsubscribe.functions.php');

$langfile = sed_langfile('forumsubscribe', 'plugin');
if (!empty($langfile)) {
	include_once($langfile);
}

if (!empty($cfg['plugin']['forumsubscribe']['include_css'])) {
	sed_add_css('plugins/forumsubscribe/css/forumsubscribe.css', true);
}

if (!empty($cfg['ajax'])) {
	sed_add_javascript('plugins/forumsubscribe/js/forumsubscribe.js', true);
}
