<?
/**
 * Подписка на товары
 * Оповещаем:
 * 	1. Изменилась Цена
 * 	2. Товар стал Новинкой
 * 	3. Товар стал Спецпредложением
 * 	4. Товар стал Лидером продаж 
 */
 
AddEventHandler("iblock", "OnBeforeIblockElementUpdate", "notifyTracking");
function notifyTracking(&$arFields){	
	if($arFields['IBLOCK_ID'] == IBLOCK_ID_CATALOG){				
		$records = CProductTracking::getAllTrackingRecords($arFields['ID']);
		
		if(count($records) > 0){						
			$arNotifyProps = array('IS_NEW', 'IS_BESTSELLER', 'IS_SPECIAL');
			$currentProps = getIblockElementProps($arFields['ID'], $arNotifyProps);
			
			foreach($arNotifyProps as $propCode){
				$propId = $currentProps[$propCode]['id'];
				$yesValue = CProductTracking::getPropYesValue($propCode);
				$newValue = $arFields['PROPERTY_VALUES'][$propId][0]['VALUE'];
				$oldValue = $currentProps[$propCode]['value'];
				
				if($oldValue != 'Y' && $newValue == $yesValue){
					foreach($records as $record){
						CProductTracking::notify($propCode, $record);
					}
				}
			}
		}
	}
}

function getIblockElementProps($id, $props){	
	$elemProps = array();
	
	$arSelect = array('IBLOCK_ID');
	foreach($props as $prop)
		$arSelect[] = 'PROPERTY_'.$prop;
	
	$dbElems = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_CATALOG, 'ID' => $id), false, false, $arSelect);
	if($elem = $dbElems->Fetch()){		
		foreach($props as $prop){
			$id = $elem['PROPERTY_'.$prop.'_VALUE_ID'];
			$id = explode(':', $id);			
			$id = $id[1];
			
			$elemProps[$prop] = array(
				'id' => $id,
				'value' => $elem['PROPERTY_'.$prop.'_VALUE']
			);
		}
	}
	
	return $elemProps;
}

AddEventHandler("catalog", "OnBeforePriceUpdate", "notifyTrackingPrice");
function notifyTrackingPrice($id, &$arFields){
	if($arFields['CATALOG_GROUP_ID'] == 1){//base price
		$records = CProductTracking::getAllTrackingRecords($arFields['PRODUCT_ID']);
		
		if(count($records) > 0){
			$curPrice = getCurPrice($arFields['PRODUCT_ID']);
			$newPrice = intval($arFields['PRICE']);
			if($newPrice != $curPrice){
				foreach($records as $record){
					$record['OLD_PRICE'] = $curPrice; 
					$record['NEW_PRICE'] = $newPrice;
					CProductTracking::notify('NEW_PRICE', $record);
				}	
			}
		}
	}
}

function getCurPrice($productId){
	$dbPrice = CPrice::GetList(array(), array('PRODUCT_ID' => $productId, 'CATALOG_GROUP_ID' => 1));
	if ($arPrice = $dbPrice->Fetch()){
		return intval($arPrice['PRICE']);
	}
	return 0;
}
 
?>