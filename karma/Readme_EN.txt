Description:

The plugin allows you to implement a rating (reputation) system for users on the forum. Each user can increase (or decrease) another user's reputation level based on their actions on the site! This can include forum posts, comments, or pages created.

Installation:

Unpack the plugin from the archive into the /plugins directory.

If you are using more than one skin, move the karma.tpl file into the plugins folder of your skins and edit it to match your skin.

Install via the admin panel (the plugin automatically creates the 'sed_karma' table).

Add the necessary tags to forums.posts.tpl:

Place in the FORUMS_POSTS_ROW section!

{FORUMS_POSTS_ROW_KARMA_ADD} - increase reputation
{FORUMS_POSTS_ROW_KARMA_DEL} - decrease reputation
{FORUMS_POSTS_ROW_KARMA} - reputation value
Add the tags to users.details.tpl:

{USERS_DETAILS_KARMA}