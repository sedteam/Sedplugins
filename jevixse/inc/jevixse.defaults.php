<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

Default JevixSE rules (tags + params) per mode. Used when jevixse.rules.php is absent.
Do not use #domain and other complex structures here; those stay in jevixse.class.php.
==================== */

if (!defined('SED_CODE')) {
	exit;
}

function sed_jevixse_default_rules()
{
	return array(
		'full' => array(
			'tags' => array(
				'p', 'a', 'img', 'i', 'b', 'u', 's', 'em', 'strong', 'strike', 'small', 'nobr',
				'li', 'ol', 'ul', 'sup', 'abbr', 'sub', 'acronym', 'h1', 'h2', 'button', 'h3', 'h4', 'h5', 'h6',
				'br', 'hr', 'pre', 'code', 'object', 'param', 'embed', 'adabracut', 'blockquote', 'iframe',
				'span', 'div', 'table', 'tbody', 'thead', 'tfoot', 'tr', 'td', 'th', 'video', 'source'
			),
			'params' => array(
				'p' => array('style'),
				'i' => array('class'),
				'span' => array('style', 'class', 'id'),
				'a' => array('title', 'href' => '#link', 'rel' => '#text', 'name' => '#text', 'target' => array('_blank')),
				'img' => array('src' => '#image', 'style' => '#text', 'alt' => '#text', 'title', 'align' => array('right', 'left', 'center'), 'width' => '#int', 'height' => '#int', 'hspace' => '#int', 'vspace' => '#int'),
				'object' => array('width' => '#int', 'height' => '#int', 'data' => '#link', 'type' => '#text', 'class' => '#text', 'frameborder' => '#int', 'title' => '#text'),
				'param' => array('name' => '#text', 'value' => '#text'),
				'embed' => array('src' => '#image', 'type' => '#text', 'allowscriptaccess' => '#text', 'allowfullscreen' => '#text', 'width' => '#int', 'height' => '#int', 'flashvars' => '#text', 'wmode' => '#text'),
				'iframe' => array('width' => '#int', 'height' => '#int', 'src' => '#link', 'type' => '#text', 'class' => '#text', 'frameborder' => '#int', 'title' => '#text'),
				'pre' => array('class'),
				'acronym' => array('title'),
				'abbr' => array('title'),
				'hr' => array('id' => '#text', 'class'),
				'div' => array('class', 'id', 'style', 'data-minlevel', 'data-mingroup'),
				'button' => array('class', 'id', 'style'),
				'h1' => array('style'), 'h2' => array('style'), 'h3' => array('style'), 'h4' => array('style'), 'h5' => array('style'), 'h6' => array('style'),
				'ul' => array('class', 'id', 'style'), 'ol' => array('class', 'id', 'style'), 'li' => array('class', 'id', 'style'),
				'table' => array('border', 'class', 'width', 'align', 'valign', 'style'),
				'tr' => array('height', 'class'),
				'td' => array('colspan', 'rowspan', 'class', 'width', 'height', 'align', 'valign'),
				'th' => array('colspan', 'rowspan', 'class', 'width', 'height', 'align', 'valign'),
				'video' => array('controls', 'style', 'class', 'src' => '#link', 'type' => '#text', 'width' => '#text', 'height' => '#text', 'autoplay', 'loop', 'muted', 'poster' => '#link', 'preload' => '#text'),
				'source' => array('src' => '#link', 'type' => '#text'),
			),
		),
		'medium' => array(
			'tags' => array(
				'p', 'a', 'img', 'i', 'b', 'u', 's', 'em', 'strong', 'strike', 'small', 'nobr',
				'li', 'ol', 'ul', 'sup', 'abbr', 'sub', 'acronym', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
				'br', 'hr', 'pre', 'code', 'blockquote', 'span', 'div'
			),
			'params' => array(
				'p' => array('style'),
				'i' => array('class'),
				'span' => array('style', 'class', 'id'),
				'div' => array('class', 'id', 'style', 'data-minlevel', 'data-mingroup'),
				'a' => array('title', 'href' => '#link', 'rel' => '#text', 'name' => '#text', 'target' => array('_blank')),
				'img' => array('src' => '#image', 'style' => '#text', 'alt' => '#text', 'title', 'align' => array('right', 'left', 'center'), 'width' => '#int', 'height' => '#int', 'hspace' => '#int', 'vspace' => '#int'),
				'pre' => array('class'),
				'acronym' => array('title'),
				'abbr' => array('title'),
				'hr' => array('id' => '#text', 'class'),
				'h1' => array('style'), 'h2' => array('style'), 'h3' => array('style'), 'h4' => array('style'), 'h5' => array('style'), 'h6' => array('style'),
				'ul' => array('class', 'id', 'style'), 'ol' => array('class', 'id', 'style'), 'li' => array('class', 'id', 'style'),
			),
		),
		'micro' => array(
			'tags' => array('p', 'a', 'i', 'b', 'u', 's', 'em', 'strong', 'br', 'strike'),
			'params' => array(
				'i' => array('class'),
				'a' => array('title', 'href' => '#link', 'rel' => '#text', 'name' => '#text', 'target' => array('_blank')),
			),
		),
	);
}

/**
 * Default behavior (short, preformatted, cut_with_content, empty, no_typography, params_required, childs, param_default, auto_replace, xhtml, auto_br, auto_link) per mode.
 * Used when rules file has no behavior keys; applied in sed_jevixse() from rules.
 */
function sed_jevixse_default_behavior()
{
	$auto_replace = array(
		'from' => array('+/-', '(c)', '(с)', '(r)', '(C)', '(С)', '(R)'),
		'to'   => array('±', '©', '©', '®', '©', '©', '®'),
	);
	return array(
		'full' => array(
			'short'            => array('br', 'img', 'hr', 'source'),
			'preformatted'     => array('pre', 'code'),
			'cut_with_content' => array('script', 'style', 'meta'),
			'empty'            => array('param', 'embed', 'a', 'i', 'iframe', 'source', 'video'),
			'no_typography'    => array('code', 'video', 'object'),
			'params_required'  => array('img' => array('src'), 'a' => array('href')),
			'childs'           => array(
				array('ul', array('li'), false, true),
				array('ol', array('li'), false, true),
				array('object', array('param'), false, true),
				array('object', array('embed'), false, false),
				array('video', array('source'), false, true),
			),
			'param_default'    => array(
				array('embed', 'wmode', 'opaque', true),
			),
			'auto_replace'     => $auto_replace,
			'xhtml'            => true,
			'auto_br'          => false,
			'auto_link'        => false,
		),
		'medium' => array(
			'short'            => array('br', 'img', 'hr'),
			'preformatted'     => array('pre', 'code'),
			'cut_with_content' => array('script', 'style', 'meta'),
			'empty'            => array('a', 'i'),
			'no_typography'    => array('code'),
			'params_required'  => array('img' => array('src'), 'a' => array('href')),
			'childs'           => array(
				array('ul', array('li'), false, true),
				array('ol', array('li'), false, true),
			),
			'param_default'    => array(),
			'auto_replace'     => $auto_replace,
			'xhtml'            => true,
			'auto_br'          => false,
			'auto_link'        => false,
		),
		'micro' => array(
			'short'            => array('br'),
			'preformatted'     => array(),
			'cut_with_content' => array(),
			'empty'            => array('a', 'i'),
			'no_typography'    => array(),
			'params_required'  => array('a' => array('href')),
			'childs'           => array(),
			'param_default'    => array(),
			'auto_replace'     => $auto_replace,
			'xhtml'            => true,
			'auto_br'          => false,
			'auto_link'        => false,
		),
	);
}
