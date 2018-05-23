<?
foreach($arResult["SECTION"]["PATH"] as $arPath){
	$APPLICATION->AddChainItem($arPath["NAME"],$arPath["SECTION_PAGE_URL"] );
}

$APPLICATION->AddChainItem($arResult["NAME"], '#' );
?>