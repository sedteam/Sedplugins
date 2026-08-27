<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumsubscribe/inc/forumsubscribe.functions.php
Version=186
Updated=2026-aug-24
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

global $cfg, $db_forum_subscribed;
$db_forum_subscribed = $cfg['sqldbprefix'] . 'forum_subscribed';

/**
 * Check if a user is subscribed to a forum topic
 *
 * @param int $userid User ID
 * @param int $topicid Topic ID
 * @return bool
 */
function sed_forumsubscribe_check($userid, $topicid)
{
	global $db_forum_subscribed;

	$userid = (int)$userid;
	$topicid = (int)$topicid;

	if ($userid <= 0 || $topicid <= 0) {
		return false;
	}

	$sql = sed_sql_query("SELECT sfs_id FROM $db_forum_subscribed WHERE sfs_userid = $userid AND sfs_topicid = $topicid LIMIT 1");
	return (sed_sql_numrows($sql) > 0);
}

/**
 * Add a subscription to a forum topic
 *
 * @param int $userid User ID
 * @param int $topicid Topic ID
 * @param int $sectionid Section ID
 * @return bool
 */
function sed_forumsubscribe_add($userid, $topicid, $sectionid = 0)
{
	global $db_forum_subscribed, $db_forum_topics, $sys;

	$userid = (int)$userid;
	$topicid = (int)$topicid;
	$sectionid = (int)$sectionid;

	if ($userid <= 0 || $topicid <= 0) {
		return false;
	}

	if ($sectionid <= 0) {
		$sql = sed_sql_query("SELECT ft_sectionid FROM $db_forum_topics WHERE ft_id = $topicid LIMIT 1");
		if ($row = sed_sql_fetchassoc($sql)) {
			$sectionid = (int)$row['ft_sectionid'];
		}
	}

	$check = sed_sql_query("SELECT sfs_id FROM $db_forum_subscribed WHERE sfs_userid = $userid AND sfs_topicid = $topicid LIMIT 1");
	if (sed_sql_numrows($check) == 0) {
		$now = (int)$sys['now_offset'];
		sed_sql_query("INSERT INTO $db_forum_subscribed (sfs_userid, sfs_topicid, sfs_sectionid, sfs_date) VALUES ($userid, $topicid, $sectionid, $now)");
		return true;
	}

	return false;
}

/**
 * Delete a user subscription for a forum topic
 *
 * @param int $userid User ID
 * @param int $topicid Topic ID
 * @return bool
 */
function sed_forumsubscribe_delete($userid, $topicid)
{
	global $db_forum_subscribed;

	$userid = (int)$userid;
	$topicid = (int)$topicid;

	if ($userid <= 0 || $topicid <= 0) {
		return false;
	}

	sed_sql_query("DELETE FROM $db_forum_subscribed WHERE sfs_userid = $userid AND sfs_topicid = $topicid");
	return (sed_sql_affectedrows() > 0);
}

/**
 * Delete all subscriptions for a specific topic (e.g. topic deletion)
 *
 * @param int $topicid Topic ID
 * @return bool
 */
function sed_forumsubscribe_delete_by_topic($topicid)
{
	global $db_forum_subscribed;

	$topicid = (int)$topicid;
	if ($topicid <= 0) {
		return false;
	}

	sed_sql_query("DELETE FROM $db_forum_subscribed WHERE sfs_topicid = $topicid");
	return true;
}

/**
 * Delete all subscriptions of a specific user
 *
 * @param int $userid User ID
 * @return bool
 */
function sed_forumsubscribe_delete_all($userid)
{
	global $db_forum_subscribed;

	$userid = (int)$userid;
	if ($userid <= 0) {
		return false;
	}

	sed_sql_query("DELETE FROM $db_forum_subscribed WHERE sfs_userid = $userid");
	return true;
}

/**
 * Generate a secure direct unsubscribe token for email links
 *
 * @param int $userid User ID
 * @param int $topicid Topic ID
 * @param string $passhash User password hash
 * @return string
 */
function sed_forumsubscribe_token($userid, $topicid, $passhash = '')
{
	global $cfg, $db_users;
	$userid = (int)$userid;
	$topicid = (int)$topicid;

	if (empty($passhash) && $userid > 0) {
		$sql = sed_sql_query("SELECT user_password FROM $db_users WHERE user_id = $userid LIMIT 1");
		$passhash = ($row = sed_sql_fetchassoc($sql)) ? $row['user_password'] : '';
	}
	$salt = isset($cfg['site_secret']) ? $cfg['site_secret'] : (isset($cfg['secret_key']) ? $cfg['secret_key'] : $cfg['sqldbpass']);
	return md5($userid . ':' . $topicid . ':' . $passhash . ':' . $salt);
}

