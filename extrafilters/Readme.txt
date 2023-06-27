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

� list.tpl:
=====================================

1) ����������.
���������� �������� �� ���� �����, ������� ���������� ��������� ����� ������ "����������� � ����������".

��� ������ ����������: {SORT_FILTERS}

� ������� ������� (Sorting by field - asc or desc, empty - desc separate by commas) 
������ ���� ������, ����� ������� ���� ��������������� ����� ��� ����������.

��������: 
	price(asc|desc),stock 
��� 
	price(asc|desc),stock,title(asc|desc)

� ����� �������� (Name sorting fields separate by commas.): �� ����,�� ������,�� ��������

-------------------------------------------------------------------------------------------------------

2) �������.
������� �������� ������������� �� ����������� ��������� ����� ������ "����������� � ����������"!!!

��� ������ ������� ���������� ������: {FILTER_������������������}

��������: {FILTER_BRAND} � {FILTER_STOCK}

� ������� ������� (Extra fields separate by commas) ������ ���� ��������� ����� ������� ���� 
�������������� �����������.

��������: 
	brand,stock

� ����� ��������, ����������� (Placeholder for select field separate by commas)

��������: 
	�������� �����,�������� ������

