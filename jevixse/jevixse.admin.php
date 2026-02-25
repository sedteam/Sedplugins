<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/jevixse/jevixse.admin.php
Version=2.0
Updated=2026-feb-24
Type=Plugin
Author=Seditio Team
Description=JevixSE admin - tags and attributes by editor mode
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=jevixse
Part=admin
File=jevixse.admin
Hooks=tools
Tags=
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('plug', 'jevixse');
sed_block($usr['auth_read']);

$mode = sed_import('mode', 'G', 'ALP', 16);
if (!in_array($mode, array('full', 'medium', 'micro'), true)) {
	$mode = 'full';
}
$n = sed_import('n', 'G', 'ALP', 16);
$tag = sed_import('tag', 'G', 'ALP', 32);
$a = sed_import('a', 'P', 'ALP', 16);
if (empty($a)) {
	$a = sed_import('a', 'G', 'ALP', 16);
}

$jevix_rules_path = SED_ROOT . '/plugins/jevixse/inc/jevixse.rules.php';
require_once SED_ROOT . '/plugins/jevixse/inc/jevixse.defaults.php';

function sed_jevixse_write_rules($rules)
{
	$def = sed_jevixse_default_behavior();
	foreach ($rules as $mode => &$data) {
		if (!isset($def[$mode])) {
			continue;
		}
		foreach ($def[$mode] as $key => $val) {
			if (!isset($data[$key])) {
				$data[$key] = $val;
			}
		}
	}
	unset($data);
	$path = SED_ROOT . '/plugins/jevixse/inc/jevixse.rules.php';
	$out = "<?php\nif (!defined('SED_CODE')) { exit; }\nreturn " . sed_jevixse_rules_export($rules) . ";\n";
	file_put_contents($path, $out);
}

function sed_jevixse_rules_export($rules)
{
	$behavior_keys = array('short', 'preformatted', 'cut_with_content', 'empty', 'no_typography', 'params_required', 'childs', 'param_default', 'auto_replace', 'xhtml', 'auto_br', 'auto_link');
	$p = array();
	foreach ($rules as $mode => $data) {
		$parts = array();
		$parts[] = "'tags' => " . var_export(isset($data['tags']) ? $data['tags'] : array(), true);
		if (!empty($data['tags_disabled']) && is_array($data['tags_disabled'])) {
			$parts[] = "'tags_disabled' => " . var_export($data['tags_disabled'], true);
		}
		$parts[] = "'params' => " . sed_jevixse_export_params(isset($data['params']) ? $data['params'] : array());
		foreach ($behavior_keys as $k) {
			if (isset($data[$k])) {
				$parts[] = "'" . $k . "' => " . var_export($data[$k], true);
			}
		}
		$p[] = "'" . $mode . "' => array(\n\t\t" . implode(",\n\t\t", $parts) . "\n\t)";
	}
	return "array(\n\t" . implode(",\n\t", $p) . "\n)";
}

function sed_jevixse_export_params($params)
{
	$p = array();
	foreach ($params as $tag => $arr) {
		$p[] = "'" . addslashes($tag) . "' => " . var_export($arr, true);
	}
	return "array(\n\t\t\t" . implode(",\n\t\t\t", $p) . "\n\t\t)";
}

if (file_exists($jevix_rules_path)) {
	$jevix_rules = include $jevix_rules_path;
	if (!is_array($jevix_rules)) {
		$jevix_rules = array();
	}
} else {
	$jevix_rules = sed_jevixse_default_rules();
}

$def_rules = sed_jevixse_default_rules();
$def_behavior = sed_jevixse_default_behavior();
foreach (array('full', 'medium', 'micro') as $m) {
	if (empty($jevix_rules[$m]) || !is_array($jevix_rules[$m])) {
		$jevix_rules[$m] = isset($def_rules[$m]) ? $def_rules[$m] : array('tags' => array(), 'params' => array());
	}
	if (empty($jevix_rules[$m]['tags'])) {
		$jevix_rules[$m]['tags'] = array();
	}
	if (empty($jevix_rules[$m]['params'])) {
		$jevix_rules[$m]['params'] = array();
	}
	foreach (isset($def_behavior[$m]) ? $def_behavior[$m] : array() as $bk => $bv) {
		if (!isset($jevix_rules[$m][$bk])) {
			$jevix_rules[$m][$bk] = $bv;
		}
	}
}

