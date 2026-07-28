<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/hybridauth/hybridauth.common.php
Version=186
Updated=2026-jul-28
Type=Plugin
Author=Amro
Description=Load hybridauth config (common hook)
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=hybridauth
Part=common
File=hybridauth.common
Hooks=common
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

// Load config so it is available for tags hooks
$ha_config_path = SED_ROOT . '/plugins/hybridauth/config/hybridauth_config.php';
if (file_exists($ha_config_path)) {
	require_once($ha_config_path);
}

// Load helper functions
require_once(SED_ROOT . '/plugins/hybridauth/inc/hybridauth.functions.php');
