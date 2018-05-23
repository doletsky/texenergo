<?
foreach($arResult['CITIES'] as &$arCity){
	foreach($arCity['OFFICES'] as &$arOffice){
		foreach($arOffice['DEPARTMENTS'] as &$arDep){
			foreach($arDep['EMPLOYEES'] as &$arEmp){
				$img = CFile::ResizeImageGet($arEmp["DETAIL_PICTURE"], array('width'=>80, 'height'=>100), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);				
				$arEmp['PICTURE_SMALL'] = $img['src'];
				$arEmp['PICTURE_BIG'] = CFile::GetPath($arEmp["DETAIL_PICTURE"]);
				/* $arEmp['PICTURE_SMALL'] = SITE_TEMPLATE_PATH.'/img/contact/person-1.jpg';
				$arEmp['PICTURE_BIG'] = SITE_TEMPLATE_PATH.'/img/contact/person-1.jpg'; */			
			}			
		}
	}
}
unset($arEmp);
unset($arDep);
unset($arOffice);
unset($arCity);
?>