$s = new XTemplate(SED_ROOT . '/plugins/jevixse/jevixse.admin.tpl');
if (!isset($L)) {
	$L = array();
}
$s->assign('PHP', array('L' => $L));

$base_mode_url = sed_url('admin', 'm=manage&p=jevixse' . ($n === 'behavior' ? '&n=behavior' : ''), '', true) . '&mode=';
$jevix_mode_select = '<select name="jevix_mode" onchange="sedjs.redirect(this)" size="1">';
$jevix_mode_select .= '<option value="' . $base_mode_url . 'full"' . ($mode === 'full' ? ' selected="selected"' : '') . '>' . $L['jevix_mode_full'] . '</option>';
$jevix_mode_select .= '<option value="' . $base_mode_url . 'medium"' . ($mode === 'medium' ? ' selected="selected"' : '') . '>' . $L['jevix_mode_medium'] . '</option>';
$jevix_mode_select .= '<option value="' . $base_mode_url . 'micro"' . ($mode === 'micro' ? ' selected="selected"' : '') . '>' . $L['jevix_mode_micro'] . '</option>';
$jevix_mode_select .= '</select>';

$s->assign(array(
	'JEVIX_MODE_SELECT'        => $jevix_mode_select,
	'JEVIX_MODE_FULL_ACTIVE'   => ($mode === 'full') ? 'active' : '',
	'JEVIX_MODE_MEDIUM_ACTIVE' => ($mode === 'medium') ? 'active' : '',
	'JEVIX_MODE_MICRO_ACTIVE'  => ($mode === 'micro') ? 'active' : '',
	'JEVIX_MODE'               => $mode,
	'JEVIX_URL_FULL'           => sed_url('admin', 'm=manage&p=jevixse&mode=full'),
	'JEVIX_URL_MEDIUM'         => sed_url('admin', 'm=manage&p=jevixse&mode=medium'),
	'JEVIX_URL_MICRO'          => sed_url('admin', 'm=manage&p=jevixse&mode=micro'),
	'JEVIX_FORM_ACTION'        => sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode),
	'JEVIX_URL_TAGS'           => sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode),
	'JEVIX_URL_BEHAVIOR'       => sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode . '&n=behavior'),
	'JEVIX_NAV_TAGS_ACTIVE'    => ($n !== 'behavior') ? 'active' : '',
	'JEVIX_NAV_BEHAVIOR_ACTIVE' => ($n === 'behavior') ? 'active' : '',
));

