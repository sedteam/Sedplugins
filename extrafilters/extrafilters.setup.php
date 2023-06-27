<?PHP

/* ====================
Seditio - Website engine
Copyright Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/extrafilters/extrafilters.setup.php
Version=178
Updated=2022-aug-01
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=extrafilters
Name=Extra Filters
Description=The plugin displays extrafilters in list
Version=178
Date=2022-aug-01
Author=Amro
Copyright=
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=W12345A
[END_SED_EXTPLUGIN]?

[BEGIN_SED_EXTPLUGIN_CONFIG]
extraf=01:text::brand,stock:Extra fields separate by commas.
extrafplaceholder=02:text::Выберите бренд,Выберите статус:Placeholder for select field separate by commas.
extrasorting=03:text::price(asc|desc),stock:Sorting by field - asc or desc, empty - desc separate by commas.
extrasortingnaming=05:text::По цене,По акциям:Name sorting fields separate by commas.
[END_SED_EXTPLUGIN_CONFIG]

==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }