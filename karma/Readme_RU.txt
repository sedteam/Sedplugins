Описание:

Плагин позволяет реализовать на форуме систему рейтингов (репутации) для пользователей на сайте.
Каждый пользователь может повысить (понизить) уровень репутации другого пользователя за его действия на сайте!
Это могут быть сообщения на форуме, комментарии, или размещенные страницы.

Установка:

1. Распаковать плагин из архива в /plugins
2. Если вы используете более одного скина, то перенесите  файл karma.tpl в ваши скины в папку plugins и отредактируйте под свой скин. 
3. Установить через админ-панель (Плагин автоматически создает таблицу 'sed_karma')

4. В forums.posts.tpl добавить теги (какие вам нужны):

Размещать в секцию FORUMS_POSTS_ROW !
                    
{FORUMS_POSTS_ROW_KARMA_ADD} - повышение репутации
{FORUMS_POSTS_ROW_KARMA_DEL} - понижение репутации
{FORUMS_POSTS_ROW_KARMA} - значение репутации

5. В users.details.tpl добавляем теги:
{USERS_DETAILS_KARMA}