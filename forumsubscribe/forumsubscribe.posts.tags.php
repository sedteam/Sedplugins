<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumsubscribe/forumsubscribe.posts.tags.php
Version=186
Updated=2026-aug-26
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=forumsubscribe
Part=posts.tags
Hooks=forums.posts.tags
File=forumsubscribe.posts.tags
Order=10
Lock=0
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

if ($usr['id'] > 0 && !empty($q)) {
	$is_subscribed = sed_forumsubscribe_check($usr['id'], $q);
	$sub_action = $is_subscribed ? 'unsubscribe' : 'subscribe';
	$sub_url = sed_url('plug', 'e=forumsubscribe&a=' . $sub_action . '&q=' . $q . '&' . sed_xg());
	$ajax_url = sed_url('plug', 'ajx=forumsubscribe&a=' . $sub_action . '&q=' . (int)$q, '', false, false);
	$sub_text = $is_subscribed ? $L['forumsub_unsubscribe'] : $L['forumsub_subscribe'];
	$sub_class = $is_subscribed ? 'btn btn-forumsub-unsub' : 'btn';

	$sub_button = '<a href="' . $sub_url . '" class="' . $sub_class . '" data-forumsub-topic="' . (int)$q . '" data-forumsub-action="' . $sub_action . '" data-forumsub-ajax-url="' . $ajax_url . '" rel="nofollow"><span>' . $sub_text . '</span></a>';

	$t->assign(array(
		"FORUMS_POSTS_SUBSCRIBE" => $sub_button,
		"FORUMS_POSTS_SUBSCRIBE_URL" => $sub_url,
		"FORUMS_POSTS_SUBSCRIBE_AJAX_URL" => $ajax_url,
		"FORUMS_POSTS_SUBSCRIBE_TEXT" => $sub_text,
		"FORUMS_POSTS_SUBSCRIBE_STATE" => ($is_subscribed ? 1 : 0),
		"FORUMS_POSTS_SUBSCRIBE_HINT" => $L['forumsub_subscribe_hint']
	));

	$t->parse("MAIN.FORUMS_POSTS_SUBSCRIBE");
}
