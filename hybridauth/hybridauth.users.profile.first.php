<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/hybridauth/hybridauth.users.profile.first.php
Version=181
Updated=2026-mar-15
Type=Plugin
Author=Amro
Description=OAuth unlink in profile
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=hybridauth
Part=profile.first
File=hybridauth.users.profile.first
Hooks=profile.first
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$a = sed_import('a', 'G', 'ALP');

if ($usr['id'] < 1) {
	return;
}

if (file_exists(SED_ROOT . '/plugins/hybridauth/config/hybridauth_config.php')) {
	require_once(SED_ROOT . '/plugins/hybridauth/config/hybridauth_config.php');
}

if ($a === 'oauthunlink') {
	sed_check_xg();
	$sql = sed_sql_query("SELECT user_email FROM $db_users WHERE user_id='" . (int)$usr['id'] . "' LIMIT 1");
	$row = sed_sql_fetchassoc($sql);
	if (!$row || empty(trim($row['user_email']))) {
		sed_redirect(sed_url("users", "m=profile&oauth_error=no_email", "", true));
		exit;
	}
	sed_sql_query("UPDATE $db_users SET user_oauth_uid='', user_oauth_provider='', user_token='' WHERE user_id='" . (int)$usr['id'] . "' LIMIT 1");
	sed_redirect(sed_url("users", "m=profile&oauth_unlinked=1", "", true));
	exit;
}
