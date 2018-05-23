<?php
$discount = getUserDiscount();

$basketItems = getGoodsIdsInBasket();

foreach ($arResult['ITEMS'] as &$arItem) {
    
	if(in_array($arItem['ID'], $basketItems))
		$arItem['IN_BASKET'] = true;
	
	if($discount) {
        $arItem['OLD_PRICE'] = $arItem['PRICE'];
        $arItem['PRICE'] = $arItem['OLD_PRICE'] - $arItem['OLD_PRICE'] * $discount / 100;
    } else {
        $arResult['OLD_PRICE'] = $arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"];
    }

    $arItem['PICTURE'] = SITE_TEMPLATE_PATH . '/img/catalog/no-image.png';
    if (is_array($arItem['DETAIL_PICTURE'])) {
        if ($arPic = CFile::ResizeImageGet($arItem['DETAIL_PICTURE']['ID'], array('width' => 180, 'height' => 180), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
            $arItem['PICTURE'] = $arPic['src'];
        }
    }

    if (empty($arItem['PREVIEW_TEXT']) && !empty($arItem['DETAIL_TEXT'])) {
        $arItem['PREVIEW_TEXT'] = TruncateText(strip_tags($arItem['DETAIL_TEXT']), 300);
    }

    // до конца акции осталось...
    //if ($arParams['SHOW_SPECIAL_TIMER'] == 'Y') {
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
    //}

    // выбираем серию и 4 товара для превью
    if ($arParams['SHOW_SERIES_BLOCK'] == 'Y' && !empty($arItem['PROPERTIES']['SERIES']['VALUE'])) {
        //$arFilterProps = CIBlockSectionPropertyLink::GetArray($arParams['IBLOCK_ID'], $arResult['ID']);
        $arSeriesIDS = $arItem['PROPERTIES']['SERIES']['VALUE'];
        if (!is_array($arSeriesIDS)) $arSeriesIDS = Array($arSeriesIDS);
        $seriesID = reset($arSeriesIDS);

        $arSeries = CIBlockElement::GetList(Array(), Array(
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => IBLOCK_ID_SERIES,
                '=ID' => $seriesID,
            ), false, Array('nTopCount' => 1),
            Array(
                'ID', 'NAME', 'DETAIL_PAGE_URL', 'PREVIEW_TEXT',
            ))->GetNext();

        $rsSeriesItems = CIBlockElement::GetList(Array(), Array(
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => IBLOCK_ID_CATALOG,
                '=PROPERTY_SERIES.ID' => $seriesID,
            ), false, false,
            Array(
                'ID',
            ));

        $arSeries['COUNT'] = IntVal($rsSeriesItems->SelectedRowsCount());

        $rsSeriesItems = CIBlockElement::GetList(Array(), Array(
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => IBLOCK_ID_CATALOG,
                '=PROPERTY_SERIES.ID' => $seriesID,
            ), false, Array('nTopCount' => 3),
            Array(
                'ID', 'NAME', 'DETAIL_PAGE_URL', 'DETAIL_PICTURE',
            ));

        $arSeries['ITEMS'] = Array();

        $arRootSection = CIBlockSection::GetNavChain(IBLOCK_ID_CATALOG, $arResult['ID'], Array('CODE'))->GetNext();

        $sFilterParam = 'f_' . $arItem['PROPERTIES']['SERIES']['ID'] . '_' . abs(crc32($arSeries['ID']));

        $arSeries['DETAIL_PAGE_URL'] = '/catalog/' . $arRootSection['CODE'] . '/?set_filter=y&' . $sFilterParam . '=Y';

        while ($arSeriesItem = $rsSeriesItems->GetNext()) {
            $arSeriesItem['PICTURE'] = SITE_TEMPLATE_PATH . '/img/catalog/no-image.png';
            if ($arSeriesItem['DETAIL_PICTURE'] > 0) {
                if ($arPic = CFile::ResizeImageGet($arSeriesItem['DETAIL_PICTURE'], array('width' => 130, 'height' => 130), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
                    $arSeriesItem['PICTURE'] = $arPic['src'];
                }
            }
            $arSeries['ITEMS'][] = $arSeriesItem;
        }

        $arItem['SERIES'] = $arSeries;
    }

}