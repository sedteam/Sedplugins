<?PHP

/* ====================
Seditio - Website engine
Copyright Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/extrafilters/extrafilters.list.tags.php
Version=178
Updated=2022-aug-01
Type=Plugin
Author=Amro
Description=The plugin displays extrafilters in list
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=extrafilters
Part=main
File=extrafilters.list.tags
Hooks=list.tags
Tags=
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]

==================== */

function sed_filters_sorting($sort_fields, $url, $defaulturl)
	{
	global $filter_urlspar, $filter_urlparams, $c, $sed_dic, $w, $s, $pn_w, $pn_s;
	$filter_urlspar_arr = array();
	if (count($filter_urlspar) > 0)
		{
			foreach($filter_urlspar as $fkey => $fval) 
				{ 
				$filter_urlspar_arr[] = $fkey."=".$fval; 
				}
			$filter_urlparams = (count($filter_urlspar_arr) > 0) ? "&".implode('&', $filter_urlspar_arr) : '';
		}

	$sort_filters = 		
	"<select onchange=\"location = this.value;\">
		<option value=\"".sed_url('list', $defaulturl.$filter_urlparams)."\" disabled>Сортировать</option>
		<option value=\"".sed_url('list', $defaulturl.$filter_urlparams)."\">По умолчанию</option>
	";
	
	foreach ($sort_fields['s'] as $key => $val)
		{
		$selected = ($s == $val && $w == $sort_fields['w'][$key]) ? 'selected' : '';
		$url2 = "&s=".$val."&w=".$sort_fields['w'][$key];
		$sort_filters .= "<option ".$selected." value=\"".sed_url('list', $url.$filter_urlparams.$url2)."\">".$sort_fields['title'][$key]."</option>";
		}
			
	$sort_filters .= "</select>";		
	return $sort_filters;
	}

function sed_filters_selectbox($check, $name, $values, $url, $empty_option = true, $placeholder = '')
	{
	global $filter_urlspar, $filter_urlparams, $c, $sed_dic;
	
	$filter_urlspar_arr = array();
	if (count($filter_urlspar) > 0)
		{
			foreach($filter_urlspar as $fkey => $fval) 
				{ 
				if ($fkey != 'filter_'.$name) $filter_urlspar_arr[] = $fkey."=".$fval; 
				}
			$filter_urlparams = (count($filter_urlspar_arr) > 0) ? "&".implode('&', $filter_urlspar_arr) : '';
		}
	
	$check = trim($check);	
	
	$selected = (empty($check) || $check == "00") ? "selected=\"selected\"" : '';
	
	if (!empty($placeholder)) 
		{ $placeholder_option = "<option value=\"".sed_url('list', $url.$filter_urlparams)."\" disabled $selected>".$placeholder."</option>"; $selected = ''; } 
	else { $placeholder_option = ''; }
	
	if ($empty_option) { $first_option = "<option value=\"".sed_url('list', $url.$filter_urlparams)."\" $selected>Все</option>"; } else { $first_option = ''; }
	
	$result =  "<select name=\"$name\" size=\"1\" onchange=\"location = this.value;\">".$placeholder_option.$first_option;
	
	foreach ($values as $k => $x)
		{
		$url2 = "&filter_".$name."=".sed_cc($x);
		$selected = ($x == $check) ? "selected=\"selected\"" : '';
		
		$optname = sed_cc($x);
		if (isset($sed_dic[$name]) && $sed_dic[$name]['type'] == 'select') {
			$optname = (isset($sed_dic[$name]['terms'][$x])) ? $sed_dic[$name]['terms'][$x] : $optname;
		}
				
		$result .= "<option value=\"".sed_url('list', $url.$filter_urlparams.$url2)."\" $selected>".$optname."</option>";
		}
		
	$result .= "</select>";
	return($result);	
	}

/**/
$extra_filters = $cfg['plugin']['extrafilters']['extraf'];
$extra_placeholders = $cfg['plugin']['extrafilters']['extrafplaceholder'];
$extra_sorting = $cfg['plugin']['extrafilters']['extrasorting'];
$extra_sortingnames = $cfg['plugin']['extrafilters']['extrasortingnaming'];
/**/