if ($n === 'attrs' && $tag !== '') {
	sed_block($usr['auth_write']);

	$tags_list = array_flip($jevix_rules[$mode]['tags']);
	if (!isset($tags_list[$tag])) {
		sed_redirect(sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode, '', true));
		exit;
	}

	$attrs = isset($jevix_rules[$mode]['params'][$tag]) ? $jevix_rules[$mode]['params'][$tag] : array();

	if ($a === 'saveattrs') {
		sed_check_xg();
		$new_attrs = array();
		$attr_names = sed_import('attr_name', 'P', 'ARR');
		$attr_types = sed_import('attr_type', 'P', 'ARR');
		$attr_values = sed_import('attr_values', 'P', 'ARR');
		$attr_required_post = sed_import('attr_required', 'P', 'ARR');
		if (is_array($attr_names)) {
			foreach ($attr_names as $i => $name) {
				$name = trim(preg_replace('/[^a-z0-9_-]/i', '', $name));
				if ($name === '') {
					continue;
				}
				$type = isset($attr_types[$i]) ? $attr_types[$i] : '';
				$vals = isset($attr_values[$i]) ? $attr_values[$i] : '';
				if ($type === 'list' && $vals !== '') {
					$arr = array_map('trim', explode(',', $vals));
					$arr = array_filter($arr);
					$new_attrs[$name] = array_values($arr);
				} elseif (in_array($type, array('#text', '#int', '#link', '#image'), true)) {
					$new_attrs[$name] = $type;
				} else {
					$new_attrs[$name] = true;
				}
			}
		}
		$jevix_rules[$mode]['params'][$tag] = $new_attrs;
		$required_for_tag = array();
		if (is_array($attr_required_post)) {
			foreach ($attr_required_post as $aname) {
				$aname = trim(preg_replace('/[^a-z0-9_-]/i', '', $aname));
				if ($aname !== '' && isset($new_attrs[$aname])) {
					$required_for_tag[] = $aname;
				}
			}
		}
		if (!isset($jevix_rules[$mode]['params_required'])) {
			$jevix_rules[$mode]['params_required'] = array();
		}
		$jevix_rules[$mode]['params_required'][$tag] = array_values(array_unique($required_for_tag));
		sed_jevixse_write_rules($jevix_rules);
		sed_redirect(sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode, '', true));
		exit;
	}

	if ($a === 'delattr' && sed_import('attr', 'G', 'ALP', 32) !== '') {
		sed_check_xg();
		$del_attr = sed_import('attr', 'G', 'ALP', 32);
		if (isset($jevix_rules[$mode]['params'][$tag][$del_attr])) {
			unset($jevix_rules[$mode]['params'][$tag][$del_attr]);
			if (!empty($jevix_rules[$mode]['params_required'][$tag]) && is_array($jevix_rules[$mode]['params_required'][$tag])) {
				$jevix_rules[$mode]['params_required'][$tag] = array_values(array_filter($jevix_rules[$mode]['params_required'][$tag], function ($a) use ($del_attr) {
					return $a !== $del_attr;
				}));
			}
			sed_jevixse_write_rules($jevix_rules);
		}
		sed_redirect(sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode . '&n=attrs&tag=' . $tag, '', true));
		exit;
	}

	$s->assign(array(
		'JEVIX_ATTRS_TAG'        => sed_cc($tag),
		'JEVIX_ATTRS_BACK_URL'   => sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode),
		'JEVIX_ATTRS_FORM_ACTION' => sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode . '&n=attrs&tag=' . $tag . '&a=saveattrs&' . sed_xg()),
	));

	$params_required_tag = isset($jevix_rules[$mode]['params_required'][$tag]) && is_array($jevix_rules[$mode]['params_required'][$tag])
		? $jevix_rules[$mode]['params_required'][$tag] : array();
	$type_options = array('' => 'jevix_type_none', '#text' => '#text', '#int' => '#int', '#link' => '#link', '#image' => '#image', 'list' => 'jevix_type_list');
	foreach ($attrs as $attr_name => $attr_val) {
		if (is_int($attr_name) || (is_string($attr_name) && ctype_digit($attr_name))) {
			$attr_name = $attr_val;
			$attr_val = true;
		}
		$type_val = is_array($attr_val) ? 'list' : (is_string($attr_val) ? $attr_val : '');
		$values_str = is_array($attr_val) ? implode(', ', $attr_val) : '';
		$opts = '';
		foreach ($type_options as $k => $v) {
			$label = isset($L[$v]) ? $L[$v] : $v;
			$sel = ($k === $type_val) ? ' selected="selected"' : '';
			$opts .= '<option value="' . sed_cc($k) . '"' . $sel . '>' . sed_cc($label) . '</option>';
		}
		$required_checked = in_array($attr_name, $params_required_tag, true) ? ' checked="checked"' : '';
		$s->assign(array(
			'JEVIX_ATTR_ROW_NAME'    => sed_cc($attr_name),
			'JEVIX_ATTR_ROW_TYPE_OPTIONS' => $opts,
			'JEVIX_ATTR_ROW_VALUES'  => sed_cc($values_str),
			'JEVIX_ATTR_ROW_REQUIRED' => $required_checked,
			'JEVIX_ATTR_ROW_DEL_URL' => sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode . '&n=attrs&tag=' . $tag . '&a=delattr&attr=' . urlencode($attr_name) . '&' . sed_xg()),
		));
		$s->parse('ADMIN_JEVIX.JEVIX_CONTENT.JEVIX_ATTRS.JEVIX_ATTR_ROW');
	}

	$s->parse('ADMIN_JEVIX.JEVIX_CONTENT.JEVIX_ATTRS');
	$s->parse('ADMIN_JEVIX.JEVIX_CONTENT');
	$s->parse('ADMIN_JEVIX');
	$plugin_body = $s->text('ADMIN_JEVIX');
	return;
}

