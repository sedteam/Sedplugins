<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumsubscribe/forumsubscribe.topic.delete.php
Version=186
Updated=2026-aug-24
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=forumsubscribe
Part=topic.delete
Hooks=forums.posts.topic.delete.done
File=forumsubscribe.topic.delete
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (!empty($q)) {
	sed_forumsubscribe_delete_by_topic($q);
}
