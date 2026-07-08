<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tpltags/tpltags.php
Version=185
Updated=2026-jul-07
Type=Plugin
Author=Seditio Team / Chris T
Description=Template tags directory for skin builders (XTemplate)
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tpltags
Part=main
File=tpltags
Hooks=standalone
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE') || !defined('SED_PLUG')) { 
	die('Wrong URL.'); 
}

include(SED_ROOT . '/plugins/tpltags/inc/functions.php');

global $db_tpltags, $tpls, $types, $versions, $yesno_arr, $sed_img_down, $sed_img_up, $a;

$db_tpltags = isset($db_tpltags) ? $db_tpltags : $cfg['sqldbprefix'] . 'tpltags';

$sql_tpls = sed_sql_query("SELECT tag_tpl FROM $db_tpltags GROUP BY tag_tpl ORDER BY tag_tpl ASC");
if ($sql_tpls) {
	while ($row_tpls = sed_sql_fetchassoc($sql_tpls)) {
		if (!empty($row_tpls['tag_tpl'])) {
			$tpls[] = $row_tpls['tag_tpl'];
		}
	}
}

$a = sed_import('a', 'G', 'ALP', 24);
$f = sed_import('f', 'G', 'TXT');
$m = sed_import('m', 'G', 'ALP');
$s = sed_import('s', 'G', 'ALP', 13);
$w = sed_import('w', 'G', 'ALP', 5);

// ---------- Breadcrumbs
$urlpaths = array();

$title = $L['plu_title'];		
$urlpaths[sed_url('plug', 'e=tpltags')] = $L['plu_title'];

$subtitle = $L['plu_subtitle_main'];

$total_global_tags = 0;

if (empty($f) && empty($m)) {
	$total_tags = 0;
	$aa = 0;
	$sql = sed_sql_query("SELECT tag_tpl, COUNT(*) as total FROM $db_tpltags WHERE 1 GROUP BY tag_tpl ORDER BY tag_tpl ASC");
	while ($row = sed_sql_fetchassoc($sql)) {
		$aa++;
		$t->assign(array(
			"TPL_ROW_URL" => sed_url("plug", "e=tpltags&f=" . $row['tag_tpl']),
			"TPL_ROW_TOTAL" => $row['total'],
			"TPL_ROW_TITLE" => $row['tag_tpl'],
			"TPL_ROW_COUNTER" => $aa,
			"TPL_ROW_ODDEVEN" => sed_build_oddeven($aa)
		));
		$total_tags = $total_tags + $row['total'];
		if ($row['tag_tpl'] == 'global') {
			$total_global_tags = $row['total'];
		}
		$t->parse("MAIN.HOME.TAGS");
	}

	$aa = 0;
	$sql = sed_sql_query("SELECT tag_ver, COUNT(*) as total FROM $db_tpltags WHERE 1 GROUP BY tag_ver ORDER BY tag_ver ASC");
	while ($row = sed_sql_fetchassoc($sql)) {
		$aa++;
		$t->assign(array(
			"VER_ROW_URL" => sed_url("plug", "e=tpltags&f=" . $row['tag_ver']),
			"VER_ROW_TOTAL" => $row['total'],
			"VER_ROW_TITLE" => $row['tag_ver'],
			"VER_ROW_COUNTER" => $aa,
			"VER_ROW_ODDEVEN" => sed_build_oddeven($aa)
		));
		$t->parse("MAIN.HOME.VER");
	}

	$t->assign(array(
		"TOTAL_TAGS_GLOBAL" => $total_global_tags,
		"TOTAL_TAGS_GLOBAL_URL" => sed_url("plug", "e=tpltags&f=global"),
		"TOTAL_TAGS" => $total_tags,
		"TOTAL_TAGS_URL" => sed_url("plug", "e=tpltags&f=all")
	));
	$t->parse("MAIN.HOME");	
}

