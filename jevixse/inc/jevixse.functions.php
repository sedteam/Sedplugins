<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

Public API: sed_jevixse(), sed_external_link_encode().
Loaded by jevixse.import.filter.php so available when JevixSE plugin is used.
==================== */

if (!defined('SED_CODE')) {
	exit;
}

function sed_external_link_encode($tag, $params, $content)
{
	global $cfg;

	$href_params = parse_url($params['href']);
	$is_external_link = !empty($href_params['host']) && !strstr($params['href'], parse_url($cfg['mainurl'], PHP_URL_HOST));

	if ($is_external_link) {
		$params['class']  = (isset($params['class']) ? $params['class'] . ' external_link' : 'external_link');
		$params['target'] = '_blank';
		$params['href']   = sed_url('go', 'url=' . base64_encode($params['href']));
		$params['rel']    = 'nofollow';
	}

	$tag_string = '<a';

	foreach ($params as $param => $value) {
		if ($value != '') {
			$tag_string .= ' ' . $param . '="' . $value . '"';
		}
	}

	$tag_string .= '>' . $content . '</a>';
	return $tag_string;
}

function sed_jevixse($text, $filter = 'medium', $use_admin = true, $ext_link_enc = false)
{
	if ($use_admin == false) return $text; //Disable JevixSE for Admin

	$jevix = new Jevix();

	$jevix_inc = SED_ROOT . '/plugins/jevixse/inc';
	$jevix_rules_path = $jevix_inc . '/jevixse.rules.php';
	$use_custom_rules = false;
	$custom_rules = array();
	if (file_exists($jevix_rules_path)) {
		$custom_rules = include $jevix_rules_path;
		if (is_array($custom_rules) && isset($custom_rules[$filter]) && !empty($custom_rules[$filter]['tags'])) {
			$use_custom_rules = true;
		}
		require_once $jevix_inc . '/jevixse.defaults.php';
		$def_behavior = sed_jevixse_default_behavior();
		foreach (array('full', 'medium', 'micro') as $m) {
			if (isset($def_behavior[$m]) && isset($custom_rules[$m])) {
				foreach ($def_behavior[$m] as $bk => $bv) {
					if (!isset($custom_rules[$m][$bk])) {
						$custom_rules[$m][$bk] = $bv;
					}
				}
			}
		}
	} else {
		require_once $jevix_inc . '/jevixse.defaults.php';
	}

	$def_rules = sed_jevixse_default_rules();
	$rules_for_filter = ($use_custom_rules && isset($custom_rules[$filter])) ? $custom_rules[$filter] : (isset($def_rules[$filter]) ? $def_rules[$filter] : array());

	$allowed_tags = array();
	if (!empty($rules_for_filter['tags'])) {
		$allowed_tags = $rules_for_filter['tags'];
		if (!empty($rules_for_filter['tags_disabled']) && is_array($rules_for_filter['tags_disabled'])) {
			$allowed_tags = array_values(array_diff($allowed_tags, $rules_for_filter['tags_disabled']));
		}
		$jevix->cfgAllowTags($allowed_tags);
	}
	$allowed_set = array_flip($allowed_tags);
	if (!empty($rules_for_filter['params']) && is_array($rules_for_filter['params'])) {
		foreach ($rules_for_filter['params'] as $t => $p) {
			if (isset($allowed_set[$t])) {
				$jevix->cfgAllowTagParams($t, $p);
			}
		}
	}
	if ($filter === 'full' || $filter === 'medium') {
		$jevix->cfgSetTagStyleParams(
			array('span'),
			array(
				'text-decoration'   => array('none', 'line-through', 'underline'),
				'font-style'        => array('normal', 'italic'),
				'font-family',
				'font-weight'       => array('normal', 'bold'),
				'font-size'         => '#regexp:%^(8|10|12|14|16|18|20)px$%i',
				'color'             => '#regexp:%^(#([a-f0-9]{6}|[a-f0-9]{3}))|(rgb\\((\\d{1,3}),\\s*(\\d{1,3}),\\s*(\\d{1,3})\\))$%i',
				'background-color'  => '#regexp:%^(#([a-f0-9]{6}|[a-f0-9]{3}))|(rgb\\((\\d{1,3}),\\s*(\\d{1,3}),\\s*(\\d{1,3})\\))$%i'
			)
		);
		$jevix->cfgSetTagStyleParams(
			array('p'),
			array(
				'padding-left' => '#regexp:%^(10|20|30|40|50|60|70|80|90|100|120|140|150|160|180)px$%i',
				'margin-left'  => '#regexp:%^(10|20|30|40|50|60|70|80|90|100|120|140|150|160|180)px$%i',
				'text-align'   => array('left', 'center', 'right', 'justify')
			)
		);
	}
	if ($ext_link_enc && isset($allowed_set['a'])) {
		$jevix->cfgSetTagCallbackFull('a', 'sed_external_link_encode');
	}

	// Apply behavior (short, preformatted, cut_with_content, empty, no_typography, params_required, childs, param_default, auto_replace)
	// Only apply for tags that are in the allowed list (disabled tags are excluded)
	$b = array();
	if (isset($custom_rules[$filter])) {
		$b = $custom_rules[$filter];
	} else {
		if (!function_exists('sed_jevixse_default_behavior')) {
			require_once $jevix_inc . '/jevixse.defaults.php';
		}
		$def_b = sed_jevixse_default_behavior();
		$b = isset($def_b[$filter]) ? $def_b[$filter] : array();
	}
	$filterAllowed = function ($arr) use ($allowed_set) {
		return is_array($arr) ? array_values(array_intersect($arr, array_keys($allowed_set))) : array();
	};
	if (!empty($b['short'])) {
		$jevix->cfgSetTagShort($filterAllowed($b['short']));
	}
	if (!empty($b['preformatted'])) {
		$jevix->cfgSetTagPreformatted($filterAllowed($b['preformatted']));
	}
	if (!empty($b['cut_with_content'])) {
		$jevix->cfgSetTagCutWithContent($filterAllowed($b['cut_with_content']));
	}
	if (!empty($b['empty'])) {
		$jevix->cfgSetTagIsEmpty($filterAllowed($b['empty']));
	}
	if (!empty($b['no_typography'])) {
		$jevix->cfgSetTagNoTypography($filterAllowed($b['no_typography']));
	}
	if (!empty($b['params_required']) && is_array($b['params_required'])) {
		foreach ($b['params_required'] as $t => $pr) {
			if (isset($allowed_set[$t])) {
				$jevix->cfgSetTagParamsRequired($t, is_array($pr) ? $pr : array($pr));
			}
		}
	}
	if (!empty($b['childs']) && is_array($b['childs'])) {
		foreach ($b['childs'] as $row) {
			if (is_array($row) && count($row) >= 2) {
				$parent = $row[0];
				$childs = is_array($row[1]) ? $row[1] : array($row[1]);
				if (!isset($allowed_set[$parent])) {
					continue;
				}
				$childs = array_values(array_intersect($childs, array_keys($allowed_set)));
				if (empty($childs)) {
					continue;
				}
				$containerOnly = isset($row[2]) ? (bool)$row[2] : false;
				$childOnly = isset($row[3]) ? (bool)$row[3] : false;
				$jevix->cfgSetTagChilds($parent, $childs, $containerOnly, $childOnly);
			}
		}
	}
	if (!empty($b['param_default']) && is_array($b['param_default'])) {
		foreach ($b['param_default'] as $row) {
			if (is_array($row) && count($row) >= 3) {
				$t = $row[0];
				if (!isset($allowed_set[$t])) {
					continue;
				}
				$param = $row[1];
				$value = $row[2];
				$rewrite = isset($row[3]) ? (bool)$row[3] : false;
				$jevix->cfgSetTagParamDefault($t, $param, $value, $rewrite);
			}
		}
	}
	if (!empty($b['auto_replace']['from']) && !empty($b['auto_replace']['to'])) {
		$jevix->cfgSetAutoReplace($b['auto_replace']['from'], $b['auto_replace']['to']);
	}

	$jevix->cfgSetXHTMLMode(isset($b['xhtml']) ? (bool)$b['xhtml'] : false);
	$jevix->cfgSetAutoBrMode(isset($b['auto_br']) ? (bool)$b['auto_br'] : false);
	$jevix->cfgSetAutoLinkMode(isset($b['auto_link']) ? (bool)$b['auto_link'] : false);
	$errors = null;
	return $jevix->parse($text, $errors);
}