if ($n === 'opts' && $tag !== '') {
	sed_block($usr['auth_write']);

	$tags_list = array_flip($jevix_rules[$mode]['tags']);
	if (!isset($tags_list[$tag])) {
		sed_redirect(sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode, '', true));
		exit;
	}

	if ($a === 'saveopts') {
		sed_check_xg();
		$opt_short = sed_import('opt_short', 'P', 'INT');
		$opt_preformatted = sed_import('opt_preformatted', 'P', 'INT');
		$opt_cut = sed_import('opt_cut_with_content', 'P', 'INT');
		$opt_empty = sed_import('opt_empty', 'P', 'INT');
		$opt_no_typo = sed_import('opt_no_typography', 'P', 'INT');
		foreach (array('short', 'preformatted', 'cut_with_content', 'empty', 'no_typography') as $key) {
			if (!isset($jevix_rules[$mode][$key]) || !is_array($jevix_rules[$mode][$key])) {
				$jevix_rules[$mode][$key] = array();
			}
			$arr = &$jevix_rules[$mode][$key];
			$arr = array_values(array_filter($arr, function ($t) use ($tag) { return $t !== $tag; }));
			$on = false;
			if ($key === 'short') $on = (bool)$opt_short;
			elseif ($key === 'preformatted') $on = (bool)$opt_preformatted;
			elseif ($key === 'cut_with_content') $on = (bool)$opt_cut;
			elseif ($key === 'empty') $on = (bool)$opt_empty;
			elseif ($key === 'no_typography') $on = (bool)$opt_no_typo;
			if ($on) {
				$arr[] = $tag;
				sort($arr);
			}
			unset($arr);
		}
		sed_jevixse_write_rules($jevix_rules);
		sed_redirect(sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode, '', true));
		exit;
	}

	$opts_short = in_array($tag, isset($jevix_rules[$mode]['short']) ? $jevix_rules[$mode]['short'] : array(), true) ? ' checked="checked"' : '';
	$opts_preformatted = in_array($tag, isset($jevix_rules[$mode]['preformatted']) ? $jevix_rules[$mode]['preformatted'] : array(), true) ? ' checked="checked"' : '';
	$opts_cut = in_array($tag, isset($jevix_rules[$mode]['cut_with_content']) ? $jevix_rules[$mode]['cut_with_content'] : array(), true) ? ' checked="checked"' : '';
	$opts_empty = in_array($tag, isset($jevix_rules[$mode]['empty']) ? $jevix_rules[$mode]['empty'] : array(), true) ? ' checked="checked"' : '';
	$opts_no_typo = in_array($tag, isset($jevix_rules[$mode]['no_typography']) ? $jevix_rules[$mode]['no_typography'] : array(), true) ? ' checked="checked"' : '';

	$s->assign(array(
		'JEVIX_OPTS_TAG'         => sed_cc($tag),
		'JEVIX_OPTS_BACK_URL'    => sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode),
		'JEVIX_OPTS_FORM_ACTION' => sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode . '&n=opts&tag=' . urlencode($tag) . '&a=saveopts&' . sed_xg()),
		'JEVIX_OPTS_SHORT'       => $opts_short,
		'JEVIX_OPTS_PREFORMATTED' => $opts_preformatted,
		'JEVIX_OPTS_CUT'         => $opts_cut,
		'JEVIX_OPTS_EMPTY'       => $opts_empty,
		'JEVIX_OPTS_NO_TYPOGRAPHY' => $opts_no_typo,
	));
	$s->parse('ADMIN_JEVIX.JEVIX_CONTENT.JEVIX_OPTS');
	$s->parse('ADMIN_JEVIX.JEVIX_CONTENT');
	$s->parse('ADMIN_JEVIX');
	$plugin_body = $s->text('ADMIN_JEVIX');
	return;
}

