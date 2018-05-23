<?php


/**
 * Варианты отображения списка товаров
 */
$arResult['CATALOG_SECTION_VIEWS'] = Array('grid' => 'Кратко', 'list' => 'Списком', 'detail' => 'Подробно');

/**
 * Текущий вариант отображения списка товаров
 */

if (!in_array($_COOKIE['catalog_view'], array_keys($arResult['CATALOG_SECTION_VIEWS']))) {
    $_COOKIE['catalog_view'] = 'list';
}
$arResult['CATALOG_SECTION_VIEW'] = $_COOKIE['catalog_view'];



/**
 * Группируем результаты поиска по типам контента для раздельного отображения
 */
$arResult['PUBLICATIONS'] = Array();
$arResult['NEWS'] = Array();
$arResult['SECTIONS'] = Array();
$arResult['PRODUCTS'] = Array();
$arResult['OTHER'] = Array();

CModule::IncludeModule('iblock');
CModule::IncludeModule('catalog');
CModule::IncludeModule('sale');

if(empty($arResult['SEARCH']) && !isset($_GET['r'])){
    global $APPLICATION;
    $url = $APPLICATION->GetCurPageParam("r=y&q=".en2ru($_GET['q']), array('q'));
    LocalRedirect($url);
}

foreach ($arResult['SEARCH'] as $arItem) {
    if ($arItem['MODULE_ID'] == 'iblock') {
        $arElement = CIBlockElement::GetByID($arItem['ITEM_ID'])->Fetch();

        $picID = $arElement['DETAIL_PICTURE'] ? : $arElement['PREVIEW_PICTURE'];

        $arItem['PREVIEW_TEXT'] = $arElement['PREVIEW_TEXT'];

        if ($picID && $arPic = CFile::ResizeImageGet($picID, array('width' => 160, 'height' => 120), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
            $arItem['PICTURE'] = $arPic['src'];
        } else {
            $arItem['PICTURE'] = 'http://dummyimage.com/160x120/949294/ffffff.png&text=нет+фото';
        }
    }

    switch ($arItem['PARAM1']) {

        case 'catalog':

            if ($arItem['ITEM_ID'][0] == 'S') { // разделы инфоблока в отдельный массив
                $arResult['SECTIONS'][] = $arItem;
            } else {

                $arItem['PRICE'] = 0;
                if ($arPrice = CPrice::GetBasePrice($arItem['ID'])) {
                    $arItem['PRICE'] = $arPrice['PRICE'];
                }

                $arResult['PRODUCTS'][] = $arItem;
            }
            break;

        case 'news':
            $arResult['NEWS'][] = $arItem;
            break;

        case 'shop_articles':
            $arResult['PUBLICATIONS'][] = $arItem;
            break;

        default:
            $arResult['OTHER'][] = $arItem;
            break;

    }
}

$arResult['REQUEST']['QUERY'] = rtrim($arResult['REQUEST']['QUERY'], '*');