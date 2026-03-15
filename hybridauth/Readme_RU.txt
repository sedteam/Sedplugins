Hybridauth — вход и привязка через соцсети (OAuth)
================================================

1. УСТАНОВКА ПЛАГИНА
--------------------
- Убедитесь, что в таблице пользователей есть поля: user_token, user_oauth_provider, user_oauth_uid
  (добавляются при установке плагина через Admin → Plugins → Hybridauth → Install).

- В админке (Admin → Plugins) подключите части плагина к хукам:
  * hybridauth.common             → Hook: common            (обработка OAuth: вход, регистрация, привязка)
  * hybridauth.users.auth.tags    → Hook: users.auth.tags   (кнопки на форме логина)
  * hybridauth.users.profile.first → Hook: profile.first    (отвязка соцсети)
  * hybridauth.users.profile.tags  → Hook: profile.tags     (блок соцсетей в профиле)

2. НАСТРОЙКА КОНФИГА
--------------------
Отредактируйте файл: plugins/hybridauth/config/hybridauth_config.php

- Укажите ключи (id и secret) для каждого провайдера (Google, Yandex, VKontakte, Mailru и т.д.).
- Конфиг хранится в PHP-файле, в настройки движка не выносится.

3. CALLBACK URL В КАБИНЕТАХ ПРОВАЙДЕРОВ
----------------------------------------
Callback — корень сайта: $sys['abs_url'] (без /login). Обработка OAuth выполняется в хуке common.

В настройках приложения каждого провайдера (Google Cloud Console, Яндекс OAuth, VK ID, Mailru и т.д.) укажите разрешённые Redirect URI в таком виде:

  {ваш_сайт}?oauth_provider=Google
  {ваш_сайт}?oauth_provider=Yandex
  {ваш_сайт}?oauth_provider=Vkontakte
  {ваш_сайт}?oauth_provider=Mailru

(замените {ваш_сайт} на полный URL без слэша в конце, например https://example.com)

Один callback для входа и для привязки в профиле. В админке подключите часть плагина hybridauth.common к хуку common.

4. КУДА ДОБАВИТЬ ТЕГИ В ШАБЛОНАХ (СКИН)
----------------------------------------
Ядро и скин не изменяются плагином. Теги нужно вручную добавить в свои шаблоны.

--- Форма входа (логин) ---
Файл скина: шаблон формы входа пользователей (например, users.auth.tpl или аналог в вашем скине).

Куда: в блок формы логина (рядом с кнопкой "Войти" или после полей логин/пароль).

Тег:
  {USERS_AUTH_OAUTH_BUTTONS}

Что даёт: подставляет ссылки-кнопки "Google", "Yandex", "VKontakte", "Mailru" и др. для входа через соцсеть.
Пример фрагмента шаблона:
  <!-- BEGIN USERS_AUTH_OAUTH -->
  <p>Войти через соцсеть:</p>
  <p>{USERS_AUTH_OAUTH_BUTTONS}</p>
  <!-- END USERS_AUTH_OAUTH -->

(Блок USERS_AUTH_OAUTH создаёте сами; внутри него тег {USERS_AUTH_OAUTH_BUTTONS} будет заменён плагином.)

--- Профиль пользователя ---
Файл скина: шаблон профиля (например, users.profile.tpl или аналог — см. скин, раздел users/profile).

Куда: в любое место страницы профиля, где должен отображаться блок "Соцсети" (например, отдельная секция или внизу формы).

Тег:
  {PROFILE_OAUTH_BLOCK}

Что даёт: блок с привязкой/отвязкой соцсети:
  - если соцсеть привязана: текст "Attached: Google" (или другой провайдер), кнопка "Unlink" (если указан email) и кнопки "Attach ..." для смены;
  - если не привязана: кнопки "Attach Google", "Attach Yandex" и т.д.
Пример фрагмента шаблона:
  <!-- BEGIN PROFILE_OAUTH -->
  <h4>Соцсети</h4>
  {PROFILE_OAUTH_BLOCK}
  <!-- END PROFILE_OAUTH -->

(Блок PROFILE_OAUTH и заголовок создаёте сами; внутри блока тег {PROFILE_OAUTH_BLOCK} заменяется плагином.)

5. ПРАВИЛА РАБОТЫ
-----------------
- Вход: пользователь нажимает ссылку на форме логина (например, Google) → редирект к провайдеру → после входа создаётся учётка или выполняется вход. При первой регистрации пароль генерируется и отправляется на email (или в ЛС, если email нет).
- Отвязка: в профиле кнопка "Unlink" доступна только если у пользователя указан email.
- Привязка: в профиле кнопки "Attach ..." ведут на страницу профиля с параметрами provider и a=link; обновляются только поля привязки (user_oauth_uid, user_oauth_provider, user_token), данные профиля (имя, email и т.д.) не меняются.

Список провайдеров Hybridauth: https://hybridauth.github.io/providers.html
