<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/hybridauth/hybridauth.users.auth.tags.php
Version=186
Updated=2026-jul-28
Type=Plugin
Author=Amro
Description=OAuth buttons on login form
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=hybridauth
Part=auth.tags
File=hybridauth.users.auth.tags
Hooks=users.auth.tags
Tags=users.auth.tpl:{USERS_AUTH_OAUTH_BUTTONS}
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

// Build buttons
$oauth_buttons = '';
if (isset($config_hybridauth['providers']) && is_array($config_hybridauth['providers'])) {
	$redir = !empty($redirect) ? $redirect : '';
	$oauth_buttons = sed_hybridauth_buttons($config_hybridauth['providers'], 'hybridauth_or_login_via', $redir);
}

$t->assign('USERS_AUTH_OAUTH_BUTTONS', $oauth_buttons);
