<?php
/* ====================
[BEGIN_SED]
File=plugins/tableofcontents/tableofcontents.page.edit.first.php
Version=185
Updated=2026-jul-07
Type=Plugin
Author=Amro
Description=Inject generated TOC on page edit
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tableofcontents
Part=page
File=tableofcontents.page.edit.first
Hooks=page.edit.update.first
Tags=
Minlevel=0
Order=11
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

require_once(SED_ROOT . '/plugins/tableofcontents/inc/tableofcontents.functions.php');

// Import the submitted updated page text
$rpagetext = sed_import('rpagetext', 'P', 'HTM');

// Generate TOC data
$tbc_data = sed_generate_tbc($rpagetext);

// Inject TOC contents into $_POST so Seditio's extrafield builder picks it up
$_POST['rpagetbc'] = $tbc_data['tbc_contents'];
?>