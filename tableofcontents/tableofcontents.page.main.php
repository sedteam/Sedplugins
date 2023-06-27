<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
https://seditio.org

[BEGIN_SED]
File=plugins/tableofcontents/tableofcontents.page.main.php
Version=179
Updated=2023-may-02
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tableofcontents
Part=page
File=tableofcontents.page.main
Hooks=page.main
Tags=page.tpl
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

function generate_tbc($content) {
	global $pag;
	
	// Если в тексте нет заголовков от h1 до h5 - выходим
	if (!preg_match_all('#<h([1-5])[^<>]*>(.*?)</h[1-5]>#', $content, $headers)) {return;}
	
	// Если заголовков меньше 2х - тоже выходим
	if (count($headers[0]) < 2) {return;}

	$base = $pag['page_pageurl'];
	$from = $to = $result = array();
	$depth = 0;
	$start = null;
	
	// Генерация меню
	$tbc_contents = '<ul class="level-1">';
	foreach ($headers[2] as $i => $header) {
		$header = preg_replace('#\s+#', ' ', trim(rtrim($header, ':!.?;')));
		$anchor = sed_translit_seourl(str_replace(' ', '-', $header));
		$header = "<a href=\"{$base}#{$anchor}\">{$header}</a>";

		if ($depth > 0) {
			if ($headers[1][$i] > $depth) {
				while ($headers[1][$i] > $depth) {
					$tbc_contents .= "<ul class=\"level-".$depth."\">";
					$depth++;
				}
			}
			elseif ($headers[1][$i] < $depth) {
				while ($headers[1][$i] < $depth) {
					$tbc_contents .= '</ul>';
					$depth --;
				}
			}
		}
		$depth = $headers[1][$i];
		if ($start === null) {
			$start = $depth;
		}
		$tbc_contents .= '<li>' . $header . '</li>';

		$from[$i] = $headers[0][$i];
		$to[$i] = '<a name="' . $anchor . '" class="page-contents-link"></a>' . $headers[0][$i];
	}
	// Закрытие всех открытых списков
	for ($i = 0; $i <= ($depth - $start); $i ++) {
		$tbc_contents .= "</ul>";
	}
	// Добавление якорей к заголовкам
	$content = str_replace($from, $to, $content);
	
	$result['tbc_contents'] = $tbc_contents; // оглавление
	$result['content'] = $content; // контент с заголовками
	
	return $result;
}

$page_tbc = generate_tbc($pag['page_text']);

$pag['page_tbc'] = $page_tbc['tbc_contents'];
$pag['page_text'] = $page_tbc['content'];

?>
