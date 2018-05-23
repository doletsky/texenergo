<?
if(!empty($arResult['ACTIVE_FROM'])){
	$MES = array( 
		"01" => "января", 
		"02" => "февраля", 
		"03" => "марта", 
		"04" => "апреля", 
		"05" => "мая", 
		"06" => "июня", 
		"07" => "июля", 
		"08" => "августа", 
		"09" => "сентября", 
		"10" => "октября", 
		"11" => "ноября", 
		"12" => "декабря"
	);
	
	$parts = explode(" ", $arResult['ACTIVE_FROM']);
	$arData = explode(".", $parts[0]); 
	$d = ($arData[0] < 10) ? substr($arData[0], 1) : $arData[0];

	$arResult['ACTIVE_FROM_FORMATTED'] = $d." ".$MES[$arData[1]]." ".$arData[2];

	// до конца акции осталось...
	$arResult['SPECIAL_TIMER'] = Array();
	if (!empty($arResult['PROPERTIES']['IS_SPECIAL_TO']['VALUE'])) {				
		$arDate = ParseDateTime($arResult['PROPERTIES']['IS_SPECIAL_TO']['VALUE']);
		$iDate = mktime($arDate['HH'], $arDate['MI'], $arDate['SS'], $arDate['MM'], $arDate['DD'], $arDate['YYYY']);
		$iDiff = $iDate - time();
		if ($iDiff > 0) {

			$arResult['SPECIAL_TIMER']['DAYS'] = str_pad(IntVal(($iDiff / 86400)), 2, '0', STR_PAD_LEFT);
			$arResult['SPECIAL_TIMER']['HOURS'] = str_pad(IntVal(($iDiff / 3600) % 24), 2, '0', STR_PAD_LEFT);
			$arResult['SPECIAL_TIMER']['MINUTES'] = str_pad(IntVal(($iDiff / 60) % 60), 2, '0', STR_PAD_LEFT);

		}
	}	
}
?>