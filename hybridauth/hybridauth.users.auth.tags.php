<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/hybridauth/hybridauth.users.auth.tags.php
Version=181
Updated=2026-mar-15
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

$oauth_buttons = '';
if (file_exists(SED_ROOT . '/plugins/hybridauth/config/hybridauth_config.php')) {
	require_once(SED_ROOT . '/plugins/hybridauth/config/hybridauth_config.php');
	$providers = isset($config_hybridauth['providers']) ? $config_hybridauth['providers'] : [];
	$ha_base = rtrim($sys['abs_url'], '/');
	$redirect_param = !empty($redirect) ? '&redirect=' . urlencode($redirect) : '';
	foreach ($providers as $name => $p) {
		if (empty($p['enabled'])) {
			continue;
		}
		$url = $ha_base . '?oauth_provider=' . urlencode($name) . $redirect_param;
		$oauth_buttons .= '<a href="' . sed_cc($url) . '" class="btn-social">' . sed_cc($name) . '</a> ';
	}
	$oauth_buttons = trim($oauth_buttons);
}

$t->assign('USERS_AUTH_OAUTH_BUTTONS', $oauth_buttons);
