<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
http://www.neocrome.net

[BEGIN_SED]
File=plugins/forumbasicstats/forumbasicstats.php
Version=178
Updated=2020-jun-07
Type=Plugin
Author=Neocrome
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=forumbasicstats
Part=main
File=forumbasicstats
Hooks=forums.sections.tags
Tags=forums.sections.tpl:{FORUMS_BASICSTATS}
Order=10
[END_SED_EXTPLUGIN]

============ */

if ( !defined('SED_CODE') || !defined('SED_FORUMS') ) { die("Wrong URL."); }

$evil_p = sed_import('forumbasicstats','P','TXT');
$evil_g = sed_import('forumbasicstats','G','TXT');

require("plugins/forumbasicstats/lang/forumbasicstats.".$usr['lang'].".lang.php");

$cfg_days = $cfg['plugin']['forumbasicstats']['config_days'];

if (!$forumbasicstats || $evil_g!='' || $evil_p!='' )
	{
	$timeback = $sys['now_offset'] - ($cfg_days * 86400);
	$weekback1 = 	 $sys['now_offset'] - (604800);
	$weekback2 = 	 $sys['now_offset'] - (1209600);
	$totaltopics = sed_sql_rowcount($db_forum_topics);
	$totalposts = sed_sql_rowcount($db_forum_posts); 
	
	/*$sqltmp = sed_sql_query("SELECT SUM(fs_topiccount_pruned) FROM $db_forum_sections ");
	$totaltopics += sed_sql_result($sqltmp,0,"SUM(fs_topiccount_pruned)");
	$sqltmp = sed_sql_query("SELECT SUM(fs_postcount_pruned) FROM $db_forum_sections ");
	$totalposts += sed_sql_result($sqltmp,0,"SUM(fs_postcount_pruned)");*/
	
	$sqltmp = sed_sql_query("SELECT stat_value FROM $db_stats where stat_name='maxusers' LIMIT 1");
	$row = sed_sql_fetcharray($sqltmp);
	$maxusers = $row[0];	
	$sqltmp = sed_sql_query("SELECT user_name,user_id FROM $db_users WHERE user_maingrp!=2 ORDER by user_regdate DESC LIMIT 1");
	$row=sed_sql_fetcharray($sqltmp);
	$latestuser = sed_build_user($row["user_id"], stripslashes($row["user_name"]));
	$sqltmp = sed_sql_query("SELECT COUNT(*) FROM $db_users WHERE user_regdate>'$timeback' ");
	$recentreg = sed_sql_result($sqltmp,0,"COUNT(*)");
	$sqltmp = sed_sql_query("SELECT COUNT(*) FROM $db_forum_posts WHERE fp_updated>'$timeback' ");
	$recentposts = sed_sql_result($sqltmp,0,"COUNT(*)");
	$sqltmp = sed_sql_query("SELECT COUNT(*) FROM $db_forum_posts WHERE fp_updated>'$weekback1' ");
	$postslastweek = sed_sql_result($sqltmp,0,"COUNT(*)");
	$sqltmp = sed_sql_query("SELECT COUNT(*) FROM $db_forum_posts WHERE fp_updated>'$weekback2' AND fp_updated<'$weekback1'");
	$postsweekbefore = sed_sql_result($sqltmp,0,"COUNT(*)");
	
	if (!$postsweekbefore==0)
		{
		$weeklyactivity = floor( 100 * ($postslastweek - $postsweekbefore) / $postsweekbefore);
		$weeklyactivity = ($weeklyactivity>0) ? "+".$weeklyactivity : $weeklyactivity;
		}
	else 
		{
		$weeklyactivity ="+oo";
		}
		
	$forumbasicstats = "".$L['fbs_our']." <a href=\"".sed_url('users')."\"><strong>".sed_sql_rowcount($db_users)."</strong> ".$L['fbs_members']."</a> ".$L['fbs_totalpostsmade']." <strong>".$totalposts."</strong> ".$L['fbs_postsin']." <strong>".$totaltopics."</strong> ".$L['fbs_topics'].".<br />";
	$forumbasicstats .= "".$L['fbs_duration']." ".$cfg_days." ".$L['fbs_day'].": <strong>".$recentposts."</strong> ".$L['fbs_newposts'].",";
	$forumbasicstats .= " <strong>".$recentreg."</strong> ".$L['fbs_totalnewmembers'].".<br />";
	$forumbasicstats .= "".$L['fbs_varactivity'].": <strong>".$weeklyactivity."%</strong>.<br />";
	$forumbasicstats .= "".$L['fbs_newestmember']." ".$latestuser.".<br />";
	$forumbasicstats .= "".$L['fbs_mostonline'].": <strong>".$maxusers."</strong>.";
	sed_cache_store('forumbasicstats',$forumbasicstats,120);
	}

$t-> assign(array(
	"FORUMS_BASICSTATS" => $forumbasicstats
));

?>
