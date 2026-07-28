# Hybridauth — Social Login (OAuth)

Social login and registration via VK, Yandex, Google, Sber ID, Mail.ru, GitHub, Discord, and 40+ other OAuth providers.

## Features

- Login / auto-register via social networks
- Multiple social accounts per user (via `social_accounts` table)
- Link/unlink providers in user profile
- Styled buttons with brand colors and SVG icons
- Universal: any Hybridauth provider works out of the box

## Installation

1. **Admin → Plugins → Hybridauth → Install** — creates the `social_accounts` table.
2. Activate plugin parts in Admin → Plugins:
   - `hybridauth.common` → Hook: `common` (loads config)
   - `hybridauth` → Hook: `standalone` (OAuth handler)
   - `hybridauth.users.auth.tags` → Hook: `users.auth.tags` (login buttons)
   - `hybridauth.users.register.tags` → Hook: `users.register.tags` (register buttons)
   - `hybridauth.users.profile.tags` → Hook: `profile.tags` (profile block)
   - `hybridauth.users.profile.first` → Hook: `profile.first` (compat stub)

## Configuration

Edit: `plugins/hybridauth/config/hybridauth_config.php`

Set `id` and `secret` keys for each provider. Enable/disable providers with `'enabled' => true/false`.

### Callback URL

Set this URL in each provider's app settings (one URL for all providers):

- **SEF:** `https://yoursite.com/plug/hybridauth`
- **non-SEF:** `https://yoursite.com/index.php?module=plug&e=hybridauth`

### Adding a new provider

1. Check if a provider adapter exists in `lib/Provider/`. See https://hybridauth.github.io/providers.html
2. Add the provider to `config/hybridauth_config.php`:
   ```php
   'GitHub' => array(
       'enabled' => true,
       'keys' => array('id' => 'your-id', 'secret' => 'your-secret'),
   ),
   ```
3. Done — the button appears automatically on login/register forms.

## Templates

Add these tags to your skin templates (they are wrapped in `<!-- IF -->` blocks, so they only render when the plugin is active):

### Login form (`users.auth.tpl`)
```html
<!-- IF {USERS_AUTH_OAUTH_BUTTONS} -->
{USERS_AUTH_OAUTH_BUTTONS}
<!-- ENDIF -->
```

### Registration form (`users.register.tpl`)
```html
<!-- IF {USERS_REGISTER_OAUTH_BUTTONS} -->
{USERS_REGISTER_OAUTH_BUTTONS}
<!-- ENDIF -->
```

### Profile (`users.profile.tpl`)
```html
<!-- IF {PROFILE_OAUTH_BLOCK} -->
<div>{PHP.L.hybridauth_social_accounts}: {PROFILE_OAUTH_BLOCK}</div>
<!-- ENDIF -->
```

## Provider list

See all supported providers: https://hybridauth.github.io/providers.html
