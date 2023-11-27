<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=plugins/banstoplist/banstoplist.setup.php
Version=178
Updated=2022-may-22
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=banstoplist
Name=Banstoplist 1.0
Description=Ban user from keywords stoplist
Version=179
Date=2023-nov-27
Author=Amro
Copyright=Amro
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=W12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
stoplist=1:text::Exam dumps,Bitcoin UP:Stop keywords separated by commas
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
    die('Wrong URL.');
}
