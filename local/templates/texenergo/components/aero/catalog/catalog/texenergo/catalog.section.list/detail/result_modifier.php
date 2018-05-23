<?php


uasort($arResult['SECTIONS'], function ($a, $b) {
    return $b['DEPTH_LEVEL'] - $a['DEPTH_LEVEL'];
});

foreach ($arResult['SECTIONS'] as $ID => $arSection) {

    if(!isset($arSection['ELEMENT_CNT'])){
        $arSection['ELEMENT_CNT'] = $arSection['UF_ELEMENT_CNT'];
    }

    $arSection['PICTURE'] = CFile::GetPath($arSection['PICTURE']);

    $arResult['SECTIONS'][$ID] = $arSection;
    if ($arSection['IBLOCK_SECTION_ID'] > 0
        && is_array($arResult['SECTIONS'][$arSection['IBLOCK_SECTION_ID']])) {
        $arResult['SECTIONS'][$arSection['IBLOCK_SECTION_ID']]['SECTIONS'][] = $arResult['SECTIONS'][$ID];
        unset($arResult['SECTIONS'][$ID]);
    }
}

uasort($arResult['SECTIONS'], function ($a, $b) {
    return $a['SORT'] - $b['SORT'];
});
