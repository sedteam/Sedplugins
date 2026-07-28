<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/hybridauth/hybridauth.users.register.tags.php
Version=186
Updated=2026-jul-28
Type=Plugin
Author=Amro
Description=OAuth buttons on registration form
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=hybridauth
Part=register.tags
File=hybridauth.users.register.tags
Hooks=users.register.tags
Tags=users.register.tpl:{USERS_REGISTER_OAUTH_BUTTONS}
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

// Build buttons
$register_oauth_buttons = '';
if (isset($config_hybridauth['providers']) && is_array($config_hybridauth['providers'])) {
	$register_oauth_buttons = sed_hybridauth_buttons($config_hybridauth['providers'], 'hybridauth_or_register_via', '');
}

$t->assign('USERS_REGISTER_OAUTH_BUTTONS', $register_oauth_buttons);
