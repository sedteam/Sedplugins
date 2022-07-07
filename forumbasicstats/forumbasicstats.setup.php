<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome
http://www.neocrome.net

[BEGIN_SED]
File=plugins/forumbasicstats/forumbasicstats.setup.php
Version=178
Updated=2020-jun-07
Type=Plugin
Author=Neocrome
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=forumbasicstats
Name=Forum Basic Stats
Description=
Version=1.0
Author=Neocrome
Date=2020-jun-07
Copyright=
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=12345
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
config_days=01:select:0,1,2,3,4,5,6,7,8,9,10,15,20,25,30:2:Select the days before you want the plugin to grab the stats
[END_SED_EXTPLUGIN_CONFIG]
==================== */

if ( !defined('SED_CODE') ) { die("Wrong URL."); }

?>