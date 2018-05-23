<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

CModule::IncludeModule('iblock');

if($_POST['add_reclamation'] == 'y'){

	if(is_array($_FILES['description_file'])){
		$arFile = CIBlock::makeFileArray($_FILES['description_file']);	
	}else{
		$arFile = false;
	}
	
	$productsProp = array();
	$realizations = array();
	
	foreach($_POST['products'] as $realizationNum => $products){
			
		if($realizationNum !== 0){
			
			$rNumOriginal = $realizationNum;
			
			if($_POST['type'] == 'peresort' && $realizationNum == '-' && !empty($_POST['realize_number'])){				
				$realizationNum = $_POST['realize_number'];
			}
			
			if($realizationNum != '-'){
				$realizations[] = $realizationNum;
			}
			
			//В поле количество передается кол-во по факту, считаем разницу по документам и по факту
			if($_POST['type'] == 'nedostacha' || $_POST['type'] == 'peresort'){
				if($realizationNum != '-'){
					$realizationInfo = $this->getRealizationInfo($realizationNum);
				}
			}
			
			if(!is_array($products)){
				$products = array($products);
			}
			
			foreach($products as $productId){
				$serials = implode(';', $_POST['serial'][$realizationNum][$productId]);
				if($rNumOriginal == '-'){
					$productName = $_POST['products_name'][$rNumOriginal][$productId];
				}else{
					$productName = $this->getProductName($productId);
				}
				
				if($_POST['type'] == 'nedostacha' || $_POST['type'] == 'peresort'){
					if($rNumOriginal == '-'){
						$quantity = $_POST['quantity'][$rNumOriginal][$productId];
					}else{
						$quantityDocs = $realizationInfo['PRODUCTS'][$productId]['QUANTITY'];
						$quantity = abs($quantityDocs - $_POST['quantity'][$realizationNum][$productId]);
					}
				}else{
					$quantity = $_POST['quantity'][$realizationNum][$productId];
				}
				
				$productsProp[] = array(
					'REALIZ_NUM' => $realizationNum,
					'PROD_ID' => $productId,
					'NAME' => $productName,
					'QUANTITY' => $quantity,
					'S_NUM' => $serials
				);
			}
		}
	}
	
	$types = $this->getAvailableTypes();

	$arFields = array(
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'NAME' => 'Рекламация '.date('d-m-Y H:i'),
		'PROPERTY_VALUES' => array(
			'PRODUCTS' => $productsProp,
			'DESCRIPTION' => array('VALUE' => array('TEXT' => $_POST['description'])),
			'TYPE' => $types[$_POST['type']]['ID'],
			'CASE' => array('VALUE' => array('TEXT' => $_POST['case'])),
			'DESCRIPTION_FILE' => $arFile,
			'USER_ID' => $USER->GetID()
		)	
	);
	
	$el = new CIBlockElement;
	$reclId = $el->Add($arFields);
	
	if($reclId){
		if(count($realizations) > 0){
			$realizations = array_unique($realizations);
			foreach($realizations as $rId){
				$realizationInfo = $this->getRealizationInfo($rId);
				
				$messageFields = array(
					'IBLOCK_ID' => IBLOCK_ID_MESSAGES,
					'NAME' => 'Создана новая рекламация',
					'PROPERTY_VALUES' => array(
						'DATE' => date('d.m.Y H:i:s'),
						'ORDER_ID' => $realizationInfo['ORDER_ID'],
						'INVOICE_ID' => $realizationInfo['INVOICE_ID'],
						'MESSAGE' => 'По реализации № '.$rId.' Тип: '.$types[$_POST['type']]['VALUE'].'.'
						
					)
				);
				$el = new CIBlockElement;		
				$el->Add($messageFields);
				
				break;//сейчас одна рекламация на одну реализацию
			}
		}else{
			$messageFields = array(
				'IBLOCK_ID' => IBLOCK_ID_MESSAGES,
				'NAME' => 'Создана новая рекламация',
				'PROPERTY_VALUES' => array(
					'DATE' => date('d.m.Y H:i:s'),
					'USER_ID' => $USER->GetID(),
					'MESSAGE' => 'Тип: '.$types[$_POST['type']]['VALUE'].'.'
					
				)
			);
			$el = new CIBlockElement;		
			$el->Add($messageFields);
		}
		
		LocalRedirect('/personal/orders/reclamation/');
		die();
	}else{
		$arResult = array('status' => 'fail', 'msg' => $el->LAST_MESSAGE);
	}

}

$this->IncludeComponentTemplate();
