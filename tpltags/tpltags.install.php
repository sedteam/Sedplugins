<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tpltags/tpltags.install.php
Version=185
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) {
	die('Wrong URL.');
}

global $cfg;

if (!isset($res)) {
	$res = '';
}

$mysqlengine = $cfg['mysqlengine'];
$mysqlcharset = $cfg['mysqlcharset'];
$mysqlcollate = $cfg['mysqlcollate'];
$prefix = $cfg['sqldbprefix'];

$check = sed_sql_query("SHOW TABLES LIKE '{$prefix}tpltags'");
if (sed_sql_numrows($check) == 0) {
	$res .= "Creating tpltags dictionary table...<br />";
	sed_sql_query("CREATE TABLE IF NOT EXISTS {$prefix}tpltags (
		tag_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		tag_title varchar(255) DEFAULT NULL,
		tag_location varchar(255) DEFAULT NULL,
		tag_type varchar(255) DEFAULT NULL,
		tag_tpl varchar(255) DEFAULT NULL,
		tag_desc varchar(255) DEFAULT NULL,
		tag_ver varchar(255) DEFAULT NULL,
		PRIMARY KEY (tag_id)
	) ENGINE={$mysqlengine} DEFAULT CHARSET={$mysqlcharset} COLLATE={$mysqlcollate}");
	$res .= "Table {$prefix}tpltags created.<br />";

	// Import default tags from dump if table was created successfully
	$sql_file = SED_ROOT . "/plugins/tpltags/sed_tpltags.sql";
	if (file_exists($sql_file)) {
		$res .= "Importing default template tags from dump...<br />";
		$sql_content = file_get_contents($sql_file);
		
		// Replace default prefix 'sed_' with active $prefix
		$sql_content = str_replace('`sed_tpltags`', '`' . $prefix . 'tpltags`', $sql_content);
		
		// Remove SQL comments
		$sql_content = preg_replace('/--.*\n/', '', $sql_content);
		$sql_content = preg_replace('/\/\*.*?\*\//s', '', $sql_content);
		
		// Split queries by semicolon ending a line
		$queries = preg_split('/;(?=\s*\n|$)/', $sql_content);
		
		$imported_count = 0;
		foreach ($queries as $query) {
			$query = trim($query);
			if (!empty($query)) {
				// Skip table creation statements since table is already created
				if (stripos($query, 'CREATE TABLE') !== false || stripos($query, 'DROP TABLE') !== false) {
					continue;
				}
				$q_res = sed_sql_query($query);
				if (!$q_res) {
					$res .= "<span style=\"color: red;\">SQL Error: " . sed_sql_error() . " in query: " . htmlspecialchars(substr($query, 0, 100)) . "...</span><br />";
				} else {
					$imported_count++;
				}
			}
		}
		$res .= "Imported $imported_count SQL queries from dump.<br />";
	} else {
		$res .= "SQL dump file not found.<br />";
	}
} else {
	$res .= "Table {$prefix}tpltags already exists. Skipping default data import to avoid overwriting.<br />";
}
