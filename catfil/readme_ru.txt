CATFIL - фильтр категорий для list.tags

Назначение
- Плагин выводит список дочерних категорий для листинга страниц.
- Текущая категория отмечается классом active.
- Пустые категории (без страниц) не выводятся.

Где работает
- Хук: list.tags
- Файл плагина: catfil.list.tags.php

Настройка
- parent_cat: код родительской категории.
- Если пусто, родитель определяется автоматически:
  1) если текущая категория является группой, берется она;
  2) иначе берется верхний сегмент пути текущей категории.

Подключение в шаблон (в вашем скине)
- Вставьте блок в шаблон листинга, например в:
  - modules/page/tpl/list.tpl
  - modules/page/tpl/list.group.tpl
- Рекомендуется делать это в пользовательском скине, не изменяя ядро.

Шаблонный блок
<!-- BEGIN: LIST_CATFIL -->
<ul class="categories-list">
	<li><a href="{LIST_CATFIL_ALL_URL}" class="category-link{LIST_CATFIL_ALL_ACTIVE}">{PHP.L.All|strtolower}</a></li>
	<!-- BEGIN: LIST_CATFIL_ITEM -->
	<li><a href="{LIST_CATFIL_ITEM_URL}" class="category-link{LIST_CATFIL_ITEM_ACTIVE}" data-category="{LIST_CATFIL_ITEM_CATID}">{LIST_CATFIL_ITEM_TITLE|strtolower}</a></li>
	<!-- END: LIST_CATFIL_ITEM -->
</ul>
<!-- END: LIST_CATFIL -->

Установка
1) Скопируйте папку plugins/catfil.
2) Установите/включите плагин в админке.
3) При необходимости задайте parent_cat в настройках плагина.
4) Добавьте блок LIST_CATFIL в шаблон вашего скина.
