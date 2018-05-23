<?php
/**
 * User: timokhin
 * Date: 10.06.14
 * Time: 14:30
 */

$arResult['OWN_ONLY'] = ($_GET['own'] == 'yes');

$arPropsDiff = Array();
foreach ($arResult['ITEMS'] as $k => $arItem) {
    if ($arResult['OWN_ONLY'] && $arItem['PROPERTIES']['IS_OWN']['VALUE'] != 'Y') {
        unset($arResult['ITEMS'][$k]);
        continue;
    }

    if (is_array($arItem['DETAIL_PICTURE'])) {
        $arPic = CFile::ResizeImageGet($arItem['DETAIL_PICTURE']['ID'], array('width' => 133, 'height' => 133), BX_RESIZE_IMAGE_PROPORTIONAL, true);
        $arItem['PICTURE'] = $arPic['src'];
    } else {
        $arItem['PICTURE'] = SITE_TEMPLATE_PATH . '/img/catalog/no-image.png';
    }

    $arItem['PRICE'] = $arItem['PRICES']['base_price']['VALUE'];

    $arProduct = CCatalogProduct::GetByID($arItem['ID']);

    $arItem['PROPERTIES']['WEIGHT'] = Array(
        'VALUE' => $arProduct['WEIGHT'],
        'CODE' => 'WEIGHT'
    );

    $arItem['PROPERTIES']['WIDTH'] = Array(
        'VALUE' => $arProduct['WIDTH'],
        'CODE' => 'WIDTH'
    );

    $arItem['PROPERTIES']['LENGTH'] = Array(
        'VALUE' => $arProduct['LENGTH'],
        'CODE' => 'LENGTH'
    );

    $arItem['PROPERTIES']['HEIGHT'] = Array(
        'VALUE' => $arProduct['HEIGHT'],
        'CODE' => 'HEIGHT'
    );
    if ($arProduct['MEASURE'] > 0) {
        if ($arMeasure = CCatalogMeasure::getList(Array(), Array('ID' => $arProduct['MEASURE']))->Fetch()) {
            $arItem['PROPERTIES']['MEASURE'] = Array(
                'VALUE' => $arMeasure['SYMBOL_RUS'],
                'CODE' => 'MEASURE'
            );
        }
    }
    foreach ($arItem['PROPERTIES'] as &$arItemProp) {

        if ($arItemProp['CODE'] == 'SKU') {
            $arItem['SKU'] = $arItemProp['VALUE'];
            unset($arItemProp);
            continue;
        }

        if ($arItemProp['PROPERTY_TYPE'] == 'E' && $arItemProp['LINK_IBLOCK_ID'] > 0 && $arItemProp['VALUE'] > 0) {
            $arValue = CIBlockElement::GetList(Array(),
                Array(
                    'IBLOCK_ID' => IntVal($arItemProp['LINK_IBLOCK_ID']),
                    'ID' => IntVal($arItemProp['VALUE']),
                    'ACTIVE' => 'Y'
                ),
                false,
                Array('nTopCount' => 1),
                Array('ID', 'NAME', 'PREVIEW_PICTURE')
            )->Fetch();
            if ($arValue) {
                $arItemProp['VALUE_ID'] = $arItemProp['VALUE'];
                $arItemProp['VALUE'] = $arValue['NAME'];
                if ($arValue['PREVIEW_PICTURE']) {
                    $arItemProp['PICTURE'] = CFile::GetPath($arValue['PREVIEW_PICTURE']);
                }
            }
        }

        $arPropsDiff[$arItemProp['CODE']][] = $arItemProp['VALUE'];
    }

    $arResult['ITEMS'][$k] = $arItem;
}

$arResult['SHOW_PROPERTIES'] = array_merge($arResult['SHOW_PROPERTIES'],
    Array(
        'WEIGHT' => Array('NAME' => 'Вес (кг)', 'VALUE' => '', 'CODE' => 'WEIGHT'),
        'WIDTH' => Array('NAME' => 'Ширина (мм)', 'VALUE' => '', 'CODE' => 'WIDTH'),
        'LENGTH' => Array('NAME' => 'Длина (мм)', 'VALUE' => '', 'CODE' => 'LENGTH'),
        'HEIGHT' => Array('NAME' => 'Высота (мм)', 'VALUE' => '', 'CODE' => 'HEIGHT'),
        'MEASURE' => Array('NAME' => 'Ед. измерения', 'VALUE' => '', 'CODE' => 'MEASURE'),
    )
);

$arNewProps = Array();
foreach ($arResult['SHOW_PROPERTIES'] as $k => $arProp) {
    $bNotEmpty = false;
    $bSimilar = true;
    $firstVal = $arResult['ITEMS'][0]['PROPERTIES'][$arProp['CODE']]['VALUE'];
    foreach ($arResult['ITEMS'] as $arItem) {
        $val = $arItem['PROPERTIES'][$arProp['CODE']]['VALUE'];
        if (!empty($val)) {
            $bNotEmpty = true;
        }
        if ($val != $firstVal) {
            $bSimilar = false;
        }
    }
    if ($bNotEmpty) {
        $arProp['SIMILAR'] = $bSimilar ? 'Y' : 'N';
        $arNewProps[$arProp['CODE']] = $arProp;
    }
}
$arResult['SHOW_PROPERTIES'] = $arNewProps;