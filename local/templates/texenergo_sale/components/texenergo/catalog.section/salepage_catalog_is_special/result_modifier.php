<?php
CModule::IncludeModule("iblock");
foreach ($arResult['ITEMS'] as &$arItem) {
/*    $arItem['PICTURE'] = SITE_TEMPLATE_PATH . '/img/catalog/no-image.png';
    if (is_array($arItem['DETAIL_PICTURE'])) {
        if ($arPic = CFile::ResizeImageGet($arItem['DETAIL_PICTURE']['ID'], array('width' => 130, 'height' => 130), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
            $arItem['PICTURE'] = $arPic['src'];
        }
    }
*/
$arItem["PICTURE_40"] = array();
//pr($arItem['EXTERNAL_ID']);                    
$arParamPhoto['GUID'] 	= $arItem['EXTERNAL_ID']; 
$arParamPhoto['WIDTH']  = 130;
$arParamPhoto['HEIGHT'] = 130;
$URL_Thumb = getURLThumbnailPhoto($arParamPhoto);
//pr($URL_Thumb);
$arItem['PICTURE'] = $URL_Thumb;
//PHOTOS_PRODUCT
    $arItem['PICTURE_PHOTOS_PRODUCT']=array();
    if (!empty($arItem["PROPERTIES"]["PHOTOS_PRODUCT"]["VALUE"])) {
        $arPhotosProducts = array();
        foreach ($arItem['PROPERTIES']['PHOTOS_PRODUCT']['VALUE'] as $key => $sPhotosProductXmlId) {
                if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/upload/resize_cache/import')) {
                    mkdir($_SERVER['DOCUMENT_ROOT'] . '/upload/resize_cache/import', 0775, true);
                }
                $arPhotosProduct = CIBlockElement::GetList(array(), array('XML_ID' => $sPhotosProductXmlId), false, false, array('ID', 'NAME', 'PROPERTY_PATH'))->GetNext();
                $sSourceName = $_SERVER['DOCUMENT_ROOT'] . '/upload/restrict' . $arPhotosProduct['PROPERTY_PATH_VALUE'];
                $sDestinationName = $_SERVER["DOCUMENT_ROOT"] . "/upload/resize_cache/import/" . $sPhotosProductXmlId;
                if(count($arItem['PROPERTIES']['PHOTOS_PRODUCT']['VALUE'])>1 && $key<2){
                    if (!file_exists($sDestinationName . '_size_40.jpg')) {
                        CFile::ResizeImageFile($sSourceName, $sDestinationNameSmall = $sDestinationName . '_size_40.jpg', array('width' => 40, 'height' => 40), BX_RESIZE_IMAGE_PROPORTIONAL, array(), false, array());
                    }
                    $arItem["PICTURE_40"][$key]='/upload/resize_cache/import/' . $sPhotosProductXmlId . '_size_40.jpg';
                }

                if (!file_exists($sDestinationName . '_size_130.jpg')) {
                    CFile::ResizeImageFile($sSourceName, $sDestinationNamePreview = $sDestinationName . '_size_130.jpg', array('width' => 130, 'height' => 130), BX_RESIZE_IMAGE_PROPORTIONAL, array(), false, array());
                }
                unlink($sSourceName);
                $arItem['PICTURE_PHOTOS_PRODUCT'][]='/upload/resize_cache/import/' . $sPhotosProductXmlId . '_size_130.jpg';

        }

    }

}