<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/hybridauth/hybridauth.common.php
Version=181
Updated=2026-mar-15
Type=Plugin
Author=Amro
Description=OAuth handling in common hook (login, register, link)
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

use Hybridauth\Hybridauth;

$provider = sed_import('oauth_provider', 'G', 'TXT');
$a = sed_import('a', 'G', 'ALP');

if (empty($provider)) {
	return;
}

require_once(SED_ROOT . '/plugins/hybridauth/config/hybridauth_config.php');

$providersConfig = isset($config_hybridauth['providers']) ? $config_hybridauth['providers'] : [];
$providerKey = null;
foreach ($providersConfig as $name => $p) {
	if (strcasecmp($name, $provider) === 0 && !empty($p['enabled'])) {
		$providerKey = $name;
		break;
	}
}
if ($providerKey === null) {
	sed_redirect(sed_url("users", "m=auth", "", true));
	exit;
}

$is_link_mode = ($a === 'link' || !empty($_SESSION['hybridauth_link_mode'])) && $usr['id'] > 0;

if ($is_link_mode) {
	if ($a === 'link') {
		$_SESSION['hybridauth_link_mode'] = 1;
	}
	require_once(SED_ROOT . '/plugins/hybridauth/lib/autoload.php');
	try {
		$hybridauth = new Hybridauth($config_hybridauth);
		$adapter = $hybridauth->authenticate($providerKey);
		$userProfile = $adapter->getUserProfile();
		$token = $adapter->getAccessToken();
		$accessToken = (is_array($token) && isset($token['access_token'])) ? $token['access_token'] : '';
		sed_sql_query("UPDATE $db_users SET user_oauth_uid='" . sed_sql_prep($userProfile->identifier) . "', user_oauth_provider='" . sed_sql_prep($providerKey) . "', user_token='" . sed_sql_prep($accessToken) . "' WHERE user_id='" . (int)$usr['id'] . "' LIMIT 1");
		unset($_SESSION['hybridauth_link_mode']);
		sed_redirect(sed_url("users", "m=profile&oauth_linked=1", "", true));
		exit;
	} catch (Exception $e) {
		unset($_SESSION['hybridauth_link_mode']);
		sed_log('Hybridauth link error: ' . $e->getMessage(), 'usr');
		sed_redirect(sed_url("users", "m=profile&oauth_error=1", "", true));
		exit;
	}
}

$rcookiettl = isset($rcookiettl) ? (int)$rcookiettl : (int)sed_import('rcookiettl', 'G', 'INT');
if ($rcookiettl <= 0) {
	$rcookiettl = 604800;
}

require_once(SED_ROOT . '/plugins/hybridauth/lib/autoload.php');

if (!isset($redirect)) {
	$redirect = sed_import('redirect', 'G', 'TXT');
}
if ($redirect === null || $redirect === false) {
	$redirect = '';
}

