<?php


CModule::IncludeModule('iblock');

foreach ($arResult['BASKET'] as &$arItem) {
    $arProduct = CIBlockElement::GetList(Array(),
        Array(
            'IBLOCK_ID' => IBLOCK_ID_CATALOG,
            'ACTIVE' => 'Y',
            '=ID' => $arItem['PRODUCT_ID']
        ), false,
        Array('nTopCount' => 1),
        Array('PROPERTY_SKU', 'PROPERTY_REFERENCE','EXTERNAL_ID', 'DETAIL_PICTURE', 'DETAIL_PAGE_URL'))->GetNext();

    $arItem['SKU'] = $arProduct['PROPERTY_SKU_VALUE'];
    $arItem['REFERENCE'] = $arProduct['PROPERTY_REFERENCE_VALUE'];
    $arItem['EXTERNAL_ID'] = $arProduct['EXTERNAL_ID'];
    $arItem['DETAIL_PAGE_URL'] = $arProduct['DETAIL_PAGE_URL'];

/*
    $arItem['PICTURE'] = SITE_TEMPLATE_PATH . '/img/catalog/no-image.png';
    if ($arProduct['DETAIL_PICTURE']) {
        if ($arPic = CFile::ResizeImageGet($arProduct['DETAIL_PICTURE'], array('width' => 130, 'height' => 130), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
            $arItem['PICTURE'] = $arPic['src'];
        }

    }
*/
	//pr($arItem['EXTERNAL_ID']);                    
	$arParamPhoto['GUID'] 	= $arItem['EXTERNAL_ID']; 
	$arParamPhoto['WIDTH']  = 130;
	$arParamPhoto['HEIGHT'] = 130;
	$URL_Thumb = getURLThumbnailPhoto($arParamPhoto);
	//pr($URL_Thumb);
	$arItem['PICTURE'] = $URL_Thumb;

    $arItem["NAME"] = htmlspecialchars_decode($arItem["NAME"], ENT_QUOTES);
}

//if ($arResult['COMPANY']['LOCATION_ACTUAL'] > 0) {
//
//    if (empty($arResult['PROPS_VALUES']['LOCATION_DELIVERY'])) {
//        $arResult['PROPS_VALUES']['LOCATION_DELIVERY'] = $arResult['COMPANY']['LOCATION_ACTUAL'];
//    }
//
//    if (empty($arResult['PROPS_VALUES']['ZIP_DELIVERY'])) {
//        $arResult['PROPS_VALUES']['ZIP_DELIVERY'] = $arResult['COMPANY']['ZIP_ACTUAL'];
//    }
//
//    if (empty($arResult['PROPS_VALUES']['STREET_DELIVERY'])) {
//        $arResult['PROPS_VALUES']['STREET_DELIVERY'] = $arResult['COMPANY']['STREET_ACTUAL'];
//    }
//
//    if (empty($arResult['PROPS_VALUES']['HOUSE_DELIVERY'])) {
//        $arResult['PROPS_VALUES']['HOUSE_DELIVERY'] = $arResult['COMPANY']['HOUSE_ACTUAL'];
//    }
//
//    if (empty($arResult['PROPS_VALUES']['HOUSING_DELIVERY'])) {
//        $arResult['PROPS_VALUES']['HOUSING_DELIVERY'] = $arResult['COMPANY']['HOUSING_ACTUAL'];
//    }
//
//    if (empty($arResult['PROPS_VALUES']['OFFICE_DELIVERY'])) {
//        $arResult['PROPS_VALUES']['OFFICE_DELIVERY'] = $arResult['COMPANY']['OFFICE_ACTUAL'];
//    }
//
//    if (empty($arResult['PROPS_VALUES']['STAGE_DELIVERY'])) {
//        $arResult['PROPS_VALUES']['STAGE_DELIVERY'] = $arResult['COMPANY']['STAGE_ACTUAL'];
//    }
//}