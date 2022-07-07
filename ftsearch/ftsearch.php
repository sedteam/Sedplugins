<?PHP
/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ftsearch/ftsearch.php
Version=0.2
Updated=2022-jul-07
Type=Plugin
Author=Tefra & Amro
Description
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ftsearch
Part=main
File=ftsearch
Hooks=standalone
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$d = sed_import('d','G','INT');
$o = sed_import('o','G','ALP');
$w = sed_import('w','G','ALP',4);
$s = sed_import('s','G','INT');

function rev($sway)
	{
	if ($sway=='desc')
{ return ('asc'); }
	else
{ return ('desc'); }
	}

if (empty($o)) { $o = 'updated'; }
if (empty($w)) { $w = 'desc'; }
if (empty($d)) { $d = '0'; }

switch($a)
{
// ========================
case 'unanswered':
// ========================	
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_topics WHERE  ft_postcount=1");
	$totaltopics = sed_sql_result($sql, 0, "COUNT(*)");
	$sql = sed_sql_query("SELECT * FROM $db_forum_topics WHERE ft_postcount=1 ORDER by ft_".$o." ".$w." LIMIT $d, ".$cfg['maxtopicsperpage']);
	$cases="&a=unanswered";
	$title= $L['unanswered'];
	$errormessage = $L['error_case_unanswered'];
break;


// ========================
case 'getdaily':
// ========================
	$timeback = $sys['now_offset'] - (86400);
	$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_topics WHERE ft_updated >= $timeback");
	$totaltopics = sed_sql_result($sql, 0, "COUNT(*)");
	$sql = sed_sql_query("SELECT * FROM $db_forum_topics WHERE ft_updated >= $timeback ORDER by ft_".$o." ".$w." LIMIT $d, ".$cfg['maxtopicsperpage']);
	$cases="&a=getdaily";
	$title= $L['getdaily'];
	$errormessage = $L['error_case_getdaily'];
break;
// ========================
default:
// ========================
	if ($usr['id']>0 ) 
		{
		$timeback = $usr['lastvisit'];
		$sql = sed_sql_query("SELECT COUNT(*) FROM $db_forum_topics WHERE ft_updated >= $timeback and ft_lastposterid!=".$usr['id']."");
		$totaltopics = sed_sql_result($sql, 0, "COUNT(*)");
		$sql = sed_sql_query("SELECT * FROM $db_forum_topics WHERE ft_updated >= $timeback ORDER by ft_".$o." ".$w." LIMIT $d, ".$cfg['maxtopicsperpage']);
		}
	else
		{
		sed_redirect(sed_url("plug", "e=ftsearch&a=getdaily", "", true));
		}
	$cases="";
	$title= $L['ftsearch'];
	$errormessage = $L['error_case_getnew'];
break;
}
// =========== END OF CASES ============= //

/* === Hook === */
$extp = sed_getextplugins('ftsearch');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */


