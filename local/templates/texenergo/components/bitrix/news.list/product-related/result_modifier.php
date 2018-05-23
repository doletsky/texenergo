<?php

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['PICTURE'] = SITE_TEMPLATE_PATH . '/img/catalog/no-image.png';
/*    if (!empty($arItem['DETAIL_PICTURE'])) {
        if ($arPic = CFile::ResizeImageGet($arItem['DETAIL_PICTURE'], array('width' => 45, 'height' => 45), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
            $arItem['PICTURE'] = $arPic['src'];
        }

    }
*/
//pr($arItem['EXTERNAL_ID']);                    
$arParamPhoto['GUID'] 	= $arItem['EXTERNAL_ID']; 
$arParamPhoto['WIDTH']  = 60;
$arParamPhoto['HEIGHT'] = 60;
$URL_Thumb = getURLThumbnailPhoto($arParamPhoto);
//pr($URL_Thumb);
$arItem['PICTURE'] = $URL_Thumb;

}

foreach ($GLOBALS['arRelatedFilter']['ID'] as $arRel) {
  foreach ($arResult['ITEMS'] as &$arItem) {
    $arRel = trim($arRel);
    $itemID = trim($arItem['ID']);
    if ($arRel == $itemID) {
      $arResult['ITEMS_MODIF'][] = $arItem;      
      break;
    } 
  }

}
