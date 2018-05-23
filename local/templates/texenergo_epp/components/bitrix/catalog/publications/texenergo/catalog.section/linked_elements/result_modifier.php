<?php

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['PICTURE'] = SITE_TEMPLATE_PATH . '/img/catalog/no-image.png';
    if (is_array($arItem['DETAIL_PICTURE'])) {
        if ($arPic = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width' => 130, 'height' => 130), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
            $arItem['PICTURE'] = $arPic['src'];
        }
    }

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