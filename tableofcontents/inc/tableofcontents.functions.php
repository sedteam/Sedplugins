<?php
/* ====================
Seditio - Website engine
Copyright Neocrome
https://seditio.org

[BEGIN_SED]
File=plugins/tableofcontents/inc/tableofcontents.functions.php
Version=179
Updated=2026-jul-07
Type=Plugin
Author=Amro
Description=Common functions for Table of Contents plugin
[END_SED]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

/**
 * Generate table of contents and inject anchors into content headers.
 *
 * @param string $content Raw HTML/text page content
 * @param string $pageurl Base URL for heading anchor links
 * @return array Array with keys 'tbc_contents' (TOC HTML list) and 'content' (modified HTML with anchors)
 */
function sed_generate_tbc($content, $pageurl = '') 
{
	$result = array('tbc_contents' => '', 'content' => $content);

	// Find headers from h1 to h5 in the content
	if (!preg_match_all('#<h([1-5])[^<>]*>(.*?)</h[1-5]>#', $content, $headers)) {
		return $result;
	}
	
	// Return early if there are fewer than 2 headers
	if (count($headers[0]) < 2) {
		return $result;
	}

	$from = array();
	$to = array();
	$depth = 0;
	$start = null;
	
	// Generate TOC HTML list
	$tbc_contents = '<ul class="level-1">';
	foreach ($headers[2] as $i => $header) {
		$header_text = preg_replace('#\s+#', ' ', trim(rtrim($header, ':!.?;')));
		$anchor = sed_translit_seourl(str_replace(' ', '-', $header_text));
		$header_link = '<a href="' . $pageurl . '#' . $anchor . '">' . $header_text . '</a>';

		if ($depth > 0) {
			if ($headers[1][$i] > $depth) {
				while ($headers[1][$i] > $depth) {
					$tbc_contents .= '<ul class="level-' . $depth . '">';
					$depth++;
				}
			} elseif ($headers[1][$i] < $depth) {
				while ($headers[1][$i] < $depth) {
					$tbc_contents .= '</ul>';
					$depth--;
				}
			}
		}
		$depth = (int)$headers[1][$i];
		if ($start === null) {
			$start = $depth;
		}
		$tbc_contents .= '<li>' . $header_link . '</li>';

		$from[$i] = $headers[0][$i];
		// Prepend anchor target markup to the header tag
		$to[$i] = '<a name="' . $anchor . '" class="page-contents-link"></a>' . $headers[0][$i];
	}
	
	// Close all open list tags
	for ($i = 0; $i <= ($depth - $start); $i++) {
		$tbc_contents .= '</ul>';
	}
	
	// Replace headers in content with headers prepended with anchor tags
	$content = str_replace($from, $to, $content);
	
	$result['tbc_contents'] = $tbc_contents;
	$result['content'] = $content;
	
	return $result;
}
