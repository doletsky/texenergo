<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? include $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/include/yt.php'; ?>

<? include $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/sidebar.php'; ?>

<section class="main main-floated">

	<?
pr("Вывод конкретной секции");
	$isVideo = false;//for video other template
	$isSpecial = false;
	
	CModule::IncludeModule('iblock');
	$dbSect = CIBlockSection::GetList(
		array(),
		array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'CODE' => $arResult['VARIABLES']['SECTION_CODE']),
		false,
		array('ID', 'DEPTH_LEVEL', 'SECTION_ID', 'XML_ID', 'LEFT_MARGIN', 'RIGHT_MARGIN')
	);
	if($sect = $dbSect->Fetch()){
		
		$sectionPath = array();
		$dbSect = CIBlockSection::GetNavChain($arParams['IBLOCK_ID'], $sect['ID'], array('XML_ID', 'ID'));
		while($sect1 = $dbSect->Fetch()){
			$sectionPath[] = array('ID' => $sect['ID'], 'XML_ID' => $sect1['XML_ID']);
		}
		$sectionPath = array_reverse($sectionPath);
		
		if($sect['DEPTH_LEVEL'] == 1){									
			if($sect['XML_ID'] == 'video'){
				$isVideo = true;
			}else if($sect['XML_ID'] == 'special'){
				$isSpecial = true;
			}
		}else{			
			$rootSect = $sectionPath[count($sectionPath) - 1];			
			if($rootSect['XML_ID'] == 'video'){
				$isVideo = true;
			}else if($sect['XML_ID'] == 'special'){
				$isSpecial = true;
			}			
		}
	}

	$sId = false;
	foreach($sectionPath as $sectId){	
	$cnt = CIBlockElement::GetList(array(), array('IBLOCK_ID' => 32, 'PROPERTY_RAZDEL' => $sectId, 'ACTIVE' => 'Y'), array());
		if($cnt > 0) $sId = $sectId;		
	}
	
	
	global $pubSliderFilter;
	$pubSliderFilter = array('PROPERTY_RAZDEL' => $sId);
	
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		'events',
		array(			
			"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
			"IBLOCK_ID" => 32,
			"ELEMENT_SORT_FIELD" => 'ACTIVE_FROM',
			"ELEMENT_SORT_ORDER" => 'DESC',			
			"PROPERTY_CODE" => $arParams['LIST_PROPERTY_CODE'],
			"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
			"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
			"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
			"INCLUDE_SUBSECTIONS" => 'Y', //$arParams["INCLUDE_SUBSECTIONS"]),
			"BY_LINK" => ($_REQUEST['set_filter'] == 'y' ? 'Y' : 'N'),
			"BASKET_URL" => $arParams["BASKET_URL"],
			"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
			"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
			"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
			"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
			"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
			"FILTER_NAME" => 'pubSliderFilter',
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_FILTER" => $arParams["CACHE_FILTER"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"SET_TITLE" => "N", //$arParams["SET_TITLE"],
			"SET_STATUS_404" => $arParams["SET_STATUS_404"],
			"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
			"PAGE_ELEMENT_COUNT" => 10,
			"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
			"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
			"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

			"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
			"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
			"PAGER_TITLE" => $arParams["PAGER_TITLE"],
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => "bottom-pagenavigation",
			"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
			"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
			"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],

			"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
			"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
			"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
			"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
			"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
			"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
			"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
			"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

			"ADD_SECTIONS_CHAIN" => "N",
			
			"SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
			"DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
			'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
			'CURRENCY_ID' => $arParams['CURRENCY_ID'],
			'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],

			'LABEL_PROP' => $arParams['LABEL_PROP'],
			'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
			'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
			'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
			'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
			'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
			'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
			'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
			'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
			'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
			'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
			'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
			'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
			'SHOW_SERIES_BLOCK' => ($_REQUEST['set_filter'] == 'y' ? 'N' : 'Y'),
			'SHOW_SPECIAL_TIMER' => 'Y',
			'DISPLAY_BANNERS' => 'Y',
		),
		$component
	);
	
	
	$bDateFilter = false;
	$dateTo = date('d.m.Y');
	$dateFrom = date('d.m.Y', time() - 86400 * 30);
	
	$dateToFilter = date('Y-m-d');
	$dateFromFilter = date('Y-m-d', time() - 86400 * 30);

	$sDateFrom = trim($_REQUEST['date_from']);
	if (strlen($sDateFrom) > 0 && $arDateFrom = ParseDateTime($sDateFrom, 'YYYY.MM.DD')) {		
		$bDateFilter = true;
		$dateFrom = $arDateFrom['DD'] . '.' . $arDateFrom['MM'] . '.' . $arDateFrom['YYYY'];
		$dateFromFilter = $arDateFrom['YYYY'] . '-' . $arDateFrom['MM'] . '-' . $arDateFrom['DD'];
	}

	$sDateTo = trim($_REQUEST['date_to']);
	if (strlen($sDateTo) > 0 && $arDateTo = ParseDateTime($sDateTo, 'YYYY.MM.DD')) {
		$bDateFilter = true;
		$dateTo = $arDateTo['DD'] . '.' . $arDateTo['MM'] . '.' . $arDateTo['YYYY'];
		$dateToFilter = $arDateTo['YYYY'] . '-' . $arDateTo['MM'] . '-' . $arDateTo['DD'];
	}

	$dateFromFilter	.= ' 00:00:00';
	$dateToFilter .= ' 11:59:59';

	global $dateFilter;
	$dateFilter = array();
	//TODO::фильтры переделать, не верно фильтрует
	if($bDateFilter){
		$dateFilter['>=DATE_ACTIVE_FROM'] = $dateFrom.' 00:00:00';
		$dateFilter['<=DATE_ACTIVE_FROM'] = $dateTo.' 23:59:59';
	}
		
	global $importantFltr;
	if($isVideo){
		$categoryTpl = 'category_video';				
	}else if($isSpecial){		
		$categoryTpl = 'category_special';
		unset($dateFilter['>=DATE_ACTIVE_FROM']);
		unset($dateFilter['<=DATE_ACTIVE_FROM']);
		
		$dateFilter = array();
		if($bDateFilter){
			$dateFilter[] = array(
				'LOGIC' => 'OR',
				array(
					'>=PROPERTY_IS_SPECIAL_FROM' => $dateFromFilter,
					'<=PROPERTY_IS_SPECIAL_FROM' => $dateToFilter		
				),
				array(
					'<PROPERTY_IS_SPECIAL_FROM' => $dateFromFilter,
					'>PROPERTY_IS_SPECIAL_TO' => $dateFromFilter		
				)
			);
		}		
	}else{		
		$categoryTpl = 'category';
		//$importantFltr = array('PROPERTY_ARTICLE_MAIN_ON_VALUE' => 'Да');
	}	

	$APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		$categoryTpl,
		array(	
			"FILTER_DATE_FROM" => $dateFrom,
			"FILTER_DATE_TO" => $dateTo,
			"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
			"IBLOCK_ID" => $arParams['IBLOCK_ID'],
			"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
			"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
			"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
			"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
			"PROPERTY_CODE" => $arParams['LIST_PROPERTY_CODE'],
			"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
			"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
			"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
			"INCLUDE_SUBSECTIONS" => 'Y',
			"BY_LINK" => ($_REQUEST['set_filter'] == 'y' ? 'Y' : 'N'),
			"BASKET_URL" => $arParams["BASKET_URL"],
			"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
			"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
			"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
			"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
			"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
			"FILTER_NAME" => 'dateFilter',
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_FILTER" => $arParams["CACHE_FILTER"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"SET_TITLE" => "Y", //$arParams["SET_TITLE"],
			"SET_STATUS_404" => $arParams["SET_STATUS_404"],
			"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
			"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
			"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
			"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
			"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

			"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
			"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
			"PAGER_TITLE" => $arParams["PAGER_TITLE"],
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => "bottom-pagenavigation",
			"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
			"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
			"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],

			"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
			"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
			"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
			"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
			"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
			"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
			"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
			"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

			"ADD_SECTIONS_CHAIN" => "Y",

			"SECTION_CODE" => $arResult['VARIABLES']['SECTION_CODE'],					
			'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
			'CURRENCY_ID' => $arParams['CURRENCY_ID'],
			'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],

			'LABEL_PROP' => $arParams['LABEL_PROP'],
			'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
			'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
			'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
			'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
			'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
			'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
			'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
			'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
			'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
			'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
			'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
			'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
			'SHOW_SERIES_BLOCK' => ($_REQUEST['set_filter'] == 'y' ? 'N' : 'Y'),
			'SHOW_SPECIAL_TIMER' => 'Y',
			'DISPLAY_BANNERS' => 'Y',
		),
		$component
	);
	?>
	
</section>