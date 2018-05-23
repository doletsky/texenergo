<?php
foreach ($arResult['ITEMS'] as &$arItem) {
    $parts = explode(' ', $arItem['DATE_ACTIVE_FROM']);
	$arItem['DATE_ACTIVE_FROM_FORMATTED'] = $parts[0];
	
	$arItem['PICTURE'] = SITE_TEMPLATE_PATH . '/img/no-foto-slider-publication.jpg';

    if (is_array($arItem['PREVIEW_PICTURE'])) {
        if ($arPic = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width' => 200, 'height' => 200), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
            $arItem['PICTURE'] = $arPic['src'];
        }
    }
/*
pr($arItem['PROPERTIES']['MINIATURE_FOR_SLIDER']['ID']);
    if (is_array($arItem['PROPERTIES']['MINIATURE_FOR_SLIDER'])) {
        if ($arPic = CFile::ResizeImageGet($arItem['PROPERTIES']['MINIATURE_FOR_SLIDER']['ID'], array('width' => 300, 'height' => 150), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
	    pr($arPic);	
            $arItem['PICTURE'] = $arPic['src'];
        }
    }
*/
}

$arParent = 0;
$arResult['ROOT_SECTIONS'] = array();
$dbSect = CIBlockSection::GetList(array('LEFT_MARGIN' => 'ASC'), array('IBLOCK_ID' => $arParams['IBLOCK_ID']), false, array('ID', 'NAME', 'DEPTH_LEVEL'));
while($sect = $dbSect->Fetch()){
	if($sect['DEPTH_LEVEL'] == 1){
		$arParent = $sect;
		$arResult['ROOT_SECTIONS'][$sect['ID']] = array('ID' => $arParent['ID'], 'NAME' => $sect['NAME']);
	}else{
		$arResult['ROOT_SECTIONS'][$sect['ID']] = array('ID' => $arParent['ID'], 'NAME' => $arParent['NAME']);
	}	
}
?>

