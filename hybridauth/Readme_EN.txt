Hybridauth — Login and social network linking (OAuth)
=====================================================

1. PLUGIN INSTALLATION
----------------------
- Ensure the users table has columns: user_token, user_oauth_provider, user_oauth_uid
  (added when installing the plugin via Admin → Plugins → Hybridauth → Install).

- In the admin panel (Admin → Plugins) connect plugin parts to the correct hooks:
  * hybridauth.common             → Hook: common           (OAuth handling: login, register, link)
  * hybridauth.users.auth.tags    → Hook: users.auth.tags  (buttons on login form)
  * hybridauth.users.profile.first → Hook: profile.first   (unlink social)
  * hybridauth.users.profile.tags  → Hook: profile.tags    (social block in profile)

2. CONFIG SETUP
---------------
Edit the file: plugins/hybridauth/config/hybridauth_config.php

- Set keys (id and secret) for each provider (Google, Yandex, VKontakte, Mailru, etc.).
- Config is stored in the PHP file; it is not moved to engine settings.

3. CALLBACK URL IN PROVIDER DASHBOARDS
--------------------------------------
Callback is the site root: $sys['abs_url'] (no /login). OAuth is handled in the common hook.

In each provider's app settings (Google Cloud Console, Yandex OAuth, VK ID, Mailru, etc.) add the allowed Redirect URI in this form:

  {your_site}?oauth_provider=Google
  {your_site}?oauth_provider=Yandex
  {your_site}?oauth_provider=Vkontakte
  {your_site}?oauth_provider=Mailru

(Replace {your_site} with the full URL without a trailing slash, e.g. https://example.com)

One callback for login and profile linking. In admin, connect the hybridauth.common plugin part to the common hook.

4. WHERE TO ADD TAGS IN SKIN TEMPLATES
-------------------------------------
Core and skin are not modified by the plugin. Add the tags manually in your templates.

--- Login form ---
Skin file: user login form template (e.g. users.auth.tpl or equivalent in your skin).

Where: inside the login form block (next to the "Login" button or after login/password fields).

Tag:
  {USERS_AUTH_OAUTH_BUTTONS}

What it does: outputs link-buttons "Google", "Yandex", "VKontakte", "Mailru", etc. for social login.
Example template fragment:
  <!-- BEGIN USERS_AUTH_OAUTH -->
  <p>Login with social network:</p>
  <p>{USERS_AUTH_OAUTH_BUTTONS}</p>
  <!-- END USERS_AUTH_OAUTH -->

(You create the USERS_AUTH_OAUTH block; the plugin replaces {USERS_AUTH_OAUTH_BUTTONS} inside it.)

--- User profile ---
Skin file: profile template (e.g. users.profile.tpl or equivalent — check your skin, users/profile section).

Where: anywhere on the profile page where the "Social networks" block should appear (e.g. a separate section or at the bottom of the form).

Tag:
  {PROFILE_OAUTH_BLOCK}

What it does: block for linking/unlinking social account:
  - if linked: text "Attached: Google" (or other provider), "Unlink" button (if email is set) and "Attach ..." buttons to change;
  - if not linked: buttons "Attach Google", "Attach Yandex", etc.
Example template fragment:
  <!-- BEGIN PROFILE_OAUTH -->
  <h4>Social networks</h4>
  {PROFILE_OAUTH_BLOCK}
  <!-- END PROFILE_OAUTH -->

(You create the PROFILE_OAUTH block and heading; the plugin replaces {PROFILE_OAUTH_BLOCK} inside it.)

5. HOW IT WORKS
---------------
- Login: user clicks a link on the login form (e.g. Google) → redirect to provider → after auth either a new account is created or user is logged in. On first registration a password is generated and sent by email (or by PM if no email).
- Unlink: in profile the "Unlink" button is only available if the user has an email set.
- Link: in profile the "Attach ..." buttons go to the profile page with provider and a=link; only link fields are updated (user_oauth_uid, user_oauth_provider, user_token); profile data (name, email, etc.) is not changed.

Hybridauth providers list: https://hybridauth.github.io/providers.html
