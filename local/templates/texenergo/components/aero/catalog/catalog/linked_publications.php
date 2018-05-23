<?
global $APPLICATION;
$publicationHtml = false;

CModule::IncludeModule('iblock');
$dbElems = CIBlockElement::GetList(
	array(), 
	array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'CODE' => $arResult["VARIABLES"]["ELEMENT_CODE"]), 
	false, 
	false, 
	array('ID', 'IBLOCK_SECTION_ID')
);
if($currentProduct = $dbElems->Fetch()){
	

	/**
	 * Собираем ID публикаций в значение св-ва
	 */
	$publicationsId = Array();
	$rsPublications = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => IBLOCK_ID_PUBLICATIONS, 'ACTIVE' => 'Y', 'PROPERTY_PRODUCTS' => $currentProduct['ID']), false, false, Array('ID'));
	while ($arPublication = $rsPublications->Fetch()) {
		$publicationsId[] = $arPublication['ID'];
	}

	/**
	 * Публикации по привязке публикация - символьный код раздела (PRODUCTS_SECTIONS)
	 */
	$arSection = CIBlockSection::GetByID($currentProduct['IBLOCK_SECTION_ID'])->Fetch();
	$rsPublications = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => IBLOCK_ID_PUBLICATIONS, 'ACTIVE' => 'Y', 'PROPERTY_PRODUCTS_SECTIONS' => $arSection['CODE']), false, false, Array('ID'));
	while ($arPublication = $rsPublications->Fetch()) {
		$publicationsId[] = $arPublication['ID'];
	}
	$publicationsId = array_unique($publicationsId);

	if(count($publicationsId) > 0){	

		global $arPublicFilter;
		$arPublicFilter = Array(
			"ACTIVE" => "Y",
			"ID" => $publicationsId
		);

		ob_start();
		$APPLICATION->IncludeComponent(
			"bitrix:news.list",
			"publication",
			Array(                        
				"DISPLAY_DATE" => "Y",
				"DISPLAY_NAME" => "Y",
				"DISPLAY_PICTURE" => "Y",
				"DISPLAY_PREVIEW_TEXT" => "N",
				"AJAX_MODE" => "N",
				"IBLOCK_TYPE" => "shop_articles",
				"IBLOCK_ID" => IBLOCK_ID_PUBLICATIONS,
				"NEWS_COUNT" => "1000",
				"SORT_BY1" => "ACTIVE_FROM",
				"SORT_ORDER1" => "DESC",
				"SORT_BY2" => "SORT",
				"SORT_ORDER2" => "ASC",
				"FILTER_NAME" => "arPublicFilter",
				"FIELD_CODE" => array(),
				"PROPERTY_CODE" => array("GOODS_ART"),
				"CHECK_DATES" => "Y",
				"DETAIL_URL" => "",
				"PREVIEW_TRUNCATE_LEN" => "",
				"ACTIVE_DATE_FORMAT" => "d.m.Y",
				"SET_TITLE" => "N",
				"SET_STATUS_404" => "N",
				"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
				"ADD_SECTIONS_CHAIN" => "N",
				"HIDE_LINK_WHEN_NO_DETAIL" => "N",
				"PARENT_SECTION" => "",
				"PARENT_SECTION_CODE" => "",
				"INCLUDE_SUBSECTIONS" => "Y",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36000000",
				"CACHE_FILTER" => "N",
				"CACHE_GROUPS" => "Y",
				"PAGER_TEMPLATE" => ".default",
				"DISPLAY_TOP_PAGER" => "N",
				"DISPLAY_BOTTOM_PAGER" => "Y",
				"PAGER_TITLE" => "",
				"PAGER_SHOW_ALWAYS" => "Y",
				"PAGER_DESC_NUMBERING" => "N",
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
				"PAGER_SHOW_ALL" => "Y",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N"
			),
			false
		);
		$publicationHtml = ob_get_contents();
		ob_end_clean();
	}
}
?>