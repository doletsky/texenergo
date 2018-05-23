<?php
/**
 * Путь до папки шаблона компонента
 */
$templateFolder = substr(dirname(__FILE__), strlen($_SERVER['DOCUMENT_ROOT']));

/**
 * js-файлы для каталога
 */
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/catalog.js');



/**
 * Фильтровать каталог по главному вендору (Техэнерго)
 */
$arResult['FILTER_BY_VENDOR'] = IntVal($_COOKIE['filter_own_products']) > 0;

/**
 * Представление лендинга каталога: detail или short
 */
$arResult['CATALOG_LANDING_VIEW'] = (IntVal($_COOKIE['catalog_view']) > 0 ? 'detail' : 'short');

/**
 * Поле для сортировки товаров
 */
$arResult['SORT_FIELD'] = IntVal($_COOKIE['catalog_sort']) > 0 ? IntVal($_COOKIE['catalog_sort']) : 4;
$arResult["ELEMENT_SORT_FIELD"] = getSortFieldById($arResult['SORT_FIELD']);
$arResult["ELEMENT_SORT_ORDER"] = getSortTypeById($arResult['SORT_FIELD']);

/**
 * Доступные поля для сортировки
 */
$arResult['SORT_FIELDS'] = getSortFieldArray();

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