<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=plugins/feedback/feedback.php
Version=179
Updated=2022-jul-15
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=feedback
Part=main
File=feedback
Hooks=standalone
Tags=
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE') || !defined('SED_PLUG')) {
  die('Wrong URL.');
}

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("plug", "e=feedback")] = $L['plu_title'];

$t->assign(array(
  "PLUGIN_FEEDBACK_TITLE" => "<a href=\"" . sed_url("plug", "e=feedback") . "\">" . $L['plu_title'] . "</a>",
  "PLUGIN_FEEDBACK_SHORTTITLE" => $L['plu_title'],
  "PLUGIN_FEEDBACK_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
  "PLUGIN_FEEDBACK_URL" => sed_url("plug", "e=feedback"),
));
