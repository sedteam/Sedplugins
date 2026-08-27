<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/forumsubscribe/lang/forumsubscribe.tr.lang.php
Version=186
Updated=2026-aug-24
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['forumsub_title'] = "Forum Konu Abonelikleri";
$L['forumsub_subscribe'] = "Konuya abone ol";
$L['forumsub_unsubscribe'] = "Abonelikten çık";
$L['forumsub_subscribed'] = "Bu konuya abonesiniz";
$L['forumsub_not_subscribed'] = "Bu konuya abone değilsiniz";
$L['forumsub_subscribe_hint'] = "Cevapları e-posta ile bildir";
$L['forumsub_newtopic_subscribe'] = "Bu konudaki cevaplara abone ol";
$L['forumsub_quickreply_subscribe'] = "Bu konuya abone ol";

$L['forumsub_msg_subscribed'] = "Konu güncellemelerine başarıyla abone oldunuz.";
$L['forumsub_msg_unsubscribed'] = "Bu konunun aboneliğinden çıktınız.";
$L['forumsub_msg_unsuball'] = "Tüm forum konularının aboneliğinden çıktınız.";
$L['forumsub_msg_already'] = "Zaten bu konuya abonesiniz.";
$L['forumsub_msg_notfound'] = "Abonelik bulunamadı veya konu mevcut değil.";

$L['forumsub_my_subscriptions'] = "Forum Aboneliklerim";
$L['forumsub_topic'] = "Konu";
$L['forumsub_section'] = "Bölüm";
$L['forumsub_date'] = "Abonelik Tarihi";
$L['forumsub_action'] = "İşlem";
$L['forumsub_unsub_all'] = "Tüm aboneliklerden çık";
$L['forumsub_no_subscriptions'] = "Şu anda herhangi bir forum konusuna aboneliğiniz bulunmamaktadır.";

// Email notification
$L['forumsub_mail_subject'] = "Konuda yeni cevap: %s";
$L['forumsub_mail_body'] = "Merhaba %s,\n\n%s kullanıcısı \"%s\" konusuna (\"%s\" bölümü) yeni bir cevap yazdı.\n\n--- Mesaj Özeti ---\n%s\n--------------------\n\nCevabı çevrimiçi okumak için:\n%s\n\nBu konuyla ilgili artık bildirim almak istemiyorsanız, abonelikten çıkmak için tıklayın:\n%s\n\nSaygılarımızla,\nYönetim";

// Config descriptions
$L['cfg_autosubscribe_newtopic'] = array("Yeni konuda otomatik abone", "Yeni konu açarken abonelik onay kutusunu otomatik işaretle");
$L['cfg_autosubscribe_reply'] = array("Hızlı cevapta onay kutusu", "Abone olmayanlar için hızlı cevap formunda abonelik kutusunu göster");
$L['cfg_notify_once'] = array("Bir kez bildir", "Kullanıcı konuyu tekrar ziyaret edene kadar yalnızca bir kez bildir");
$L['cfg_itemsperpage'] = array("Sayfa başına abonelik", "Abonelik yöneticisinde sayfa başına gösterilecek konu sayısı");
$L['cfg_include_css'] = array("CSS dahil et", "Varsayılan eklenti CSS stillerini yükle");
