CATFIL - list.tags icin kategori filtresi

Amac
- Eklenti, sayfa listelemelerinde alt kategorileri gosterir.
- Gecerli kategori active sinifini alir.
- Bos kategoriler (sayfa olmayanlar) gosterilmez.

Nerede calisir
- Hook: list.tags
- Eklenti dosyasi: catfil.list.tags.php

Ayar
- parent_cat: ust kategori kodu.
- Bos ise parent otomatik belirlenir:
  1) mevcut kategori bir grup ise parent olarak o kullanilir;
  2) degilse kategori yolunun ilk segmenti kullanilir.

Sablona ekleme (kendi skininizde)
- Bloku listeleme sablonuna ekleyin, ornek:
  - modules/page/tpl/list.tpl
  - modules/page/tpl/list.group.tpl
- Oneri: bunu ozel skininizde yapin, core dosyalarini degistirmeyin.

Sablon blogu
<!-- BEGIN: LIST_CATFIL -->
<ul class="categories-list">
	<li><a href="{LIST_CATFIL_ALL_URL}" class="category-link{LIST_CATFIL_ALL_ACTIVE}">{PHP.L.All|strtolower}</a></li>
	<!-- BEGIN: LIST_CATFIL_ITEM -->
	<li><a href="{LIST_CATFIL_ITEM_URL}" class="category-link{LIST_CATFIL_ITEM_ACTIVE}" data-category="{LIST_CATFIL_ITEM_CATID}">{LIST_CATFIL_ITEM_TITLE|strtolower}</a></li>
	<!-- END: LIST_CATFIL_ITEM -->
</ul>
<!-- END: LIST_CATFIL -->

Kurulum
1) plugins/catfil klasorunu kopyalayin.
2) Eklentiyi yonetim panelinden kurun/aktif edin.
3) Gerekirse parent_cat ayarini yapin.
4) LIST_CATFIL blogunu skin sablonunuza ekleyin.
