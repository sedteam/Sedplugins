<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/hybridauth/hybridauth.php
Version=186
Updated=2026-jul-28
Type=Plugin
Author=Amro
Description=OAuth standalone handler: login, register, link, unlink
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=hybridauth
Part=hybridauth
File=hybridauth
Hooks=standalone
Tags=
Order=10
Lock=1
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

use Hybridauth\Hybridauth;

global $db_users, $db_groups_users, $cfg, $sys, $usr, $L;

$db_social_accounts = $cfg['sqldbprefix'] . 'social_accounts';

// --- Load lang file ---
$langfile = sed_langfile('hybridauth', 'plugin');
if (!empty($langfile)) {
	require($langfile);
}

// --- Import parameters ---
$provider = sed_import('provider', 'G', 'TXT', 50);
$a        = sed_import('a', 'G', 'ALP');
$redirect = sed_import('redirect', 'G', 'TXT');

if ($redirect === null || $redirect === false) {
	$redirect = '';
}

// ==========================================
// Action: Unlink social account
// ==========================================
if ($a === 'unlink') {
	sed_check_xg();

	if ($usr['id'] < 1) {
		sed_redirect(sed_url('users', 'm=auth', '', true));
		exit;
	}

	$sa_id = sed_import('sa_id', 'G', 'INT');
	if ($sa_id > 0) {
		// Verify ownership
		$sql = sed_sql_query("SELECT sa_id FROM `" . $db_social_accounts . "` WHERE sa_id=" . (int)$sa_id . " AND sa_userid=" . (int)$usr['id'] . " LIMIT 1");
		if (sed_sql_numrows($sql) > 0) {
			sed_sql_query("DELETE FROM `" . $db_social_accounts . "` WHERE sa_id=" . (int)$sa_id . " LIMIT 1");
		}
	}

	sed_redirect(sed_url('users', 'm=profile&oauth_unlinked=1', '', true));
	exit;
}

// ==========================================
// Action: Link mode (logged-in user attaching a provider)
// ==========================================
$is_link_mode = ($a === 'link' || !empty($_SESSION['hybridauth_link_mode'])) && $usr['id'] > 0;

if ($is_link_mode && $a === 'link') {
	$_SESSION['hybridauth_link_mode'] = 1;
}

// ==========================================
// Store/restore provider in session
// ==========================================
if (!empty($provider)) {
	$_SESSION['hybridauth_provider'] = $provider;
} elseif (!empty($_SESSION['hybridauth_provider'])) {
	$provider = $_SESSION['hybridauth_provider'];
}

if (empty($provider)) {
	sed_redirect(sed_url('users', 'm=auth', '', true));
	exit;
}

// --- Store redirect for after-auth ---
if ($redirect !== '' && empty($_SESSION['hybridauth_redirect'])) {
	$_SESSION['hybridauth_redirect'] = $redirect;
}

// --- Load config ---
$config_path = SED_ROOT . '/plugins/hybridauth/config/hybridauth_config.php';
if (!file_exists($config_path)) {
	sed_log('Hybridauth: config file not found', 'usr');
	sed_redirect(sed_url('users', 'm=auth', '', true));
	exit;
}
require_once($config_path);

// --- Validate provider is enabled ---
$providerKey = null;
$providersConfig = isset($config_hybridauth['providers']) ? $config_hybridauth['providers'] : array();
foreach ($providersConfig as $name => $p) {
	if (strcasecmp($name, $provider) === 0 && !empty($p['enabled'])) {
		$providerKey = $name;
		break;
	}
}
if ($providerKey === null) {
	unset($_SESSION['hybridauth_provider'], $_SESSION['hybridauth_link_mode'], $_SESSION['hybridauth_redirect']);
	sed_redirect(sed_url('users', 'm=auth', '', true));
	exit;
}

// --- Load Hybridauth library ---
require_once(SED_ROOT . '/plugins/hybridauth/lib/autoload.php');

