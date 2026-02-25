<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/jevixse/jevixse.setup.php
Version=2.0
Updated=2026-feb-24
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=jevixse
Name=JevixSE
Description=JevixSE - HTML Filter for Seditio
Version=2.0
Date=2026-feb-24
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
use_for_admin=02:select:yes,no:yes:Use JevixSE for Administrators
ext_link_enc=03:select:yes,no:no:Nofollow and Base64 encode external link
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
    die('Wrong URL.');
}
