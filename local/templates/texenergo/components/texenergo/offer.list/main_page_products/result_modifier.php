<?
if (CModule::IncludeModule("catalog")) {
	foreach ($arResult['ITEMS'] as &$item) {
    	$item['price_info'] = CPrice::GetBasePrice($item['ID']);
	}
}
?>