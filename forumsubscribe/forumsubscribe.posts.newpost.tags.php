<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumsubscribe/forumsubscribe.posts.newpost.tags.php
Version=186
Updated=2026-aug-24
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=forumsubscribe
Part=posts.newpost.tags
Hooks=forums.posts.newpost.tags
File=forumsubscribe.posts.newpost.tags
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if ($usr['id'] > 0 && !empty($q) && !empty($cfg['plugin']['forumsubscribe']['autosubscribe_reply'])) {
	$is_subscribed = sed_forumsubscribe_check($usr['id'], $q);
	if (!$is_subscribed) {
		$sub_checkbox = sed_checkbox('forumsubscribe_newpost', 1, false);

		$t->assign(array(
			"FORUMS_POSTS_NEWPOST_SUBSCRIBE" => $sub_checkbox,
			"FORUMS_POSTS_NEWPOST_SUBSCRIBE_TITLE" => $L['forumsub_quickreply_subscribe']
		));

		$t->parse("MAIN.FORUMS_POSTS_NEWPOST.FORUMSUBSCRIBE");
	}
}
