<?php
/* ====================
Seditio - Website engine
Copyright Neocrome
https://seditio.org

[BEGIN_SED]
File=plugins/tableofcontents/tableofcontents.uninstall.php
Version=185
Updated=2026-jul-07
Type=Plugin
Author=Amro
Description=Uninstallation script for Table of Contents plugin
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }

global $db_dic;

// Remove the column from the database pages table
sed_extrafield_remove('pages', 'tbc');

// Clean up dictionary row
sed_sql_query("DELETE FROM $db_dic WHERE dic_code = 'tbc' AND dic_extra_location = ''");
sed_cache_clear('sed_dic');
?>
