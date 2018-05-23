<?php
/**
 * Путь до папки шаблона компонента
 */
$templateFolder = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));

/**
 * js-файлы для каталога
 */
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/catalog.js');

/**
 * css-файлы для каталога
 */
$APPLICATION->SetAdditionalCSS($templateFolder . '/style.css');


/**
 * Фильтровать каталог по главному вендору (Техэнерго)
 */
$arResult['FILTER_BY_VENDOR'] = IntVal($_COOKIE['filter_own_products']) > 0;

/**
 * Представление лендинга каталога: detail или short
 */
$arResult['CATALOG_LANDING_VIEW'] = (IntVal($_COOKIE['catalog_view']) > 0 ? 'short' : 'detail');

/**
 * Поле для сортировки товаров
 */
$arResult['SORT_FIELD'] = IntVal($_COOKIE['catalog_sort']) > 0 ? IntVal($_COOKIE['catalog_sort']) : 6;
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

if ($arResult['FILTER_BY_VENDOR']) {

    $arFilter = array(
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'CODE' => $arResult["VARIABLES"]["SECTION_CODE"]
    );

    $rsSections = CIBlockSection::GetList(array(), $arFilter);

    if ($arSection = $rsSections->GetNext()) {

        $arResult['COUNT_BY_VENDOR'] = CIBlockElement::GetList(
            Array(),
            Array(
                "IBLOCK_ID"=>$arParams['IBLOCK_ID'],
                "ACTIVE_DATE"=>"Y",
                "ACTIVE"=>"Y",
                "SUBSECTION" => $arSection["ID"],
                'PROPERTY_IS_OWN_VALUE' => 'Y',
            ),
            Array()
        );

    }



    //print_r($arResult);
}
$GLOBALS['SEO']['CATALOG_SECTION_CODE'] = $arResult['VARIABLES']['SECTION_CODE'];