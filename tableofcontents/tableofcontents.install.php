<?php
/* ====================
Seditio - Website engine
Copyright Neocrome
https://seditio.org

[BEGIN_SED]
File=plugins/tableofcontents/tableofcontents.install.php
Version=185
Updated=2026-jul-07
Type=Plugin
Author=Amro
Description=Installation script for Table of Contents plugin
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }

global $db_dic;

$dtitle = "Table of Contents";
$dcode = "tbc";
$dtype = 5; // Textarea type
$dextralocation = "pages";
$dextratype = "text";
$dextrasize = "";

// Check if the extra field already exists in the dictionary
$check = sed_sql_query("SELECT COUNT(*) FROM $db_dic WHERE dic_code = 'tbc' AND dic_extra_location = 'pages'");
if (sed_sql_result($check, 0, 0) == 0) {
	// Create the column in the database pages table
	sed_extrafield_add($dextralocation, $dcode, $dextratype, $dextrasize);

	// Insert dictionary metadata
	sed_sql_query("INSERT INTO $db_dic 
		(dic_title, 
		dic_code, 
		dic_type, 
		dic_values, 
		dic_mera,
		dic_form_title, 
		dic_form_desc,
		dic_form_size,
		dic_form_maxsize,
		dic_form_cols,
		dic_form_rows,
		dic_form_wysiwyg,
		dic_extra_location,	
		dic_extra_type,	
		dic_extra_size
		) 
		VALUES 
		('" . sed_sql_prep($dtitle) . "', 
		'" . sed_sql_prep($dcode) . "', 
		" . (int)$dtype . ", 
		'', 
		'',
		'Содержание', 
		'Автоматически сгенерированное содержание страницы',
		'',
		'',
		'',
		'',	
		'noeditor',
		'" . sed_sql_prep($dextralocation) . "',
		'" . sed_sql_prep($dextratype) . "',
		'" . sed_sql_prep($dextrasize) . "')");

	sed_log("Added Table of Contents extrafield", 'adm');
	sed_cache_clear('sed_dic');
}
?>