# Hybridauth — Sosyal Giriş (OAuth)

VK, Yandex, Google, Sber ID, Mail.ru, GitHub, Discord ve 40+ diğer OAuth sağlayıcısı ile giriş ve kayıt.

## Özellikler

- Sosyal ağlar ile giriş / otomatik kayıt
- Kullanıcı başına birden fazla sosyal hesap (`social_accounts` tablosu)
- Profilde sağlayıcı bağlama/kaldırma
- Marka renkleri ve SVG simgeleri ile stilize düğmeler
- Evrensel: herhangi bir Hybridauth sağlayıcısı kutudan çıktığı gibi çalışır

## Kurulum

1. **Yönetim → Eklentiler → Hybridauth → Kur** — `social_accounts` tablosunu oluşturur.
2. Eklenti parçalarını Yönetim → Eklentiler'de etkinleştirin:
   - `hybridauth.common` → Hook: `common`
   - `hybridauth` → Hook: `standalone`
   - `hybridauth.users.auth.tags` → Hook: `users.auth.tags`
   - `hybridauth.users.register.tags` → Hook: `users.register.tags`
   - `hybridauth.users.profile.tags` → Hook: `profile.tags`
   - `hybridauth.users.profile.first` → Hook: `profile.first`

## Yapılandırma

Düzenleyin: `plugins/hybridauth/config/hybridauth_config.php`

Her sağlayıcı için `id` ve `secret` anahtarlarını ayarlayın.

### Callback URL

Tüm sağlayıcılar için tek URL:

- **SEF:** `https://siteniz.com/plug/hybridauth`
- **non-SEF:** `https://siteniz.com/index.php?module=plug&e=hybridauth`

## Şablonlar

Skin şablonlarınıza aşağıdaki etiketleri ekleyin:

### Giriş formu (`users.auth.tpl`)
```html
<!-- IF {USERS_AUTH_OAUTH_BUTTONS} -->
{USERS_AUTH_OAUTH_BUTTONS}
<!-- ENDIF -->
```

### Kayıt formu (`users.register.tpl`)
```html
<!-- IF {USERS_REGISTER_OAUTH_BUTTONS} -->
{USERS_REGISTER_OAUTH_BUTTONS}
<!-- ENDIF -->
```

### Profil (`users.profile.tpl`)
```html
<!-- IF {PROFILE_OAUTH_BLOCK} -->
<div>{PHP.L.hybridauth_social_accounts}: {PROFILE_OAUTH_BLOCK}</div>
<!-- ENDIF -->
```

## Sağlayıcı listesi

Tam liste: https://hybridauth.github.io/providers.html
