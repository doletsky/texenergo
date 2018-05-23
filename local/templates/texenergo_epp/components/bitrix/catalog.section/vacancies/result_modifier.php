<?
$arResult['CITIES'] = array();
$dbSect = CIBlockSection::GetList(array('SORT' => 'ASC'), array('IBLOCK_ID' => $arParams['IBLOCK_ID']), false);
while($sect = $dbSect->Fetch()){
	$arResult['CITIES'][$sect['ID']] = $sect;
}

foreach($arResult['ITEMS'] as $arItem){
	$cityId = $arItem['IBLOCK_SECTION_ID'];
	$arResult['CITIES'][$cityId]['VACANCIES'][] = $arItem;
}
?>