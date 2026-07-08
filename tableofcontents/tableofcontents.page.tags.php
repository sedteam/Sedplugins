<?php
/* ====================
Seditio - Website engine
Copyright Neocrome
https://seditio.org

[BEGIN_SED]
File=plugins/tableofcontents/tableofcontents.page.tags.php
Version=185
Updated=2026-jul-07
Type=Plugin
Author=Amro
Description=Parse and assign Table of Contents template tag
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tableofcontents
Part=page
File=tableofcontents.page.tags
Hooks=page.tags
Tags=page.tpl
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

if ($path_lang = sed_langfile('tableofcontents', 'plugin', $usr['lang'])) {
	require($path_lang);
}

if (!empty($pag['page_tbc'])) {
	// Prepend the page URL to the cached anchors so links work from other contexts/bases
	$tbc_with_url = str_replace('href="#', 'href="' . $pag['page_pageurl'] . '#', $pag['page_tbc']);
	
	$mskin = sed_skinfile('tableofcontents', true);
	if (!empty($mskin)) {
		$t_toc = new XTemplate($mskin);
		$t_toc->assign('PAGE_TBC', $tbc_with_url);
		$t_toc->parse('MAIN');
		$tbc_html = $t_toc->text('MAIN');
		$t->assign('PAGE_TOC', $tbc_html);
	} else {
		// Fallback directly to raw HTML if template is missing
		$t->assign('PAGE_TOC', $tbc_with_url);
	}
} else {
	$t->assign('PAGE_TOC', '');
}
?>
