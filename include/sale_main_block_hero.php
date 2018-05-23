     <!-- HERO START -->
    <div class="wrapper-main-hero">
        <div class="container main-hero">
          <div class="hero-bg-cycle-pager"></div>
<? if (false) :?>
        <div class="hero-selector mainpage_banner_select">
            Анонсы
            <div class="switch"></div>
            Акции
        </div>
	       <ul id="heroSlider" class="heroSlider owl-carousel" data-switch="1">
<? endif; ?>
               <ul id="heroSlider" class="heroSlider row">
    <li class="cycle-prev"></li>
    <li class="cycle-next"></li>
			<?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "mainpage_actions",
				Array(
					"VIEW_MODE" => "TEXT",
					"SHOW_PARENT_NAME" => "Y",
					"IBLOCK_TYPE" => "banners",
					"IBLOCK_ID" => IBLOCK_ID_BANNERS_MAIN,
					"SECTION_ID" => "",
					"SECTION_CODE" => "",
					"SECTION_URL" => "",
					"COUNT_ELEMENTS" => "N",
					"TOP_DEPTH" => "1",
					"SECTION_FIELDS" => "",
					"SECTION_USER_FIELDS" => array("UF_LAYOUT"),
					"ADD_SECTIONS_CHAIN" => "Y",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "36000000",
					"CACHE_NOTES" => "",
					"CACHE_GROUPS" => "Y"
				)
			);?>
		<li class="cycle-pager"></li>

        </ul>
<? if (false) :?>
		<div class="heroSlider specialSlider hidden" data-switch="2">
			<?
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
					"ELEMENT_SORT_FIELD" => "SORT",
					"ELEMENT_SORT_ORDER" => "asc",
					"ELEMENT_SORT_FIELD2" => "",
					"ELEMENT_SORT_ORDER2" => "",
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
					"CACHE_TIME" => "3600",
					"CACHE_FILTER" => "Y",
					"CACHE_GROUPS" => "Y",
					"PAGER_TEMPLATE" => ".default",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "Y",
					"PAGER_TITLE" => "Товары",
					"PAGER_SHOW_ALWAYS" => "Y",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600",
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
<? endif; ?>
<? if (false) :?>
        <?$APPLICATION->IncludeComponent('aero:about.section', '', Array(
            'IBLOCK_ID' => IBLOCK_ID_MAIN_BLOCK,
            'CACHE_TYPE' => 'A',
            'CACHE_TIME' => 36000000
        ), false);?>
<? endif; ?>

	</div> <!-- .container -->
    </div><!-- .hero mainpage_banners -->

    <!-- HERO END -->
