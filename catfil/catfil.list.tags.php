<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/catfil/catfil.list.tags.php
Version=185
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=catfil
Part=main
File=catfil.list.tags
Hooks=list.tags
Order=15
Lock=0
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if (empty($c) || $c == 'all' || $c == 'system' || empty($sed_cat[$c])) {
	return;
}

$cfg_parent = '';
if (!empty($cfg['plugin']['catfil']['parent_cat'])) {
	$cfg_parent = trim($cfg['plugin']['catfil']['parent_cat']);
}

if (!empty($cfg_parent)) {
	$catfil_parent = $cfg_parent;
} elseif (!empty($sed_cat[$c]['group'])) {
	$catfil_parent = $c;
} else {
	$path_codes = explode('.', $sed_cat[$c]['path']);
	$catfil_parent = !empty($path_codes[0]) ? $path_codes[0] : $c;
}

if (empty($sed_cat[$catfil_parent])) {
	return;
}

$current_path = $sed_cat[$c]['path'];
$parent_path = $sed_cat[$catfil_parent]['path'];
$inside_parent = ($c == $catfil_parent || mb_substr($current_path, 0, mb_strlen($parent_path . '.')) == $parent_path . '.');
if (!$inside_parent) {
	return;
}

$mtch = $parent_path . '.';
$mtchlen = mb_strlen($mtch);
$mtchlvl = mb_substr_count($mtch, '.');

// Collect direct child categories first (no per-category COUNT queries)
$candidates = array();
foreach ($sed_cat as $cat_code => $cat_info) {
	if (
		mb_substr($cat_info['path'], 0, $mtchlen) != $mtch ||
		mb_substr_count($cat_info['path'], '.') != $mtchlvl ||
		!sed_auth('page', $cat_code, 'R')
	) {
		continue;
	}
	$candidates[$cat_code] = $cat_info;
}

if (empty($candidates)) {
	return;
}

// One grouped query for all candidate categories
$inlist = array();
foreach ($candidates as $cat_code => $cat_info) {
	$inlist[] = "'" . sed_sql_prep($cat_code) . "'";
}
$inlist_sql = implode(',', $inlist);

$sql_count = sed_sql_query("SELECT page_cat, COUNT(*) AS cnt FROM $db_pages
	WHERE page_cat IN ($inlist_sql)
	AND (page_state='0' OR page_state='2')
	GROUP BY page_cat");

$pages_count_map = array();
while ($row = sed_sql_fetchassoc($sql_count)) {
	$pages_count_map[$row['page_cat']] = (int) $row['cnt'];
}

$has_items = false;
foreach ($candidates as $cat_code => $cat_info) {
	$pages_count = isset($pages_count_map[$cat_code]) ? $pages_count_map[$cat_code] : 0;
	if ($pages_count < 1) {
		continue;
	}

	$t->assign(array(
		'LIST_CATFIL_ITEM_URL' => sed_url('page', 'c=' . $cat_code),
		'LIST_CATFIL_ITEM_ACTIVE' => ($c == $cat_code) ? ' active' : '',
		'LIST_CATFIL_ITEM_CATID' => $cat_info['id'],
		'LIST_CATFIL_ITEM_TITLE' => $cat_info['title']
	));
	$t->parse('MAIN.LIST_CATFIL.LIST_CATFIL_ITEM');
	$has_items = true;
}

if (!$has_items) {
	return;
}

$t->assign(array(
	'LIST_CATFIL_ALL_URL' => sed_url('page', 'c=' . $catfil_parent),
	'LIST_CATFIL_ALL_ACTIVE' => ($c == $catfil_parent) ? ' active' : ''
));
$t->parse('MAIN.LIST_CATFIL');
