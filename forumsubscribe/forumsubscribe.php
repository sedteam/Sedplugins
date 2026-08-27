<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumsubscribe/forumsubscribe.php
Version=186
Updated=2026-aug-26
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=forumsubscribe
Part=main
Hooks=standalone
File=forumsubscribe
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE') || !defined('SED_PLUG')) {
	die('Wrong URL.');
}

$a = sed_import('a', 'G', 'ALP');
$q = sed_import('q', 'G', 'INT');
$d = sed_import('d', 'G', 'INT');
$d = empty($d) ? 0 : (int)$d;

$uid = sed_import('uid', 'G', 'INT');
$token = sed_import('token', 'G', 'ALP', 32);

// Handle direct one-click unsubscribe from email links (works even for non-logged-in users)
if ($a == 'unsubscribe' && $uid > 0 && !empty($token) && !empty($q)) {
	$expected_token = sed_forumsubscribe_token($uid, $q);
	if ($token === $expected_token) {
		sed_forumsubscribe_delete($uid, $q);
		sed_redirect(sed_url("forums", "m=posts&q=" . $q, "", true));
	} else {
		sed_diefatal('Invalid unsubscribe token.');
	}
}

// For all other actions and subscription list view, require authentication
sed_blockguests();

if (!empty($a)) {
	switch ($a) {
		case 'subscribe':
			sed_check_xg();
			sed_die(empty($q));

			$res = sed_forumsubscribe_add($usr['id'], $q);
			sed_redirect(sed_url("forums", "m=posts&q=" . $q, "", true));
			break;

		case 'unsubscribe':
			sed_check_xg();
			sed_die(empty($q));

			$res = sed_forumsubscribe_delete($usr['id'], $q);
			$from = sed_import('from', 'G', 'ALP');
			if ($from == 'cp') {
				sed_redirect(sed_url("plug", "e=forumsubscribe", "", true));
			} else {
				sed_redirect(sed_url("forums", "m=posts&q=" . $q, "", true));
			}
			break;

		case 'unsuball':
			sed_check_xg();
			sed_forumsubscribe_delete_all($usr['id']);
			sed_redirect(sed_url("plug", "e=forumsubscribe", "", true));
			break;
	}
}

// User Subscriptions List Page
$itemsperpage = !empty($cfg['plugin']['forumsubscribe']['itemsperpage']) ? (int)$cfg['plugin']['forumsubscribe']['itemsperpage'] : 20;

$plugin_title = $L['forumsub_my_subscriptions'];

// Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("forums")] = $L['Forums'];
$urlpaths[sed_url("plug", "e=forumsubscribe")] = $L['forumsub_my_subscriptions'];

$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_subscribed WHERE sfs_userid = " . (int)$usr['id']);
$totalsubscriptions = sed_sql_result($sql, 0, "COUNT(*)");

$pages = sed_pagination(sed_url("plug", "e=forumsubscribe"), $d, $totalsubscriptions, $itemsperpage);
list($pages_prev, $pages_next) = sed_pagination_pn(sed_url("plug", "e=forumsubscribe"), $d, $totalsubscriptions, $itemsperpage, TRUE);

$sql = sed_sql_query("SELECT s.*, t.ft_title, t.ft_mode, t.ft_postcount, t.ft_updated, t.ft_lastpostername, sec.fs_title, sec.fs_category
	FROM $db_forum_subscribed AS s
	INNER JOIN $db_forum_topics AS t ON t.ft_id = s.sfs_topicid
	INNER JOIN $db_forum_sections AS sec ON sec.fs_id = s.sfs_sectionid
	WHERE s.sfs_userid = " . (int)$usr['id'] . "
	ORDER BY s.sfs_date DESC
	LIMIT $d, $itemsperpage");

$t->assign(array(
	"FORUMSUB_PAGETITLE" => $L['forumsub_my_subscriptions'],
	"FORUMSUB_SHORTTITLE" => $L['forumsub_my_subscriptions'],
	"FORUMSUB_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
	"FORUMSUB_TOTAL_COUNT" => $totalsubscriptions,
	"FORUMSUB_UNSUB_ALL_URL" => sed_url("plug", "e=forumsubscribe&a=unsuball&" . sed_xg()),
	"FORUMSUB_UNSUB_ALL_TEXT" => $L['forumsub_unsub_all']
));

if (!empty($pages)) {
	$t->assign(array(
		"FORUMSUB_PAGINATION" => $pages,
		"FORUMSUB_PAGEPREV" => $pages_prev,
		"FORUMSUB_PAGENEXT" => $pages_next
	));
	$t->parse("MAIN.FORUMSUB_PAGINATION");
}

if ($totalsubscriptions > 0 && sed_sql_numrows($sql) > 0) {
	$jj = 0;
	while ($row = sed_sql_fetchassoc($sql)) {
		$jj++;
		$topic_id = (int)$row['sfs_topicid'];
		$section_id = (int)$row['sfs_sectionid'];
		$topic_title = ($row['ft_mode'] == 1) ? "# " . sed_cc($row['ft_title']) : sed_cc($row['ft_title']);
		$unsub_url = sed_url("plug", "e=forumsubscribe&a=unsubscribe&q=" . $topic_id . "&from=cp&" . sed_xg());

		$t->assign(array(
			"FORUMSUB_ROW_ID" => $row['sfs_id'],
			"FORUMSUB_ROW_NUM" => $jj,
			"FORUMSUB_ROW_TOPIC_ID" => $topic_id,
			"FORUMSUB_ROW_TOPIC_TITLE" => $topic_title,
			"FORUMSUB_ROW_TOPIC_URL" => sed_url("forums", "m=posts&q=" . $topic_id),
			"FORUMSUB_ROW_SECTION_ID" => $section_id,
			"FORUMSUB_ROW_SECTION_TITLE" => sed_cc($row['fs_title']),
			"FORUMSUB_ROW_SECTION_URL" => sed_url("forums", "m=topics&s=" . $section_id),
			"FORUMSUB_ROW_POSTCOUNT" => $row['ft_postcount'],
			"FORUMSUB_ROW_DATE" => sed_build_date($cfg['dateformat'], $row['sfs_date']),
			"FORUMSUB_ROW_UPDATED" => sed_build_date($cfg['dateformat'], $row['ft_updated']),
			"FORUMSUB_ROW_LASTPOSTER" => sed_cc($row['ft_lastpostername']),
			"FORUMSUB_ROW_UNSUB_URL" => $unsub_url,
			"FORUMSUB_ROW_ODDEVEN" => sed_build_oddeven($jj)
		));

		$t->parse("MAIN.SUBSCRIPTIONS.FORUMSUB_ROW");
	}

	$t->parse("MAIN.SUBSCRIPTIONS");
} else {
	$t->assign("FORUMSUB_NO_SUBSCRIPTIONS_TEXT", $L['forumsub_no_subscriptions']);
	$t->parse("MAIN.NO_SUBSCRIPTIONS");
}
