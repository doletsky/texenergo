<?
CModule::IncludeModule('iblock');
$dbElems = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_VACANCIES, 'ID' => $arParams['VACANCY_ID']), false, false, array('ID', 'IBLOCK_ID', 'NAME'));
if($elem = $dbElems->Fetch()){
	$name = trim($elem['NAME']);
	$arResult['VACANCY_NAME'] = $name;
}
?>