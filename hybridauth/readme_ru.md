# Hybridauth — Вход через соцсети (OAuth)

Вход и регистрация через VK, Яндекс, Google, Сбер ID, Mail.ru, GitHub, Discord и 40+ других OAuth-провайдеров.

## Возможности

- Вход / авто-регистрация через социальные сети
- Несколько соцсетей на один аккаунт (таблица `social_accounts`)
- Привязка/отвязка провайдеров в профиле пользователя
- Стилизованные кнопки с фирменными цветами и SVG-иконками
- Универсальность: любой провайдер Hybridauth работает из коробки

## Установка

1. **Админка → Плагины → Hybridauth → Установить** — создаёт таблицу `social_accounts`.
2. Подключите части плагина в Админка → Плагины:
   - `hybridauth.common` → Хук: `common` (загрузка конфига)
   - `hybridauth` → Хук: `standalone` (обработчик OAuth)
   - `hybridauth.users.auth.tags` → Хук: `users.auth.tags` (кнопки на форме входа)
   - `hybridauth.users.register.tags` → Хук: `users.register.tags` (кнопки на форме регистрации)
   - `hybridauth.users.profile.tags` → Хук: `profile.tags` (блок в профиле)
   - `hybridauth.users.profile.first` → Хук: `profile.first` (заглушка совместимости)

## Настройка

Отредактируйте: `plugins/hybridauth/config/hybridauth_config.php`

Укажите ключи `id` и `secret` для каждого провайдера. Включайте/выключайте провайдеры параметром `'enabled' => true/false`.

### Callback URL

Укажите этот URL в настройках приложения каждого провайдера (один URL для всех):

- **С ЧПУ:** `https://ваш-сайт.com/plug/hybridauth`
- **Без ЧПУ:** `https://ваш-сайт.com/index.php?module=plug&e=hybridauth`

### Добавление нового провайдера

1. Проверьте наличие адаптера в `lib/Provider/`. Список: https://hybridauth.github.io/providers.html
2. Добавьте провайдер в `config/hybridauth_config.php`:
   ```php
   'GitHub' => array(
       'enabled' => true,
       'keys' => array('id' => 'ваш-id', 'secret' => 'ваш-secret'),
   ),
   ```
3. Готово — кнопка появится автоматически на формах входа и регистрации.

## Шаблоны

Добавьте теги в шаблоны скина (обёрнуты в `<!-- IF -->` — не отображаются если плагин не установлен):

### Форма входа (`users.auth.tpl`)
```html
<!-- IF {USERS_AUTH_OAUTH_BUTTONS} -->
{USERS_AUTH_OAUTH_BUTTONS}
<!-- ENDIF -->
```

### Форма регистрации (`users.register.tpl`)
```html
<!-- IF {USERS_REGISTER_OAUTH_BUTTONS} -->
{USERS_REGISTER_OAUTH_BUTTONS}
<!-- ENDIF -->
```

### Профиль (`users.profile.tpl`)
```html
<!-- IF {PROFILE_OAUTH_BLOCK} -->
<div>{PHP.L.hybridauth_social_accounts}: {PROFILE_OAUTH_BLOCK}</div>
<!-- ENDIF -->
```

## Список провайдеров

Полный список: https://hybridauth.github.io/providers.html
