<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

CModule::IncludeModule('iblock');
CModule::IncludeModule('search');

$arParams["CACHE_TIME"] = $arParams["CACHE_TIME"] ? $arParams["CACHE_TIME"] : 3600;

$arParams["CHECK_DATES"] = $arParams["CHECK_DATES"] == "Y";


$arParams['QUERY'] = trim($arParams['QUERY']);

$arResult['ITEMS'] = Array();

//Начало кеширования
$obCache = new CPHPCache;
if ($obCache->StartDataCache($arParams["CACHE_TIME"], md5($arParams['QUERY']))) {

    $obSearch = new CSearch;
    $arFilter = array(
        "QUERY" => $arParams['QUERY'],
        "SITE_ID" => LANG,
        "MODULE_ID" => "iblock",
    );

    if ($arParams['IBLOCK_CODE']) {
        $arFilter['PARAM1'] = $arParams['IBLOCK_CODE'];
    }

    if ($arParams['IBLOCK_ID']) {
        $arFilter['PARAM2'] = IntVal($arParams['IBLOCK_ID']);
    }

    $arResult['TOTAL'] = 0;
    $arTags = Array();

    $rsSections = CIBlockSection::GetList(
        Array('sort' => 'asc', 'name' => 'asc'),
        Array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'DEPTH_LEVEL' => 1, 'ACTIVE' => 'Y'),
        false,
        Array('ID', 'NAME')
    );

    while ($arSection = $rsSections->Fetch()) {
        $arTag = array(
            "URL" => $APPLICATION->GetCurPageParam("tags=" . urlencode($arSection['NAME']), array("tags")),
            "TAG_NAME" => htmlspecialcharsex(str_replace(",", "&comma;", $arSection['NAME'])),
        );

        $arFilter['TAGS'] = $arTag['TAG_NAME'];
        $obSearch->Search($arFilter);
        $arTag['COUNT'] = $obSearch->SelectedRowsCount();
        if ($arTag['COUNT'] > 0) {
            $arResult['TOTAL'] += $arTag['COUNT'];
            $arResult['ITEMS'][] = $arTag;
        }
    }


    $obCache->EndDataCache(Array(
                               'TOTAL' => $arResult['TOTAL'],
                               'ITEMS' => $arResult['ITEMS'],
                           ));

} else {
    $arVars = $obCache->GetVars();
    $arResult['ITEMS'] = $arVars['ITEMS'];
    $arResult['TOTAL'] = $arVars['TOTAL'];
}

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['URL'] = $APPLICATION->GetCurPageParam("tags=" . urlencode($arItem['TAG_NAME']), array("tags"));
}


$this->IncludeComponentTemplate();
