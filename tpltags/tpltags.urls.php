<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tpltags/tpltags.urls.php
Version=185
Updated=2026-jul-07
Type=Plugin
Author=Seditio Team
Description=Template Tags Dictionary URL rewrite and translation rules
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$mod_urlrewrite_order = 505;

$mod_urlrewrite = array(
	array(
		'cond' => '#^/tpltags(/?)$#',
		'rule' => 'system/core/plug/plug.php?e=tpltags'
	),
	array(
		'cond' => '#^/tpltags/([a-zA-Z0-9_\.\-]+)(/?)$#',
		'rule' => 'system/core/plug/plug.php?e=tpltags&f=$1'
	),
);

$mod_urltrans = array();
$mod_urltrans['plug'] = array(
	array(
		'params' => 'e=tpltags&f=*',
		'rewrite' => 'tpltags/{f}'
	),
	array(
		'params' => 'e=tpltags',
		'rewrite' => 'tpltags'
	),
);
