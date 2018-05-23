<?php

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['PICTURE'] = '';
    if (is_array($arItem['PREVIEW_PICTURE'])) {
        if ($arPic = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width' => 177, 'height' => 140), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
            $arItem['PICTURE'] = $arPic['src'];
        }
    }
    $arItem['SECTION'] = CIBlockSection::GetByID($arItem['IBLOCK_SECTION_ID'])->Fetch();

}


