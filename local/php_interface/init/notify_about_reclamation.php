<?
/**
 * Оповещение администраторов о новой рекламации на сайте
 */
 
AddEventHandler("iblock", "OnAfterIblockElementAdd", "notifyNewReclamation");
function notifyNewReclamation($arFields){	
	if($arFields['IBLOCK_ID'] == IBLOCK_ID_RECLAMATIONS){						
		$dbElems = CIBlockElement::GetList(array(), array('ID' => $arFields['ID'], 'IBLOCK_ID' => $arFields['IBLOCK_ID']), false, false, array('ID', 'IBLOCK_ID', 'NAME', 'DATE_CREATE'));
		if($elem = $dbElems->GetNextElement()){			
			$fields = $elem->GetFields();
			$props = $elem->GetProperties();
			
			$arUser = CUser::GetByID($props['USER_ID']['VALUE'])->Fetch();
			$userFIO = $arUser['LAST_NAME'].' '.$arUser['NAME'];
			
			$strAddress = '';
			if($arUser['UF_COMPANY_ID'])
				$strAddress = reclGetUserAddress($arUser['UF_COMPANY_ID']);
			
			$emailFields = array(
				'COMPANY' => '',
				'USER' => $userFIO,
				'ADDRESS' => $strAddress,
				'RECL_ID' => $fields['ID'],
				'RECL_DATE' => $fields['DATE_CREATE'],
				'PHONE' => $arUser['PERSONAL_PHONE'],
				'TYPE' => $props['TYPE']['VALUE_ENUM'],
				'EMAIL' => $arUser['EMAIL'],				
				'EDIT_LINK' => '/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.IBLOCK_ID_RECLAMATIONS.'&type=orders&ID='.$arFields['ID']
			);			
				
			if(!empty($props['DESCRIPTION_FILE']['VALUE'])){
				$path = CFile::GetPath($props['DESCRIPTION_FILE']['VALUE']);
				$emailFields['ADDITIONAL_NOTES'] = 'К рекламации прикреплен файл <a href="http://'.SITE_SERVER_NAME.$path.'">(скачать)</a>';
			}else{
				$emailFields['ADDITIONAL_NOTES'] = '';
			}
				
			if(!empty($arUser['UF_COMPANY_ID'])){
				$dbElems = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_CONTRACTS, 'ID' => $arUser['UF_COMPANY_ID']), false, false, array('NAME'));
				if($elem = $dbElems->Fetch()){
					$emailFields['COMPANY'] = '<b>Компания:</b> '.$elem['NAME'].'<br>';
				}
			}

			try{
				$filePath = aero\CDocGen::fillReclamation($arFields['ID']);
				$fileName = basename($filePath);
				$emailFields['ATTACH_FILES'] = $filePath.'=>'.$fileName.';';				
			}catch(Exception $ex){
				echo $ex->getMessage();
			}
						
			$ev = new CEvent;
			//$ev->Send('SALE_NEW_RECLAMATION', 's1', $emailFields);
			
			require_once($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/helpers/custom_mail.php');
			$ev->SendImmediate('SALE_NEW_RECLAMATION', 's1', $emailFields);
		}
	}
}

function reclGetUserAddress($agentId){
	$strAddress = '';
	CModule::IncludeModule('sale');

	$dbElems = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_AGENTS, 'ID' => $agentId), false, false);
	if($elem = $dbElems->GetNextElement()){
		
		$props = $elem->GetProperties();
		$propsByCode = array();
		
		foreach($props as $prop){
			$propsByCode[$prop['CODE']] = $prop;
		}
		
		if(!empty($propsByCode['LOCATION_ACTUAL']['VALUE']))
			$suffix = 'ACTUAL';
		else if(!empty($propsByCode['LOCATION_ACTUAL']['VALUE']))
			$suffix = 'LEGAL';
		
		if($suffix){
			$arLoc = CSaleLocation::GetByID($propsByCode['LOCATION_'.$suffix]['VALUE']);
			$cityName = $arLoc['CITY_NAME_LANG'];
			
			$strAddress = $propsByCode['ZIP_'.$suffix]['VALUE'].' '.$cityName.', ул.'.$propsByCode['STREET_'.$suffix]['VALUE'].' '.$propsByCode['HOUSE_'.$suffix]['VALUE'];
			
			if($propsByCode['HOUSING_'.$suffix]['VALUE'])
				$strAddress .= 'к'.$propsByCode['HOUSING_'.$suffix]['VALUE'];
			
			if($propsByCode['OFFICE_'.$suffix]['VALUE'])
				$strAddress .= ', оф.'.$propsByCode['OFFICE_'.$suffix]['VALUE'];
			
		}		
		
	}
	
	return $strAddress;
}
?>