try {
	$hybridauth = new Hybridauth($config_hybridauth);
	$adapter = $hybridauth->authenticate($providerKey);
	$userProfile = $adapter->getUserProfile();
	$token = $adapter->getAccessToken();
	$accessToken = (is_array($token) && isset($token['access_token'])) ? $token['access_token'] : '';

	$oauth_uid = (string)(isset($userProfile->identifier) ? $userProfile->identifier : '');

	if ($oauth_uid === '') {
		throw new Exception('Provider returned empty identifier.');
	}

	// Restore redirect from session
	$redirect = '';
	if (!empty($_SESSION['hybridauth_redirect'])) {
		$redirect = $_SESSION['hybridauth_redirect'];
	}

	// Clean up session
	unset($_SESSION['hybridauth_provider'], $_SESSION['hybridauth_redirect']);

	// ==============================================
	// LINK MODE: attach provider to current account
	// ==============================================
	if ($is_link_mode) {
		unset($_SESSION['hybridauth_link_mode']);

		// Check if this social account is already linked to another user
		$sql = sed_sql_query("SELECT sa_userid FROM `" . $db_social_accounts . "` WHERE sa_provider='" . sed_sql_prep($providerKey) . "' AND sa_uid='" . sed_sql_prep($oauth_uid) . "' LIMIT 1");
		if (sed_sql_numrows($sql) > 0) {
			$row = sed_sql_fetchassoc($sql);
			if ((int)$row['sa_userid'] !== (int)$usr['id']) {
				// Already linked to a different user
				sed_redirect(sed_url('users', 'm=profile&oauth_error=already_linked', '', true));
				exit;
			}
			// Already linked to same user — just redirect
			sed_redirect(sed_url('users', 'm=profile&oauth_linked=1', '', true));
			exit;
		}

		// Insert new link
		$sa_email = isset($userProfile->email) ? mb_strtolower(trim((string)$userProfile->email)) : '';
		$sa_name  = isset($userProfile->displayName) ? trim((string)$userProfile->displayName) : '';
		$sa_photo = isset($userProfile->photoURL) ? (string)$userProfile->photoURL : '';

		sed_sql_query("INSERT INTO `" . $db_social_accounts . "`
			(sa_userid, sa_provider, sa_uid, sa_email, sa_name, sa_photo, sa_token, sa_created)
			VALUES
			(" . (int)$usr['id'] . ", '" . sed_sql_prep($providerKey) . "', '" . sed_sql_prep($oauth_uid) . "', '" . sed_sql_prep($sa_email) . "', '" . sed_sql_prep($sa_name) . "', '" . sed_sql_prep($sa_photo) . "', '" . sed_sql_prep($accessToken) . "', " . (int)$sys['now_offset'] . ")");

		sed_redirect(sed_url('users', 'm=profile&oauth_linked=1', '', true));
		exit;
	}

	// ==============================================
	// LOGIN MODE: find user by social account
	// ==============================================

	// 1. Search in social_accounts table
	$sql = sed_sql_query("SELECT sa_userid FROM `" . $db_social_accounts . "` WHERE sa_provider='" . sed_sql_prep($providerKey) . "' AND sa_uid='" . sed_sql_prep($oauth_uid) . "' LIMIT 1");

	if (sed_sql_numrows($sql) == 1) {
		$row = sed_sql_fetchassoc($sql);
		$ruserid = (int)$row['sa_userid'];

		// Update token
		sed_sql_query("UPDATE `" . $db_social_accounts . "` SET sa_token='" . sed_sql_prep($accessToken) . "' WHERE sa_provider='" . sed_sql_prep($providerKey) . "' AND sa_uid='" . sed_sql_prep($oauth_uid) . "' LIMIT 1");

		// Log the user in
		sed_hybridauth_login($ruserid, $redirect);
		exit;
	}

	// 2. Check by email — maybe user exists but not linked yet
	$ruseremail = isset($userProfile->email) ? mb_strtolower(trim((string)$userProfile->email)) : '';
	if ($ruseremail === '' && isset($userProfile->emailVerified) && trim((string)$userProfile->emailVerified) !== '') {
		$ruseremail = mb_strtolower(trim((string)$userProfile->emailVerified));
	}

	$existing_user = null;
	if ($ruseremail !== '') {
		$sql = sed_sql_query("SELECT user_id, user_skin, user_secret FROM $db_users WHERE user_email='" . sed_sql_prep($ruseremail) . "' LIMIT 1");
		if (sed_sql_numrows($sql) > 0) {
			$existing_user = sed_sql_fetchassoc($sql);
		}
	}

	if ($existing_user !== null) {
		// User found by email — create social link and log in
		$ruserid = (int)$existing_user['user_id'];
		$sa_name  = isset($userProfile->displayName) ? trim((string)$userProfile->displayName) : '';
		$sa_photo = isset($userProfile->photoURL) ? (string)$userProfile->photoURL : '';

		sed_sql_query("INSERT INTO `" . $db_social_accounts . "`
			(sa_userid, sa_provider, sa_uid, sa_email, sa_name, sa_photo, sa_token, sa_created)
			VALUES
			(" . (int)$ruserid . ", '" . sed_sql_prep($providerKey) . "', '" . sed_sql_prep($oauth_uid) . "', '" . sed_sql_prep($ruseremail) . "', '" . sed_sql_prep($sa_name) . "', '" . sed_sql_prep($sa_photo) . "', '" . sed_sql_prep($accessToken) . "', " . (int)$sys['now_offset'] . ")");

		sed_hybridauth_login($ruserid, $redirect);
		exit;
	}

	// 3. No user found — create a new one
	$defgroup = 4;
	$mdsalt = sed_unique(16);
	$plain_password = sed_unique(12);
	$mdpass = sed_hash($plain_password, 1, $mdsalt);
	$rmdpass_secret = md5(sed_unique(16));

	$ruserfirstname = isset($userProfile->firstName) ? trim((string)$userProfile->firstName) : '';
	$ruserlastname  = isset($userProfile->lastName) ? trim((string)$userProfile->lastName) : '';

	// Build username
	$rusername = isset($userProfile->displayName) ? trim((string)$userProfile->displayName) : '';
	if ($rusername === '' && $ruseremail !== '') {
		$parts = explode('@', $ruseremail, 2);
		$rusername = isset($parts[0]) ? trim($parts[0]) : '';
	}
	if ($rusername === '') {
		$rusername = trim($ruserfirstname . ' ' . $ruserlastname);
	}
	if ($rusername === '') {
		$rusername = 'user_' . $oauth_uid;
	}
	$rusername = preg_replace('/\s+/', '_', $rusername);
	if (mb_strlen($rusername) > 100) {
		$rusername = mb_substr($rusername, 0, 100);
	}

	// Ensure unique username
	while (true) {
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_users WHERE user_name='" . sed_sql_prep($rusername) . "'");
		if (sed_sql_result($sql, 0, "COUNT(*)") == 0) {
			break;
		}
		$rusername = preg_replace('/\d+$/', '', $rusername);
		$rusername = ($rusername === '' ? 'user' : $rusername) . mt_rand(2, 9999);
	}

	// Gender
	$rusergender = 'U';
	if (isset($userProfile->gender)) {
		$g = $userProfile->gender;
		if ($g === 'male' || $g === '2') {
			$rusergender = 'M';
		} elseif ($g === 'female' || $g === '1') {
			$rusergender = 'F';
		}
	}

	// Birthdate
	$ruserbirthdate = 0;
	if (isset($userProfile->birthDay) && isset($userProfile->birthMonth) && isset($userProfile->birthYear)) {
		$ruserbirthdate = sed_mktime(1, 0, 0, $userProfile->birthMonth, $userProfile->birthDay, $userProfile->birthYear);
	}

	// Insert user
	sed_sql_query("INSERT INTO $db_users
		(user_name, user_firstname, user_lastname, user_password, user_salt, user_secret, user_passtype, user_maingrp, user_email, user_hideemail, user_pmnotify, user_skin, user_lang, user_regdate, user_logcount, user_gender, user_birthdate, user_lastip)
		VALUES
		('" . sed_sql_prep($rusername) . "', '" . sed_sql_prep($ruserfirstname) . "', '" . sed_sql_prep($ruserlastname) . "', '" . sed_sql_prep($mdpass) . "', '" . sed_sql_prep($mdsalt) . "', '" . sed_sql_prep($rmdpass_secret) . "', 1, " . (int)$defgroup . ", '" . sed_sql_prep($ruseremail) . "', 1, 1, '" . sed_sql_prep($cfg['defaultskin']) . "', '" . sed_sql_prep($cfg['defaultlang']) . "', " . (int)$sys['now_offset'] . ", 0, '" . sed_sql_prep($rusergender) . "', " . (int)$ruserbirthdate . ", '" . sed_sql_prep($usr['ip']) . "')");

	$ruserid = sed_sql_insertid();
	sed_sql_query("INSERT INTO $db_groups_users (gru_userid, gru_groupid) VALUES (" . (int)$ruserid . ", " . (int)$defgroup . ")");

	// Create social account link
	$sa_name  = isset($userProfile->displayName) ? trim((string)$userProfile->displayName) : '';
	$sa_photo = isset($userProfile->photoURL) ? (string)$userProfile->photoURL : '';

	sed_sql_query("INSERT INTO `" . $db_social_accounts . "`
		(sa_userid, sa_provider, sa_uid, sa_email, sa_name, sa_photo, sa_token, sa_created)
		VALUES
		(" . (int)$ruserid . ", '" . sed_sql_prep($providerKey) . "', '" . sed_sql_prep($oauth_uid) . "', '" . sed_sql_prep($ruseremail) . "', '" . sed_sql_prep($sa_name) . "', '" . sed_sql_prep($sa_photo) . "', '" . sed_sql_prep($accessToken) . "', " . (int)$sys['now_offset'] . ")");

	// Send password notification
	if ($plain_password !== '' && $ruseremail !== '') {
		if ($cfg['regrequireadmin']) {
			$rsubject = $cfg['maintitle'] . ' - ' . (isset($L['aut_regrequesttitle']) ? $L['aut_regrequesttitle'] : 'Registration request');
			$rbody = isset($L['aut_regrequest']) ? sprintf($L['aut_regrequest'], $rusername, $plain_password) : "Username = " . $rusername . "\nPassword = " . $plain_password;
		} else {
			$rsubject = $cfg['maintitle'] . ' - ' . (isset($L['Registration']) ? $L['Registration'] : 'Registration');
			$ractivate = $cfg['mainurl'] . '/' . sed_url('users', 'm=profile', '', false, false);
			$rbody = isset($L['aut_emailreg']) ? sprintf($L['aut_emailreg'], $rusername, $plain_password, $ractivate) : "Username = " . $rusername . "\nPassword = " . $plain_password;
		}
		if (isset($L['aut_contactadmin'])) {
			$rbody .= "\n\n" . $L['aut_contactadmin'];
		}
		sed_mail($ruseremail, $rsubject, $rbody);
	} elseif ($plain_password !== '') {
		// No email — send password via PM
		$pm_from = isset($cfg['maintitle']) ? $cfg['maintitle'] : 'Site';
		$pm_subject = isset($L['Registration']) ? $L['Registration'] : 'Registration';
		$pm_body = isset($L['aut_regrequest']) ? sprintf($L['aut_regrequest'], $rusername, $plain_password) : "Username = " . $rusername . "\nPassword = " . $plain_password;
		global $db_pm;
		sed_sql_query("INSERT INTO $db_pm (pm_state, pm_date, pm_fromuserid, pm_fromuser, pm_touserid, pm_title, pm_text) VALUES (0, " . (int)$sys['now_offset'] . ", 0, '" . sed_sql_prep($pm_from) . "', " . (int)$ruserid . ", '" . sed_sql_prep($pm_subject) . "', '" . sed_sql_prep($pm_body) . "')");
		sed_sql_query("UPDATE $db_users SET user_newpm=1 WHERE user_id=" . (int)$ruserid);
	}

	// Log the new user in — redirect to profile to fill details
	sed_hybridauth_login($ruserid, '');
	exit;

} catch (Exception $e) {
	unset($_SESSION['hybridauth_provider'], $_SESSION['hybridauth_link_mode'], $_SESSION['hybridauth_redirect']);
	sed_log('Hybridauth error: ' . $e->getMessage(), 'usr');
	sed_redirect(sed_url('users', 'm=auth', '', true));
	exit;
}

// ==========================================
// Helper: log user in via cookie/session
// ==========================================

/**
 * Authenticates a user by ID and redirects.
 *
 * @param int    $userid   User ID
 * @param string $redirect Redirect URL after login
 */
function sed_hybridauth_login($userid, $redirect)
{
	global $db_users, $cfg, $sys, $usr;

	$sql = sed_sql_query("SELECT user_id, user_skin, user_secret FROM $db_users WHERE user_id=" . (int)$userid . " LIMIT 1");
	if (sed_sql_numrows($sql) == 0) {
		sed_redirect(sed_url('users', 'm=auth', '', true));
		exit;
	}
	$row = sed_sql_fetchassoc($sql);
	$ruserid = (int)$row['user_id'];
	$rdefskin = $row['user_skin'];
	$rmdpass_secret = ($cfg['authsecret']) ? md5(sed_unique(16)) : $row['user_secret'];

	// Update secret and last IP
	sed_sql_query("UPDATE $db_users SET user_secret='" . sed_sql_prep($rmdpass_secret) . "', user_lastip='" . sed_sql_prep($usr['ip']) . "' WHERE user_id=" . (int)$ruserid . " LIMIT 1");

	$rcookiettl = 604800; // 7 days
	if ($rcookiettl > $cfg['cookielifetime']) {
		$rcookiettl = $cfg['cookielifetime'];
	}

	// Cookie auth
	if ($cfg['authmode'] == 1 || $cfg['authmode'] == 3) {
		$u = base64_encode($ruserid . ':_:' . $rmdpass_secret . ':_:' . $rdefskin);
		sed_setcookie($sys['site_id'], $u, time() + $rcookiettl, $cfg['cookiepath'], $cfg['cookiedomain'], $sys['secure'], true);
	}

	// Session auth
	if ($cfg['authmode'] == 2 || $cfg['authmode'] == 3) {
		$_SESSION[$sys['site_id'] . '_n'] = $ruserid;
		$_SESSION[$sys['site_id'] . '_p'] = $rmdpass_secret;
		$_SESSION[$sys['site_id'] . '_s'] = $rdefskin;
	}

	if ($redirect !== '') {
		sed_redirect(sed_url('message', 'msg=104&redirect=' . $redirect, '', true));
	} else {
		sed_redirect(sed_url('message', 'msg=104', '', true));
	}
	exit;
}
