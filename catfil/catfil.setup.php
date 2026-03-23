<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/catfil/catfil.setup.php
Version=185
Type=Plugin
Author=
Description=Category filter for list pages
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=catfil
Name=Catfil
Description=Shows sibling categories in list.tags with active item
Version=1.0
Date=2026-mar-23
Author=
Copyright=
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=W12345A
Requires_modules=page
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
parent_cat=01:string:::Parent category code (blank = auto detect)
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
