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
/*
    if (is_array($arItem['DETAIL_PICTURE'])) {
        if ($arPic = CFile::ResizeImageGet($arItem['DETAIL_PICTURE']['ID'], array('width' => 130, 'height' => 130), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
            $arItem['PICTURE'] = $arPic['src'];
        }

    }
*/
/*
		if(!empty($arItem['PROPERTIES']['PHOTOS_PRODUCT']['VALUE'][0])){
                        $sPhotosProductXmlId = $arItem['PROPERTIES']['PHOTOS_PRODUCT']['VALUE'][0];
                        $sDestinationName = $_SERVER["DOCUMENT_ROOT"]."/upload/resize_cache/import/".$sPhotosProductXmlId;
                        if(!is_dir($_SERVER['DOCUMENT_ROOT'] . '/upload/resize_cache/import')){
                            mkdir($_SERVER['DOCUMENT_ROOT'] . '/upload/resize_cache/import', 0775, true);
                        }
                        if(!file_exists($sDestinationName.'_grid.jpg')){
                            $arPhotosProduct = CIBlockElement::GetList(array(), array('XML_ID' => $sPhotosProductXmlId), false, false, array('ID', 'NAME', 'PROPERTY_PATH'))->GetNext();
                            $sSourceName = 'https://www.texenergo.ru/upload/restrict' . $arPhotosProduct['PROPERTY_PATH_VALUE'];
                            $sContent = file_get_contents($sSourceName);
                            $sSourceName = $_SERVER['DOCUMENT_ROOT'] . '/upload/resize_cache/import/'.$sPhotosProductXmlId.'.jpg';
                            file_put_contents($sSourceName, $sContent);
                            CFile::ResizeImageFile($sSourceName, $sDestinationNameSmall = $sDestinationName . '_grid.jpg', array('width' => 130, 'height' => 130), BX_RESIZE_IMAGE_PROPORTIONAL, array(), false, array());
                            unlink($sSourceName);
                        }
                        $arItem['PICTURE'] = '/upload/resize_cache/import/'.$sPhotosProductXmlId.'_grid.jpg';
                    }
*/
	//pr($arItem['EXTERNAL_ID']);
	$arParamPhoto['GUID'] 	= $arItem['EXTERNAL_ID']; 
	$arParamPhoto['WIDTH']  = 130;
	$arParamPhoto['HEIGHT'] = 130;
	$URL_Thumb = getURLThumbnailPhoto($arParamPhoto);
	//pr($URL_Thumb);
	$arItem['PICTURE'] = $URL_Thumb;


    if ($arParams['SHOW_SPECIAL_TIMER'] == 'Y') {
        // до конца акции осталось...
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

    foreach ($arItem['DISPLAY_PROPERTIES'] as $key => $arProp) {
        $isPacking = preg_match('/_PACKING$/', $arProp['CODE']);
        $arItem['DISPLAY_PROPERTIES'][$key]["IS_PACKING"] = $isPacking;

        if ($isPacking) {
            $arItem['DISPLAY_PROPERTIES'][$key]["IS_PACKING"] = true;

            if ($arProp['VALUE'] != "") {

                $arItem['DISPLAY_PROPERTIES'][$key]['VALUE_PRINT'] = array();
                $arPrint = explode(", ", $arProp['VALUE']);

                foreach ($arPrint as $print) {
                    $arItem['DISPLAY_PROPERTIES'][$key]['VALUE_PRINT'][] = explode(":", $print);
                }
            }
        }
    }
}