<?
CModule::IncludeModule('iblock');

$arSalesFilter = Array(
	"USER_ID" => $USER->GetID(),
);

$orderIds = array();
$rsSales = CSaleOrder::GetList(array("DATE_INSERT" => "ASC"), $arSalesFilter);
while ($arSales = $rsSales->Fetch()) {
	$orderIds[] = $arSales["ACCOUNT_NUMBER"];    
}

$arFilter = Array(
	'IBLOCK_ID' => IBLOCK_ID_MESSAGES,
	'!PROPERTY_READ_VALUE' => 'Y',
	array(
		'LOGIC' => 'OR',
		array('PROPERTY_ORDER_ID' => $orderIds),
		array('PROPERTY_USER_ID' => $USER->GetID()),
	)
);


$events = CIBlockElement::GetList(
	array(
		'DATE_INSERT' => 'desc'
	),
	$arFilter,
	false,
	false,
	array('ID')	
);

$arResult['EVENTS_CNT'] = $events->SelectedRowsCount();

foreach ($arResult as $arItem){
	if($arItem['PARAMS']['events'] == 'y' && $arItem["SELECTED"]){
		
		$arProperty = CIBlockPropertyEnum::GetList(array(), Array("IBLOCK_ID" => IBLOCK_ID_MESSAGES, "CODE" => "READ", "VALUE" => "Y"))->Fetch();		
		while($event = $events->Fetch()){
			CIBlockElement::SetPropertyValues($event['ID'], IBLOCK_ID_MESSAGES, $arProperty['ID'], 'READ');
		}
		$arResult['EVENTS_CNT'] = 0;
		break;
	}
}
?>