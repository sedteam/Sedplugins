<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumsubscribe/forumsubscribe.ajax.php
Version=186
Updated=2026-aug-26
Type=Plugin
Description=Forum Subscribe AJAX handler
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=forumsubscribe
Part=ajax
File=forumsubscribe.ajax
Hooks=ajax
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $cfg, $usr, $L, $db_forum_subscribed;

$a = sed_import('a', 'G', 'ALP');
$q = sed_import('q', 'G', 'INT');

$json_res = function($data, $code = '200 OK') {
	sed_sendheaders('application/json', $code);
	echo json_encode($data);
	exit;
};

if (empty($cfg['ajax'])) {
	$json_res(array('status' => 'error', 'message' => 'AJAX is disabled'), '403 Forbidden');
}

if ($usr['id'] <= 0) {
	$json_res(array('status' => 'error', 'message' => 'Access denied'), '403 Forbidden');
}

if (!sed_check_csrf()) {
	$json_res(array('status' => 'error', 'message' => 'CSRF error'), '403 Forbidden');
}

if (empty($q) || empty($a)) {
	$json_res(array('status' => 'error', 'message' => 'Invalid parameters'), '400 Bad Request');
}

switch ($a) {
	case 'subscribe':
		$res = sed_forumsubscribe_add($usr['id'], $q);
		$json_res(array(
			'status' => 'success',
			'subscribed' => 1,
			'text' => $L['forumsub_unsubscribe'],
			'action' => 'unsubscribe',
			'url' => sed_url('plug', 'e=forumsubscribe&a=unsubscribe&q=' . $q . '&' . sed_xg(), '', false, false),
			'ajax_url' => sed_url('plug', 'ajx=forumsubscribe&a=unsubscribe&q=' . $q, '', false, false),
			'msg' => $L['forumsub_msg_subscribed']
		));
		break;

	case 'unsubscribe':
		$res = sed_forumsubscribe_delete($usr['id'], $q);
		$json_res(array(
			'status' => 'success',
			'subscribed' => 0,
			'text' => $L['forumsub_subscribe'],
			'action' => 'subscribe',
			'url' => sed_url('plug', 'e=forumsubscribe&a=subscribe&q=' . $q . '&' . sed_xg(), '', false, false),
			'ajax_url' => sed_url('plug', 'ajx=forumsubscribe&a=subscribe&q=' . $q, '', false, false),
			'msg' => $L['forumsub_msg_unsubscribed']
		));
		break;

	default:
		$json_res(array('status' => 'error', 'message' => 'Unknown action'), '400 Bad Request');
		break;
}