elseif (!empty($f) && empty($m)) {

	if ($f == 'all') {
		$where_clause = "WHERE 1";
		$title = $L['plu_title_all'];		
		$urlpaths[sed_url("plug", "e=tpltags&f=all")] = $L['plu_title_all'];	
		$subtitle = $L['plu_subtitle_all'];
		$Pass = TRUE;
	}
	elseif ($f == 'global') {
		$where_clause = "WHERE tag_tpl='global'";
		$title = $L['plu_title_global'];		
		$urlpaths[sed_url("plug", "e=tpltags&f=global")] = $L['plu_title_global'];		
		$subtitle = $L['plu_subtitle_global'];
		$Pass = TRUE;
	}
	elseif (in_array($f, $versions)) {
		$where_clause = "WHERE tag_ver='$f'";
		$title = $L['plu_title_ver'] . " " . $f;		
		$urlpaths[sed_url("plug", "e=tpltags&f=" . $f)] = $L['plu_title_ver'] . " " . $f;	
		$subtitle = $L['plu_subtitle_ver'] . " " . $f;
		$Pass = TRUE;
	}
	elseif (in_array($f, $tpls)) {
		$where_clause = "WHERE tag_tpl='$f'";
		$title = $L['plu_title_tpl'] . " " . $f;		
		$urlpaths[sed_url("plug", "e=tpltags&f=" . $f)] = $L['plu_title_tpl'] . " " . $f;
		$subtitle = $L['plu_subtitle_tpl'] . " " . $f;
		$Pass = TRUE;
	}
	else {
		$Pass = FALSE;	
	}

	sed_block($Pass);

	if (empty($s) || !in_array($s, array('title', 'location', 'type', 'tpl', 'desc', 'ver'))) { $s = 'title'; }
	if (empty($w) || !in_array($w, array('asc', 'desc'))) { $w = 'asc'; }
	
	$t->assign(array(
		"TAG_TOP_TITLE" => "<a href=\"" . sed_url("plug", "e=tpltags&f=$f&s=title&w=asc") . "\">$sed_img_down</a><a href=\"" . sed_url("plug", "e=tpltags&f=$f&s=title&w=desc") . "\">$sed_img_up</a>",
		"TAG_TOP_LOC" => "<a href=\"" . sed_url("plug", "e=tpltags&f=$f&s=location&w=asc") . "\">$sed_img_down</a><a href=\"" . sed_url("plug", "e=tpltags&f=$f&s=location&w=desc") . "\">$sed_img_up</a>",
		"TAG_TOP_TYPE" => "<a href=\"" . sed_url("plug", "e=tpltags&f=$f&s=type&w=asc") . "\">$sed_img_down</a><a href=\"" . sed_url("plug", "e=tpltags&f=$f&s=type&w=desc") . "\">$sed_img_up</a>",
		"TAG_TOP_TPL" => "<a href=\"" . sed_url("plug", "e=tpltags&f=$f&s=tpl&w=asc") . "\">$sed_img_down</a><a href=\"" . sed_url("plug", "e=tpltags&f=$f&s=tpl&w=desc") . "\">$sed_img_up</a>",
		"TAG_TOP_DESC" => "<a href=\"" . sed_url("plug", "e=tpltags&f=$f&s=desc&w=asc") . "\">$sed_img_down</a><a href=\"" . sed_url("plug", "e=tpltags&f=$f&s=desc&w=desc") . "\">$sed_img_up</a>",
		"TAG_TOP_VER" => "<a href=\"" . sed_url("plug", "e=tpltags&f=$f&s=ver&w=asc") . "\">$sed_img_down</a><a href=\"" . sed_url("plug", "e=tpltags&f=$f&s=ver&w=desc") . "\">$sed_img_up</a>",
	));
		
	$aa = 0;
	$sql = sed_sql_query("SELECT * FROM $db_tpltags $where_clause ORDER BY tag_$s $w");
	while ($row = sed_sql_fetchassoc($sql)) {
		$row['tag_type'] = (empty($row['tag_type'])) ? '?' : $row['tag_type'];
		$row['tag_location'] = (empty($row['tag_location'])) ? '-' : $row['tag_location'];
		$aa++;
		$tag_admin = ($usr['isadmin']) ? "[<a href=\"" . sed_url("plug", "e=tpltags&m=delete&id=" . $row['tag_id']) . "\" onclick=\"return confirm('" . addslashes($L['tpltags_delete_hint']) . "');\">" . $L['tpltags_delete'] . "</a>][<a href=\"" . sed_url("plug", "e=tpltags&m=edit&id=" . $row['tag_id']) . "\">" . $L['tpltags_edit'] . "</a>] " : '';
		$t->assign(array(
			"TAG_ROW_COUNTER" => $aa,
			"TAG_ROW_ID" => $row['tag_id'],
			"TAG_ROW_TITLE" => $row['tag_title'],
			"TAG_ROW_LOCATION" => $row['tag_location'],
			"TAG_ROW_TYPE" => $row['tag_type'],
			"TAG_ROW_TPL" => $row['tag_tpl'],
			"TAG_ROW_URL" => sed_url("plug", "e=tpltags&f=" . $row['tag_tpl']),
			"TAG_ROW_DETAILS" => $row['tag_desc'],
			"TAG_ROW_VERSION" => $row['tag_ver'],
			"TAG_ROW_ADMIN" => $tag_admin,
			"TAG_ROW_ODDEVEN" => sed_build_oddeven($aa)
		));
		$t->parse("MAIN.TAGS.ROW");
	}
	$t->parse("MAIN.TAGS");
}

