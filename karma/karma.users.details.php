<?php

/* ====================
Seditio - Website engine
Copyright Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/karma/karma.users.details.php
Version=180
Updated=2025-feb-25
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=karma
Part=main
File=karma.users.details
Hooks=users.details.tags
Tags=users.details.tpl:{USERS_DETAILS_KARMA}
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) {
	die("Wrong URL.");
}

if ($urr['user_karma'] > 0) {
	$t->assign(array(
		"USERS_DETAILS_KARMA" => "<b><font color=\"green\">" . $urr['user_karma'] . "</font></b>"
	));
} elseif ($urr['user_karma'] == 0) {
	$t->assign(array(
		"USERS_DETAILS_KARMA" => "---"
	));
} else {
	$t->assign(array(
		"USERS_DETAILS_KARMA" => "<b><font color=\"red\">" . $urr['user_karma'] . "</font></b>"
	));
}
