<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tpltags/inc/functions.php
Version=185
Updated=2026-jul-07
Type=Plugin
Author=Seditio Team / Chris T
Description=Functions
[END_SED]
==================== */

if (!defined('SED_CODE')) { 
	die('Wrong URL.'); 
}

global $tpls, $types, $versions;

$tpls = array();

$types = array (
	'Alphanumerical', 'Array', 'Boolean', 'Composite', 'Date', 'Image', 'Input', 
	'Integer', 'Link', 'Level', 'Raw link', 'String', 'Simple text', 'Text', 'Time', 
	'URL', 'System'
);

$versions = array (
	'100', '101', '102', '110', '111', '120', '121', '125', '126', '130', '150', '160', 
	'161', '170', '171', '172', '173', '175', '178', '185'
);

/**
 * Builds HTML select for tag parameters
 *
 * @param string $check Selected value
 * @param string $name Select element name
 * @param array $values Options array
 * @return string
 */
function tpltags_build_select($check, $name, $values)
{
	$check = trim($check);
	$selected = (empty($check)) ? "selected=\"selected\"" : '';
	$result = "<select name=\"$name\" size=\"1\"><option value=\"\" $selected>---</option>";
	foreach ($values as $x) {
		$x = trim($x);
		$selected = ($x == $check) ? "selected=\"selected\"" : '';
		$result .= "<option value=\"$x\" $selected>" . sed_cc($x) . "</option>";
	}
	$result .= "</select>";
	return $result;
}