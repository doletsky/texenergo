<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arResult['ITEMS'] = array();
if($USER->IsAuthorized()){
	$arResult['ITEMS'] = CProductTracking::getUserTrackingRecords($USER->GetID());	
}

$this->IncludeComponentTemplate();
