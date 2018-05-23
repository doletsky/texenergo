<?
foreach($arResult["RUBRICS"] as $itemID => $itemValue){
	if($itemValue['ID'] != 1)//news
		unset($arResult["RUBRICS"][$itemID]);
}