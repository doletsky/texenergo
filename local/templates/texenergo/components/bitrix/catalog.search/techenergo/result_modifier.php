<?php


/**
 * Варианты отображения списка товаров
 */
$arResult['CATALOG_SECTION_VIEWS'] = Array('grid' => 'Кратко', 'list' => 'Списком', 'detail' => 'Подробно');

/**
 * Текущий вариант отображения списка товаров
 */

if (!in_array($_COOKIE['catalog_view'], array_keys($arResult['CATALOG_SECTION_VIEWS']))) {
    $_COOKIE['catalog_view'] = 'list';
}
$arResult['CATALOG_SECTION_VIEW'] = $_COOKIE['catalog_view'];

/**
 * Доступные поля для сортировки
 */
$arResult['SORT_FIELDS'] = getSortFieldArray();

/**
 * Поле для сортировки товаров
 */
$arResult['SORT_FIELD'] = IntVal($_COOKIE['catalog_sort']) > 0 ? IntVal($_COOKIE['catalog_sort']) : 2;
$arResult["ELEMENT_SORT_FIELD"] = getSortFieldById($arResult['SORT_FIELD']);
$arResult["ELEMENT_SORT_ORDER"] = getSortTypeById($arResult['SORT_FIELD']);
?>