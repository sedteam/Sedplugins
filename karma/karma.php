<?php

/* ====================
Seditio - Website engine
Copyright Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/karma/karma.php
Version=180
Updated=2025-feb-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=karma
Part=main
File=karma
Hooks=popup
Tags=
Order=10
[END_SED_EXTPLUGIN]

==================== */
if (!defined('SED_CODE')) {
	die("Wrong URL.");
}

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('plug', 'karma');

$act = sed_import('act', 'G', 'ALP');
$fp = sed_import('fp', 'G', 'INT');
$a = sed_import('a', 'G', 'ALP');

$error_string = '';
$success_string = '';

$kt = new XTemplate(sed_skinfile('karma', true));

$kt->assign(array(
	"KARMA_TITLE" => $L['karma_title']
));

if (empty($fp)) { 
	sed_diefatal($L['karma_no_fp']); 
}

$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_posts WHERE fp_posterid = ".(int)$usr['id']);

if ((!$usr['auth_write'] || sed_sql_result($sql, 0, 'COUNT(*)') < $cfg['plugin']['karma']['minpost']) && $act != "show") {
	$act = "minpost";
}

switch ($act) {
	case 'change':

		if ($a == "add") {
			$do = $L['karma_add'];
		} elseif ($a == "del") {
			$do = $L['karma_del'];
		} else {
			sed_diefatal($L['karma_no_karma']);
		}

		$sql = sed_sql_query("SELECT f.fp_id, f.fp_text, f.fp_postername, u.user_name, u.user_id FROM sed_forum_posts f LEFT JOIN sed_users u ON (f.fp_posterid=u.user_id) WHERE f.fp_id = '".(int)$fp."' LIMIT 1");
		$row = sed_sql_fetchassoc($sql);

		if ($usr['id'] == $row['user_id']) {
			$error_string .= $L['karma_no_self'] . "<br />";
			break;
		}

		$sql_check_fp = sed_sql_query("SELECT COUNT(*) FROM sed_karma WHERE karma_rater = ".(int)$usr['id']." AND karma_fp = '$fp' AND karma_recipient = '" . $row['user_id'] . "'");
		if (sed_sql_result($sql_check_fp, 0, 'COUNT(*)') > 0) {
			$error_string .= $L['karma_no_repeat'] . "<br />";
			break;
		}

		$fp_text = "<blockquote><a href=\"" . sed_url("forums", "m=posts&p=" . $row['fp_id'], "#" . $row['fp_id']) . "\">#" . $row['fp_id'] . "</a> <strong>" . $row['fp_postername'] . " :</strong><br />" . $row['fp_text'] . "</blockquote>";

		$kt->assign(array(
			"KARMA_CHANGE_TITLE" => $L['karma_title'],
			"KARMA_CHANGE_SUBTITLE" => $L['karma_do_1'] . " " . $do . " " . $L['karma_do_2']. " <strong>" . $row['user_name']. "</strong> " . $L['karma_do_3'],
			"KARMA_CHANGE_FORM_SEND" => sed_url("plug", "o=karma&act=dochange&fp=" . $fp),
			"KARMA_CHANGE_FP_TEXT" => $fp_text,
			"KARMA_CHANGE_REASON_MESSAGE" => sed_textarea('reason', isset($reason) ? $reason : '', 5, 60, 'noeditor') . sed_textbox_hidden('recipient', $row['user_id']) . sed_textbox_hidden('a', $a)
		));
		
		$kt->parse("MAIN.CHANGE");

		break;
		
	case 'dochange':

		$a = sed_import('a', 'P', 'ALP');
		$reason = sed_import('reason', 'P', 'TXT');
		$recipient = sed_import('recipient', 'P', 'INT');
		
		if (empty($reason)) {
			$error_string .= $L['karma_no_comm'] . "<br />";
			break;
		} 
		
		if (empty($reason)) {
			$error_string .= $L['karma_no_recipient'] . "<br />";
			break;
		}

		if ($a == "add") {
			$karma_value = "+1";
		} elseif ($a == "del") {
			$karma_value = "-1";
		} else {
			sed_diefatal($L['karma_no_karma']);
		}

		if ($usr['id'] == $recipient) {
			$error_string .= $L['karma_no_self'] . "<br />";
			break;
		}

		$sql_check_fp = sed_sql_query("SELECT COUNT(*) FROM sed_karma WHERE karma_rater = ".(int)$usr['id']." AND karma_fp = '".(int)$fp."' AND karma_recipient = '".(int)$recipient."'");

		if (sed_sql_result($sql_check_fp, 0, 'COUNT(*)') >= 1) {
			$error_string .= $L['karma_no_repeat'] . "<br />";
			break;
		}

		$sql = sed_sql_query("INSERT sed_karma 
			(karma_recipient, 
			karma_rater, 
			karma_value, 
			karma_text, 
			karma_fp) 
			VALUES 
			('".(int)$recipient."', 
			'".(int)$usr['id']."', 
			'".sed_sql_prep($karma_value)."', 
			'" . sed_sql_prep($reason) . "',
			'".(int)$fp."')");
		
		$sqlmess = sed_sql_query("INSERT INTO $db_pm 
			(pm_state, 
			pm_date, 
			pm_fromuserid, 
			pm_fromuser, 
			pm_touserid, 
			pm_title, 
			pm_text) 
			VALUES 
			('0', 
			'" . (int)$sys['now_offset'] . "', 
			'0', 
			'" . sed_sql_prep($L['karma_pmnotify']) . "', 
			'" . (int)$recipient . "', 
			'" . sed_sql_prep($L['karma_pmtitle']) . "', 
			'" . sed_sql_prep($L['karma_pmtext']) . "')");		
			
		sed_stat_inc('totalpms');
		
		/**/
		$sqlsum = sed_sql_query("SELECT SUM(karma_value) AS karma FROM sed_karma WHERE karma_recipient = '".(int)$recipient."'");
		$rowsum = sed_sql_fetchassoc($sqlsum);		
		$sql_injection = sed_sql_query("UPDATE $db_users SET user_karma = " . $rowsum['karma'] . " WHERE user_id = '".(int)$recipient."'");
		/**/
		
		$success_string .= $L['karma_changed_ok'];

		break;
		
	case 'moderate':

		if (!$usr['isadmin']) {
			sed_diefatal($L['karma_low_level']);
		}
		
		$sqlrecip = sed_sql_query("SELECT karma_recipient FROM sed_karma WHERE karma_id = '".(int)$fp."' AND karma_recipient != '".$usr['id']."' LIMIT 1");
		if (sed_sql_numrows($sqlrecip) > 0) {
			$rowrecip = sed_sql_fetchassoc($sqlrecip);
			$recipient = $rowrecip['karma_recipient'];
			
			$sqldel = sed_sql_query("DELETE FROM sed_karma WHERE karma_id = '".(int)$fp."' AND karma_recipient != '".$usr['id']."' LIMIT 1");
		
			/**/
			$sqlsum = sed_sql_query("SELECT SUM(karma_value) AS karma FROM sed_karma WHERE karma_recipient = '".(int)$recipient."'");
			$rowsum = sed_sql_fetchassoc($sqlsum);		
			$sql_injection = sed_sql_query("UPDATE $db_users SET user_karma = '" . (int)$rowsum['karma'] . "' WHERE user_id = '".(int)$recipient."'");
			/**/
		}

		$success_string .= $L['karma_del_ok'];

		break;

	case 'minpost':

		$error_string .= str_replace("{minpost}", $cfg['plugin']['karma']['minpost'], $L['karma_no_minpost']) . "<br />";
		break;

	case 'show':
	default:

		$sql = sed_sql_query("SELECT u.user_name, u.user_id, k.* FROM sed_karma k LEFT JOIN sed_users u ON (u.user_id=k.karma_rater) WHERE k.karma_recipient = '".(int)$fp."' ORDER BY k.karma_id");
		$sql1 = sed_sql_query("SELECT user_name FROM sed_users WHERE user_id = '".(int)$fp."' LIMIT 1");
		$ths = sed_sql_fetchassoc($sql1);

		$kt->assign("SHOW_USERNAME", $ths['user_name']);
		
		while ($row = sed_sql_fetchassoc($sql)) {
			$color = ($row['karma_value'] > 0) ? "eeffee" : "ffeeee";
			$kt->assign(array(			
				"KARMA_COLOR" => $color,
				"KARMA_NAME" => "<a href=\"" . sed_url("users", "m=details&id=" . $row['user_id']) . "\" target=\"_blank\">" . $row['user_name'] . "</a>",
				"KARMA_VALUE" => $row['karma_value'],
				"KARMA_TEXT" => sed_parse($row['karma_text']),
				"KARMA_VIEW" => "<a href=\"" . sed_url("forums", "m=posts&p=" . $row['karma_fp'], "#" . $row['karma_fp']) . "\" target=\"_blank\">" . $L['View'] . "</a>",
				"KARMA_ACTION" => (sed_auth('plug', 'karma', 'A') && $usr['id'] !== $fp) ? "<a href=\"" . sed_url("plug", "o=karma&act=moderate&fp=" . $row['karma_id']) . "\">" . $L['Delete'] . "</a>" : ""
			));
			$kt->parse("MAIN.SHOW.SHOW_ROW");
		}
		$kt->parse("MAIN.SHOW");

		break;
}

if (!empty($success_string)) {
	$kt->assign("KARMA_SUCCESS", sed_alert($success_string, 's', false));
	$kt->parse("MAIN.SUCCESS");
}

if (!empty($error_string)) {
	$kt->assign("KARMA_ERROR", sed_alert($error_string, 'e', false));
	$kt->parse("MAIN.ERROR");
}

$kt->parse("MAIN");
$popup_body = $kt->text("MAIN");