if ($n === 'behavior') {
	sed_block($usr['auth_write']);

	if ($a === 'savebehavior') {
		sed_check_xg();
		$auto_from = sed_import('auto_from', 'P', 'ARR');
		$auto_to = sed_import('auto_to', 'P', 'ARR');
		$from_arr = array();
		$to_arr = array();
		if (is_array($auto_from) && is_array($auto_to)) {
			foreach ($auto_from as $i => $f) {
				$f = trim((string)$f);
				$to_val = isset($auto_to[$i]) ? trim((string)$auto_to[$i]) : '';
				if ($f !== '' || $to_val !== '') {
					$from_arr[] = $f;
					$to_arr[] = $to_val;
				}
			}
		}
		$jevix_rules[$mode]['auto_replace'] = array('from' => $from_arr, 'to' => $to_arr);

		$c_parent = sed_import('childs_parent', 'P', 'ARR');
		$c_children = sed_import('childs_children', 'P', 'ARR');
		$c_container = sed_import('childs_container', 'P', 'ARR');
		$c_childonly = sed_import('childs_childonly', 'P', 'ARR');
		$childs = array();
		if (is_array($c_parent) && is_array($c_children)) {
			foreach ($c_parent as $i => $p) {
				$p = trim(preg_replace('/[^a-z0-9_-]/i', '', (string)$p));
				if ($p === '') continue;
				$ch = isset($c_children[$i]) ? $c_children[$i] : '';
				$ch_arr = array_map('trim', array_filter(explode(',', preg_replace('/[^a-z0-9,_\s-]/i', '', $ch))));
				$ch_arr = array_values(array_unique(array_filter($ch_arr)));
				$container = is_array($c_container) && isset($c_container[$i]);
				$childonly = is_array($c_childonly) && isset($c_childonly[$i]);
				$childs[] = array($p, $ch_arr, $container, $childonly);
			}
		}
		$jevix_rules[$mode]['childs'] = $childs;

		$pd_tag = sed_import('param_default_tag', 'P', 'ARR');
		$pd_attr = sed_import('param_default_attr', 'P', 'ARR');
		$pd_value = sed_import('param_default_value', 'P', 'ARR');
		$pd_rewrite = sed_import('param_default_rewrite', 'P', 'ARR');
		$param_default = array();
		if (is_array($pd_tag) && is_array($pd_attr)) {
			foreach ($pd_tag as $i => $pt) {
				$pt = trim(preg_replace('/[^a-z0-9_-]/i', '', (string)$pt));
				$a = isset($pd_attr[$i]) ? trim(preg_replace('/[^a-z0-9_-]/i', '', (string)$pd_attr[$i])) : '';
				if ($pt === '' || $a === '') continue;
				$v = isset($pd_value[$i]) ? (string)$pd_value[$i] : '';
				$rew = is_array($pd_rewrite) && isset($pd_rewrite[$i]);
				$param_default[] = array($pt, $a, $v, $rew);
			}
		}
		$jevix_rules[$mode]['param_default'] = $param_default;

		$jevix_rules[$mode]['xhtml'] = isset($_POST['behavior_opt_xhtml']);
		$jevix_rules[$mode]['auto_br'] = isset($_POST['behavior_opt_auto_br']);
		$jevix_rules[$mode]['auto_link'] = isset($_POST['behavior_opt_auto_link']);

		sed_jevixse_write_rules($jevix_rules);
		sed_redirect(sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode . '&n=behavior', '', true));
		exit;
	}

	$def_b = sed_jevixse_default_behavior();
	$def_mode = isset($def_b[$mode]) ? $def_b[$mode] : array();
	$s->assign(array(
		'JEVIX_BEHAVIOR_XHTML'     => (isset($jevix_rules[$mode]['xhtml']) ? (bool)$jevix_rules[$mode]['xhtml'] : !empty($def_mode['xhtml'])) ? ' checked="checked"' : '',
		'JEVIX_BEHAVIOR_AUTO_BR'   => (isset($jevix_rules[$mode]['auto_br']) ? (bool)$jevix_rules[$mode]['auto_br'] : !empty($def_mode['auto_br'])) ? ' checked="checked"' : '',
		'JEVIX_BEHAVIOR_AUTO_LINK' => (isset($jevix_rules[$mode]['auto_link']) ? (bool)$jevix_rules[$mode]['auto_link'] : !empty($def_mode['auto_link'])) ? ' checked="checked"' : '',
	));

	$ar = isset($jevix_rules[$mode]['auto_replace']) && is_array($jevix_rules[$mode]['auto_replace'])
		? $jevix_rules[$mode]['auto_replace'] : array('from' => array(), 'to' => array());
	$from_list = isset($ar['from']) ? $ar['from'] : array();
	$to_list = isset($ar['to']) ? $ar['to'] : array();
	$max_ar = max(count($from_list), count($to_list), 1);
	$empty_ar_rows = 1;
	for ($i = 0; $i < $max_ar + $empty_ar_rows; $i++) {
		$s->assign(array(
			'JEVIX_AR_FROM' => sed_cc(isset($from_list[$i]) ? $from_list[$i] : ''),
			'JEVIX_AR_TO'   => sed_cc(isset($to_list[$i]) ? $to_list[$i] : ''),
		));
		$s->parse('ADMIN_JEVIX.JEVIX_CONTENT.JEVIX_MAIN_BEHAVIOR.JEVIX_AUTOREPLACE_ROW');
	}
	$childs_list = isset($jevix_rules[$mode]['childs']) && is_array($jevix_rules[$mode]['childs']) ? $jevix_rules[$mode]['childs'] : array();
	$child_idx = 0;
	foreach ($childs_list as $row) {
		$parent = is_array($row) && isset($row[0]) ? $row[0] : '';
		$children = is_array($row) && isset($row[1]) ? (is_array($row[1]) ? implode(', ', $row[1]) : $row[1]) : '';
		$container = is_array($row) && !empty($row[2]) ? ' checked="checked"' : '';
		$childonly = is_array($row) && !empty($row[3]) ? ' checked="checked"' : '';
		$s->assign(array(
			'JEVIX_CHILD_INDEX'     => $child_idx,
			'JEVIX_CHILD_PARENT'   => sed_cc($parent),
			'JEVIX_CHILD_CHILDREN' => sed_cc($children),
			'JEVIX_CHILD_CONTAINER' => $container,
			'JEVIX_CHILD_CHILDONLY' => $childonly,
		));
		$s->parse('ADMIN_JEVIX.JEVIX_CONTENT.JEVIX_MAIN_BEHAVIOR.JEVIX_CHILDS_ROW');
		$child_idx++;
	}
	// Пустая строка для добавления новой связи
	$s->assign(array('JEVIX_CHILD_INDEX' => $child_idx, 'JEVIX_CHILD_PARENT' => '', 'JEVIX_CHILD_CHILDREN' => '', 'JEVIX_CHILD_CONTAINER' => '', 'JEVIX_CHILD_CHILDONLY' => ''));
	$s->parse('ADMIN_JEVIX.JEVIX_CONTENT.JEVIX_MAIN_BEHAVIOR.JEVIX_CHILDS_ROW');
	$pd_list = isset($jevix_rules[$mode]['param_default']) && is_array($jevix_rules[$mode]['param_default']) ? $jevix_rules[$mode]['param_default'] : array();
	$pd_idx = 0;
	foreach ($pd_list as $row) {
		$row_tag = is_array($row) && isset($row[0]) ? $row[0] : '';
		$a = is_array($row) && isset($row[1]) ? $row[1] : '';
		$v = is_array($row) && isset($row[2]) ? $row[2] : '';
		$rew = is_array($row) && !empty($row[3]) ? ' checked="checked"' : '';
		$s->assign(array(
			'JEVIX_PD_INDEX'   => $pd_idx,
			'JEVIX_PD_TAG'    => sed_cc($row_tag),
			'JEVIX_PD_ATTR'   => sed_cc($a),
			'JEVIX_PD_VALUE'  => sed_cc($v),
			'JEVIX_PD_REWRITE' => $rew,
		));
		$s->parse('ADMIN_JEVIX.JEVIX_CONTENT.JEVIX_MAIN_BEHAVIOR.JEVIX_PARAM_DEFAULT_ROW');
		$pd_idx++;
	}
	// Пустая строка для добавления нового атрибута по умолчанию
	$s->assign(array('JEVIX_PD_INDEX' => $pd_idx, 'JEVIX_PD_TAG' => '', 'JEVIX_PD_ATTR' => '', 'JEVIX_PD_VALUE' => '', 'JEVIX_PD_REWRITE' => ''));
	$s->parse('ADMIN_JEVIX.JEVIX_CONTENT.JEVIX_MAIN_BEHAVIOR.JEVIX_PARAM_DEFAULT_ROW');
	$s->assign('JEVIX_BEHAVIOR_FORM_ACTION', sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode . '&n=behavior&a=savebehavior&' . sed_xg()));
	$s->parse('ADMIN_JEVIX.JEVIX_CONTENT.JEVIX_MAIN_BEHAVIOR');
	$s->parse('ADMIN_JEVIX.JEVIX_CONTENT');
	$s->parse('ADMIN_JEVIX');
	$plugin_body = $s->text('ADMIN_JEVIX');
	return;
}

