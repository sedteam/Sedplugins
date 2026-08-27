<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumsubscribe/forumsubscribe.newtopic.done.php
Version=186
Updated=2026-aug-24
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=forumsubscribe
Part=newtopic.done
Hooks=forums.newtopic.newtopic.done
File=forumsubscribe.newtopic.done
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if ($usr['id'] > 0 && !empty($q)) {
	$forumsubscribe_newtopic = sed_import('forumsubscribe_newtopic', 'P', 'BOL');
	if ($forumsubscribe_newtopic) {
		sed_forumsubscribe_add($usr['id'], $q, $s);
	}
}
