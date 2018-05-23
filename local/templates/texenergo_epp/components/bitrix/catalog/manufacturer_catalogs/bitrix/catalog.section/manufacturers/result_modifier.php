<?
foreach($arResult['ITEMS'] as &$arItem){
    if($arItem['DETAIL_PICTURE']){
        $img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array('width'=>173, 'height'=>110), BX_RESIZE_IMAGE_PROPORTIONAL, true);
        $arItem['PICTURE'] = $img['src'];
    } else if($arItem['PREVIEW_PICTURE']){
        $img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>173, 'height'=>110), BX_RESIZE_IMAGE_PROPORTIONAL, true);
        $arItem['PICTURE'] = $img['src'];
    }
}
?>