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
    // Query the database to get the sum of karma values for the user
    $sql6 = sed_sql_query("SELECT SUM(karma_value) AS karma FROM sed_karma WHERE karma_recipient = ".(int)$row['fp_posterid']);
    $tmp = sed_sql_fetchassoc($sql6);

    // Adjust the karma value to 0 if it's empty
    $tmp['karma'] = (empty($tmp['karma'])) ? 0 : $tmp['karma'];

    // Cap the karma value at 100 if it's greater than or equal to 100
    $karma_value = ($tmp['karma'] >= 100) ? 100 : $tmp['karma'];

    // Cap the karma value at -100 if it's less than or equal to -100
    $karma_value = ($tmp['karma'] <= -100) ? 100 : $karma_value;

    // Calculate the hexadecimal color value based on the karma value
    $hex = dechex(floor(256 - abs($karma_value) * 2.56));

    // Ensure the hex value is two characters long
    $hex = (strlen($hex) == 1) ? "0" . $hex : $hex;

    // Adjust the hex value for zero karma
    $hex = ($hex == 100) ? "ff" : $hex;

    // Determine the final color based on the karma value
    $color = ($karma_value > 0) ? $hex . "ff" . $hex : "ff" . $hex . $hex;
    $color = ($karma_value == 0) ? "ffffff" : $color;

    // Store the karma value and color in the karma array
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

