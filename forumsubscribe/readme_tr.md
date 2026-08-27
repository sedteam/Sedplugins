# "Forum Subscribe" Eklentisi (Forum Konu Abonelikleri)

Bu eklenti, forum üyelerinin ilgilendikleri konulardaki yeni cevaplara abone olmalarını ve yeni mesaj yayınlandığında e-posta bildirimi almalarını sağlar.

## Özellikler

- **Konuda Abone Ol / Abonelikten Çık:** Sayfa yenilenmeden AJAX desteğiyle tek tıkla hızlı abonelik yönetimi.
- **Yeni Konuda Otomatik Abone Olma:** Yeni konu açarken yazar için otomatik abonelik onay kutusu seçeneği.
- **Cevap Yazarken Abone Olma:** Hızlı cevap formunda abonelik onay kutusu gösterme seçeneği.
- **Akıllı E-posta Gönderimi:**
  - Yeni mesajın yazarı bildirim alıcılarından otomatik olarak hariç tutulur (kendi mesajları için bildirim almaz).
  - Yetki kontrolü: Bildirimler yalnızca ilgili forum bölümünü okuma yetkisine sahip kullanıcılara gönderilir.
  - Özel konular (`ft_mode = 1`) için tam destek.
- **Kullanıcı Abonelik Yönetim Paneli:** Sayfalamalı ve tek tıkla tüm aboneliklerden çıkma özellikli özel abonelik sayfası.
- **Otomatik Temizleme:** Konular silindiğinde veya budandığında abonelik kayıtları veritabanından otomatik olarak silinir.

## Kurulum

1. `forumsubscribe` klasörünü `plugins/` dizinine kopyalayın.
2. **Yönetim Paneli &rarr; Eklentiler** (`/admin/plug` / `index.php?m=plug`) sayfasına gidin.
3. Listeden **Forum Subscribe** eklentisini bulun ve **Kur** butonuna tıklayın.

## Tema (Skin) Entegrasyonu

### 1. Konu görüntüleme sayfası (`skins/{skin}/modules/forums/forums.posts.tpl` veya `modules/forums/tpl/forums.posts.tpl`):

Konu açıklaması bölümünün sağına `{FORUMS_POSTS_SUBSCRIBE}` etiketini ekleyin:

```html
<div class="section-desc">
    <div class="forumsub-desc-wrapper">
        <div class="forumsub-desc-text">
            {FORUMS_POSTS_TOPICDESC}
        </div>
        <!-- IF {PHP.usr.id} > 0 -->
        <div class="forumsub-desc-action">
            {FORUMS_POSTS_SUBSCRIBE}
        </div>
        <!-- ENDIF -->
    </div>
</div>
```

Kullanılabilir etiketler:
- `{FORUMS_POSTS_SUBSCRIBE}` — hazır abone ol/çık butonu.
- `{FORUMS_POSTS_SUBSCRIBE_URL}` — işlem URL bağlantısı.
- `{FORUMS_POSTS_SUBSCRIBE_TEXT}` — metin etiketi ("Konuya abone ol" / "Abonelikten çık").
- `{FORUMS_POSTS_SUBSCRIBE_STATE}` — `1` (abone) veya `0` (abone değil).

### 2. Yeni konu oluşturma formu (`skins/{skin}/modules/forums/forums.newtopic.tpl` veya `modules/forums/tpl/forums.newtopic.tpl`):

```html
<!-- BEGIN: FORUMSUBSCRIBE -->
<li class="form-row">
    <div class="form-field-100">
        <label>{FORUMS_NEWTOPIC_SUBSCRIBE} {FORUMS_NEWTOPIC_SUBSCRIBE_TITLE}</label>
    </div>
</li>
<!-- END: FORUMSUBSCRIBE -->
```

### 3. Hızlı cevap formu (`forums.posts.tpl`):

```html
<!-- BEGIN: FORUMSUBSCRIBE -->
<li class="form-row">
    <div class="form-field-100">
        <label>{FORUMS_POSTS_NEWPOST_SUBSCRIBE} {FORUMS_POSTS_NEWPOST_SUBSCRIBE_TITLE}</label>
    </div>
</li>
<!-- END: FORUMSUBSCRIBE -->
```

## Abonelik yönetimi bağlantıları

- SEF URL ile: `/forumsubscribe`
- SEF URL olmadan: `index.php?module=plug&e=forumsubscribe`
