<?
foreach($arResult['ITEMS'] as &$arElement){
	$img = CFile::ResizeImageGet($arElement["PREVIEW_PICTURE"], array('width'=>240, 'height'=>200), BX_RESIZE_IMAGE_PROPORTIONAL, true);
	$arElement['PICTURE'] = $img['src'];
}
unset($arElement);
?>