<?
if($arResult['DETAIL_PICTURE']) {
    //$img = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], array('width'=>173, 'height'=>110), BX_RESIZE_IMAGE_PROPORTIONAL, true);
    //$arResult['PICTURE'] = $img['src'];
    $arResult['PICTURE'] = $arResult['DETAIL_PICTURE']['SRC'];
} else if($arResult['PREVIEW_PICTURE']) {
	//$img = CFile::ResizeImageGet($arResult["PREVIEW_PICTURE"], array('width'=>173, 'height'=>110), BX_RESIZE_IMAGE_PROPORTIONAL, true);
	//$arResult['PICTURE'] = $img['src'];
	$arResult['PICTURE'] = $arResult['PREVIEW_PICTURE']['SRC'];
}

$arResult['FILES'] = Array();
if (count($arResult["PROPERTIES"]["CATALOG"]["VALUE"]) > 0) {
	foreach($arResult["PROPERTIES"]["CATALOG"]["VALUE"] as $fileId){
		if ($arFile = CFile::GetByID($fileId)->Fetch()) {
			$arFile['SRC'] = CFile::GetPath($arFile['ID']);
			$name = $arFile['ORIGINAL_NAME'];
			$parts = explode('.', $name);
			$ext = strtolower($parts[count($parts) - 1]);
			$img = SITE_TEMPLATE_PATH.'/img/price_list/'.$ext.'.png';
			if(file_exists($_SERVER['DOCUMENT_ROOT'].$img)){
				$arFile['ICON'] = $img;
			}
			
			//уберем расширение
			//$pos = strrpos($name, '.');
			//$arFile['ORIGINAL_NAME'] = substr($arFile['ORIGINAL_NAME'], 0, $pos);
			$arFile['ORIGINAL_NAME'] = $parts[0];
			
			$arResult['FILES'][] = $arFile;
		}
	}
}

?>