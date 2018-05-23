<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<div class="twelve">
    <h1 class="headline">
        Все результаты поиска
        <span class="searchQuery">
            (<?=$_REQUEST['q']; ?>)
            <? /*<em class="searchQuantity"><?= count($arResult["SEARCH"]); ?></em>*/ ?>
        </span>
    </h1>
    <aside class="share">
        <ul>
            <li><img src="<?= SITE_TEMPLATE_PATH; ?>/img/catalog/rss.png" alt="Подписаться на рассылку"></li>
            <li><img src="<?= SITE_TEMPLATE_PATH; ?>/img/catalog/share.png" alt="Поделиться с друзьями"></li>
        </ul>
    </aside>
</div>


<div class="three three-nomargin">
    <?	
	/* $APPLICATION->IncludeComponent(
        "aero:search.sections",
        "",
        Array(
            "IBLOCK_CODE" => 'catalog',
            "IBLOCK_ID" => IBLOCK_ID_CATALOG,
            'ALL_LABEL' => 'все товары',
            "QUERY" => $_REQUEST['q'],
            "CACHE_TYPE" => $arParams['CACHE_TYPE'],
            "CACHE_TIME" => $arParams['CACHE_TIME'],
            "RESTART" => $arParams["RESTART"] ? 'Y' : 'N',
            "NO_WORD_LOGIC" => $arParams["NO_WORD_LOGIC"] ? 'Y' : 'N',
            "CHECK_DATES" => $arParams["CHECK_DATES"] ? 'Y' : 'N',
            "arrFILTER" => array('iblock_catalog' => IBLOCK_ID_CATALOG),
        ),
        false
    ); */?>
	
	<?$APPLICATION->ShowViewContent('search_sections_tree');?>
	
</div>

<section class="main main-floated">
	
	<?
	$arElements = $APPLICATION->IncludeComponent(
		"bitrix:search.page",
		".default",
		Array(			
			"RESTART" => $arParams["RESTART"],
			"NO_WORD_LOGIC" => $arParams["NO_WORD_LOGIC"],
			"USE_LANGUAGE_GUESS" => $arParams["USE_LANGUAGE_GUESS"],
			"CHECK_DATES" => $arParams["CHECK_DATES"],
			"arrFILTER" => array("iblock_".$arParams["IBLOCK_TYPE"]),
			"arrFILTER_iblock_".$arParams["IBLOCK_TYPE"] => array($arParams["IBLOCK_ID"]),
			"USE_TITLE_RANK" => "N",
			"DEFAULT_SORT" => "rank",
			"FILTER_NAME" => $arParams["FILTER_NAME"],
			"SHOW_WHERE" => "N",
			"arrWHERE" => array(),
			"SHOW_WHEN" => "N",
			"PAGE_RESULT_COUNT" => 50,
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "N",
			"PAGER_TITLE" => "",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => "N",
		),
		$component
	);
	?>

    <? if(!empty($arElements) && is_array($arElements)): ?>
	
		<nav class="sort">
			<span class="text">Сортировка:</span>
            <select class="j-catalog-sort-id j-sort-select product-sort-top">
                <? foreach ($arResult['SORT_FIELDS'] as $arField): ?>
                    <option
                        value="<?= $arField['CODE']; ?>" <?= ($arResult['SORT_FIELD'] == $arField['CODE'] ? 'selected' : ''); ?>>
                        <?= $arField['NAME']; ?>
                    </option>
                <? endforeach; ?>
            </select>

			<span class="text">Отображение товаров:</span>
			<select class="j-catalog-view-id j-goods-full-view j-sort-select product-sort-top">
				<? foreach ($arResult['CATALOG_SECTION_VIEWS'] as $sVal => $sView): ?>
					<option
						value="<?= $sVal; ?>" <?= ($arResult['CATALOG_SECTION_VIEW'] == $sVal ? 'selected' : ''); ?>>
						<?= $sView; ?>
					</option>
				<? endforeach; ?>
			</select>

		</nav>

		<?		
		$GLOBALS[$sSectionFilterName] = Array(
			'=ID' => $arElements,
		);
		?>


		<?$APPLICATION->IncludeComponent(
			"texenergo:catalog.section",
			$arResult['CATALOG_SECTION_VIEW'],
			array(
				"SHOW_SEARCH_TREE" => "Y",
				"ELEMENT_SORT_FIELD" => $arResult["ELEMENT_SORT_FIELD"],
				"ELEMENT_SORT_ORDER" => $arResult["ELEMENT_SORT_ORDER"],
				"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
				"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
				"IBLOCK_TYPE" => $arParams["IBLOCK_CODE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"FIELD_CODE" => Array("DETAIL_PICTURE"),
				"PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
				"INCLUDE_SUBSECTIONS" => 'Y',
				"BY_LINK" => 'Y',
				"BASKET_URL" => '/basket/',
				"ACTION_VARIABLE" => 'action',
				"FILTER_NAME" => $sSectionFilterName,
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_FILTER" => $arParams["CACHE_FILTER"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"SET_TITLE" => "N",
				"SET_STATUS_404" => 'N',
				"DISPLAY_COMPARE" => 'Y',
				"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
				"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
				"PRICE_CODE" => $arParams["PRICE_CODE"],
				"USE_PRICE_COUNT" => "N",
				"SHOW_PRICE_COUNT" => "N",

				"PRICE_VAT_INCLUDE" => "N",
				"USE_PRODUCT_QUANTITY" => "N",
				"PRODUCT_PROPERTIES" => Array(),

				"DISPLAY_TOP_PAGER" => "N",
				"DISPLAY_BOTTOM_PAGER" => "N",
				"PAGER_TITLE" => "Товары",
				"PAGER_SHOW_ALWAYS" => "Y",
				"PAGER_DESC_NUMBERING" => "N",
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
				"PAGER_SHOW_ALL" => "Y",
				"PAGER_TEMPLATE" => "bottom-pagenavigation",

				"OFFERS_CART_PROPERTIES" => array(),
				"OFFERS_FIELD_CODE" => array(),
				"OFFERS_SORT_FIELD" => "sort",
				"OFFERS_SORT_ORDER" => "asc",
				"OFFERS_SORT_FIELD2" => "id",
				"OFFERS_SORT_ORDER2" => "desc",
				"OFFERS_LIMIT" => 5,

				"ADD_SECTIONS_CHAIN" => "N",

				"SECTION_ID" => 0,
				'CONVERT_CURRENCY' => "N",
				'CURRENCY_ID' => "RUB",
				'SHOW_SPECIAL_TIMER' => 'Y',

			),
			$component
		);?>
       
    <? else: ?>
        <? ShowNote(GetMessage("SEARCH_NOTHING_TO_FOUND")); ?>
    <?endif; ?>

</section>