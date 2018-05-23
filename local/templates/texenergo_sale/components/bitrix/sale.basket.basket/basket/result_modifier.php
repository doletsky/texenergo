<?php
/**
 * Массив с позициями корзины, используемый в шаблоне
 * Для каждой позиции выбирается информация из каталога
 */
$arResult['BASKET'] = Array();

/**
 * Итого в рублях
 */
$arResult['TOTAL'] = 0;

/**
 * Массив с ID аксессуаров для товаров, присутствующих в корзине
 */
$arResult['ACCESSORIES'] = Array();


//echo '<pre>'.print_r($arResult['ITEMS']['AnDelCanBuy'], true).'</pre>';
foreach ($arResult['ITEMS']['AnDelCanBuy'] as $arItem) {

    $arElement = CIBlockElement::GetList(
        Array(),
        Array(
            'IBLOCK_ID' => IBLOCK_ID_CATALOG,
            '=ID' => $arItem['PRODUCT_ID'],
            'ACTIVE' => 'Y'
        ),
        false,
        Array('nTopCount' => 1),
        Array('DETAIL_PAGE_URL','EXTERNAL_ID','CATALOG_QUANTITY','PROPERTY_SKU','PROPERTY_REFERENCE', 'PROPERTY_ACCESSORIES', 'PROPERTY_OLD_PRICE')
    )->GetNext();

    if (!$arElement) {
        CSaleBasket::Delete($arItem['ID']);
        continue;
    }

    $arResult['ACCESSORIES'] = array_merge($arResult['ACCESSORIES'], $arElement['PROPERTY_ACCESSORIES_VALUE']);


    $arItem['DETAIL_PAGE_URL'] = $arElement['DETAIL_PAGE_URL'];
    $arItem['SKU'] = $arElement['PROPERTY_SKU_VALUE'];
    $arItem['REFERENCE'] = $arElement['PROPERTY_REFERENCE_VALUE'];
    $arItem['CATALOG_QUANTITY'] = $arElement['CATALOG_QUANTITY'];
    $arItem['EXTERNAL_ID'] = $arElement['EXTERNAL_ID'];

/*
    $arItem['PICTURE'] = SITE_TEMPLATE_PATH . '/img/catalog/no-image.png';
    if ($arItem['DETAIL_PICTURE'] > 0) {
        if ($arPic = CFile::ResizeImageGet($arItem['DETAIL_PICTURE'], array('width' => 130, 'height' => 130), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
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

    $discount = getUserDiscount();

    if($discount) {
        $arItem['OLD_PRICE'] = $arItem['FULL_PRICE'];
    } else {
        $arItem['OLD_PRICE'] = DoubleVal($arElement['PROPERTY_OLD_PRICE_VALUE']);
    }

    $arItem['SUBTOTAL'] = $arItem['PRICE'] * $arItem['QUANTITY'];
    $arResult['TOTAL'] += $arItem['SUBTOTAL'];

    $arItem["NAME"] = htmlspecialchars_decode($arItem["NAME"], ENT_QUOTES);

    $arResult['BASKET'][] = $arItem;
}

$arResult['ACCESSORIES'] = array_unique($arResult['ACCESSORIES']);


/**
 * Считаем кол-во спец-предложений, чтобы понять, выводить вкладку или нет
 */
$arSpecialFilter = Array(
    'IBLOCK_ID' => IBLOCK_ID_CATALOG,
    'PROPERTY_IS_SPECIAL_VALUE' => 'Y',
    '<=PROPERTY_IS_SPECIAL_FROM' => date('Y-m-d H:i:s', time()),
    '>PROPERTY_IS_SPECIAL_TO' => date('Y-m-d H:i:s', time()),
    'ACTIVE' => 'Y'
);

$rsSpecialItems = CIBlockElement::GetList(Array(), $arSpecialFilter, false, Array('nTopCount' => 1), Array('ID'));

$arResult['HAVE_SPECIALS'] = ($rsSpecialItems->SelectedRowsCount() > 0);

$arResult['MIN_ORDER_PRICE'] = 0;
CModule::IncludeModule('iblock');
$dbElems = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_SITE_SETTINGS, 'PROPERTY_CODE' => 'MIN_ORDER_PRICE', 'ACTIVE' => 'Y'), false, false, array('ID', 'IBLOCK_ID', 'PROPERTY_VALUE'));
while($elem = $dbElems->Fetch()){
	$arResult['MIN_ORDER_PRICE'] = $elem['PROPERTY_VALUE_VALUE'];
}

if($arResult['TOTAL'] >= $arResult['MIN_ORDER_PRICE'])
	$arResult['ORDER_ALLOWED'] = true;
else
	$arResult['ORDER_ALLOWED'] = false;

$wholeSaleData = getBasketPriceMarkup();
if(!empty($wholeSaleData)){
    $arResult['SHOW_PRICE_MSG'] = true;
    $arResult = array_merge($arResult, $wholeSaleData);
}