/**
 * Notify subscribers about a new post in a forum topic
 *
 * @param int $topicid Topic ID
 * @param int $sectionid Section ID
 * @param int $posterid Author User ID of the new post
 * @param string $postername Author username of the new post
 * @param string $posttext Message text of the new post
 * @return int Number of sent notifications
 */
function sed_forumsubscribe_notify($topicid, $sectionid, $posterid, $postername, $posttext)
{
	global $cfg, $db_forum_subscribed, $db_forum_topics, $db_forum_sections, $db_users, $L;

	$topicid = (int)$topicid;
	$sectionid = (int)$sectionid;
	$posterid = (int)$posterid;

	if ($topicid <= 0) {
		return 0;
	}

	// Fetch topic info
	$sql = sed_sql_query("SELECT ft_title, ft_mode, ft_firstposterid FROM $db_forum_topics WHERE ft_id = $topicid LIMIT 1");
	if (!$row = sed_sql_fetchassoc($sql)) {
		return 0;
	}
	$ft_title = $row['ft_title'];
	$ft_mode = (int)$row['ft_mode'];
	$ft_firstposterid = (int)$row['ft_firstposterid'];

	// Fetch section info
	$sql = sed_sql_query("SELECT fs_title FROM $db_forum_sections WHERE fs_id = $sectionid LIMIT 1");
	$fs_title = ($row = sed_sql_fetchassoc($sql)) ? $row['fs_title'] : '';

	// Select subscribers, excluding the poster of the new message
	$sql = sed_sql_query("SELECT s.sfs_id, s.sfs_userid, u.user_id, u.user_name, u.user_email, u.user_password, u.user_lang, u.user_maingrp
		FROM $db_forum_subscribed AS s
		INNER JOIN $db_users AS u ON u.user_id = s.sfs_userid
		WHERE s.sfs_topicid = $topicid AND s.sfs_userid != $posterid");

	$sent_count = 0;

	// Prepare cleaned post snippet
	$snippet = strip_tags($posttext);
	if (mb_strlen($snippet) > 300) {
		$snippet = mb_substr($snippet, 0, 300) . '...';
	}

	while ($sub = sed_sql_fetchassoc($sql)) {
		$sub_id = (int)$sub['user_id'];
		$sub_email = $sub['user_email'];
		$sub_name = $sub['user_name'];
		$sub_lang = !empty($sub['user_lang']) ? $sub['user_lang'] : $cfg['defaultlang'];
		$sub_maingrp = (int)$sub['user_maingrp'];
		$sub_pass = $sub['user_password'];

		if (empty($sub_email)) {
			continue;
		}

		// Private topic check: only admin or first poster can view private topic
		if ($ft_mode == 1 && $sub_id != $ft_firstposterid && $sub_maingrp != 5) {
			continue;
		}

		// Check user forum section read access
		$user_auth = sed_auth_build($sub_id, $sub_maingrp);
		$has_read_auth = isset($user_auth['forums'][$sectionid]) && (($user_auth['forums'][$sectionid] & 1) == 1);
		if (!$has_read_auth) {
			continue;
		}

		// Load localized strings for the recipient language
		$langfile = SED_ROOT . '/plugins/forumsubscribe/lang/forumsubscribe.' . $sub_lang . '.lang.php';
		if (!file_exists($langfile)) {
			$langfile = SED_ROOT . '/plugins/forumsubscribe/lang/forumsubscribe.en.lang.php';
		}
		
		$sub_L = array();
		if (file_exists($langfile)) {
			include($langfile);
			$sub_L = $L;
		}

		$token = sed_forumsubscribe_token($sub_id, $topicid, $sub_pass);
		$post_url = $cfg['mainurl'] . '/' . sed_url('forums', 'm=posts&q=' . $topicid . '&n=last', '#bottom', false, false);
		$unsub_url = $cfg['mainurl'] . '/' . sed_url('plug', 'e=forumsubscribe&a=unsubscribe&q=' . $topicid . '&uid=' . $sub_id . '&token=' . $token, '', false, false);

		$mail_subject = isset($sub_L['forumsub_mail_subject']) ? sprintf($sub_L['forumsub_mail_subject'], $ft_title) : "New reply in topic: " . $ft_title;
		
		$mail_body = isset($sub_L['forumsub_mail_body']) 
			? sprintf($sub_L['forumsub_mail_body'], $sub_name, $postername, $ft_title, $fs_title, $snippet, $post_url, $unsub_url)
			: "Hello " . $sub_name . ",\n\n" . $postername . " has replied to the topic \"" . $ft_title . "\".\n\nMessage preview:\n" . $snippet . "\n\nView reply:\n" . $post_url . "\n\nUnsubscribe from this topic:\n" . $unsub_url;

		if (sed_mail($sub_email, $mail_subject, $mail_body)) {
			$sent_count++;
		}
	}

	return $sent_count;
}
