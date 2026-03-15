<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/hybridauth/hybridauth.users.profile.tags.php
Version=181
Updated=2026-mar-15
Type=Plugin
Author=Amro
Description=OAuth block in profile
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=hybridauth
Part=profile.tags
File=hybridauth.users.profile.tags
Hooks=profile.tags
Tags=users.profile.tpl:{PROFILE_OAUTH_BLOCK}
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$oauth_block = '';
$providers = isset($config_hybridauth['providers']) ? $config_hybridauth['providers'] : [];
$has_email = !empty(trim($urr['user_email'] ?? ''));
$attached = !empty(trim($urr['user_oauth_provider'] ?? ''));

if ($attached) {
	$oauth_block .= '<p><strong>Attached: ' . sed_cc($urr['user_oauth_provider']) . '</strong></p>';
	if ($has_email) {
		$oauth_block .= '<p><a href="' . sed_cc(sed_url("users", "m=profile&a=oauthunlink&" . sed_xg())) . '">Unlink</a></p>';
		$oauth_block .= '<p>Attach another:</p>';
	} else {
		$oauth_block .= '<p>Specify email in profile to unlink.</p>';
	}
}

if (!$attached || $has_email) {
	$oauth_block .= '<p>';
	$ha_base = rtrim($sys['abs_url'], '/');
	foreach ($providers as $name => $p) {
		if (empty($p['enabled'])) {
			continue;
		}
		$url = $ha_base . '?oauth_provider=' . urlencode($name) . '&a=link';
		$oauth_block .= '<a href="' . sed_cc($url) . '" class="btn-social">' . sed_cc($name) . '</a> ';
	}
	$oauth_block .= '</p>';
}

$t->assign('PROFILE_OAUTH_BLOCK', $oauth_block);
