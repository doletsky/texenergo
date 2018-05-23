<?php

foreach ($arResult['SECTIONS'] as $ID => $arSection) {
    $arSection['ELEMENT_CNT'] = $arSection['UF_ELEMENT_CNT'];
    $arResult['SECTIONS'][$ID] = $arSection;
}

foreach ($arResult['SECTIONS'] as $ID => $arSection) {
    if ($arSection['IBLOCK_SECTION_ID'] > 0 && array_key_exists($arSection['IBLOCK_SECTION_ID'], $arResult['SECTIONS'])) {
        $arResult['SECTIONS'][$arSection['IBLOCK_SECTION_ID']]['IS_PARENT'] = true;
    }
}