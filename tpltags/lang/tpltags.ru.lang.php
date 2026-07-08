<?php
/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tpltags/lang/tpltags.ru.lang.php
Version=185
Updated=2026-jul-07
Type=Plugin
Author=Seditio Team / Amro
Description=Russian localization
[END_SED]
==================== */

// Titles and subtitles
$L['plu_title'] = "Справочник тегов шаблонов (tpltags)";
$L['plu_subtitle_main'] = "Здесь перечислены все стандартные теги для Seditio.<br /><br />Теги размещаются в файлах скина (*.tpl). Проще говоря, теги — это метки в HTML-коде страницы, которые указывают движку места, куда именно нужно вставлять те или иные динамические данные (результат выполнения PHP-кода).<br /><br />Теги, имеющие приставку '{PHP.', называют глобальными. Это означает, что они работают во всех файлах скина. Все остальные теги работают только в пределах собственного модуля/шаблона, например, теги, начинающиеся с '{FORUMS_POSTS_', будут работать только в шаблоне сообщений форума.";

$L['plu_title_all'] = "Все теги";
$L['plu_subtitle_all'] = "Ниже перечислены все теги, использующиеся в файлах скина.";
$L['plu_title_tpl'] = "Все теги в файле скина ";
$L['plu_subtitle_tpl'] = "Нижеперечисленные теги работают только в файле скина:";
$L['plu_title_global'] = "Глобальные теги";
$L['plu_subtitle_global'] = "Нижеперечисленные теги работают во всех файлах скина.";
$L['plu_title_ver'] = "Новые теги в версии: ";
$L['plu_subtitle_ver'] = "Ниже перечислены теги, которые были добавлены в Seditio ver. ";
$L['plu_title_add'] = "Добавление нового тега";
$L['plu_subtitle_add'] = "Добавить новый тег в справочник.";
$L['plu_title_edit'] = "Свойства тега";
$L['plu_subtitle_edit'] = "Обновить значение для данного тега.";

// All the rest
$L['tpltags_edit'] = "Изменить";
$L['tpltags_delete'] = "Удалить";
$L['tpltags_add'] = "Добавить новый тег";
$L['tpltags_submit'] = "Добавить";
$L['tpltags_update'] = "Обновить";
$L['tpltags_skin_files'] = "Теги по файлам скина (TPL)";
$L['tpltags_tags'] = "Теги";
$L['tpltags_tag'] = "Тег";
$L['tpltags_loc'] = "В блоке";
$L['tpltags_type'] = "Тип";
$L['tpltags_tpl'] = "TPL";
$L['tpltags_details'] = "Детали";
$L['tpltags_ver'] = "Ver.";
$L['tpltags_delete_hint'] = "Удалить этот тег?";
$L['tpltags_version'] = "Версия";
$L['tpltags_by_version'] = "Теги по всем версиям (добавленные):";
$L['tpltags_new_tags'] = "Новые теги";
$L['tpltags_filters'] = "Фильтры";
$L['tpltags_all'] = "Все теги";
$L['tpltags_global'] = "Глобальные теги";
$L['tpltags_tag_missing'] = "Tag отсутствует.";
$L['tpltags_tpl_missing'] = "Tpl отсутствует.";
$L['tpltags_type_missing'] = "Не указан тип.";
$L['tpltags_version_missing'] = "Номер версии отсутствует.";

// Legend titles and descriptions
$L['tpltags_legend'] = "Описание типов тегов";
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

$L['tpltags_type_Alphanumerical_desc'] = "Буквы или цифры";
$L['tpltags_type_Array_desc'] = "Массив данных, используемый как: {PHP.array.pointer}";
$L['tpltags_type_Boolean_desc'] = "Логическое значение (0 или 1)";
$L['tpltags_type_Composite_desc'] = "Смесь нескольких других типов";
$L['tpltags_type_Date_desc'] = "Форматированная дата";
$L['tpltags_type_Image_desc'] = "Изображение (&lt;img src=&quot;...&quot; alt=&quot;&quot;&gt;)";
$L['tpltags_type_Input_desc'] = "Поле ввода формы или текстовая область (textarea)";
$L['tpltags_type_Integer_desc'] = "Целое число, положительное или отрицательное";
$L['tpltags_type_Link_desc'] = "Стандартная ссылка (&lt;a href=&quot;...&quot;&gt;...&lt;/a&gt;)";
$L['tpltags_type_Level_desc'] = "Уровень (целое число от 0 до 99)";
$L['tpltags_type_Raw_link_desc'] = "Неформатированная ссылка (например, page.php?id=...)";
$L['tpltags_type_String_desc'] = "Строка символов, аналогично тексту, но длиной до 255 символов";
$L['tpltags_type_Simple_text_desc'] = "Текст без кавычек и двойных кавычек";
$L['tpltags_type_Text_desc'] = "Текст, включая специальные символы";
$L['tpltags_type_Time_desc'] = "Временная метка Unix (в секундах)";
$L['tpltags_type_URL_desc'] = "Адрес ссылки (http://... или без префикса)";
$L['tpltags_type_System_desc'] = "Требуется системными модулями";
?>