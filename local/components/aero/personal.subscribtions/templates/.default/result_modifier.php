<?
foreach ($arResult['RUBRICS']['content'] as $i => $arRubric){
	if($arRubric['ID'] != 1)//����� ������ �������
		unset($arResult['RUBRICS']['content'][$i]);
}

foreach ($arResult['RUBRICS']['files'] as $i => $arRubric){
	if($arRubric['ID'] != 7)//����� ������ �����-����
		unset($arResult['RUBRICS']['files'][$i]);
}
?>