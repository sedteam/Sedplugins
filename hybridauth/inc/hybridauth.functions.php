<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/hybridauth/inc/hybridauth.functions.php
Version=186
Updated=2026-jul-28
Type=Plugin
Author=Amro
Description=Helper functions: icon paths, provider name mapping
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/**
 * Returns path to SVG icon file for a provider.
 *
 * @param string $provider Provider class name (e.g. 'Vkontakte')
 * @return string Relative path to SVG file or empty string
 */
function sed_hybridauth_icon_path($provider)
{
	$key = strtolower($provider);
	$path = 'plugins/hybridauth/img/' . $key . '.svg';
	if (file_exists(SED_ROOT . '/' . $path)) {
		return $path;
	}
	return '';
}

/**
 * Returns localized display name for a provider.
 *
 * @param string $provider Provider class name (e.g. 'Vkontakte')
 * @return string Display name
 */
function sed_hybridauth_provider_name($provider)
{
	global $L;
	$key = 'hybridauth_provider_' . $provider;
	return isset($L[$key]) ? $L[$key] : $provider;
}

/**
 * Returns CSS class suffix for a provider (lowercase).
 *
 * @param string $provider Provider class name
 * @return string CSS class suffix
 */
function sed_hybridauth_css_class($provider)
{
	return strtolower($provider);
}

/**
 * Builds social login buttons HTML using XTemplate.
 *
 * @param array  $providers   Config array of providers
 * @param string $divider_key L key for divider text
 * @param string $redirect    Redirect URL after login (optional)
 * @return string HTML
 */
function sed_hybridauth_buttons($providers, $divider_key, $redirect)
{
	global $L;

	if (empty($providers)) {
		return '';
	}

	$count = 0;
	$tpl_path = SED_ROOT . '/plugins/hybridauth/tpl/hybridauth.buttons.tpl';
	if (!file_exists($tpl_path)) {
		return '';
	}

	$ht = new XTemplate($tpl_path);

	foreach ($providers as $name => $p) {
		if (empty($p['enabled'])) {
			continue;
		}

		$params = 'e=hybridauth&provider=' . urlencode($name);
		if ($redirect !== '') {
			$params .= '&redirect=' . urlencode($redirect);
		}
		$url = sed_url('plug', $params);
		$icon = sed_hybridauth_icon_path($name);
		$label = sed_hybridauth_provider_name($name);
		$css = sed_hybridauth_css_class($name);

		$ht->assign(array(
			'HYBRIDAUTH_BTN_URL' => $url,
			'HYBRIDAUTH_BTN_ICON' => $icon,
			'HYBRIDAUTH_BTN_LABEL' => $label,
			'HYBRIDAUTH_BTN_CSS' => $css,
		));
		$ht->parse('MAIN.BUTTON_ROW');
		$count++;
	}

	if ($count === 0) {
		return '';
	}

	$divider = isset($L[$divider_key]) ? $L[$divider_key] : '';
	$ht->assign('HYBRIDAUTH_DIVIDER', $divider);
	$ht->parse('MAIN');

	return $ht->text('MAIN');
}
