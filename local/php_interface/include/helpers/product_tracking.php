<?
class CProductTracking{
	public static $_propInfo = false;
	
	function getPropYesValue($code){
		
		if(!isset($_propInfo[$code])){
			$dbEnum = CIBlockPropertyEnum::GetList(array(), array('IBLOCK_ID' =>IBLOCK_ID_CATALOG, 'CODE' => $code));
			while($arEnum = $dbEnum->Fetch()){
				if($arEnum['VALUE'] == 'Y'){
					$_propInfo[$code] = $arEnum['ID'];
				}
			}
		}
		
		return $_propInfo[$code];
	}
	
	function isTrackingOn($userId, $productId){
		$id = self::getTrackingRecord($userId, $productId);
		if($id) return true;
		else return false;
	}
	
	function getTrackingRecord($userId, $productId){
		if(!empty($userId) && !empty($productId)){
			CModule::IncludeModule('iblock');
			$dbElems = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_TRACKING, 'PROPERTY_PRODUCT_ID' => $productId, 'PROPERTY_USER_ID' => $userId), false, false, array('ID'));
			if($elem = $dbElems->Fetch()){
				return $elem['ID'];
			}
		}
		return false;
	}
	
	function getAllTrackingRecords($productId){		
		CModule::IncludeModule('iblock');
		$records = array();
		$dbElems = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_TRACKING, 'PROPERTY_PRODUCT_ID' => $productId), false, false, array('ID', 'IBLOCK_ID', 'PROPERTY_USER_ID', 'PROPERTY_PRODUCT_ID', 'PROPERTY_PRODUCT_ID.NAME', 'PROPERTY_PRODUCT_ID.DETAIL_PAGE_URL'));
		while($elem = $dbElems->Fetch()){			
			$records[] = $elem;
		}		
		return $records;
	}
	
	function getUserTrackingRecords($userId){		
		CModule::IncludeModule('iblock');
		$records = array();
		$dbElems = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_TRACKING, 'PROPERTY_USER_ID' => $userId), false, false, array('ID', 'IBLOCK_ID', 'PROPERTY_USER_ID', 'PROPERTY_PRODUCT_ID',  'PROPERTY_PRODUCT_ID.NAME', 'PROPERTY_PRODUCT_ID.DETAIL_PAGE_URL'));
		while($elem = $dbElems->Fetch()){			
			$elem['PROPERTY_PRODUCT_ID_DETAIL_PAGE_URL'] = str_replace('//', '/', $elem['PROPERTY_PRODUCT_ID_DETAIL_PAGE_URL']);
			$records[] = $elem;
		}		
		return $records;
	}
	
	function startTracking($userId, $productId){
		CModule::IncludeModule('iblock');
		if(!self::isTrackingOn($userId, $productId)){
			$el = new CIBlockElement;
			$id = $el->Add(
				array(
					'NAME' => date('d-m-Y H:i:s'),
					'IBLOCK_ID' => IBLOCK_ID_TRACKING,
					'PROPERTY_VALUES' => array(
						'PRODUCT_ID' => $productId,
						'USER_ID' => $userId
					)
				)
			);
			
			if($id){
				echo 'ok';
			}
		}
	}
	
	function stopTracking($userId, $productId){
		CModule::IncludeModule('iblock');
		if($productId == 'all'){			
			$records = self::getUserTrackingRecords($userId);
			foreach($records as $rec){
				CIBlockElement::Delete($rec['ID']);
			}
		}else{
			$id = self::getTrackingRecord($userId, $productId);
			if($id){
				CIBlockElement::Delete($id);
			}
		}
		echo 'ok';
	}
	
	function notify($propCode, $trackingRecord){
		$email = self::getUserEmail($trackingRecord['PROPERTY_USER_ID_VALUE']);
		if($email && !empty($email)){
			switch($propCode){
				case 'IS_NEW':
					$eventName = 'PRODUCT_TRACKING_'.$propCode;
				break;
				case 'IS_BESTSELLER':
					$eventName = 'PRODUCT_TRACKING_'.$propCode;
				break;
				case 'IS_SPECIAL':
					$eventName = 'PRODUCT_TRACKING_'.$propCode;
				break;
				case 'NEW_PRICE':
					$eventName = 'PRODUCT_TRACKING_'.$propCode;
				break;
			}
			
			if($eventName){
				$emailFields = array(
					'OLD_PRICE' => $trackingRecord['OLD_PRICE'],
					'NEW_PRICE' => $trackingRecord['NEW_PRICE'],
					'EMAIL_TO' => $email,
					'PRODUCT_NAME' => $trackingRecord['PROPERTY_PRODUCT_ID_NAME'],
					'PRODUCT_URL' => $trackingRecord['PROPERTY_PRODUCT_ID_DETAIL_PAGE_URL']
				);
				$ev = new CEvent;				
				$ev->SendImmediate($eventName, 's1', $emailFields);				
			}
		}
	}
	
	function getUserEmail($userId){
		$arUser = CUser::GetByID($userId)->Fetch();
		if($arUser)
			return $arUser['EMAIL'];
		else
			return false;
	}
}
?>