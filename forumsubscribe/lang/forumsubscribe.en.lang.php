<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumsubscribe/lang/forumsubscribe.en.lang.php
Version=186
Updated=2026-aug-24
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['forumsub_title'] = "Forum Topic Subscriptions";
$L['forumsub_subscribe'] = "Subscribe to topic";
$L['forumsub_unsubscribe'] = "Unsubscribe from topic";
$L['forumsub_subscribed'] = "You are subscribed to this topic";
$L['forumsub_not_subscribed'] = "You are not subscribed to this topic";
$L['forumsub_subscribe_hint'] = "Notify me of replies via email";
$L['forumsub_newtopic_subscribe'] = "Subscribe to replies in this topic";
$L['forumsub_quickreply_subscribe'] = "Subscribe to this topic";

$L['forumsub_msg_subscribed'] = "You have successfully subscribed to topic updates.";
$L['forumsub_msg_unsubscribed'] = "You have been unsubscribed from this topic.";
$L['forumsub_msg_unsuball'] = "You have unsubscribed from all forum topics.";
$L['forumsub_msg_already'] = "You are already subscribed to this topic.";
$L['forumsub_msg_notfound'] = "Subscription not found or topic does not exist.";

$L['forumsub_my_subscriptions'] = "My Forum Subscriptions";
$L['forumsub_topic'] = "Topic";
$L['forumsub_section'] = "Section";
$L['forumsub_date'] = "Subscribed since";
$L['forumsub_action'] = "Action";
$L['forumsub_unsub_all'] = "Unsubscribe from all topics";
$L['forumsub_no_subscriptions'] = "You are not currently subscribed to any forum topics.";

// Email notification
$L['forumsub_mail_subject'] = "New reply in topic: %s";
$L['forumsub_mail_body'] = "Hello %s,\n\n%s has posted a new reply to the topic \"%s\" in the forum section \"%s\".\n\n--- Message excerpt ---\n%s\n-------------------------\n\nYou can read the reply online at:\n%s\n\nIf you no longer wish to receive notifications for this topic, you can unsubscribe by clicking:\n%s\n\nBest regards,\nAdministration";

// Config descriptions
$L['cfg_autosubscribe_newtopic'] = array("Autosubscribe on new topic", "Pre-check the subscription checkbox when creating a new forum topic");
$L['cfg_autosubscribe_reply'] = array("Checkbox in reply form", "Display subscription checkbox in forum quick reply form for non-subscribers");
$L['cfg_notify_once'] = array("Notify once", "Notify once until the user visits the topic again (reserved)");
$L['cfg_itemsperpage'] = array("Subscriptions per page", "Number of items per page in user subscription manager");
$L['cfg_include_css'] = array("Include CSS", "Load default plugin CSS styles");
