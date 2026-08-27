<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumsubscribe/forumsubscribe.urls.php
Version=186
Updated=2026-aug-24
Type=Plugin
Author=Seditio Team
Description=Forum Subscribe URL rewrite (plug.php?e=forumsubscribe)
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$mod_urlrewrite_order = 190;

$mod_urlrewrite = array(
	array(
		'cond' => '#^/forumsubscribe/d/([0-9]+)$#',
		'rule' => 'system/core/plug/plug.php?e=forumsubscribe&d=$1'
	),
	array(
		'cond' => '#^/forumsubscribe$#',
		'rule' => 'system/core/plug/plug.php?e=forumsubscribe'
	),
);

$mod_urltrans = array();
$mod_urltrans['plug'] = array(
	array(
		'params' => 'e=forumsubscribe&d=*',
		'rewrite' => 'forumsubscribe/d/{d}'
	),
	array(
		'params' => 'e=forumsubscribe',
		'rewrite' => 'forumsubscribe'
	),
);
