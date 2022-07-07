<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=plugins/censor/censor.import.filter.php
Version=178
Updated=2022-may-22
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=censor
Part=censorimport
File=censor.import.filter
Hooks=import.filter
Tags=
Order=11
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

global $usr, $location, $flocation;

$flocation = (empty($flocation)) ? $location : $flocation;

if ($flocation == "Forums")
	{
	require_once(SED_ROOT . '/plugins/censor/inc/censor.class.php');
	$v = ObsceneCensorRus::getFiltered($v);
	}
	
?>