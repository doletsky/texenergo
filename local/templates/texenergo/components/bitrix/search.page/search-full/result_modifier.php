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


foreach ($arResult['SEARCH'] as $arItem) {
    if ($arItem['MODULE_ID'] == 'iblock') {
        $arElement = CIBlockElement::GetByID($arItem['ITEM_ID'])->Fetch();

        $picID = $arElement['DETAIL_PICTURE'] ? : $arElement['PREVIEW_PICTURE'];

        // имеются ли аналоги
        $arItem['HAVE_ANALOGS'] = false;
        $arAnalogs = CIBlockElement::GetProperty($arElement['IBLOCK_ID'], $arElement['ID'], 'sort', 'asc', Array('CODE' => 'ANALOGS'))->Fetch();
        if ($arAnalogs && !empty($arAnalogs['VALUE'])) $arItem['HAVE_ANALOGS'] = true;

        // признак для метки "Акция"
        $arItem['IS_SPECIAL'] = false;
        $arIsSpecial = CIBlockElement::GetProperty($arElement['IBLOCK_ID'], $arElement['ID'], 'sort', 'asc', Array('CODE' => 'IS_SPECIAL'))->Fetch();
        if ($arIsSpecial['VALUE_ENUM'] == 'Y') {
            // если является спецпредложением, проверяем диапазон дат
            $arIsSpecialFrom = CIBlockElement::GetProperty($arElement['IBLOCK_ID'], $arElement['ID'], 'sort', 'asc', Array('CODE' => 'IS_SPECIAL_FROM'))->Fetch();
            $arIsSpecialTo = CIBlockElement::GetProperty($arElement['IBLOCK_ID'], $arElement['ID'], 'sort', 'asc', Array('CODE' => 'IS_SPECIAL_TO'))->Fetch();
            if (strlen($arIsSpecialFrom['VALUE']) > 0 && strlen($arIsSpecialTo['VALUE']) > 0) {
                $arFrom = ParseDateTime($arIsSpecialFrom['VALUE']);
                $iFrom = mktime($arFrom['HH'], $arFrom['MI'], $arFrom['SS'], $arFrom['MM'], $arFrom['DD'], $arFrom['YYYY']);
                $arTo = ParseDateTime($arIsSpecialTo['VALUE']);
                $iTo = mktime($arTo['HH'], $arTo['MI'], $arTo['SS'], $arTo['MM'], $arTo['DD'], $arTo['YYYY']);
                $iNow = time();
                if ($iNow > $iFrom && $iNow < $iTo) {
                    $arItem['IS_SPECIAL'] = true;
                }

            }
        }
        // изображение
            
        /*if ($picID && $arPic = CFile::ResizeImageGet($picID, array('width' => 43, 'height' => 30), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
            $arItem['PICTURE'] = $arPic['src'];
        } else {
            $arItem['PICTURE'] = SITE_TEMPLATE_PATH . '/img/catalog/no-image.png';
        }*/

        $arParamPhoto['GUID'] 	= $arElement['EXTERNAL_ID']; 
        $arParamPhoto['WIDTH']  = 25;
        $arParamPhoto['HEIGHT'] = 25;
        $URL_Thumb = getURLThumbnailPhoto($arParamPhoto);
        //pr($URL_Thumb);
        $arItem['PICTURE'] = $URL_Thumb;
    }

    // в зависимости от типа результата, помещаем элемент в нужный массив
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

// максимальное число результатов
$arResult['MAX_RESULTS'] = COption::GetOptionInt('search', 'max_result_size', 10000);

$arResult['PRODUCTS_TOTAL'] = $arResult['NAV_RESULT']->NavRecordCount;
$arResult['PRODUCTS_TOTAL'] = $arResult['PRODUCTS_TOTAL'] > $arResult['MAX_RESULTS'] ? $arResult['MAX_RESULTS'] : $arResult['PRODUCTS_TOTAL'];

$arResult['REQUEST']['QUERY'] = rtrim($arResult['REQUEST']['QUERY'], '*');