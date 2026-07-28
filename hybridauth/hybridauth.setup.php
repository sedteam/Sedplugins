<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=plugins/hybridauth/hybridauth.setup.php
Version=186
Updated=2026-jul-28
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=hybridauth
Name=Hybridauth 2.0
Description=Social login via OAuth (VK, Yandex, Google, Sber ID, etc.)
Version=2.0.0
Date=2026-jul-28
Author=Amro
Copyright=Amro & Seditio Team
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=W12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]

[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
    die('Wrong URL.');
}
