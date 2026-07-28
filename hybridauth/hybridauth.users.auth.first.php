<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/hybridauth/hybridauth.users.auth.first.php
Version=186
Updated=2026-jul-28
Type=Plugin
Author=Amro
Description=Load CSS and lang before auth page header
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=hybridauth
Part=auth.first
File=hybridauth.users.auth.first
Hooks=users.auth.first
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

// Load CSS before header output
sed_add_css('plugins/hybridauth/css/hybridauth.css', true);

// Load lang
$langfile = sed_langfile('hybridauth', 'plugin');
if (!empty($langfile)) {
	require_once($langfile);
}
