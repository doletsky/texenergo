<?
foreach($arResult['ITEMS'] as $key => &$arElement){		
	$img = CFile::ResizeImageGet($arElement['PROPERTIES']['IMAGE']['VALUE'], array('width'=>81, 'height'=>76), BX_RESIZE_IMAGE_PROPORTIONAL, true);
	$arElement['IMAGE'] = $img['src'];
}
unset($arElement);
?>