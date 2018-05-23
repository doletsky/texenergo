<?php

uasort($arResult['SECTIONS'], function ($a, $b) {
    return $b['DEPTH_LEVEL'] - $a['DEPTH_LEVEL'];
});

foreach ($arResult['SECTIONS'] as $ID => $arSection) {
    $arSection['ELEMENT_CNT'] = $arSection['UF_ELEMENT_CNT'];
    $arResult['SECTIONS'][$ID] = $arSection;
}

foreach ($arResult['SECTIONS'] as $ID => $arSection) {
    if ($arSection['IBLOCK_SECTION_ID'] > 0) {
        $arResult['SECTIONS'][$arSection['IBLOCK_SECTION_ID']]['SECTIONS'][] = $arResult['SECTIONS'][$ID];
        uasort($arResult['SECTIONS'][$arSection['IBLOCK_SECTION_ID']]['SECTIONS'], function ($a, $b) {
            return $a['SORT'] - $b['SORT'];
        });
        unset($arResult['SECTIONS'][$ID]);
    }
}

uasort($arResult['SECTIONS'], function ($a, $b) {
    return $a['SORT'] - $b['SORT'];
});


$arResult['BANNER_ELEMNTS'] = array();
$sectionsIds = [];
foreach ($arResult['SECTIONS'] as $arSection){
	$sectionsIds[] = $arSection['ID'];
}

if(!empty($sectionsIds)){
	$dbElems = CIBlockElement::GetList(
		array('RAND' => 'ASC'),
		array(
			'IBLOCK_ID' => IBLOCK_ID_CATALOG,
			'SECTION_ID' => $sectionsIds,
			'INCLUDE_SUBSECTIONS' => 'Y',
			array(
				'LOGIC' => 'OR',
				array('PROPERTY_IS_NEW_VALUE' => 'Y'),
				array('PROPERTY_IS_BESTSELLER_VALUE' => 'Y'),
				array('PROPERTY_IS_SPECIAL_VALUE' => 'Y')
			)
		),
		false,
		false,
		array('ID', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'CODE', 'DETAIL_PAGE_URL', 'NAME', 'DETAIL_PICTURE', 'CATALOG_GROUP_1', 'PROPERTY_SKU'));

	while($elem = $dbElems->GetNext()){
		$elem['DETAIL_PAGE_URL'] = str_replace('#SITE_DIR#', '', $elem['DETAIL_PAGE_URL']);
		$elem['DETAIL_PAGE_URL'] = str_replace('#CODE#', $elem['CODE'], $elem['DETAIL_PAGE_URL']);

		if($elem["DETAIL_PICTURE"]){
			$img = CFile::ResizeImageGet($elem["DETAIL_PICTURE"], array('width'=>77, 'height'=>60), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$elem['PICTURE'] = $img['src'];
		}else{
			$elem['PICTURE'] = '/local/templates/texenergo/img/catalog/no-image.png';
		}

		$arResult['BANNER_ELEMNTS'][$elem['IBLOCK_SECTION_ID']] = $elem;
	}
}