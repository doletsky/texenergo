<?
/*
 * BEGIN получить наименования фильтров и наименования значений
 * 
*/
$valuesIds = array();
$goodsIdsInCompareList = getGoodsIdsInCompareList();
foreach ($arResult ["ITEMS"] as &$arItem) {
    $arItem ["~COMPARE_URL"] = "/personal/compare.php?action=ADD_TO_COMPARE_LIST&id=" . $arItem ['ID'];
    $arItem ["COMPARE_URL"] = htmlspecialcharsbx($arItem ["~COMPARE_URL"]);
    $arItem ["~COMPARE_DELETE_URL"] = "/personal/compare.php?action=DELETE_FROM_COMPARE_LIST&id=" . $arItem ['ID'];
    $arItem ["COMPARE_DELETE_URL"] = htmlspecialcharsbx($arItem ["~COMPARE_DELETE_URL"]);
    $arItem["~BUY_URL"] = $APPLICATION->GetCurPageParam("action=BUY&id=" . $arItem["ID"], array('id', 'action'));
    $arItem["BUY_URL"] = htmlspecialcharsbx($arItem["~BUY_URL"]);
    $arItem["~ADD_URL"] = $APPLICATION->GetCurPageParam("action=ADD2BASKET&id=" . $arItem["ID"], array('id', 'action'));
    $arItem["ADD_URL"] = htmlspecialcharsbx($arItem["~ADD_URL"]);
    $arItem ['IN_COMPARE_LIST'] = false;
    if (is_array($goodsIdsInCompareList) && in_array($arItem ['ID'], $goodsIdsInCompareList)) {
        $arItem ['IN_COMPARE_LIST'] = true;
    }

    $arItem['PICTURE'] = SITE_TEMPLATE_PATH . '/img/catalog/no-image.png';
    if (is_array($arItem['DETAIL_PICTURE'])) {
        if ($arPic = CFile::ResizeImageGet($arItem['DETAIL_PICTURE']['ID'], array('width' => 130, 'height' => 130), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
            $arItem['PICTURE'] = $arPic['src'];
        }
    }

    if ($arItem["PROPERTIES"]["IZGOTOVITEL"]["VALUE"]) {
        $arBrand = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => IBLOCK_ID_BRANDS, 'ACTIVE' => 'Y', '=ID' => $arItem["PROPERTIES"]["IZGOTOVITEL"]["VALUE"]), false, Array('nTopCount' => 1), Array('NAME', 'PREVIEW_PICTURE'))->Fetch();
        if ($arBrand) {
            $arItem['BRAND_LOGO'] = CFile::GetPath($arBrand['PREVIEW_PICTURE']);
            $arItem['BRAND_NAME'] = $arBrand['NAME'];
        }
    }

}


?>