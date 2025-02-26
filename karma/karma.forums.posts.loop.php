<?php

/* ====================
Seditio - Website engine
Copyright Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/karma/karma.forums.posts.loop.php
Version=180
Updated=2025-feb-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=karma
Part=main
File=karma.forums.posts.loop
Hooks=forums.posts.loop
Tags=forums.posts.tpl:{FORUMS_POSTS_ROW_KARMA_ADD},{FORUMS_POSTS_ROW_KARMA_DEL},{FORUMS_POSTS_ROW_KARMA}
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die("Wrong URL.");
}

require_once("plugins/karma/lang/karma.".$usr['lang'].".lang.php");

if (!isset($karma[$row['fp_posterid']]['karma']) || !$karma[$row['fp_posterid']]['karma']) {
	$sql6 = sed_sql_query("SELECT SUM(karma_value) AS karma FROM sed_karma WHERE karma_recipient = {$row['fp_posterid']}");
	$tmp = sed_sql_fetchassoc($sql6);

	$tmp['karma'] = (empty($tmp['karma'])) ? 0 : $tmp['karma']; //правка значения для нулевой кармы
	$karma_value = ($tmp['karma'] >= 100) ? 100 : $tmp['karma']; //правка цвета для очень большой кармы
	$karma_value = ($tmp['karma'] <= -100) ? 100 : $karma_value; //правка цвета для очень большой кармы

	$hex = dechex(floor(256 - abs($karma_value) * 2.56)); //просчет цвета
	$hex = (strlen($hex) == 1) ? "0" . $hex : $hex; // правка цвета
	$hex = ($hex == 100) ? "ff" : $hex; // для нулевой кармы

	$color = ($karma_value > 0) ? $hex . "ff" . $hex : "ff" . $hex . $hex;
	$color = ($karma_value == 0) ? "ffffff" : $color;

	$karma[$row['fp_posterid']]['karma'] = $tmp['karma'];
	$karma[$row['fp_posterid']]['color'] = $color;
}

$modal = ($cfg['enablemodal']) ? 1 : 0;

$t->assign(array(
	"FORUMS_POSTS_ROW_KARMA_ADD" => "<a href=\"javascript:sedjs.popup('karma&act=change&a=add&fp=" . $row['fp_id'] . "','700','500', " . $modal . ", '" . $L['karma_title_add'] . "')\"><nobr>[+]</nobr></a>",
	"FORUMS_POSTS_ROW_KARMA_DEL" => "<a href=\"javascript:sedjs.popup('karma&act=change&a=del&fp=" . $row['fp_id'] . "','700','500', " . $modal . ",'" . $L['karma_title_del'] . "')\"><nobr>[-]</nobr></a>",
	"FORUMS_POSTS_ROW_KARMA" => "<a href=\"javascript:sedjs.popup('karma&act=show&fp=" . $row['fp_posterid'] . "','700','500', " . $modal . ", '" . $L['karma_title'] . "')\" style=\"background-color:#" . $karma[$row['fp_posterid']]['color'] . "\"><nobr>Karma " . $karma[$row['fp_posterid']]['karma'] . "</nobr></a>"
));

//$sql_injection = sed_sql_query("UPDATE sed_users SET user_karma=" . $karma[$row['fp_posterid']]['karma'] . " WHERE user_id='".(int)$row['fp_posterid']."'");

