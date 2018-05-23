<?
foreach($arResult['ITEMS'] as &$arElement){
	$img = CFile::ResizeImageGet($arElement["PREVIEW_PICTURE"], array('width'=>177, 'height'=>139), BX_RESIZE_IMAGE_PROPORTIONAL, true);
	$arElement['PICTURE'] = $img['src'];
	
	$dateParts = explode(' ', $arElement['ACTIVE_FROM']);
	$arElement['ACTIVE_FROM_FORMATTED'] = $dateParts[0];	
	
	// до конца акции осталось...
	$arElement['SPECIAL_TIMER'] = Array();
	if (!empty($arElement['PROPERTIES']['IS_SPECIAL_TO']['VALUE'])) {				
		$arDate = ParseDateTime($arElement['PROPERTIES']['IS_SPECIAL_TO']['VALUE']);
		$iDate = mktime($arDate['HH'], $arDate['MI'], $arDate['SS'], $arDate['MM'], $arDate['DD'], $arDate['YYYY']);
		$iDiff = $iDate - time();
		if ($iDiff > 0) {

			$arElement['SPECIAL_TIMER']['DAYS'] = str_pad(IntVal(($iDiff / 86400)), 2, '0', STR_PAD_LEFT);
			$arElement['SPECIAL_TIMER']['HOURS'] = str_pad(IntVal(($iDiff / 3600) % 24), 2, '0', STR_PAD_LEFT);
			$arElement['SPECIAL_TIMER']['MINUTES'] = str_pad(IntVal(($iDiff / 60) % 60), 2, '0', STR_PAD_LEFT);

		}
	}
}
unset($arElement);
?>