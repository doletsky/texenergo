<?
global $arAccessoriesFilter;

$arAccessoriesFilter = Array(
    "ACTIVE" => "Y",
    "ID" => $arResult["PROPERTIES"]["ACCESSORIES"]["VALUE"],

);
$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "product-accessories",
    Array(
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "N",
        "AJAX_MODE" => "N",
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => $arParams['IBLOCK_ID'],
        "NEWS_COUNT" => "1000",
        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_ORDER1" => "DESC",
        "SORT_BY2" => "SORT",
        "SORT_ORDER2" => "ASC",
        "FILTER_NAME" => "arAccessoriesFilter",
        "FIELD_CODE" => array("DETAIL_PICTURE", "CATALOG_WEIGHT", "CATALOG_LENGTH", "CATALOG_MEASURE", "CATALOG_QUANTITY", "CATALOG_HEIGHT", "CATALOG_AVAILABLE", "CATALOG_WIDTH"),
        "PROPERTY_CODE" => array("SKU"),
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => $arParams['DETAIL_URL'],
        "PREVIEW_TRUNCATE_LEN" => "",
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "SET_TITLE" => "N",
        "SET_STATUS_404" => "N",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "INCLUDE_SUBSECTIONS" => "Y",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "PAGER_TEMPLATE" => ".default",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "PAGER_TITLE" => "",
        "PAGER_SHOW_ALWAYS" => "Y",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "Y",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N"
    ),
    false
);

?>