try {
	$hybridauth = new Hybridauth($config_hybridauth);
	$adapter = $hybridauth->authenticate($providerKey);
	$userProfile = $adapter->getUserProfile();
	$oauth_uid = (string)(isset($userProfile->identifier) ? $userProfile->identifier : '');

	$sql = sed_sql_query("SELECT user_id, user_skin, user_secret FROM $db_users WHERE user_oauth_uid='" . sed_sql_prep($oauth_uid) . "' AND user_oauth_provider='" . sed_sql_prep($providerKey) . "'");

	if (sed_sql_numrows($sql) == 1) {
		$row = sed_sql_fetchassoc($sql);
		$ruserid = $row['user_id'];
		$rdefskin = $row['user_skin'];
		$rmdpass_secret = ($cfg['authsecret']) ? md5(sed_unique(16)) : $row['user_secret'];

		sed_sql_query("UPDATE $db_users SET user_secret = '" . sed_sql_prep($rmdpass_secret) . "', user_lastip='" . sed_sql_prep($usr['ip']) . "' WHERE user_id='" . (int)$row['user_id'] . "' LIMIT 1");

		if ($cfg['authmode'] == 1 || $cfg['authmode'] == 3) {
			$rcookiettl = ($rcookiettl > $cfg['cookielifetime']) ? $cfg['cookielifetime'] : $rcookiettl;
			$u = base64_encode("$ruserid:_:$rmdpass_secret:_:$rdefskin");
			sed_setcookie($sys['site_id'], $u, time() + $rcookiettl, $cfg['cookiepath'], $cfg['cookiedomain'], $sys['secure'], true);
		}
		if ($cfg['authmode'] == 2 || $cfg['authmode'] == 3) {
			$_SESSION[$sys['site_id'] . '_n'] = $ruserid;
			$_SESSION[$sys['site_id'] . '_p'] = $rmdpass_secret;
			$_SESSION[$sys['site_id'] . '_s'] = $rdefskin;
		}

		sed_redirect(sed_url("message", "msg=104&redirect=" . $redirect, "", true));
		exit;
	}

	$token = $adapter->getAccessToken();
	$accessToken = (is_array($token) && isset($token['access_token'])) ? $token['access_token'] : '';

	$defgroup = 4;
	$mdsalt = sed_unique(16);
	$plain_password = sed_unique(12);
	$mdpass = sed_hash($plain_password, 1, $mdsalt);
	$rmdpass_secret = md5(sed_unique(16));

	$ruserfirstname = isset($userProfile->firstName) ? trim((string)$userProfile->firstName) : '';
	$ruserlastname = isset($userProfile->lastName) ? trim((string)$userProfile->lastName) : '';

	$ruseremail = isset($userProfile->email) ? mb_strtolower(trim((string)$userProfile->email)) : '';
	if ($ruseremail === '' && isset($userProfile->emailVerified) && trim((string)$userProfile->emailVerified) !== '') {
		$ruseremail = mb_strtolower(trim((string)$userProfile->emailVerified));
	}
	$email_exists = false;
	$existing_user_by_email = null;
	if ($ruseremail !== '') {
		$sql = sed_sql_query("SELECT user_id, user_skin, user_secret FROM $db_users WHERE user_email='" . sed_sql_prep($ruseremail) . "' LIMIT 1");
		if (sed_sql_numrows($sql) > 0) {
			$email_exists = true;
			$existing_user_by_email = sed_sql_fetchassoc($sql);
			$ruseremail = '';
		}
	}

	if ($existing_user_by_email !== null && $oauth_uid !== '') {
		$row = $existing_user_by_email;
		$ruserid = $row['user_id'];
		$rdefskin = $row['user_skin'];
		$rmdpass_secret = ($cfg['authsecret']) ? md5(sed_unique(16)) : $row['user_secret'];
		sed_sql_query("UPDATE $db_users SET user_oauth_uid='" . sed_sql_prep($oauth_uid) . "', user_oauth_provider='" . sed_sql_prep($providerKey) . "', user_token='" . sed_sql_prep($accessToken) . "', user_secret='" . sed_sql_prep($rmdpass_secret) . "', user_lastip='" . sed_sql_prep($usr['ip']) . "' WHERE user_id='" . (int)$ruserid . "' LIMIT 1");
		if ($cfg['authmode'] == 1 || $cfg['authmode'] == 3) {
			$rcookiettl = ($rcookiettl > $cfg['cookielifetime']) ? $cfg['cookielifetime'] : $rcookiettl;
			$u = base64_encode("$ruserid:_:$rmdpass_secret:_:$rdefskin");
			sed_setcookie($sys['site_id'], $u, time() + $rcookiettl, $cfg['cookiepath'], $cfg['cookiedomain'], $sys['secure'], true);
		}
		if ($cfg['authmode'] == 2 || $cfg['authmode'] == 3) {
			$_SESSION[$sys['site_id'] . '_n'] = $ruserid;
			$_SESSION[$sys['site_id'] . '_p'] = $rmdpass_secret;
			$_SESSION[$sys['site_id'] . '_s'] = $rdefskin;
		}
		sed_redirect(sed_url("message", "msg=104&redirect=" . $redirect, "", true));
		exit;
	}

	$rusername = isset($userProfile->displayName) ? trim((string)$userProfile->displayName) : '';
	if ($rusername === '' && $ruseremail !== '' && !$email_exists) {
		$parts = explode('@', $ruseremail, 2);
		$rusername = isset($parts[0]) ? trim($parts[0]) : '';
	}
	if ($rusername === '') {
		$rusername = trim($ruserfirstname . ' ' . $ruserlastname);
	}
	if ($rusername === '') {
		$rusername = 'user_' . (isset($userProfile->identifier) ? $userProfile->identifier : sed_unique(8));
	}
	$rusername = preg_replace('/\s+/', '_', $rusername);
	if (mb_strlen($rusername) > 100) {
		$rusername = mb_substr($rusername, 0, 100);
	}
	while (true) {
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_users WHERE user_name='" . sed_sql_prep($rusername) . "'");
		if (sed_sql_result($sql, 0, "COUNT(*)") == 0) {
			break;
		}
		$rusername = preg_replace('/\d+$/', '', $rusername);
		$rusername = ($rusername === '' ? 'user' : $rusername) . mt_rand(2, 9999);
	}

	$rtimezone = $cfg['defaulttimezone'];
	$ruserbirthdate = (isset($userProfile->birthDay) && isset($userProfile->birthMonth) && isset($userProfile->birthYear)) ? sed_mktime(1, 0, 0, $userProfile->birthMonth, $userProfile->birthDay, $userProfile->birthYear) : 0;
	$rusergender = 'U';
	if (isset($userProfile->gender)) {
		$g = $userProfile->gender;
		if ($g === 'male' || $g === '2') $rusergender = 'M';
		elseif ($g === 'female' || $g === '1') $rusergender = 'F';
	}

	sed_sql_query("INSERT INTO $db_users
		(user_name, user_firstname, user_lastname, user_password, user_salt, user_secret, user_passtype, user_maingrp, user_email, user_hideemail, user_pmnotify, user_skin, user_lang, user_regdate, user_logcount, user_gender, user_birthdate, user_lastip, user_oauth_uid, user_oauth_provider, user_token)
		VALUES
		('" . sed_sql_prep($rusername) . "', '" . sed_sql_prep($ruserfirstname) . "', '" . sed_sql_prep($ruserlastname) . "', '" . sed_sql_prep($mdpass) . "', '" . sed_sql_prep($mdsalt) . "', '" . sed_sql_prep($rmdpass_secret) . "', 1, " . (int)$defgroup . ", '" . sed_sql_prep($ruseremail) . "', 1, 1, '" . sed_sql_prep($cfg['defaultskin']) . "', '" . sed_sql_prep($cfg['defaultlang']) . "', " . (int)$sys['now_offset'] . ", 0, '" . sed_sql_prep($rusergender) . "', " . (int)$ruserbirthdate . ", '" . sed_sql_prep($usr['ip']) . "', '" . sed_sql_prep($oauth_uid) . "', '" . sed_sql_prep($providerKey) . "', '" . sed_sql_prep($accessToken) . "')");

	$ruserid = sed_sql_insertid();
	sed_sql_query("INSERT INTO $db_groups_users (gru_userid, gru_groupid) VALUES (" . (int)$ruserid . ", " . (int)$defgroup . ")");

	if ($plain_password !== '') {
		if ($ruseremail !== '') {
			if ($cfg['regrequireadmin']) {
				$rsubject = $cfg['maintitle'] . " - " . (isset($L['aut_regrequesttitle']) ? $L['aut_regrequesttitle'] : 'Registration request');
				$rbody = isset($L['aut_regrequest']) ? sprintf($L['aut_regrequest'], $rusername, $plain_password) : "Username = " . $rusername . "\nPassword = " . $plain_password;
			} else {
				$rsubject = $cfg['maintitle'] . " - " . (isset($L['Registration']) ? $L['Registration'] : 'Registration');
				$ractivate = $cfg['mainurl'] . "/" . sed_url("users", "m=profile", "", false, false);
				$rbody = isset($L['aut_emailreg']) ? sprintf($L['aut_emailreg'], $rusername, $plain_password, $ractivate) : "Username = " . $rusername . "\nPassword = " . $plain_password;
			}
			if (isset($L['aut_contactadmin'])) {
				$rbody .= "\n\n" . $L['aut_contactadmin'];
			}
			sed_mail($ruseremail, $rsubject, $rbody);
		} else {
			$pm_from = isset($cfg['maintitle']) ? $cfg['maintitle'] : 'Site';
			$pm_subject = isset($L['Registration']) ? $L['Registration'] : 'Registration';
			$pm_body = (isset($L['aut_regrequest']) ? sprintf($L['aut_regrequest'], $rusername, $plain_password) : "Username = " . $rusername . "\nPassword = " . $plain_password);
			sed_sql_query("INSERT INTO $db_pm (pm_state, pm_date, pm_fromuserid, pm_fromuser, pm_touserid, pm_title, pm_text) VALUES (0, " . (int)$sys['now_offset'] . ", 0, '" . sed_sql_prep($pm_from) . "', " . (int)$ruserid . ", '" . sed_sql_prep($pm_subject) . "', '" . sed_sql_prep($pm_body) . "')");
			sed_sql_query("UPDATE $db_users SET user_newpm=1 WHERE user_id=" . (int)$ruserid);
		}
	}

	$rdefskin = $cfg['defaultskin'];
	if ($cfg['authmode'] == 1 || $cfg['authmode'] == 3) {
		$rcookiettl = ($rcookiettl > $cfg['cookielifetime']) ? $cfg['cookielifetime'] : $rcookiettl;
		$u = base64_encode("$ruserid:_:$rmdpass_secret:_:$rdefskin");
		sed_setcookie($sys['site_id'], $u, time() + $rcookiettl, $cfg['cookiepath'], $cfg['cookiedomain'], $sys['secure'], true);
	}
	if ($cfg['authmode'] == 2 || $cfg['authmode'] == 3) {
		$_SESSION[$sys['site_id'] . '_n'] = $ruserid;
		$_SESSION[$sys['site_id'] . '_p'] = $rmdpass_secret;
		$_SESSION[$sys['site_id'] . '_s'] = $rdefskin;
	}

	sed_redirect(sed_url("users", "m=profile", "", true));
	exit;

} catch (Exception $e) {
	sed_log('Hybridauth error: ' . $e->getMessage(), 'usr');
	sed_redirect(sed_url("users", "m=auth", "", true));
	exit;
}
