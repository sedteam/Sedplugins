CATFIL - category filter for list.tags

Purpose
- The plugin renders a list of child categories in page listings.
- The current category gets the active class.
- Empty categories (without pages) are not shown.

Where it works
- Hook: list.tags
- Plugin file: catfil.list.tags.php

Setting
- parent_cat: parent category code.
- If empty, parent is auto-detected:
  1) if current category is a group, it is used as parent;
  2) otherwise the top segment of current category path is used.

Template integration (in your skin)
- Insert the block into listing template, for example:
  - modules/page/tpl/list.tpl
  - modules/page/tpl/list.group.tpl
- Recommended: do this in your custom skin, without editing core.

Template block
<!-- BEGIN: LIST_CATFIL -->
<ul class="categories-list">
	<li><a href="{LIST_CATFIL_ALL_URL}" class="category-link{LIST_CATFIL_ALL_ACTIVE}">{PHP.L.All|strtolower}</a></li>
	<!-- BEGIN: LIST_CATFIL_ITEM -->
	<li><a href="{LIST_CATFIL_ITEM_URL}" class="category-link{LIST_CATFIL_ITEM_ACTIVE}" data-category="{LIST_CATFIL_ITEM_CATID}">{LIST_CATFIL_ITEM_TITLE|strtolower}</a></li>
	<!-- END: LIST_CATFIL_ITEM -->
</ul>
<!-- END: LIST_CATFIL -->

Installation
1) Copy plugins/catfil folder.
2) Install/enable the plugin in admin panel.
3) Optionally set parent_cat in plugin config.
4) Add LIST_CATFIL block to your skin template.
