<?
foreach ($arResult['RUBRICS']['content'] as $i => $arRubric){
	if($arRubric['ID'] != 1)//нужны только новости
		unset($arResult['RUBRICS']['content'][$i]);
}

foreach ($arResult['RUBRICS']['files'] as $i => $arRubric){
	if($arRubric['ID'] != 7)//нужен только прайс-лист
		unset($arResult['RUBRICS']['files'][$i]);
}
?>