<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=plugins/banstoplist/banstoplist.import.filter.php
Version=179
Updated=2023-nov-27
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=banstoplist
Part=main
File=banstoplist.import.filter
Hooks=import.filter
Tags=
Order=11
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $usr, $location, $flocation;

$flocation = (empty($flocation)) ? $location : $flocation;
$stoplist_arr = explode(",", $cfg['plugin']['banstoplist']['stoplist']);

if ($flocation == "Forums" || $flocation == "Comments") {
	require_once(SED_ROOT . '/plugins/banstoplist/inc/banstoplist.inc.php');
	$v = sed_banstoplist($v, $stoplist_arr);
}
