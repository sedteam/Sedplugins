<?PHP

/* ====================
Seditio - Website engine
Copyright Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/karma/lang/karma.en.lang.php
Version=180
Updated=2025-jan-25
Type=
Author=Seditio Team
Description=
[END_SED]
==================== */

$L['karma_title'] = "Karma";

$L['karma_title_add'] = "Reputation Increase";
$L['karma_title_del'] = "Reputation Decrease";

$L['karma_add'] = "INCREASING";
$L['karma_del'] = "DECREASING";

$L['karma_changed_ok'] = "Changes applied successfully!";
$L['karma_del_ok'] = "Deleted successfully!";

$L['karma_do_1'] = "You";
$L['karma_do_2'] = "changed the reputation of the user";
$L['karma_do_3'] = "for the message:";

$L['karma_comm'] = "Comment is required:";

/* errors */
$L['karma_no_fp'] = "Main parameter not specified!";
$L['karma_no_karma'] = "Karma value not specified";
$L['karma_no_self'] = "You cannot change your own reputation!";
$L['karma_no_comm'] = "No comment text provided!!!";
$L['karma_no_recipient'] = "No recipient number provided!!!";
$L['karma_no_repeat'] = "You have already changed the reputation for this user for this post!!!";
$L['karma_no_minpost'] = "To change the reputation, you must have more than {minpost} posts on the forum";
$L['karma_low_level'] = "You do not have the rights to moderate reputation!";

/* PM */
$L['karma_pmnotify'] = "Notification System";
$L['karma_pmtitle'] = "Your reputation has changed!";
$L['karma_pmtext']  = "Hello!\n Your rating has just been changed by someone!";