if ($a === 'savetags') {
	sed_block($usr['auth_write']);
	sed_check_xg();
	$tags_list_post = sed_import('tags_list', 'P', 'ARR');
	$tags_active_post = sed_import('tags_active', 'P', 'ARR');
	$new_tag = sed_import('newtag', 'P', 'TXT', 32);
	$all_tags = array();
	if (is_array($tags_list_post)) {
		foreach ($tags_list_post as $tname) {
			$tname = strtolower(trim(preg_replace('/[^a-z0-9_-]/i', '', $tname)));
			if ($tname !== '' && !in_array($tname, $all_tags, true)) {
				$all_tags[] = $tname;
			}
		}
	}
	$active_set = array();
	if (is_array($tags_active_post)) {
		foreach ($tags_active_post as $tname) {
			$tname = strtolower(trim(preg_replace('/[^a-z0-9_-]/i', '', $tname)));
			if ($tname !== '') {
				$active_set[$tname] = true;
			}
		}
	}
	$disabled = array_values(array_diff($all_tags, array_keys($active_set)));
	if ($new_tag !== '') {
		$new_tag = strtolower(trim(preg_replace('/[^a-z0-9_-]/i', '', $new_tag)));
		if ($new_tag !== '' && !in_array($new_tag, $all_tags, true)) {
			$all_tags[] = $new_tag;
			$active_set[$new_tag] = true;
			sort($all_tags);
		}
	} else {
		sort($all_tags);
	}
	$jevix_rules[$mode]['tags'] = $all_tags;
	$jevix_rules[$mode]['tags_disabled'] = $disabled;
	sed_jevixse_write_rules($jevix_rules);
	sed_redirect(sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode, '', true));
	exit;
}

