<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/neighbortopics/neighbortopics.php
Version=178
Updated=2013-jul-08
Type=Plugin
Author=Seditio Team
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=neighbortopics
Part=main
File=neighbortopics
Hooks=forums.posts.tags
Tags=forums.posts.tpl:{NEIGHBOARD_PREV_TOPIC_TITLE},{NEIGHBOARD_PREV_TOPIC_URL},{NEIGHBOARD_NEXT_TOPIC_TITLE},{NEIGHBOARD_NEXT_TOPIC_URL}
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

if ($q > 0)
{

	$nbt_sql = sed_sql_query("(SELECT ft_id, ft_sectionid, ft_title FROM $db_forum_topics WHERE ft_id <= ".(int)$q." ORDER BY ft_id DESC LIMIT 2)
	UNION (SELECT ft_id, ft_sectionid, ft_title FROM $db_forum_topics WHERE ft_id >= ".(int)$q." ORDER BY ft_id ASC LIMIT 2) ORDER BY ft_id ASC");

	$nbt_ipn = 1;
	$current_ipn = 0;
	while ($nbt_r = sed_sql_fetchassoc($nbt_sql))
		{
		$nbt_row[$nbt_ipn] = $nbt_r;
		if ($nbt_r['ft_id'] == $q) $current_ipn = $nbt_ipn;
		$nbt_ipn++;
		}

	$nbt_ipn_count = count($nbt_row);

	if ($current_ipn > 1 && $current_ipn < $nbt_ipn_count)
	{
		$nbt_prev_id = $nbt_row[$current_ipn - 1]['ft_id'];
		$nbt_prev_title = $nbt_row[$current_ipn - 1]['ft_title'];
		$nbt_next_id = $nbt_row[$current_ipn + 1]['ft_id'];		
		$nbt_next_title = $nbt_row[$current_ipn + 1]['ft_title'];
	}
	elseif ($current_ipn == 1) //first topic in section
	{
		//$nbt_prev_id = $nbt_row[$nbt_ipn_count]['ft_id'];
		//$nbt_prev_title = $nbt_row[$nbt_ipn_count]['ft_title'];
		$nbt_next_id = $nbt_row[$current_ipn + 1]['ft_id'];	
		$nbt_next_title = $nbt_row[$current_ipn + 1]['ft_title'];
	}
	elseif ($current_ipn == $nbt_ipn_count ) //last topic in section
	{
		$nbt_prev_id = $nbt_row[$current_ipn - 1]['ft_id'];
		$nbt_prev_title = $nbt_row[$current_ipn - 1]['ft_title'];
		//$nbt_next_id = $nbt_row[1]['ft_id'];
		//$nbt_next_title = $nbt_row[1]['ft_title'];				
	}

	if (!empty($nbt_prev_id))
		{		
		$t->assign(array(
			"NEIGHBOARD_PREV_TOPIC_TITLE" => $nbt_prev_title,
			"NEIGHBOARD_PREV_TOPIC_URL" => sed_url("forums", "m=posts&q=".$nbt_prev_id)
		));
		$t->parse("MAIN.NEIGHBOARD_PREV");
		}
		
	if (!empty($nbt_next_id))
		{
		$t->assign(array(
			"NEIGHBOARD_NEXT_TOPIC_TITLE" => $nbt_next_title,
			"NEIGHBOARD_NEXT_TOPIC_URL" => sed_url("forums", "m=posts&q=".$nbt_next_id)
		));
		$t->parse("MAIN.NEIGHBOARD_NEXT");
		}
}

?>