if ($totaltopics > 0 ) {
	while ($row = sed_sql_fetcharray($sql))
		{
		$row['ft_icon'] = 'posts';
		$row['ft_postisnew'] = FALSE;
		$row['ft_pages'] = '';
		$ft_num++;

		if ($row['ft_mode']==1)
			{ 
			$row['ft_title'] = "# ".$row['ft_title'];
			}

		if ($row['ft_movedto']>0)
			{
			$row['ft_url'] = sed_url("forums", "m=posts&q=".$row['ft_movedto']);
			$row['ft_icon'] = "<img src=\"skins/$skin/img/system/posts_moved.gif\" alt=\"\" />";
			$row['ft_title']= $L['Moved'].": ".$row['ft_title'];
			$row['ft_lastpostername'] = "&nbsp;";
			$row['ft_postcount'] = "&nbsp;";
			$row['ft_replycount'] = "&nbsp;";
			$row['ft_viewcount'] = "&nbsp;";
			$row['ft_lastpostername'] = "&nbsp;";
			$row['ft_lastposturl'] = "<a href=\"".sed_url("forums", "m=posts&q=".$row['ft_movedto']."&n=last", "#bottom")."\"><img src=\"skins/$skin/img/system/arrow-follow.gif\" alt=\"\" /></a> ".$L['Moved'];
			$row['ft_timago'] = sed_build_timegap($row['ft_updated'],$sys['now_offset']);
			}
		else
			{
			$row['ft_url'] = sed_url("forums", "m=posts&q=".$row['ft_id']);
			$row['ft_lastposturl'] = ($usr['id']>0 && $row['ft_updated'] > $usr['lastvisit']) ? "<a href=\"".sed_url("forums", "m=posts&q=".$row['ft_id']."&n=unread", "#unread")."\"><img src=\"skins/$skin/img/system/arrow-unread.gif\" alt=\"\" /></a>" : "<a href=\"".sed_url("forums", "m=posts&q=".$row['ft_id']."&n=last", "#bottom")."\"><img src=\"skins/$skin/img/system/arrow-follow.gif\" alt=\"\" /></a>";
			$row['ft_lastposturl'] .= @date($cfg['formatmonthdayhourmin'], $row['ft_updated'] + $usr['timezone'] * 3600);
			$row['ft_timago'] = sed_build_timegap($row['ft_updated'],$sys['now_offset']);
			$row['ft_replycount'] = $row['ft_postcount'] - 1;

			if ($row['ft_updated']>$usr['lastvisit'] && $usr['id']>0)
				{
				$row['ft_icon'] .= '_new';
				$row['ft_postisnew'] = TRUE;
				}

			if ($row['ft_postcount']>=$cfg['hottopictrigger'] && !$row['ft_state'] && !$row['ft_sticky'])
				{ $row['ft_icon'] = ($row['ft_postisnew']) ? 'posts_new_hot' : 'posts_hot'; }
			else
				{
				if ($row['ft_sticky'])
				{ $row['ft_icon'] .= '_sticky'; }

				if ($row['ft_state'])
				{ $row['ft_icon'] .= '_locked'; }
				}

			$row['ft_icon'] = "<img src=\"skins/$skin/img/system/".$row['ft_icon'].".gif\" alt=\"\" />";
			$row['ft_lastpostername'] = sed_build_user($row['ft_lastposterid'], sed_cc($row['ft_lastpostername']));
			}

		$row['ft_firstpostername'] = sed_build_user($row['ft_firstposterid'], sed_cc($row['ft_firstpostername']));

		if ($row['ft_poll']>0)
			{ $row['ft_title'] = $L['Poll'].": ".$row['ft_title']; }

		if ($row['ft_postcount']>$cfg['maxtopicsperpage'])
			{
			$row['ft_maxpages'] = ceil($row['ft_postcount'] / $cfg['maxtopicsperpage']);
			$row['ft_pages'] = $L['Pages'].":";
			for ($a = 1; $a <= $row['ft_maxpages']; $a++)
				{
				$row['ft_pages'] .= (is_int($a/5) || $a<10 || $a==$row['ft_maxpages']) ? " <a href=\"".$row['ft_url']."?d=".($a-1) * $cfg['maxtopicsperpage']."\">".$a."</a>" : '';
				} 
			}

		$t-> assign(array(
			"FTSEARCH_ROW_ID" => $row['ft_id'],
			"FTSEARCH_ROW_STATE" => $row['ft_state'],
			"FTSEARCH_ROW_ICON" => $row['ft_icon'],
			"FTSEARCH_ROW_TITLE" => sed_cc($row['ft_title']),
			"FTSEARCH_ROW_DESC" => sed_cc($row['ft_desc']),
			"FTSEARCH_ROW_CREATIONDATE" => @date($cfg['formatmonthdayhourmin'], $row['ft_creationdate'] + $usr['timezone'] * 3600),
			"FTSEARCH_ROW_UPDATED" => $row['ft_lastposturl'],
			"FTSEARCH_ROW_TIMEAGO" => $row['ft_timago'],
			"FTSEARCH_ROW_POSTCOUNT" => $row['ft_postcount'],
			"FTSEARCH_ROW_REPLYCOUNT" => $row['ft_replycount'],
			"FTSEARCH_ROW_VIEWCOUNT" => $row['ft_viewcount'],
			"FTSEARCH_ROW_FIRSTPOSTER" => $row['ft_firstpostername'],
			"FTSEARCH_ROW_LASTPOSTER" => $row['ft_lastpostername'],
			"FTSEARCH_ROW_URL" => $row['ft_url'],
			"FTSEARCH_ROW_PAGES" => $row['ft_pages'],
			"FTSEARCH_ROW_MAXPAGES" => $row['ft_maxpages'],
			"FTSEARCH_ROW_ODDEVEN" => sed_build_oddeven($ft_num),
			"FTSEARCH_ROW" => $row
		));

		$t->parse("MAIN.FTSEARCH.TOPICS_ROW");
		}
  
	$pages = sed_pagination(sed_url("plug", "e=ftsearch".$cases."&s=".$s), $d, $totaltopics, $cfg['maxtopicsperpage']);
	list($prevpage, $nextpage) = sed_pagination_pn(sed_url("plug", "e=ftsearch".$cases."&s=".$s), $d, $totaltopics, $cfg['maxtopicsperpage'], TRUE);  

	// ---------- Breadcrumbs
	$urlpaths = array();
	$urlpaths[sed_url("forums")] = $L['Forums'];
	$urlpaths[sed_url("plug", "e=ftsearch".$cases)] = $title;

	$t->assign(array(
		"FTSEARCH_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
		"FTSEARCH_TITLE" => $title,
		"FTSEARCH_PAGES" => $pages,
		"FTSEARCH_PAGEPREV" => $prevpage,
		"FTSEARCH_PAGENEXT" => $nextpage,  
		"FTSEARCH_PRVTOPICS" => $prvtopics,
		"FTSEARCH_TITLE_TOPICS" => "<a href=\"".sed_url("plug", "e=ftsearch".$cases."&s=".$s."&o=title&w=".rev($w))."\">".$L['Topics']."</a>",
		"FTSEARCH_TITLE_VIEWS" => "<a href=\"".sed_url("plug", "e=ftsearch".$cases."&s=".$s."&o=viewcount&w=".rev($w))."\">".$L['Views']."</a>",
		"FTSEARCH_TITLE_POSTS" => "<a href=\"".sed_url("plug", "e=ftsearch".$cases."&s=".$s."&o=postcount&w=".rev($w))."\">".$L['Posts']."</a>",
		"FTSEARCH_TITLE_REPLIES" => "<a href=\"".sed_url("plug", "e=ftsearch".$cases."&s=".$s."&o=postcount&w=".rev($w))."\">".$L['Replies']."</a>",
		"FTSEARCH_TITLE_STARTED" => "<a href=\"".sed_url("plug", "e=ftsearch".$cases."&s=".$s."&o=creationdate&w=".rev($w))."\">".$L['Started']."</a>",
		"FTSEARCH_TITLE_LASTPOST" => "<a href=\"".sed_url("plug", "e=ftsearch".$cases."&s=".$s."&o=updated&w=".rev($w))."\">".$L['Lastpost']."</a>"
	));

	/* === Hook === */
	$extp = sed_getextplugins('ftsearch.tags');
	if (is_array($extp))
		{ foreach($extp as $k => $pl) { include('plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
	/* ===== */

	$t->parse("MAIN.FTSEARCH");
}
else {

	// ---------- Breadcrumbs
	$urlpaths = array();
	$urlpaths[sed_url("forums")] = $L['Forums'];
	$urlpaths[sed_url("plug", "e=ftsearch".$cases)] = $title;	

	$t->assign(array(
		"FTSEARCH_BREADCRUMBS" => sed_breadcrumbs($urlpaths),
		"FTSEARCH_TITLE" => $title
	));
	
	$t->assign("NO_TOPICS_FOUND_BODY", $errormessage);
	$t->parse("MAIN.NO_TOPICS_FOUND");
}

?>