$filters_arr = array();

if (!empty($extra_filters)) 
	{ 
	$extra_filters_arr = explode(',', $extra_filters);	
	$extra_placeholders_arr = (!empty($extra_placeholders)) ? explode(',', $extra_placeholders) : array();
	
	$jij = 0;
	foreach($extra_filters_arr as $extra_field)
		{					
		if (array_key_exists($extra_field, $sed_dic))
			{
			$sql_extra_field = "page_".trim(rtrim($extra_field));
			$sql = sed_sql_query("SELECT $sql_extra_field FROM $db_pages WHERE $sql_extra_field != '' and page_state = 0 AND page_cat ='$c' GROUP BY $sql_extra_field DESC");
			while ($row = sed_sql_fetchassoc($sql))
				{
				$filters_arr[$extra_field][] = $row[$sql_extra_field];
				$filters_arr_placeholders[$extra_field] = (isset($extra_placeholders_arr[$jij])) ? $extra_placeholders_arr[$jij] : '';
				}				
			}
		$jij++;
		}
	}

$filter_preurl_params = "c=".$c."&s=".$s."&w=".$w."&o=".$o."&p=".$p;
	
if (count($filters_arr) > 0)
	{
	foreach ($filters_arr as $filter_key => $filter_row)
		{
		$checked_filter = isset($filter_vars['filter_'.$filter_key])?$filter_vars['filter_'.$filter_key]:'';
		$placeholder_filter = isset($filters_arr_placeholders[$filter_key]) ? $filters_arr_placeholders[$filter_key] : '';		
		
		$t->assign("FILTER_".mb_strtoupper($filter_key), sed_filters_selectbox($checked_filter, $filter_key, $filter_row, $filter_preurl_params, true, $placeholder_filter));
		}
	}
	
if (!empty($extra_sorting))
	{
	$extra_sorting_arr = array();
	$extra_sorting_arr = explode(',', $extra_sorting);
	$sort_fields = array();
	$sort_fields['s'] = array();
	$sort_fields['w'] = array();
	$sort_fields['title'] = array();
	
	$extra_sortingnames_arr = (!empty($extra_sortingnames)) ? explode(',', $extra_sortingnames) : array();
	
	if (count($extra_sorting_arr) > 0)
		{
		$iji = 0;
		foreach ($extra_sorting_arr as $extra_sort)
			{
			$extra_sortingname = (isset($extra_sortingnames_arr[$iji])) ? $extra_sortingnames_arr[$iji] : '';
			
			if (preg_match("#(.*)\((.*)\|(.*)\)#", $extra_sort, $match))                     
				{
				if (!empty($match[1]) && !empty($match[2]) && !empty($match[3]))
					{
					array_push($sort_fields['s'], $match[1]);
					array_push($sort_fields['s'], $match[1]);
					array_push($sort_fields['w'], $match[2]);
					array_push($sort_fields['w'], $match[3]);					
					array_push($sort_fields['title'], $extra_sortingname.' ↑');
					array_push($sort_fields['title'], $extra_sortingname.' ↓');
					}
				}
			elseif (preg_match("#(.*)\((.*)\)#", $extra_sort, $match))                     
				{
				if (!empty($match[1]) && !empty($match[2]))
					{
					array_push($sort_fields['s'], $match[1]);
					array_push($sort_fields['w'], $match[2]);
					array_push($sort_fields['title'], $extra_sortingname.' ↓');
					}
				}
			else 
				{
				array_push($sort_fields['s'], $extra_sort);
				array_push($sort_fields['w'], 'desc');
				array_push($sort_fields['title'], $extra_sortingname);
				}
			$iji++;
			}
		}		
		$defaulturl_preurl_params = "c=".$c."&o=".$o."&p=".$p;
		$filter_preurl_params = "c=".$c."&s=".$pn_s."&w=".$pn_w."&o=".$o."&p=".$p;
		
		$t-> assign(array(
			"SORT_FILTERS" => sed_filters_sorting($sort_fields, $filter_preurl_params, $defaulturl_preurl_params)
		));	

//print_r($sort_fields);
		
	}

?>