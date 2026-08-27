<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumsubscribe/lang/forumsubscribe.ru.lang.php
Version=186
Updated=2026-aug-24
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['forumsub_title'] = "Подписки на темы форума";
$L['forumsub_subscribe'] = "Подписаться на тему";
$L['forumsub_unsubscribe'] = "Отписаться от темы";
$L['forumsub_subscribed'] = "Вы подписаны на эту тему";
$L['forumsub_not_subscribed'] = "Вы не подписаны на эту тему";
$L['forumsub_subscribe_hint'] = "Уведомлять об ответах по email";
$L['forumsub_newtopic_subscribe'] = "Уведомлять об ответах в этой теме";
$L['forumsub_quickreply_subscribe'] = "Подписаться на эту тему";

$L['forumsub_msg_subscribed'] = "Вы успешно подписались на обновления темы.";
$L['forumsub_msg_unsubscribed'] = "Вы успешно отписались от этой темы.";
$L['forumsub_msg_unsuball'] = "Вы успешно отписались от всех тем форума.";
$L['forumsub_msg_already'] = "Вы уже подписаны на эту тему.";
$L['forumsub_msg_notfound'] = "Подписка не найдена или тема не существует.";

$L['forumsub_my_subscriptions'] = "Мои подписки на форуме";
$L['forumsub_topic'] = "Тема";
$L['forumsub_section'] = "Раздел";
$L['forumsub_date'] = "Дата подписки";
$L['forumsub_action'] = "Действие";
$L['forumsub_unsub_all'] = "Отписаться от всех тем";
$L['forumsub_no_subscriptions'] = "В данный момент у вас нет активных подписок на темы форума.";

// Email notification
$L['forumsub_mail_subject'] = "Новый ответ в теме: %s";
$L['forumsub_mail_body'] = "Здравствуйте, %s!\n\nПользователь %s оставил новый ответ в теме \"%s\" (раздел \"%s\").\n\n--- Фрагмент сообщения ---\n%s\n---------------------------\n\nПрочитать ответ полностью вы можете по ссылке:\n%s\n\nЕсли вы больше не хотите получать уведомления по этой теме, перейдите по ссылке:\n%s\n\nС уважением,\nАдминистрация";

// Config descriptions
$L['cfg_autosubscribe_newtopic'] = array("Автоподписка при создании темы", "Автоматически отмечать чекбокс подписки при создании новой темы автором");
$L['cfg_autosubscribe_reply'] = array("Чекбокс в форме ответа", "Отображать чекбокс подписки в форме быстрого ответа на форуме");
$L['cfg_notify_once'] = array("Уведомлять однократно", "Отправлять уведомление только один раз до следующего посещения темы");
$L['cfg_itemsperpage'] = array("Подписок на страницу", "Количество тем на одной странице в кабинете подписок");
$L['cfg_include_css'] = array("Подключать CSS", "Использовать встроенные стили плагина");