if ($a === 'deltag' && $tag !== '') {
	sed_block($usr['auth_write']);
	sed_check_xg();
	$tag = strtolower(trim(preg_replace('/[^a-z0-9_-]/i', '', $tag)));
	$jevix_rules[$mode]['tags'] = array_values(array_filter($jevix_rules[$mode]['tags'], function ($t) use ($tag) {
		return $t !== $tag;
	}));
	if (!empty($jevix_rules[$mode]['tags_disabled']) && is_array($jevix_rules[$mode]['tags_disabled'])) {
		$jevix_rules[$mode]['tags_disabled'] = array_values(array_filter($jevix_rules[$mode]['tags_disabled'], function ($t) use ($tag) {
			return $t !== $tag;
		}));
	}
	if (isset($jevix_rules[$mode]['params'][$tag])) {
		unset($jevix_rules[$mode]['params'][$tag]);
	}
	foreach (array('short', 'preformatted', 'cut_with_content', 'empty', 'no_typography') as $key) {
		if (!empty($jevix_rules[$mode][$key]) && is_array($jevix_rules[$mode][$key])) {
			$jevix_rules[$mode][$key] = array_values(array_filter($jevix_rules[$mode][$key], function ($t) use ($tag) {
				return $t !== $tag;
			}));
		}
	}
	if (!empty($jevix_rules[$mode]['params_required'][$tag])) {
		unset($jevix_rules[$mode]['params_required'][$tag]);
	}
	sed_jevixse_write_rules($jevix_rules);
	sed_redirect(sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode, '', true));
	exit;
}

