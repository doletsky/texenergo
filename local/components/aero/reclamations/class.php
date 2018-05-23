<?
class CAeroReclamations extends CBitrixComponent{
	function getProductName($id){
		$dbElems = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_CATALOG, 'ID' => $id), false, false, array('NAME'));
		if($elem = $dbElems->Fetch()){
			return $elem['NAME'];
		}
		return false;
	}
	
	function getAvailableTypes(){
		$types = array();
		$dbEnum = CIBlockPropertyEnum::GetList(array(), Array("IBLOCK_ID"=>$this->arParams['IBLOCK_ID'], "CODE"=>"TYPE"));
		while($arEnum = $dbEnum->Fetch()){
			$types[$arEnum['XML_ID']] = $arEnum;
		}
		return $types;
	}
	
	function getRealizationInfo($rNum){
		$dbElems = CIBlockElement::GetList(array(), array("IBLOCK_ID" => IBLOCK_ID_SALES, "XML_ID" => $rNum), false, false, array('ID', 'IBLOCK_ID', 'PROPERTY_INVOICE_ID', 'PROPERTY_INVOICE_ID.PROPERTY_ORDER_ID', 'PROPERTY_BASKET_ITEMS', 'PROPERTY_DATE'));
		if($elem = $dbElems->Fetch()){
			$items = array();
			foreach($elem['PROPERTY_BASKET_ITEMS_VALUE'] as $arProduct){
				$items[$arProduct['ID']] = $arProduct;
			}
			
			return array(
				'ORDER_ID' => $elem['PROPERTY_INVOICE_ID_PROPERTY_ORDER_ID_VALUE'],
				'INVOICE_ID' => $elem['PROPERTY_INVOICE_ID_VALUE'],
				'PRODUCTS' => $items
			);
		}
		return false;
	}	
}
?>