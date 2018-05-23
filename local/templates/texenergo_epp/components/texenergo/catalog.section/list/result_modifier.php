<?php

$discount = getUserDiscount();

$basketItems = getGoodsIdsInBasket();

foreach ($arResult['ITEMS'] as &$arItem) {
	
	if(in_array($arItem['ID'], $basketItems))
		$arItem['IN_BASKET'] = true;

    if($discount) {
        $arItem['OLD_PRICE'] = $arItem['PRICE'];
        $arItem['PRICE'] = $arItem['PRICE'] - $arItem['PRICE'] * $discount / 100;
    } else {
        $arResult['OLD_PRICE'] = $arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"];
    }

    $arItem['PICTURE'] = SITE_TEMPLATE_PATH . '/img/catalog/no-image.png';
    if (is_array($arItem['DETAIL_PICTURE'])) {
        if ($arPic = CFile::ResizeImageGet($arItem['DETAIL_PICTURE']['ID'], array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
            if ($arPic['src']) {
                $arItem['PICTURE'] = $arPic['src'];
            }
        }
    }

    if (!file_exists($_SERVER['DOCUMENT_ROOT'].$arItem['PICTURE'])) {
        $arItem['PICTURE'] = SITE_TEMPLATE_PATH . '/img/catalog/no-image.png';
    }

    // до конца акции осталось...
    if ($arParams['SHOW_SPECIAL_TIMER'] == 'Y') {
        $arItem['SPECIAL_TIMER'] = Array();
        if (!empty($arItem['PROPERTIES']['IS_SPECIAL_TO']['VALUE'])) {
            $arDate = ParseDateTime($arItem['PROPERTIES']['IS_SPECIAL_TO']['VALUE']);
            $iDate = mktime($arDate['HH'], $arDate['MI'], $arDate['SS'], $arDate['MM'], $arDate['DD'], $arDate['YYYY']);
            $iDiff = $iDate - time();
            if ($iDiff > 0) {

                $arItem['SPECIAL_TIMER']['DAYS'] = str_pad(IntVal(($iDiff / 86400)), 2, '0', STR_PAD_LEFT);
                $arItem['SPECIAL_TIMER']['HOURS'] = str_pad(IntVal(($iDiff / 3600) % 24), 2, '0', STR_PAD_LEFT);
                $arItem['SPECIAL_TIMER']['MINUTES'] = str_pad(IntVal(($iDiff / 60) % 60), 2, '0', STR_PAD_LEFT);

            }
        }
    }
}

if(!empty($arParams['SORT_REFERENCES'])){
    $arSortedItems = array();
    foreach($arParams['SORT_REFERENCES'] as $iId){
        foreach($arResult['ITEMS'] as $key=>$aItem){
            if($aItem['PROPERTIES']['REFERENCE']['VALUE'] == $iId){
                $arSortedItems[] = $aItem;
                unset($arResult['ITEMS'][$key]);
                break;
            }
        }
    }
    $arResult['ITEMS'] = $arSortedItems;
}