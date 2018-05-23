<?php

$basketItems = getGoodsIdsInBasket();
if(in_array($arResult['ID'], $basketItems))
	$arResult['IN_BASKET'] = true;

/**
 * Для удобства вывода проставляем значения цен в массив с типами цен
 */
foreach ($arResult['CAT_PRICES'] as $priceCode => $arPrice) {
    $arPrice['PRICE'] = $arResult['CATALOG_PRICE_' . $arPrice['ID']];
    $arResult['CAT_PRICES'][$priceCode] = $arPrice;
}

$arResult['FILES'] = Array();
if (count($arResult["PROPERTIES"]["PDF"]["VALUE"]) > 0) {
	foreach($arResult["PROPERTIES"]["PDF"]["VALUE"] as $fileId){
		if ($arFile = CFile::GetByID($fileId)->Fetch()) {
			$arFile['SRC'] = CFile::GetPath($arFile['ID']);
			$name = $arFile['ORIGINAL_NAME'];
			$parts = explode('.', $name);
			$ext = strtolower($parts[count($parts) - 1]);
			$img = SITE_TEMPLATE_PATH.'/img/price_list/'.$ext.'.png';
			if(file_exists($_SERVER['DOCUMENT_ROOT'].$img)){
				$arFile['ICON'] = $img;
			}
			$arResult['FILES'][] = $arFile;
		}
	}
}

$arResult['PHOTOS'] = Array();
if ($arResult["PROPERTIES"]["PHOTOS"]["VALUE"]) {
    foreach ($arResult["PROPERTIES"]["PHOTOS"]["VALUE"] as $photoID) {
        $arPic = CFile::ResizeImageGet($photoID, array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
        $arPreviewPic = CFile::ResizeImageGet($photoID, array('width' => 320, 'height' => 320), BX_RESIZE_IMAGE_PROPORTIONAL, true);
        $arFullPic = CFile::ResizeImageGet($photoID, array('width' => 1200, 'height' => 1200), BX_RESIZE_IMAGE_PROPORTIONAL, true);

        $arResult['PHOTOS'][] = Array(
            'SMALL' => $arPic['src'],
            'PREVIEW' => $arPreviewPic['src'],
            'FULL' => $arFullPic['src'],
        );
    }
}

if (is_array($arResult['DETAIL_PICTURE'])) {
    $arPic = CFile::ResizeImageGet($arResult['DETAIL_PICTURE']['ID'], array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
    $arResult['DETAIL_PICTURE']['SMALL'] = $arPic['src'];
    $arPic = CFile::ResizeImageGet($arResult['DETAIL_PICTURE']['ID'], array('width' => 320, 'height' => 320), BX_RESIZE_IMAGE_PROPORTIONAL, true);
    $arResult['DETAIL_PICTURE']['PREVIEW'] = $arPic['src'];
    $arPic = CFile::ResizeImageGet($arResult['DETAIL_PICTURE']['ID'], array('width' => 1200, 'height' => 1200), BX_RESIZE_IMAGE_PROPORTIONAL, true);
    $arResult['DETAIL_PICTURE']['SRC'] = $arPic['src'];
}

// до конца акции осталось...
$arResult['SPECIAL_TIMER'] = Array();
if (!empty($arResult['PROPERTIES']['IS_SPECIAL_TO']['VALUE'])) {
    $arDate = ParseDateTime($arResult['PROPERTIES']['IS_SPECIAL_TO']['VALUE']);
    $iDate = mktime($arDate['HH'], $arDate['MI'], $arDate['SS'], $arDate['MM'], $arDate['DD'], $arDate['YYYY']);
    $iDiff = $iDate - time();
    if ($iDiff > 0) {

        $arResult['SPECIAL_TIMER']['DAYS'] = str_pad(IntVal($iDiff / 86400), 2, '0', STR_PAD_LEFT);
        $arResult['SPECIAL_TIMER']['HOURS'] = str_pad(IntVal(($iDiff / 3600) % 24), 2, '0', STR_PAD_LEFT);
        $arResult['SPECIAL_TIMER']['MINUTES'] = str_pad(IntVal(($iDiff / 60) % 60), 2, '0', STR_PAD_LEFT);

    }
}

$arResult['IN_FAVORITES'] = in_array($arResult['ID'], getGoodsIdsInFavorites());

/**
 * Вес и габариты
 */
$arResult['PRODUCT'] = CCatalogProduct::GetByID($arResult['ID']);


/**
 * Мета для соц. сетей
 */
if($arResult['DETAIL_TEXT']){
	$arResult['OG_DESCRIPTION'] = strip_tags($arResult['DETAIL_TEXT']);
	$arResult['OG_DESCRIPTION'] = str_replace("\r\n", "", $arResult['OG_DESCRIPTION']);
	$arResult['OG_DESCRIPTION'] = preg_replace( "/\r|\n/", "", $arResult['OG_DESCRIPTION'] );
	$arResult['OG_DESCRIPTION'] = TruncateText($arResult['OG_DESCRIPTION'], 100);
}else{
	$arResult['OG_DESCRIPTION'] = '';
}

if($arResult['DETAIL_PICTURE']['SRC'])
	$arResult['OG_PHOTO'] = $arResult['DETAIL_PICTURE']['SRC'];
else
	$arResult['OG_PHOTO'] = SITE_TEMPLATE_PATH."/img/catalog/no-image.png";

$APPLICATION->AddHeadString('<meta property="og:type" content="website" />');
$APPLICATION->AddHeadString('<meta property="og:locale" content="ru_RU" />');
$APPLICATION->AddHeadString('<meta property="og:title" content="'.$arResult['NAME'].'"/>');
$APPLICATION->AddHeadString('<meta property="og:url" content="http://'.SITE_SERVER_NAME.$APPLICATION->GetCurPage().'"/>');
$APPLICATION->AddHeadString('<meta property="og:description" content="'.$arResult['OG_DESCRIPTION'].'" />');
$APPLICATION->AddHeadString('<meta property="og:image" content="'.$arResult['OG_PHOTO'].'" />');

foreach ($arResult['DISPLAY_PROPERTIES'] as $key => $arProp) {
    $isPacking = preg_match('/_PACKING$/', $arProp['CODE']);
    $arResult['DISPLAY_PROPERTIES'][$key]["IS_PACKING"] = $isPacking;

    if ($isPacking) {
        $arResult['DISPLAY_PROPERTIES'][$key]["IS_PACKING"] = true;

        if ($arProp['VALUE'] != "") {

            $arResult['DISPLAY_PROPERTIES'][$key]['VALUE_PRINT'] = array();
            $arPrint = explode(", ", $arProp['VALUE']);

            foreach ($arPrint as $print) {
                $arResult['DISPLAY_PROPERTIES'][$key]['VALUE_PRINT'][] = explode(":", $print);
            }
        }
    }

    if(in_array($arProp['CODE'], ['FILES_PRODUCT', 'PHOTOS_PRODUCT','DESCRIPTION_PRODUCT'])){
        unset($arResult['DISPLAY_PROPERTIES'][$key]);
    }
}

$discount = getUserDiscount();

if($discount) {
    $arResult['OLD_PRICE'] = $arResult['CAT_PRICES']['base_price']['PRICE'];
    $arResult['CAT_PRICES']['base_price']['PRICE'] = $arResult['OLD_PRICE'] - $arResult['OLD_PRICE'] * $discount / 100;
} else {
    $arResult['OLD_PRICE'] = $arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"];
}

$arResult['impotr_folder'] = 'https://www.texenergo.ru/upload/restrict';