EN

In list.tpl:
====================================

1) Sorting.
Sorting works on all fields, including extrafields created through the "Directories & Extra fields" module.

For sort output: {SORT_FILTERS}

In plugin config (Sorting by field - asc or desc, empty - desc separate by commas)
must be specified, separated by a comma, the codes of the corresponding fields for sorting.

For example: 
	price(asc|desc),stock 
or 	
	price(asc|desc),stock,title(asc|desc)

as well as postscript (Name sorting fields separate by commas.): By price,By stock,By name

-------------------------------------------------- -------------------------------------------------- ---

2) Filters.
Filters work exclusively on extrafields created through the "Directories & Extra fields" module!!!

To display the filter, use the format: {FILTER_EXTRAFIELDNAME}

For example: {FILTER_BRAND} and {FILTER_STOCK}

In the plugin config (Extra fields separate by commas) codes must be added separated by commas
corresponding extrafields.

For example: 
	brand,stock

as well as postscript, placeholder (Placeholder for select field separate by commas)

For example: 
	Select brand,Select status

-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\-\--\-\

RU

В list.tpl:
=====================================

1) Сортировка.
Сортировка работает по всем полям, включая экстраполя созданные через раздел "Справочники и Экстраполя".

Для вывода сортировки: {SORT_FILTERS}

В конфиге плагина (Sorting by field - asc or desc, empty - desc separate by commas) 
должны быть заданы, через зяпятую коды соответствующих полей для сортировки.

Например: 
	price(asc|desc),stock 
или 
	price(asc|desc),stock,title(asc|desc)

а также приписка (Name sorting fields separate by commas.): По цене,По акциям,По названию

-------------------------------------------------------------------------------------------------------

2) Фильтры.
Фильтры работают исключительно по экстраполям созданным через раздел "Справочники и Экстраполя"!!!

Для вывода фильтра используем формат: {FILTER_НАЗВАНИЕЭКСТРАПОЛЯ}

Например: {FILTER_BRAND} и {FILTER_STOCK}

В конфиге плагина (Extra fields separate by commas) должны быть добавлены через запятую коды 
соответсвующих экстраполей.

Например: 
	brand,stock

а также приписка, плейсхолдер (Placeholder for select field separate by commas)

Например: 
	Выберите бренд,Выберите статус

