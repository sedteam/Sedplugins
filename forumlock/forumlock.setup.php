<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumlock/forumlock.setup.php
Version=180
Updated=2025-sep-25
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=forumlock
Name=Forumlock 1.1
Description=Ban on creating topics on the forum for new users
Version=180
Date=2025-sep-25
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
nominpost=01:select:0,1,2,3,4,5,6,7,8,9,10,15,20,25,30:5:Minimum number of posts
timehold=02:select:0,1,2,3,4,5,6,7,8,9,10,12,24,36,48:24:Time passed since registration
maxtopicksday=03:select:1,2,3,4,5,6,7,8,9,10,15,20,25,30:5:Max topics created per day
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
    die('Wrong URL.');
}
