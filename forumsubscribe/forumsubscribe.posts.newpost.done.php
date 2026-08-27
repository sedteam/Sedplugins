<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumsubscribe/forumsubscribe.posts.newpost.done.php
Version=186
Updated=2026-aug-24
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=forumsubscribe
Part=posts.newpost.done
Hooks=forums.posts.newpost.done
File=forumsubscribe.posts.newpost.done
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (!empty($q) && !empty($s)) {
	// If user checked subscription checkbox in quick reply form
	if ($usr['id'] > 0) {
		$forumsubscribe_newpost = sed_import('forumsubscribe_newpost', 'P', 'BOL');
		if ($forumsubscribe_newpost) {
			sed_forumsubscribe_add($usr['id'], $q, $s);
		}
	}

	// Notify all subscribers except the author of this post
	sed_forumsubscribe_notify($q, $s, $usr['id'], $usr['name'], $newmsg);
}
