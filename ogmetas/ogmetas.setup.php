<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
https://seditio.org

[BEGIN_SED]
File=plugins/ogmetas/ogmetas.setup.php
Version=179
Updated=2022-aug-29
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=ogmetas
Name=Ogmetas
Description=Open graph metas add
Version=179
Date=2022-aug-29
Author=Amro
Copyright=Amro
Notes=
SQL=
Auth_guests=0
Lock_guests=RW12345A
Auth_members=R
Lock_members=W12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
img_deafult=01:string::noimg.jpg:Default og image filename from users folder
img_width=02:string::800:Width og image
img_height=03:string::800:Height og image
locale=04:string::en_GB:Locale
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

?>
