# Plugin "Forum Subscribe"

The plugin allows forum members to subscribe to new replies in topics and receive email notifications when new posts are published.

## Features

- **Subscribe / Unsubscribe in topic:** One-click instant subscribe/unsubscribe with AJAX support without reloading the page.
- **Autosubscribe on new topic:** Option to automatically pre-check subscription for the author when creating a new topic.
- **Subscribe upon reply:** Option to display subscription checkbox in quick reply form.
- **Smart email dispatch:**
  - The author of the new post is automatically excluded from notifications (does not receive emails about their own posts).
  - Permission checks: Notifications are only sent to users who have read access to the corresponding forum section.
  - Full support for private topics (`ft_mode = 1`).
- **User Subscription Manager:** Dedicated standalone page listing all subscribed topics with pagination and one-click unsubscribe from all topics.
- **Automatic cleanup:** Subscriptions are cleaned up automatically when topics are deleted or pruned.

## Installation

1. Copy the `forumsubscribe` folder into the `plugins/` directory.
2. Go to **Administration Panel &rarr; Plugins** (`/admin/plug` / `index.php?m=plug`).
3. Locate **Forum Subscribe** in the list and click **Install**.

## Skin Integration

### 1. Topic view page (`skins/{skin}/modules/forums/forums.posts.tpl` or `modules/forums/tpl/forums.posts.tpl`):

Insert `{FORUMS_POSTS_SUBSCRIBE}` tag to the right of topic description:

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

Available tags:
- `{FORUMS_POSTS_SUBSCRIBE}` — complete subscribe/unsubscribe button.
- `{FORUMS_POSTS_SUBSCRIBE_URL}` — action URL.
- `{FORUMS_POSTS_SUBSCRIBE_TEXT}` — text label ("Subscribe to topic" / "Unsubscribe from topic").
- `{FORUMS_POSTS_SUBSCRIBE_STATE}` — `1` (subscribed) or `0` (not subscribed).

### 2. New topic creation form (`skins/{skin}/modules/forums/forums.newtopic.tpl` or `modules/forums/tpl/forums.newtopic.tpl`):

Subscription checkbox for topic creator:

```html
<!-- BEGIN: FORUMSUBSCRIBE -->
<li class="form-row">
    <div class="form-field-100">
        <label>{FORUMS_NEWTOPIC_SUBSCRIBE} {FORUMS_NEWTOPIC_SUBSCRIBE_TITLE}</label>
    </div>
</li>
<!-- END: FORUMSUBSCRIBE -->
```

### 3. Quick reply form (`forums.posts.tpl`):

```html
<!-- BEGIN: FORUMSUBSCRIBE -->
<li class="form-row">
    <div class="form-field-100">
        <label>{FORUMS_POSTS_NEWPOST_SUBSCRIBE} {FORUMS_POSTS_NEWPOST_SUBSCRIBE_TITLE}</label>
    </div>
</li>
<!-- END: FORUMSUBSCRIBE -->
```

## Subscription manager URLs

- With SEF: `/forumsubscribe`
- Without SEF: `index.php?module=plug&e=forumsubscribe`
