<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumsubscribe/forumsubscribe.setup.php
Version=186
Updated=2026-aug-24
Type=Plugin
Description=Forum topics subscription and notifications
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=forumsubscribe
Name=Forum Subscribe
Description=Allows forum members to subscribe to topic replies and receive notifications
Version=1.0.0
Date=2026-08-24
Author=Seditio Team
Copyright=
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=RW
Lock_members=12345A
[END_SED_EXTPLUGIN]

[BEGIN_SED_EXTPLUGIN_CONFIG]
autosubscribe_newtopic=01:radio:0,1:1:Autosubscribe author on new topic creation
autosubscribe_reply=02:radio:0,1:0:Show subscription checkbox in quick reply form
notify_once=03:radio:0,1:0:Notify only once until next topic visit
itemsperpage=04:string::20:Subscriptions per page in user control panel
include_css=05:radio:0,1:1:Include plugin CSS stylesheet
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
