<?
class PublicationBackLink{
	public function addProductsBackLink($iblockId, $elementId){
		$srcIblock = $iblockId;
		if($iblockId == IBLOCK_ID_PUBLICATIONS){			
			$targetIblock = IBLOCK_ID_CATALOG;
			$targetProperty = 'PUBLICATIONS';
			$srcProperty = 'PRODUCTS';
		}else{
			$targetIblock = IBLOCK_ID_PUBLICATIONS;
			$targetProperty = 'PRODUCTS';
			$srcProperty = 'PUBLICATIONS';
		}
		
		$iLinkedWith = self::getLinkedElements($srcIblock, $elementId, $srcProperty);
		
		$theyLinkedINot = self::getElementLinkedWithMeINot($targetIblock, $elementId, $iLinkedWith, $targetProperty);								
		foreach($theyLinkedINot as $id){
			self::removeLink($targetIblock, $id, $elementId, $targetProperty);
		}
		
		$iLinkedTheyNot = self::getElementILinkedTheyNot($srcIblock, $elementId, $iLinkedWith, $srcProperty);		
		foreach($iLinkedTheyNot as $id){
			self::addLink($srcIblock, $id, $elementId, $srcProperty);
		}
		
	}

	public function addBackLink($iblockId, $elementId){				
		$iLinkedWith = self::getLinkedElements($iblockId, $elementId, 'LINKED_WITH');
		
		/*Удалим себя, если кто-то случайно поставил связь с самим собой*/
		$keyToRemove = array_search($elementId, $iLinkedWith);
		if($keyToRemove !== false){
			unset($iLinkedWith[$keyToRemove]);
			CIBlockElement::SetPropertyValues($elementId, $iblockId, $iLinkedWith, 'LINKED_WITH');			
		}
		
		$theyLinkedINot = self::getElementLinkedWithMeINot($iblockId, $elementId, $iLinkedWith, 'LINKED_WITH');								
		foreach($theyLinkedINot as $id){
			self::removeLink($iblockId, $id, $elementId, 'LINKED_WITH');
		}
		
		$iLinkedTheyNot = self::getElementILinkedTheyNot($iblockId, $elementId, $iLinkedWith, 'LINKED_WITH');		
		foreach($iLinkedTheyNot as $id){
			self::addLink($iblockId, $id, $elementId, 'LINKED_WITH');
		}		
	}
	
	/*элементы, связанныес $myId, но с которыми не связан он*/
	private function getElementLinkedWithMeINot($iblockId, $myId, $iLinkedWith, $propName){
		$linked = array();
		
		$arFilter = array('IBLOCK_ID' => $iblockId, 'PROPERTY_'.$propName => $myId, '!ID' => $myId);
		if(count($iLinkedWith) != 0){		
			$arFilter['!ID'] = array_merge($myId, $iLinkedWith);
		}		
		$dbElems = CIBlockElement::GetList(array(), $arFilter, false, false, array('ID'));
		while($elem = $dbElems->Fetch()){
			$linked[] = $elem['ID'];
		}
		return $linked;
	}
	
	/*элементы, с которыми $myId связан, но они не связаны с ним*/
	private function getElementILinkedTheyNot($iblockId, $myId, $iLinkedWith, $propName){
		$linked = array();
		
		if(count($iLinkedWith) != 0){
			$arFilter = array('IBLOCK_ID' => $iblockId, '!PROPERTY_'.$propName => $myId, 'ID' => $iLinkedWith, '!ID' => $myId);					
			$dbElems = CIBlockElement::GetList(array(), $arFilter, false, false, array('ID'));
			while($elem = $dbElems->Fetch()){
				$linked[] = $elem['ID'];
			}
		}
		
		return $linked;
	}
	
	private function getLinkedElements($iblockId, $elementId, $propName){
		$linkedPubs = array();
		$dbProps = CIBlockElement::GetProperty($iblockId, $elementId, array(), Array("CODE" => $propName));
		while($arProps = $dbProps->Fetch()){
			if(!empty($arProps['VALUE'])){
				$linkedPubs[] = $arProps['VALUE'];
			}
		}
		return $linkedPubs;
	}
	
	private function addLink($iblockId, $elementId, $idToAdd, $propName){
		$linkedElements = self::getLinkedElements($iblockId, $elementId);		
		if(!in_array($idToAdd, $linkedElements)){
			$linkedElements[] = $idToAdd;
			CIBlockElement::SetPropertyValues($elementId, $iblockId, $linkedElements, $propName);
		}
	}
	
	private function removeLink($iblockId, $elementId, $idToRemove, $propName){
		$linkedElements = self::getLinkedElements($iblockId, $elementId);
		$keyToRemove = array_search($idToRemove, $linkedElements);
		if($keyToRemove !== false){
			unset($linkedElements[$keyToRemove]);			
			CIBlockElement::SetPropertyValues($elementId, $iblockId, $linkedElements, $propName);
		}		
	}
}
?>