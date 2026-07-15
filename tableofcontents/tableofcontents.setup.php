<?php
/* ====================
Seditio - Website engine
Copyright Neocrome
https://seditio.org

[BEGIN_SED]
File=plugins/tableofcontents/tableofcontents.setup.php
Version=185
Updated=2026-jul-07
Type=Plugin
Author=Amro
Description=Generate table of contents
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=tableofcontents
Name=Table of contents
Description=Generate table of contents
Version=185
Date=2026-jul-07
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
use_cache=01:select:yes,no:yes:Use caching for table of contents
[END_SED_EXTPLUGIN_CONFIG]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }