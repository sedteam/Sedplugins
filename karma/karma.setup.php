<?php

/* ====================
Seditio - Website engine
Copyright Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/karma/karma.setup.php
Version=180
Updated=2025-feb-23
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=karma
Name=Karma 1.0
Description=Karma for forum
Version=180
Date=2025-feb-23
Author=Amro
Copyright=Amro
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=RW
Lock_members=12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
minpost=01:select:0,1,5,10,15,20,25,50,75,100:1:Minimum number of posts required to change karma for others
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
    die('Wrong URL.');
}