$tags_list = $jevix_rules[$mode]['tags'];
$tags_disabled = isset($jevix_rules[$mode]['tags_disabled']) && is_array($jevix_rules[$mode]['tags_disabled']) ? $jevix_rules[$mode]['tags_disabled'] : array();
$params = isset($jevix_rules[$mode]['params']) ? $jevix_rules[$mode]['params'] : array();
foreach ($tags_list as $tname) {
	$attr_names = array();
	if (isset($params[$tname]) && is_array($params[$tname])) {
		foreach ($params[$tname] as $k => $v) {
			if (is_int($k) && is_string($v)) {
				$attr_names[] = $v;
			} elseif (is_string($k) && $k !== '') {
				$attr_names[] = $k;
			}
		}
	}
	$attr_list = implode(', ', $attr_names);
	$is_active = !in_array($tname, $tags_disabled, true);
	$s->assign(array(
		'JEVIX_TAG_ROW_ACTIVE'   => $is_active,
		'JEVIX_TAG_ROW_CHECKED' => $is_active ? ' checked="checked"' : '',
		'JEVIX_TAG_ROW_NAME'     => sed_cc($tname),
		'JEVIX_TAG_ROW_ATTRS'    => sed_cc($attr_list),
		'JEVIX_TAG_ROW_ATTR_URL' => sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode . '&n=attrs&tag=' . urlencode($tname)),
		'JEVIX_TAG_ROW_OPT_URL'  => sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode . '&n=opts&tag=' . urlencode($tname)),
		'JEVIX_TAG_ROW_DEL_URL'  => sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode . '&a=deltag&tag=' . urlencode($tname) . '&' . sed_xg()),
	));
	$s->parse('ADMIN_JEVIX.JEVIX_CONTENT.JEVIX_MAIN_TAGS.JEVIX_TAG_LIST.JEVIX_TAG_ROW');
}

$s->assign('JEVIX_FORM_ACTION_SAVE', sed_url('admin', 'm=manage&p=jevixse&mode=' . $mode . '&a=savetags&' . sed_xg()));

$s->parse('ADMIN_JEVIX.JEVIX_CONTENT.JEVIX_MAIN_TAGS.JEVIX_TAG_LIST');
$s->parse('ADMIN_JEVIX.JEVIX_CONTENT.JEVIX_MAIN_TAGS');
$s->parse('ADMIN_JEVIX.JEVIX_CONTENT');
$s->parse('ADMIN_JEVIX');
$plugin_body = $s->text('ADMIN_JEVIX');
