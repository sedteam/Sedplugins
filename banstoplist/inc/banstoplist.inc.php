<?php

function sed_banstoplist($text, $stoplist_arr)
{
	global $usr, $db_users, $db_groups_users;

	$userid = $usr['id'];
	$ban_groupid = 3;
	$stop = 0;

	foreach ($stoplist_arr as $item) {
		if (preg_match("#$item#siu", $text)) {
			$stop = 1;
		}
	}

	if ($stop == 0 || $usr['isadmin']) {
		return $text;
	} else {
		if ($usr['profile']['user_postcount'] < 5) {
			// Set Ban maingroup (3)
			$sql = sed_sql_query("UPDATE $db_users SET user_maingrp='$ban_groupid' WHERE user_id='$userid'");
			// Delete From all groups
			$sql = sed_sql_query("DELETE FROM $db_groups_users WHERE gru_userid='$userid'");
			// Add Ban group
			$sql = sed_sql_query("INSERT INTO $db_groups_users (gru_userid, gru_groupid) VALUES (" . (int)$userid . ", " . (int)$ban_groupid . ")");
			}
		$text = "";
	}
	return $text;
}
