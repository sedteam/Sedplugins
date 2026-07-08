<?php
/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tpltags/lang/tpltags.en.lang.php
Version=185
Updated=2026-jul-07
Type=Plugin
Author=Seditio Team / Chris T
Description=English localization
[END_SED]
==================== */

// Titles and subtitles
$L['plu_title'] = "Template Tags Dictionary (tpltags)";
$L['plu_subtitle_main'] = "Here are listed all the default tags for the skin files.<br />Tags go in the skin files (*.tpl), they are some kind of placeholders to let the engine know where to insert dynamic data.<br />The tags starting with '{PHP.' are called global, meaning they will work in all the skin files.<br />All the other tags are valid only within their own module/template, as example the tags starting with '{FORUMS_POSTS_' will work in the forums only.";

$L['plu_title_all'] = "All the tags";
$L['plu_subtitle_all'] = "Below are listed all the tags available in the skin files.";
$L['plu_title_tpl'] = "All tags for the skin file";
$L['plu_subtitle_tpl'] = "The tags listed here are valid only in the skin file:";
$L['plu_title_global'] = "Global tags";
$L['plu_subtitle_global'] = "Tags listed below are valid for all the skin files.";
$L['plu_title_ver'] = "New tags in the version";
$L['plu_subtitle_ver'] = "The tags listed here were added in the Seditio release";
$L['plu_title_add'] = "New tag submission form";
$L['plu_subtitle_add'] = "Submit a new tag.";
$L['plu_title_edit'] = "Tag properties";
$L['plu_subtitle_edit'] = "Update values for this tag.";

// All the rest
$L['tpltags_edit'] = "Edit";
$L['tpltags_delete'] = "Delete";
$L['tpltags_add'] = "Add new tag";
$L['tpltags_submit'] = "Submit";
$L['tpltags_update'] = "Update";
$L['tpltags_skin_files'] = "The tags by skin files (TPL)";
$L['tpltags_tags'] = "Tags";
$L['tpltags_tag'] = "Tag";
$L['tpltags_loc'] = "Block or loop";
$L['tpltags_type'] = "Type";
$L['tpltags_tpl'] = "TPL";
$L['tpltags_details'] = "Details";
$L['tpltags_ver'] = "Ver.";
$L['tpltags_delete_hint'] = "Delete this tag?";
$L['tpltags_version'] = "Version";
$L['tpltags_by_version'] = "The tags by version (added in):";
$L['tpltags_new_tags'] = "New tags";
$L['tpltags_filters'] = "Filters";
$L['tpltags_all'] = "All the tags";
$L['tpltags_global'] = "The global tags";
$L['tpltags_tag_missing'] = "Tag is missing.";
$L['tpltags_tpl_missing'] = "Tpl is missing.";
$L['tpltags_type_missing'] = "No type specified.";
$L['tpltags_version_missing'] = "Version number is missing.";

// Legend titles and descriptions
$L['tpltags_legend'] = "Legend";
$L['tpltags_type_Alphanumerical'] = "Alphanumerical";
$L['tpltags_type_Array'] = "Array";
$L['tpltags_type_Boolean'] = "Boolean";
$L['tpltags_type_Composite'] = "Composite";
$L['tpltags_type_Date'] = "Date";
$L['tpltags_type_Image'] = "Image";
$L['tpltags_type_Input'] = "Input";
$L['tpltags_type_Integer'] = "Integer";
$L['tpltags_type_Link'] = "Link";
$L['tpltags_type_Level'] = "Level";
$L['tpltags_type_Raw_link'] = "Raw link";
$L['tpltags_type_String'] = "String";
$L['tpltags_type_Simple_text'] = "Simple text";
$L['tpltags_type_Text'] = "Text";
$L['tpltags_type_Time'] = "Time";
$L['tpltags_type_URL'] = "URL";
$L['tpltags_type_System'] = "System";

$L['tpltags_type_Alphanumerical_desc'] = "Letters or numbers";
$L['tpltags_type_Array_desc'] = "Array, use with: {PHP.array.pointer}";
$L['tpltags_type_Boolean_desc'] = "0 or 1";
$L['tpltags_type_Composite_desc'] = "A mix of several other types";
$L['tpltags_type_Date_desc'] = "Formatted date";
$L['tpltags_type_Image_desc'] = "Image (&lt;img src=&quot;...&quot; alt=&quot;&quot;&gt;)";
$L['tpltags_type_Input_desc'] = "Form input or textarea";
$L['tpltags_type_Integer_desc'] = "Integer, positive or negative";
$L['tpltags_type_Link_desc'] = "A standard link (&lt;a href=&quot;...&quot;&gt;...&lt;/a&gt;)";
$L['tpltags_type_Level_desc'] = "Integer, between 0 and 59";
$L['tpltags_type_Raw_link_desc'] = "Raw link (page.php.id=...)";
$L['tpltags_type_String_desc'] = "String, same as text, but length always inferior to 255";
$L['tpltags_type_Simple_text_desc'] = "Text, without quotes or double quotes";
$L['tpltags_type_Text_desc'] = "Text, including special chars";
$L['tpltags_type_Time_desc'] = "Unix timestamp, in seconds";
$L['tpltags_type_URL_desc'] = "URL (http://www.seditio.org, or without &quot;http://&quot;)";
$L['tpltags_type_System_desc'] = "Required by the system";
?>
