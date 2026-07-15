<?php
/* ====================
Seditio - Website engine
Copyright Neocrome
https://seditio.org

[BEGIN_SED]
File=plugins/tableofcontents/tableofcontents.page.main.php
Version=185
Updated=2026-jul-07
Type=Plugin
Author=Amro
Description=Process page text anchors on page view
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tableofcontents
Part=page
File=tableofcontents.page.main
Hooks=page.main
Tags=page.tpl
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

require_once(SED_ROOT . '/plugins/tableofcontents/inc/tableofcontents.functions.php');

// Dynamically generate the anchors and TOC content
$tbc_data = sed_generate_tbc($pag['page_text']);

// Inject anchor-linked headers into page content
$pag['page_text'] = $tbc_data['content'];

// Check config option to decide if we use caching or generate on the fly
if ($cfg['plugin']['tableofcontents']['use_cache'] == 'no' || empty($pag['page_tbc'])) {
	$pag['page_tbc'] = $tbc_data['tbc_contents'];
}

// Register stylesheet before system/header.php is loaded
if (!empty($pag['page_tbc'])) {
	sed_add_css('plugins/tableofcontents/css/tbc.css', true);
}
?>
