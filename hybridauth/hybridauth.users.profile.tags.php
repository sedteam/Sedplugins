<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/hybridauth/hybridauth.users.profile.tags.php
Version=186
Updated=2026-jul-28
Type=Plugin
Author=Amro
Description=OAuth block in profile — multiple providers via XTemplate
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


global $cfg, $usr;

$db_social_accounts = $cfg['sqldbprefix'] . 'social_accounts';
$providers = isset($config_hybridauth['providers']) ? $config_hybridauth['providers'] : array();

$oauth_block = '';

if ($usr['id'] > 0) {
	$tpl_path = SED_ROOT . '/plugins/hybridauth/tpl/hybridauth.profile.tpl';
	if (!file_exists($tpl_path)) {
		$t->assign('PROFILE_OAUTH_BLOCK', '');
		return;
	}

	$ht = new XTemplate($tpl_path);

	// --- Linked accounts ---
	$sql = sed_sql_query("SELECT sa_id, sa_provider, sa_name FROM `" . $db_social_accounts . "` WHERE sa_userid=" . (int)$usr['id'] . " ORDER BY sa_created ASC");
	$linked = array();
	$linked_providers = array();

	while ($row = sed_sql_fetchassoc($sql)) {
		$linked[] = $row;
		$linked_providers[] = $row['sa_provider'];
	}

	if (count($linked) > 0) {
		foreach ($linked as $item) {
			$icon = sed_hybridauth_icon_path($item['sa_provider']);
			$name = sed_hybridauth_provider_name($item['sa_provider']);
			$display = !empty($item['sa_name']) ? $item['sa_name'] : '';
			$unlink_url = sed_url('plug', 'e=hybridauth&a=unlink&sa_id=' . (int)$item['sa_id'] . '&' . sed_xg());

			$ht->assign(array(
				'HYBRIDAUTH_ACC_ICON' => $icon,
				'HYBRIDAUTH_ACC_NAME' => $name,
				'HYBRIDAUTH_ACC_DISPLAY' => $display,
				'HYBRIDAUTH_ACC_UNLINK_URL' => $unlink_url,
			));
			$ht->parse('MAIN.LINKED_ACCOUNTS.ACCOUNT_ROW');
		}
		$ht->parse('MAIN.LINKED_ACCOUNTS');
	} else {
		$ht->parse('MAIN.NO_ACCOUNTS');
	}

	// --- Attach more buttons ---
	$has_attach = false;
	foreach ($providers as $pname => $p) {
		if (empty($p['enabled'])) {
			continue;
		}
		if (in_array($pname, $linked_providers)) {
			continue;
		}

		$link_url = sed_url('plug', 'e=hybridauth&provider=' . urlencode($pname) . '&a=link');
		$icon = sed_hybridauth_icon_path($pname);
		$label = sed_hybridauth_provider_name($pname);
		$css = sed_hybridauth_css_class($pname);

		$ht->assign(array(
			'HYBRIDAUTH_ATTACH_URL' => $link_url,
			'HYBRIDAUTH_ATTACH_ICON' => $icon,
			'HYBRIDAUTH_ATTACH_LABEL' => $label,
			'HYBRIDAUTH_ATTACH_CSS' => $css,
		));
		$ht->parse('MAIN.ATTACH_BLOCK.ATTACH_ROW');
		$has_attach = true;
	}

	if ($has_attach) {
		$ht->parse('MAIN.ATTACH_BLOCK');
	}

	$ht->parse('MAIN');
	$oauth_block = $ht->text('MAIN');
}

$t->assign('PROFILE_OAUTH_BLOCK', $oauth_block);
