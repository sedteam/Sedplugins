<?PHP
/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/feedback/feedback.header.first.php
Version=179
Updated=2022-jul-18
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=feedback
Part=Plugin
File=feedback.header.first
Hooks=header.first
Tags=
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$efee = sed_import('e', 'G', 'ALP');
if ($efee == 'feedback') {
	$moremetas .= "
		<link type=\"text/css\" rel=\"stylesheet\" href=\"plugins/feedback/css/feedback.css\" />
		<script src=\"plugins/feedback/js/feedback.js?v=4\" type=\"text/javascript\"></script>
		";
}
