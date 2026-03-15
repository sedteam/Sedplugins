Hybridauth — Giriş ve sosyal ağ bağlama (OAuth)
===============================================

1. EKLENTİ KURULUMU
-------------------
- Kullanıcılar tablosunda şu alanların olduğundan emin olun: user_token, user_oauth_provider, user_oauth_uid
  (eklenti Admin → Plugins → Hybridauth → Install ile kurulurken eklenir).

- Yönetim panelinde (Admin → Plugins) eklenti parçalarını hook'lara bağlayın:
  * hybridauth.common             → Hook: common           (OAuth: giriş, kayıt, bağlama)
  * hybridauth.users.auth.tags    → Hook: users.auth.tags (giriş formundaki düğmeler)
  * hybridauth.users.profile.first → Hook: profile.first   (sosyal ağ bağlantısını kaldırma)
  * hybridauth.users.profile.tags  → Hook: profile.tags    (profilde sosyal ağ bloğu)

2. YAPILANDIRMA
---------------
Şu dosyayı düzenleyin: plugins/hybridauth/config/hybridauth_config.php

- Her sağlayıcı (Google, Yandex, VKontakte, Mailru vb.) için anahtarları (id ve secret) girin.
- Yapılandırma PHP dosyasında tutulur; çekirdek ayarlarına taşınmaz.

3. SAĞLAYICI PANELLERİNDE CALLBACK URL
--------------------------------------
Callback sitenin köküdür: $sys['abs_url'] (/login yok). OAuth common hook içinde işlenir.

Her sağlayıcının uygulama ayarlarında (Google Cloud Console, Yandex OAuth, VK ID, Mailru vb.) izin verilen Redirect URI adresini şu biçimde ekleyin:

  {siteniz}?oauth_provider=Google
  {siteniz}?oauth_provider=Yandex
  {siteniz}?oauth_provider=Vkontakte
  {siteniz}?oauth_provider=Mailru

({siteniz} yerine sondaki eğik çizgi olmadan tam URL yazın, örn. https://example.com)

Giriş ve profil bağlama için tek callback. Yönetimde hybridauth.common parçasını common hook'a bağlayın.

4. SKİN ŞABLONLARINDA ETİKETLERİ NEREYE EKLENİR
------------------------------------------------
Çekirdek ve skin eklenti tarafından değiştirilmez. Etiketleri şablonlarınıza elle eklemeniz gerekir.

--- Giriş formu ---
Skin dosyası: kullanıcı giriş formu şablonu (örn. users.auth.tpl veya skin'inizdeki karşılığı).

Nereye: giriş formu bloğunun içine ("Giriş" düğmesinin yanına veya kullanıcı adı/parola alanlarından sonra).

Etiket:
  {USERS_AUTH_OAUTH_BUTTONS}

Ne yapar: sosyal ağ ile giriş için "Google", "Yandex", "VKontakte", "Mailru" vb. link-düğmeleri yazar.
Örnek şablon parçası:
  <!-- BEGIN USERS_AUTH_OAUTH -->
  <p>Sosyal ağ ile giriş:</p>
  <p>{USERS_AUTH_OAUTH_BUTTONS}</p>
  <!-- END USERS_AUTH_OAUTH -->

(USERS_AUTH_OAUTH bloğunu siz oluşturursunuz; eklenti içindeki {USERS_AUTH_OAUTH_BUTTONS} etiketini değiştirir.)

--- Kullanıcı profili ---
Skin dosyası: profil şablonu (örn. users.profile.tpl veya skin'inizdeki karşılığı — users/profile bölümüne bakın).

Nereye: "Sosyal ağlar" bloğunun görünmesini istediğiniz profil sayfasındaki herhangi bir yere (örn. ayrı bir bölüm veya formun altına).

Etiket:
  {PROFILE_OAUTH_BLOCK}

Ne yapar: sosyal hesap bağlama/bağlantı kesme bloğu:
  - bağlıysa: "Attached: Google" (veya diğer sağlayıcı) metni, "Unlink" düğmesi (e-posta tanımlıysa) ve değiştirmek için "Attach ..." düğmeleri;
  - bağlı değilse: "Attach Google", "Attach Yandex" vb. düğmeler.
Örnek şablon parçası:
  <!-- BEGIN PROFILE_OAUTH -->
  <h4>Sosyal ağlar</h4>
  {PROFILE_OAUTH_BLOCK}
  <!-- END PROFILE_OAUTH -->

(PROFILE_OAUTH bloğunu ve başlığı siz oluşturursunuz; eklenti içindeki {PROFILE_OAUTH_BLOCK} etiketini değiştirir.)

5. ÇALIŞMA MANTIĞI
------------------
- Giriş: kullanıcı giriş formundaki bir linke tıklar (örn. Google) → sağlayıcıya yönlendirme → kimlik doğrulama sonrası yeni hesap oluşturulur veya giriş yapılır. İlk kayıtta parola üretilir ve e-posta ile gönderilir (e-posta yoksa ÖM ile).
- Bağlantı kesme: profilde "Unlink" düğmesi yalnızca kullanıcının e-postası tanımlıysa görünür.
- Bağlama: profildeki "Attach ..." düğmeleri provider ve a=link parametreleriyle profil sayfasına gider; yalnızca bağlantı alanları güncellenir (user_oauth_uid, user_oauth_provider, user_token); profil verileri (ad, e-posta vb.) değişmez.

Hybridauth sağlayıcı listesi: https://hybridauth.github.io/providers.html
