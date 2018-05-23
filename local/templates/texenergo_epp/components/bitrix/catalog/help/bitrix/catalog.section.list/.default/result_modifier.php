<?
$totalSections = count($arResult['SECTIONS']);
for($i = 0; $i < $totalSections; $i++){
	$arSection = $arResult['SECTIONS'][$i];	
	$nextSection = $arResult['SECTIONS'][$i + 1];
	if($nextSection['DEPTH_LEVEL'] > $arSection['DEPTH_LEVEL']){
		$arResult['SECTIONS'][$i]['IS_PARENT'] = true;
	}
	
	$dbElems = CIBlockElement::GetList(array('NAME' => 'ASC'), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'SECTION_ID' => $arSection['ID']), false, false, array('ID', 'NAME', 'CODE', 'DETAIL_PAGE_URL'));
	$sectElems = array();
	while($elem = $dbElems->GetNextElement()){				
		$sectElems[] = $elem->GetFields();		
	}
	$arResult['SECTIONS'][$i]['ELEMENTS'] = $sectElems;
}
?>

