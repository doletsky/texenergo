<?
$GLOBALS['catalogAccessoriesFilter'] = Array();

if (empty($arResult['ACCESSORIES'])) $arResult['ACCESSORIES'] = "-1"; // чтобы не выбрать лишнего
$GLOBALS['catalogAccessoriesFilter']['=ID'] = $arResult['ACCESSORIES'];

?>
<?$APPLICATION->IncludeComponent(
    "texenergo:catalog.section",
    "special",
    array(
        "IBLOCK_TYPE" => 'catalog',
        "IBLOCK_ID" => IBLOCK_ID_CATALOG,
        "ELEMENT_SORT_FIELD" => 'NAME',
        "ELEMENT_SORT_ORDER" => 'ASC',
        "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
        "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
        "INCLUDE_SUBSECTIONS" => 'Y', //$arParams["INCLUDE_SUBSECTIONS"],
        "FILTER_NAME" => 'catalogAccessoriesFilter',
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => 360000,
        "CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => "N",
        "SET_TITLE" => "N", //$arParams["SET_TITLE"],
        "SET_STATUS_404" => "N",
        "PAGE_ELEMENT_COUNT" => 30, //$arParams["PAGE_ELEMENT_COUNT"],
        "DISPLAY_TOP_PAGER" => "N", //$arParams["DISPLAY_TOP_PAGER"],
        "DISPLAY_BOTTOM_PAGER" => "N", //$arParams["DISPLAY_BOTTOM_PAGER"],
        "PAGER_SHOW_ALWAYS" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "SECTION_ID" => 0,
        'BY_LINK' => 'Y',
        'SHOW_MORE' => 'N',
    ),
    $component
);?>