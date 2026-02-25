<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/jevixse/jevixse.import.filter.php
Version=2.0
Updated=2026-feb-24
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=jevixse
Part=jevixseimport
File=jevixse.import.filter
Hooks=import.filter
Tags=
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
  die('Wrong URL.');
}

global $usr, $location, $flocation;

require_once SED_ROOT . '/plugins/jevixse/inc/jevixse.class.php';
require_once SED_ROOT . '/plugins/jevixse/inc/jevixse.functions.php';

$jevix_filter_settings = array(
  'Pages' => 'full',
  'Private_Messages' => 'medium',
  'Polls' => 'micro',
  'Gallery' => 'micro',
  'PFS' => 'micro',
  'Users' => 'micro',
  'Plugins' => 'full',
  'Forums' => 'full',
  'Comments' => 'medium',
  'Administration' => 'full'
);

$flocation = (empty($flocation)) ? $location : $flocation;

if (array_key_exists($flocation, $jevix_filter_settings)) {
  $filter = $jevix_filter_settings[$flocation];
} else {
  $filter = 'micro';
}

// Use for Administrators ?
$use_admin = (($cfg['plugin']['jevixse']['use_for_admin'] == "no") && ($usr['maingrp'] == 5)) ? false : true;

$ext_link_enc = ($cfg['plugin']['jevixse']['ext_link_enc'] == "yes") ? true : false;

$v = sed_jevixse($v, $filter, $use_admin, $ext_link_enc);
