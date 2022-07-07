Forum Topics Search
================

Author : Chris T. AKA TEFRA

Date : 18 April 2006
www : t3-design.com

Update 07/07/2022
Seditio : v178

Description
-----------
This plug in this first version isn't exactly what it's own name says. In the future version a normal search will be added with options to select
where to search,search title or posts order by date,user,forum and many other things. In this first version only 3 are the features of this plugin.
1. List the new topics since your last visit.
2. List all the new topics the last 24 hours
3. List all the unanswered topics.

A big part of the code is from forums.topics.inc.php official file. In the list there are all the default features of topics list. sort by, pagination,
status icons etc. 


Installation:
-------------
Extract the folder "ftsearch" to the "plugins" folder.
Install The plugin through admin control panel.
Run the plug by using this link plug.php?e=ftsearch.

I propose you also to add the link in forum section but this is up to you.

The plug uses a skin file. This is very easy to make you own from your skin.
The base skin file is of course forums.topics.tpl. With a search and replace and by adding
2-3 tags you are ready. Don't worry in this package there is the skin file based on manta_int for
seditio 101


Notes
-----
The list of new topics since last visit is only for members if guest access this area 
they will auto redirected to see the new topics in the last 24 hours.
If search gets nothing back a nice error message will appear. For easy translation
all words are set into lang file.





Version
~~~~~~~
0.1 Initial Release.



 