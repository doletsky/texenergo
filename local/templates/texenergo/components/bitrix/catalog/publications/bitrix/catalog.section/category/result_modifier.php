<?
foreach($arResult['ITEMS'] as &$arElement){
	$img = CFile::ResizeImageGet($arElement["PREVIEW_PICTURE"], array('width'=>177, 'height'=>139), BX_RESIZE_IMAGE_PROPORTIONAL, true);
	$arElement['PICTURE'] = $img['src'];
	
	$dateParts = explode(' ', $arElement['ACTIVE_FROM']);
	$arElement['ACTIVE_FROM_FORMATTED'] = $dateParts[0];	
}
unset($arElement);
?>