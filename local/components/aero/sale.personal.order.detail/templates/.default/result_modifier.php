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
        Array('PROPERTY_SKU', 'PROPERTY_REFERENCE', 'EXTERNAL_ID', 'DETAIL_PICTURE', 'DETAIL_PAGE_URL'))->GetNext();

    $arItem['SKU'] = $arProduct['PROPERTY_SKU_VALUE'];
    $arItem['REFERENCE'] = $arProduct['PROPERTY_REFERENCE_VALUE'];
    if ($arItem['REFERENCE']) {
	$arItem['REFERENCE'] = "Референс: " . $arItem['REFERENCE']; 
	if ($arItem['SKU']) $arItem['REFERENCE'] = $arItem['REFERENCE'] . " Артикул: " . $arItem['SKU'];
   } 

    $arItem['DETAIL_PAGE_URL'] = $arProduct['DETAIL_PAGE_URL'];
    $arItem['EXTERNAL_ID'] = $arProduct['EXTERNAL_ID'];

/*    $arItem['PICTURE'] = SITE_TEMPLATE_PATH . '/img/catalog/no-image.png';
    if ($arProduct['DETAIL_PICTURE']) {
        if ($arPic = CFile::ResizeImageGet($arProduct['DETAIL_PICTURE'], array('width' => 130, 'height' => 130), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
            $arItem['PICTURE'] = $arPic['src'];
        }

    }
*/

	$arParamPhoto['GUID'] 	= $arItem['EXTERNAL_ID']; 
	$arParamPhoto['WIDTH']  = 60;
	$arParamPhoto['HEIGHT'] = 60;
	$URL_Thumb = getURLThumbnailPhoto($arParamPhoto);
	$arItem['PICTURE'] = $URL_Thumb;
}

if($arResult["PAYED"] == "Y" && !empty($arResult["DATE_PAYED"])){
	$parts = explode(' ', $arResult["DATE_PAYED"]);
	$arResult["DATE_PAYED_FORMATED"] = $parts[0];	
}