<?php

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['PICTURE'] = '';
    if (!empty($arItem['PREVIEW_PICTURE']['SRC'])) {
        if ($arPic = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width' => 999, 'height' => 44), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
            $arItem['PICTURE'] = $arPic['src'];
        }
    }
}