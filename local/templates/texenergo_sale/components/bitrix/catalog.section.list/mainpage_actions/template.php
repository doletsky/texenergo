<?
global $USER_FIELD_MANAGER;
$arFields = $USER_FIELD_MANAGER->GetUserFields("IBLOCK_".IBLOCK_ID_BANNERS_MAIN."_SECTION");

$layoutMatches = array();

$obEnum = new CUserFieldEnum;
$rsEnum = $obEnum->GetList(array(), array("USER_FIELD_ID" => $arFields["UF_LAYOUT "]["ID"]));
while($arEnum = $rsEnum->GetNext()){
   $layoutMatches[$arEnum['ID']] = $arEnum['VALUE'];
} 	
?>

<?foreach($arResult['SECTIONS'] as $arSection):?>
	
	<?if(!empty($arSection["UF_LAYOUT"])):?>
	
	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		"slides",
		Array(
			"LAYOUT_NUM" => $layoutMatches[$arSection["UF_LAYOUT"]],
			"ADD_PICT_PROP" => "-",
			"LABEL_PROP" => "-",
			"PRODUCT_SUBSCRIPTION" => "N",
			"SHOW_DISCOUNT_PERCENT" => "N",
			"SHOW_OLD_PRICE" => "N",
			"MESS_BTN_BUY" => "Купить",
			"MESS_BTN_ADD_TO_BASKET" => "В корзину",
			"MESS_BTN_SUBSCRIBE" => "Подписаться",
			"MESS_BTN_DETAIL" => "Подробнее",
			"MESS_NOT_AVAILABLE" => "Нет в наличии",
			"AJAX_MODE" => "N",
			"IBLOCK_TYPE" => "banners",
			"IBLOCK_ID" => IBLOCK_ID_BANNERS_MAIN,
			"SECTION_ID" => $arSection['ID'],
			"SECTION_CODE" => "",
			"SECTION_USER_FIELDS" => array(),
			"ELEMENT_SORT_FIELD" => "sort",
			"ELEMENT_SORT_ORDER" => "asc",
			"ELEMENT_SORT_FIELD2" => "id",
			"ELEMENT_SORT_ORDER2" => "desc",
			"FILTER_NAME" => "arrFilter",
			"INCLUDE_SUBSECTIONS" => "Y",
			"SHOW_ALL_WO_SECTION" => "N",
			"SECTION_URL" => "",
			"DETAIL_URL" => "",
			"BASKET_URL" => "/personal/basket.php",
			"ACTION_VARIABLE" => "action",
			"PRODUCT_ID_VARIABLE" => "id",
			"PRODUCT_QUANTITY_VARIABLE" => "quantity",
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"SECTION_ID_VARIABLE" => "SECTION_ID",
			"META_KEYWORDS" => "-",
			"META_DESCRIPTION" => "-",
			"BROWSER_TITLE" => "-",
			"ADD_SECTIONS_CHAIN" => "N",
			"DISPLAY_COMPARE" => "N",
			"SET_TITLE" => "N",
			"SET_STATUS_404" => "N",
			"PAGE_ELEMENT_COUNT" => "30",
			"LINE_ELEMENT_COUNT" => "3",
			"PROPERTY_CODE" => array(				
				"DESCR",				
				"LINK"
			),
			"OFFERS_LIMIT" => "5",
			"PRICE_CODE" => array(),
			"USE_PRICE_COUNT" => "N",
			"SHOW_PRICE_COUNT" => "1",
			"PRICE_VAT_INCLUDE" => "Y",
			"PRODUCT_PROPERTIES" => array(),
			"USE_PRODUCT_QUANTITY" => "N",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"PAGER_TEMPLATE" => ".default",
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"PAGER_TITLE" => "Товары",
			"PAGER_SHOW_ALWAYS" => "Y",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "Y",
			"HIDE_NOT_AVAILABLE" => "N",
			"CONVERT_CURRENCY" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N"
		),
	$component
	);?>

	<?endif;?>

<?endforeach;?>