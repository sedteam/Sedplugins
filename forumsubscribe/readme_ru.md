# Плагин «Forum Subscribe» (Подписка на темы форума)

Плагин позволяет участникам форума подписываться на новые ответы в интересующих темах и получать email-уведомления при публикации новых сообщений.

## Возможности

- **Подписка / Отписка в теме:** Быстрая подписка и отписка в один клик с поддержкой AJAX без перезагрузки страницы.
- **Подписка при создании темы:** Опция автоматической подписки автора при создании новой темы форума.
- **Подписка при ответе:** Опция включения чекбокса подписки в форме быстрого ответа.
- **Интеллектуальная рассылка:**
  - Автор нового ответа автоматически исключается из рассылки (не получает уведомлений о собственных ответах).
  - Проверка прав доступа: уведомления отправляются только пользователям, имеющим право чтения раздела форума.
  - Поддержка приватных тем (`ft_mode = 1`).
- **Личный кабинет подписок:** Отдельная страница управления всеми своими подписками с пагинацией и возможностью отписаться от всех тем в один клик.
- **Автоочистка:** Автоматическое удаление записей подписок при удалении темы на форуме.

## Установка

1. Скопируйте папку `forumsubscribe` в директорию `plugins/`.
2. Перейдите в **Панель управления &rarr; Плагины** (`/admin/plug` / `index.php?m=plug`).
3. Найдите в списке плагин **Forum Subscribe** и нажмите **Установить**.

## Интеграция в шаблоны (скины)

### 1. Страница темы форума (`skins/{skin}/modules/forums/forums.posts.tpl` или `modules/forums/tpl/forums.posts.tpl`):

Вставьте тег `{FORUMS_POSTS_SUBSCRIBE}` справа от описания темы:

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

Или с использованием тегов ссылки и текста:
- `{FORUMS_POSTS_SUBSCRIBE}` — готовая кнопка подписки/отписки.
- `{FORUMS_POSTS_SUBSCRIBE_URL}` — ссылка на действие.
- `{FORUMS_POSTS_SUBSCRIBE_TEXT}` — текстовая метка («Подписаться на тему» / «Отписаться от темы»).
- `{FORUMS_POSTS_SUBSCRIBE_STATE}` — `1` (подписан) или `0` (не подписан).

### 2. Форма создания новой темы (`skins/{skin}/modules/forums/forums.newtopic.tpl` или `modules/forums/tpl/forums.newtopic.tpl`):

Чекбокс подписки для автора темы:

```html
<!-- BEGIN: FORUMSUBSCRIBE -->
<li class="form-row">
    <div class="form-field-100">
        <label>{FORUMS_NEWTOPIC_SUBSCRIBE} {FORUMS_NEWTOPIC_SUBSCRIBE_TITLE}</label>
    </div>
</li>
<!-- END: FORUMSUBSCRIBE -->
```

Или напрямую через теги:
- `{FORUMS_NEWTOPIC_SUBSCRIBE}` — HTML чекбокс.
- `{FORUMS_NEWTOPIC_SUBSCRIBE_TITLE}` — подпись к чекбоксу.

### 3. Форма быстрого ответа (`forums.posts.tpl`):

```html
<!-- BEGIN: FORUMSUBSCRIBE -->
<li class="form-row">
    <div class="form-field-100">
        <label>{FORUMS_POSTS_NEWPOST_SUBSCRIBE} {FORUMS_POSTS_NEWPOST_SUBSCRIBE_TITLE}</label>
    </div>
</li>
<!-- END: FORUMSUBSCRIBE -->
```

## Ссылки на личный кабинет подписок

- С ЧПУ: `/forumsubscribe`
- Без ЧПУ: `index.php?module=plug&e=forumsubscribe`