elseif (empty($f) && !empty($m)) {
	sed_block($usr['isadmin']);
	$id = sed_import('id', 'G', 'INT');
	$error_string = '';

	switch ($m) {
		case 'add':
			$title = $L['plu_title_add'];		
			$urlpaths[sed_url("plug", "e=tpltags&m=add")] = $L['plu_title_add'];	
			$subtitle = $L['plu_subtitle_add'];

			$tag = '';
			$tpl = '';
			$type = '';
			$version = '';
			$loc = '';
			$details = '';

			if ($a == 'add') {
				$tag = sed_import('tag', 'P', 'TXT');
				$tpl = sed_import('tpl', 'P', 'TXT');	
				$type = sed_import('type', 'P', 'TXT');
				$version = sed_import('version', 'P', 'TXT');			
				$loc = sed_import('loc', 'P', 'TXT');
				$details = sed_import('details', 'P', 'TXT');
				
				$error_string = (empty($tag)) ? $L['tpltags_tag_missing'] . "<br />" : '';
				$error_string .= (empty($tpl)) ? $L['tpltags_tpl_missing'] . "<br />" : '';
				$error_string .= (empty($version)) ? $L['tpltags_version_missing'] . "<br />" : '';
				
				if (empty($error_string)) {
					$sql = sed_sql_query("INSERT into $db_tpltags
						(tag_title, tag_location, tag_type, tag_tpl, tag_desc, tag_ver)
						VALUES
						('" . sed_sql_prep($tag) . "',
						'" . sed_sql_prep($loc) . "',
						'" . sed_sql_prep($type) . "',
						'" . sed_sql_prep($tpl) . "',
						'" . sed_sql_prep($details) . "',
						'" . sed_sql_prep($version) . "')");
						
					sed_redirect(sed_url("plug", "e=tpltags&f=" . $tpl, "", true));
					exit;
				}
			}

			if (!empty($error_string)) {
				$t->assign("ERROR_BODY", $error_string);
				$t->parse("MAIN.ERROR");
			}

			$t->assign(array(
				"TAG_ADD_FORM_ACTION" => sed_url("plug", "e=tpltags&m=add&a=add"),
				"TAG_ADD_TITLE" => "<input type=\"text\" class=\"text\" name=\"tag\" value=\"" . sed_cc($tag) . "\" size=\"56\" maxlength=\"255\" />",
				"TAG_ADD_TPL" => tpltags_build_select($tpl, 'tpl', $tpls),
				"TAG_ADD_TYPE" => tpltags_build_select($type, 'type', $types),	
				"TAG_ADD_VERSION" => tpltags_build_select($version, 'version', $versions),	
				"TAG_ADD_LOCATION" => "<input type=\"text\" class=\"text\" name=\"loc\" value=\"" . sed_cc($loc) . "\" size=\"56\" maxlength=\"255\" />",
				"TAG_ADD_DETAILS" => "<input type=\"text\" class=\"text\" name=\"details\" value=\"" . sed_cc($details) . "\" size=\"56\" maxlength=\"255\" />",
			));
			$t->parse("MAIN.ADD_NEW_TAG");
			break;

		case 'edit':
			$title = $L['plu_title_edit'];
			$urlpaths[sed_url("plug", "e=tpltags&m=edit&id=" . $id)] = $L['plu_title_edit'];	
			$subtitle = $L['plu_subtitle_edit'];
				
			if ($a == 'update') {
				$tag = sed_import('tag', 'P', 'TXT');
				$tpl = sed_import('tpl', 'P', 'TXT');	
				$type = sed_import('type', 'P', 'TXT');
				$version = sed_import('version', 'P', 'TXT');			
				$loc = sed_import('loc', 'P', 'TXT');
				$details = sed_import('details', 'P', 'TXT');
				$delete_tag = sed_import('delete_tag', 'P', 'BOL');
				
				$error_string = (empty($tag)) ? $L['tpltags_tag_missing'] . "<br />" : '';
				$error_string .= (empty($tpl)) ? $L['tpltags_tpl_missing'] . "<br />" : '';
				$error_string .= (empty($version)) ? $L['tpltags_version_missing'] . "<br />" : '';

				if (empty($error_string) || $delete_tag) {
					if ($delete_tag) {
						$sql = sed_sql_query("DELETE FROM $db_tpltags WHERE tag_id='$id'");
						sed_redirect(sed_url("plug", "e=tpltags&f=" . $tpl, "", true));
						exit;
					} else {
						$sql = sed_sql_query("UPDATE $db_tpltags SET
							tag_title = '" . sed_sql_prep($tag) . "',
							tag_location = '" . sed_sql_prep($loc) . "',
							tag_type = '" . sed_sql_prep($type) . "',
							tag_tpl = '" . sed_sql_prep($tpl) . "',
							tag_desc = '" . sed_sql_prep($details) . "',
							tag_ver = '" . sed_sql_prep($version) . "'
							WHERE tag_id='$id'");
							
						sed_redirect(sed_url("plug", "e=tpltags&f=" . $tpl, "", true));
						exit;
					}
				}
			}

			$sql = sed_sql_query("SELECT * FROM $db_tpltags WHERE tag_id='$id' LIMIT 1");
			sed_die(sed_sql_numrows($sql) == 0);
			$row = sed_sql_fetchassoc($sql);

			if (!empty($error_string)) {
				$t->assign("ERROR_BODY", $error_string);
				$t->parse("MAIN.ERROR");
			}
		
			$t->assign(array(
				"TAG_EDIT_FORM_ACTION" => sed_url("plug", "e=tpltags&m=edit&a=update&id=" . $row['tag_id']),
				"TAG_EDIT_TITLE" => "<input type=\"text\" class=\"text\" name=\"tag\" value=\"" . sed_cc($row['tag_title']) . "\" size=\"56\" maxlength=\"255\" />",
				"TAG_EDIT_TPL" => tpltags_build_select($row['tag_tpl'], 'tpl', $tpls),
				"TAG_EDIT_TYPE" => tpltags_build_select($row['tag_type'], 'type', $types),	
				"TAG_EDIT_VERSION" => tpltags_build_select($row['tag_ver'], 'version', $versions),	
				"TAG_EDIT_LOCATION" => "<input type=\"text\" class=\"text\" name=\"loc\" value=\"" . sed_cc($row['tag_location']) . "\" size=\"56\" maxlength=\"255\" />",
				"TAG_EDIT_DETAILS" => "<input type=\"text\" class=\"text\" name=\"details\" value=\"" . sed_cc($row['tag_desc']) . "\" size=\"56\" maxlength=\"255\" />",
				"TAG_EDIT_DELETE" => sed_radiobox("delete_tag", $yesno_arr) 	
			));
			$t->parse("MAIN.EDIT_TAG");
			break;
		
		case 'delete':
			$sql = sed_sql_query("SELECT tag_tpl FROM $db_tpltags WHERE tag_id='$id' LIMIT 1");
			sed_die(sed_sql_numrows($sql) == 0);
			$row = sed_sql_fetchassoc($sql);
			
			$sql = sed_sql_query("DELETE FROM $db_tpltags WHERE tag_id='$id'");
			
			sed_redirect(sed_url("plug", "e=tpltags&f=" . $row['tag_tpl'], "", true));
			exit;
			break;

		default:
			sed_die();
			break;
	}
}

$t->assign(array(
	"TAGS_TITLE" => $title,
	"TAGS_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"TAGS_SUBTITLE" => $subtitle,
	"TAG_ADD" => ($usr['isadmin']) ? "<a href=\"" . sed_url("plug", "e=tpltags&m=add") . "\" class=\"btn\">" . $L['tpltags_add'] . "</a>" : '',
));
