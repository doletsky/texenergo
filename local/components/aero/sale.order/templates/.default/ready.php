<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="container">
	<div class="twelve">
		<h1 class="headline headline-centered">Заказ #<?=$arResult['ORDER']['ID']?> принят в обработку</h1>
	</div>
</div>

<div class="container">
	<div class="twelve">

		<section class="main main-account main-cart main-preorder">

			<?$APPLICATION->IncludeFile('/include/basket_ready_text.php');?>

			<aside class="prePromo">
				<div class="heroSlider specialSlider">
					<?
					global $specialFilter;
					$specialFilter = array(
						'<=PROPERTY_IS_SPECIAL_FROM' => date('Y-m-d H:i:s', time()),
						'>PROPERTY_IS_SPECIAL_TO' => date('Y-m-d H:i:s', time())
					);
					
					$APPLICATION->IncludeComponent(
						"bitrix:catalog.section",
						"main_actions",
						Array(
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
							"IBLOCK_TYPE" => "",
							"IBLOCK_ID" => IBLOCK_ID_PUBLICATIONS,
							"SECTION_ID" => "",
							"SECTION_CODE" => "akcii",
							"SECTION_USER_FIELDS" => array(),
							"ELEMENT_SORT_FIELD" => "RAND",
							"ELEMENT_SORT_ORDER" => "asc",
							"ELEMENT_SORT_FIELD2" => "id",
							"ELEMENT_SORT_ORDER2" => "desc",
							"FILTER_NAME" => "specialFilter",
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
							"PAGE_ELEMENT_COUNT" => "16",
							"LINE_ELEMENT_COUNT" => "4",
							"PROPERTY_CODE" => array(
								"IMG_1",
								"IMG_2",
								"IMG_3",
								"IMG_4",
								"DESCR_1",
								"DESCR_2",
								"DESCR_3",
								"DESCR_4",
								"LINK_1",
								"LINK_2",
								"LINK_3",
								"LINK_4"
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
					false
					);?>
				</div>
			</aside>			

		</section>
	</div>
</div>
