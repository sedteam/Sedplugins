<?php
/* ====================
Seditio - Website engine
Copyright Neocrome
https://seditio.org

[BEGIN_SED]
File=plugins/tableofcontents/tableofcontents.page.add.first.php
Version=185
Updated=2026-jul-07
Type=Plugin
Author=Amro
Description=Inject generated TOC on page add
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tableofcontents
Part=page
File=tableofcontents.page.add.first
Hooks=page.add.add.first
Tags=
Minlevel=0
Order=11
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

require_once(SED_ROOT . '/plugins/tableofcontents/inc/tableofcontents.functions.php');

// Import the submitted page text
$newpagetext = sed_import('newpagetext', 'P', 'HTM');

// Generate TOC data
$tbc_data = sed_generate_tbc($newpagetext);

// Inject TOC contents into $_POST so Seditio's extrafield builder picks it up
$_POST['newpagetbc'] = $tbc_data['tbc_contents'];
?>