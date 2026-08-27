<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumsubscribe/forumsubscribe.newtopic.tags.php
Version=186
Updated=2026-aug-24
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=forumsubscribe
Part=newtopic.tags
Hooks=forums.newtopic.tags
File=forumsubscribe.newtopic.tags
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if ($usr['id'] > 0) {
	$is_checked = isset($forumsubscribe_newtopic) ? (int)$forumsubscribe_newtopic : (int)$cfg['plugin']['forumsubscribe']['autosubscribe_newtopic'];
	$sub_checkbox = sed_checkbox('forumsubscribe_newtopic', 1, $is_checked);

	$t->assign(array(
		"FORUMS_NEWTOPIC_SUBSCRIBE" => $sub_checkbox,
		"FORUMS_NEWTOPIC_SUBSCRIBE_TITLE" => $L['forumsub_newtopic_subscribe']
	));

	$t->parse("MAIN.FORUMSUBSCRIBE");
}
