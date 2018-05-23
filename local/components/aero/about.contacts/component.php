<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

//if($this->StartResultCache(false, false)){
if(true){

	CModule::IncludeModule('iblock');

	$arCities = Array();
	$rsCities = CIBlockSection::GetList(Array('sort' => 'asc', 'name' => 'asc'), Array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y'), true, Array('ID', 'NAME'));
	while ($arCity = $rsCities->Fetch()) {
		if ($arCity['ELEMENT_CNT'] > 0) {
			$arCity['OFFICES'] = Array();
			$arCities[] = $arCity;
		}
	}

	$arSelect = Array(
		'ID', 'NAME', 'PROPERTY_ADDRESS', 'PROPERTY_PHONE', 'PROPERTY_EMAIL', 'PROPERTY_FAX', 'PROPERTY_WORKTIME', 'PROPERTY_SCHEME_AUTO', 'PROPERTY_SCHEME_FOOT'
	);

	foreach ($arCities as &$arCity) {
		$rsOffices = CIBlockElement::GetList(Array('sort' => 'asc', 'name' => 'asc'), Array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'SECTION_ID' => $arCity['ID'], 'ACTIVE' => 'Y'), false, false, $arSelect);
		while ($arOffice = $rsOffices->Fetch()) {
			$arOfficeData = Array(
				'ID' => $arOffice['ID'],
				'NAME' => $arOffice['NAME'],
				'ADDRESS' => $arOffice['PROPERTY_ADDRESS_VALUE'],
				'PHONE' => $arOffice['PROPERTY_PHONE_VALUE'],
				'FAX' => $arOffice['PROPERTY_FAX_VALUE'],
				'EMAIL' => $arOffice['PROPERTY_EMAIL_VALUE'],
				'WORKTIME' => $arOffice['PROPERTY_WORKTIME_VALUE'],
				'SCHEME_AUTO' => $arOffice['PROPERTY_SCHEME_AUTO_VALUE'],
				'SCHEME_FOOT' => $arOffice['PROPERTY_SCHEME_FOOT_VALUE'],
				'DEPARTMENTS' => Array(),
			);

			$rsDepartments = CIBlockElement::GetList(Array('sort' => 'asc', 'name' => 'asc'), Array('IBLOCK_ID' => $arParams['IBLOCK_ID_DEPARTMENTS'], 'PROPERTY_OFFICE.ID' => $arOfficeData['ID'], 'ACTIVE' => 'Y'), false, false, Array('ID', 'NAME', 'PROPERTY_PHONE'));
			while ($arDep = $rsDepartments->Fetch()) {
				//get employees
				$employees = array();
				$dbEmp = CIBlockElement::GetList(
					array('sort' => 'asc', 'name' => 'asc'),
					array('IBLOCK_ID' => $arParams['IBLOCK_ID_EMPLOYEES'], 'PROPERTY_DEPARTMENT' => $arDep['ID'], 'ACTIVE' => 'Y'), 
					false, 
					false,
					array('ID', 'IBLOCK_ID', 'NAME', 'DETAIL_PICTURE', 'PROPERTY_PHONE', 'PROPERTY_MOB_PHONE', 'PROPERTY_POSITION', 'PROPERTY_EMAIL', 'PROPERTY_ICQ', 'PROPERTY_EMAIL', 'PROPERTY_SKYPE')
				);
				while($arEmp = $dbEmp->Fetch()){
					$employees[] = $arEmp;
				}		
			
				$arDepartment = Array(
					'ID' => $arDep['ID'],
					'NAME' => $arDep['NAME'],
					'PHONE' => $arDep['PROPERTY_PHONE_VALUE'],
					'EMPLOYEES' => $employees,
				);
				$arOfficeData['DEPARTMENTS'][] = $arDepartment;
			}
			$arCity['OFFICES'][] = $arOfficeData;
		}
	}

	//echo '<pre>' . print_r($arCities, true) . '</pre>';

	$arResult['CITIES'] = $arCities;
}

$this->IncludeComponentTemplate();
