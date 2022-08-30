<?PHP

/* ====================
Seditio - Website engine
Copyright Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/ogmetas/ogmetas.header.first.php
Version=179
Updated=2022-aug-29
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ogmetas
Part=header
File=ogmetas.header.first
Hooks=header.first
Tags=
Minlevel=0
Order=5
[END_SED_EXTPLUGIN]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

$og_image_width =  (!empty($cfg['plugin']['ogmetas']['img_width'])) ? $cfg['plugin']['ogmetas']['img_width'] : "800";
$og_image_height =  (!empty($cfg['plugin']['ogmetas']['img_height'])) ? $cfg['plugin']['ogmetas']['img_height'] : "800";
$og_image_default = (!empty($cfg['plugin']['ogmetas']['img_deafult'])) ? crop_image($cfg['plugin']['ogmetas']['img_deafult'], $og_image_width, $og_image_height, false, false) : crop_image('noimg.jpg', $og_image_width, $og_image_height, false, false);
$og_locale = (!empty($cfg['plugin']['ogmetas']['locale'])) ? $cfg['plugin']['ogmetas']['locale'] : "en_GB";
$og_type = ($location == 'Pages') ? 'article' : "website";
$og_description = (empty($out['subdesc'])) ? $cfg['subtitle'] : htmlspecialchars($out['subdesc']);

$out['image'] = (empty($out['image'])) ? $og_image_default : crop_image($out['image'], $og_image_width, $og_image_height, false, false);

$moremetas .= "
		<meta property=\"og:site_name\" content=\"".$cfg['maintitle']." - ".$cfg['subtitle']."\" />
		<meta property=\"og:title\" content=\"".$out['subtitle']."\" />
		<meta property=\"og:description\" content=\"".$og_description."\" />
		<meta property=\"og:url\" content=\"".$sys['canonical_url']."\" />
		<meta property=\"og:type\" content=\"".$og_type."\" />
		<meta property=\"og:locale\" content=\"".$og_locale."\" />
		<meta property=\"og:image\" content=\"".$sys['abs_url'].$out['image']."\" />
		<meta property=\"og:image:secure_url\" content=\"".$sys['abs_url'].$out['image']."\" />
		<meta property=\"og:image:width\" content=\"".$og_image_width."\" />
		<meta property=\"og:image:height\" content=\"".$og_image_height."\" />
		<meta name=\"twitter:card\" content=\"summary\" />
		<meta name=\"twitter:description\" content=\"".$og_description."\" />
		<meta name=\"twitter:title\" content=\"".$out['subtitle']."\" />
		<meta name=\"twitter:image\" content=\"".$out['image']."\" />\n";

?>
