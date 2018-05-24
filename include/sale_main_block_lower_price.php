<style>
  .main-lower-price {
    position:relative;
  }
	.product_item__padd:hover{box-shadow:0 2px 21px 0 rgba(231,231,231,.68)}.product_item__padd .inner{padding:10px;box-sizing:border-box;margin:0 10px 0 0}.product_item__header{padding:10px 5px 0 0;height:70px;max-height:70px;overflow:visible}.product_item__vendor-code{text-align:right;margin-bottom:5px}.product_item__reference span,.product_item__vendor-code span{color:#000}.product_item__reference,.product_item__vendor-code{color:#7c7c7c;padding:0}.product_item__img{height:135px;margin-bottom:15px}.product_item__price{margin-top:10px;background-color:#efefef;height:37px;padding-top:10px;border-radius:5px;white-space:nowrap}.product_item{position:relative}.inner{padding:10px}.product_item___disc{display:block;position:absolute;top:20px;right:15px;color:#e79523}.lower-price__bottom_nav .pagination{margin-top:5px}.product_item__price .old-price{text-align:right}.lower-price-CartButtons{padding:0;margin-top:0;text-align:center;display:block}.animated-img{position:absolute;left:0;top:0;z-index:10000;-webkit-transform:scale(.2);-moz-transform:scale(.2);-o-transform:scale(.2);-ms-transform:scale(.2);margin:-53px 0 0 -30px;max-width:60px}.round_nav{border-radius:50%;background-color:#d9d9d9;color:#fff;width:22px;height:21px;display:inline-block;text-align:center;vertical-align:middle}.round_nav:hover{background-color:#4e4e4e}.lower-price-bottom-row{margin-top:10px;height:30px}.lower-price-bottom-row .lower-price-stars{padding-right:0}.lower-price-bottom-row .hr-before{padding:0}.lower-price-bottom-row .hr-before:before{content:'';display:inline-block;vertical-align:middle;box-sizing:border-box;position:absolute;width:7px;top:10px;right:23px;height:1px;background:#ddd;border-width:0 10px}.lower-price-bottom-row .button-desc{text-align:right;padding-right:0}.lower-price-bottom-row .button-desc div{background-color:#d9d9d9;height:30px;width:90px;padding-right:0;border-radius:5px;text-align:center;vertical-align:middle;line-height:2;display:none}
	.product_item__img .img-mini{width: 50px;background-position: 50% 50%;background-size: contain;background-repeat: no-repeat;height: 50px;text-align: right;float: right;clear: both;border-radius: 5px;border: 1px solid #ddd;margin-bottom: 5px;}
  .lower-price-wrapper {
   /* width:23%;
    float:right;*/
  }
  .lower-price__bottom_nav {
    position: absolute;
    bottom: -25px;
    left: 0;
  }
  .pagination {
    margin:0;
    padding:0;
  }
</style>

<div class="wrapper-main-lower-price">
	<div class="main-lower-price container">
		<div class="row">
			<h2 class="main-blk__header">Товары с выгодной ценой</h2>
		</div>
		<div class="row" id="lower-price-main-container-row" style="visibility: hidden" >
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xlg-10">
                <div class="row owl-slider-salepage">
            <?if(1):
            $arEls=array();
            $arFilter = Array(
                "IBLOCK_ID"=>18,
                "ACTIVE"=>"Y",
                "PROPERTY_IS_SPECIAL_VALUE"=>"Y"
            );
            $res = CIBlockElement::GetList(Array("RAND"=>"ASC"), $arFilter, false, Array("nPageSize"=>15), array("ID","IBLOCK_ID","IBLOCK_SECTION_ID","PROPERTY_IS_SPECIAL"));
            while($ar_fields = $res->GetNext())
            {

                $arEls[$ar_fields["IBLOCK_SECTION_ID"]][]=$ar_fields["ID"];
            }
            foreach ($arEls as $secId=>$arIds): echo "<!-- sec=".$secId." -->";
                global $arrFilter;
                $arrFilter=array("ID"=>$arIds);

                ?>
                <?$APPLICATION->IncludeComponent(
                "texenergo:catalog.section",
                "salepage_catalog_is_special",
                Array(
                    "ACTION_VARIABLE" => "action",
                    "ADD_PICT_PROP" => "MORE_PHOTO",
                    "ADD_PROPERTIES_TO_BASKET" => "Y",
                    "ADD_SECTIONS_CHAIN" => "N",
                    "ADD_TO_BASKET_ACTION" => "ADD",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_ADDITIONAL" => "",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "BACKGROUND_IMAGE" => "UF_BACKGROUND_IMAGE",
                    "BASKET_URL" => "/personal/basket.php",
                    "BRAND_PROPERTY" => "BRAND_REF",
                    "BROWSER_TITLE" => "-",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "Y",
                    "CACHE_TIME" => "36000000",
                    "CACHE_TYPE" => "N",
                    "COMPATIBLE_MODE" => "Y",
                    "CONVERT_CURRENCY" => "Y",
                    "CURRENCY_ID" => "RUB",
                    "CUSTOM_FILTER" => "",
                    "DATA_LAYER_NAME" => "dataLayer",
                    "DETAIL_URL" => "",
                    "DISABLE_INIT_JS_IN_COMPONENT" => "N",
                    "DISCOUNT_PERCENT_POSITION" => "bottom-right",
                    "DISPLAY_BOTTOM_PAGER" => "N",
                    "DISPLAY_TOP_PAGER" => "N",
                    "ELEMENT_SORT_FIELD" => "RAND",
                    "ELEMENT_SORT_FIELD2" => "id",
                    "ELEMENT_SORT_ORDER" => "asc",
                    "ELEMENT_SORT_ORDER2" => "desc",
                    "ENLARGE_PRODUCT" => "PROP",
                    "ENLARGE_PROP" => "NEWPRODUCT",
                    "FILTER_NAME" => "arrFilter",
                    "HIDE_NOT_AVAILABLE" => "N",
                    "HIDE_NOT_AVAILABLE_OFFERS" => "N",
                    "IBLOCK_ID" => 18,
                    "IBLOCK_TYPE" => "catalog",
                    "INCLUDE_SUBSECTIONS" => "N",
                    "LABEL_PROP" => array("NEWPRODUCT"),
                    "LABEL_PROP_MOBILE" => array(),
                    "LABEL_PROP_POSITION" => "top-left",
                    "LAZY_LOAD" => "Y",
                    "LINE_ELEMENT_COUNT" => "3",
                    "LOAD_ON_SCROLL" => "N",
                    "MESSAGE_404" => "",
                    "MESS_BTN_ADD_TO_BASKET" => "В корзину",
                    "MESS_BTN_BUY" => "Купить",
                    "MESS_BTN_DETAIL" => "Подробнее",
                    "MESS_BTN_LAZY_LOAD" => "Показать ещё",
                    "MESS_BTN_SUBSCRIBE" => "Подписаться",
                    "MESS_NOT_AVAILABLE" => "Нет в наличии",
                    "META_DESCRIPTION" => "-",
                    "META_KEYWORDS" => "-",
                    "OFFERS_CART_PROPERTIES" => array(""),
                    "OFFERS_FIELD_CODE" => array("",""),
                    "OFFERS_LIMIT" => "5",
                    "OFFERS_PROPERTY_CODE" => array(""),
                    "OFFERS_SORT_FIELD" => "sort",
                    "OFFERS_SORT_FIELD2" => "id",
                    "OFFERS_SORT_ORDER" => "asc",
                    "OFFERS_SORT_ORDER2" => "desc",
                    "OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
                    "OFFER_TREE_PROPS" => array("COLOR_REF","SIZES_SHOES","SIZES_CLOTHES"),
                    "PAGER_BASE_LINK_ENABLE" => "N",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_TEMPLATE" => ".default",
                    "PAGER_TITLE" => "Товары",
                    "PAGE_ELEMENT_COUNT" => "15",
                    "PARTIAL_PRODUCT_PROPERTIES" => "N",
                    "PRICE_CODE" => array("BASE"),
                    "PRICE_VAT_INCLUDE" => "Y",
                    "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
                    "PRODUCT_DISPLAY_MODE" => "Y",
                    "PRODUCT_ID_VARIABLE" => "id",
                    "PRODUCT_PROPERTIES" => array("NEWPRODUCT","MATERIAL"),
                    "PRODUCT_PROPS_VARIABLE" => "prop",
                    "PRODUCT_QUANTITY_VARIABLE" => "",
                    "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':true}]",
                    "PRODUCT_SUBSCRIPTION" => "Y",
                    "PROPERTY_CODE" => array(),
                    "PROPERTY_CODE_MOBILE" => array(),
                    "RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
                    "RCM_TYPE" => "personal",
                    "SECTION_CODE" => "",
                    "SECTION_ID" => $secId,
                    "SECTION_ID_VARIABLE" => "SECTION_ID",
                    "SECTION_URL" => "",
                    "SECTION_USER_FIELDS" => array("",""),
                    "SEF_MODE" => "N",
                    "SET_BROWSER_TITLE" => "Y",
                    "SET_LAST_MODIFIED" => "N",
                    "SET_META_DESCRIPTION" => "Y",
                    "SET_META_KEYWORDS" => "Y",
                    "SET_STATUS_404" => "N",
                    "SET_TITLE" => "Y",
                    "SHOW_404" => "N",
                    "SHOW_ALL_WO_SECTION" => "N",
                    "SHOW_CLOSE_POPUP" => "N",
                    "SHOW_DISCOUNT_PERCENT" => "Y",
                    "SHOW_FROM_SECTION" => "N",
                    "SHOW_MAX_QUANTITY" => "N",
                    "SHOW_OLD_PRICE" => "N",
                    "SHOW_PRICE_COUNT" => "1",
                    "SHOW_SLIDER" => "Y",
                    "SLIDER_INTERVAL" => "3000",
                    "SLIDER_PROGRESS" => "N",
                    "TEMPLATE_THEME" => "blue",
                    "USE_ENHANCED_ECOMMERCE" => "Y",
                    "USE_MAIN_ELEMENT_SECTION" => "N",
                    "USE_PRICE_COUNT" => "N",
                    "USE_PRODUCT_QUANTITY" => "N"
                )
            );?>

            <?endforeach;?>
                </div>
    </div>
            <?else:?>
			<div class="product_item product_item__padd col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xlg-2">
				<div class="inner">
					<h3 class="product_item__header"><a href="https://www.texenergo.ru/catalog/item.html/te00340324">Выключатель автоматический ВА 5731-340010 100 А</a></h3>
					<span class="product_item___disc">%</span>
					<div class="product_item__vendor-code">Артикул: <span class="vendor-code">SAV56-100</span></div>
					<div class="product_item__img">
						<!--<div class="row">
							<div class="col-xs-6">-->
                <a href="https://www.texenergo.ru/catalog/item.html/te00340324">
								<img class="img-responsive"
									 src="https://www.texenergo.ru/upload/resize_cache/thumbnail_photo/70/702e6165-976b-11e2-8daf-0025906565cb/photos_702e6165-976b-11e2-8daf-0025906565cb_001_130_130.jpg"
									 alt="Наше оборудование"
									 id="product_picture_459548" />
                </a>
							<!--</div>
							<div class="col-xs-6">
								<div class="img-mini img-responsive" id="product_picture_459548" style="background-image: url(https://www.texenergo.ru/upload/resize_cache/thumbnail_photo/7f/7f291be8-a4f8-4aba-b71e-2357c8756dec/photos_7f291be8-a4f8-4aba-b71e-2357c8756dec_001_130_130.jpg)"></div>
								<div class="img-mini img-responsive" id="product_picture_459548" style="background-image: url(https://www.texenergo.ru/upload/resize_cache/thumbnail_photo/7f/7f291be8-a4f8-4aba-b71e-2357c8756dec/photos_7f291be8-a4f8-4aba-b71e-2357c8756dec_001_130_130.jpg)"></div>
							</div>
						</div>-->
					</div>
					<div class="row">
						<div class="col-xs-6">
							Референс: <span class="reference">te00340324</span>
						</div>
						<div class="col-xs-6 round_nav_string" style="text-align: right;padding-top: 20px;">
							<div class="round_nav round_nav_left"><</div>
							<div class="round_nav round_nav_right">></div>
						</div>
					</div>
					<div class="row">
						<div class="product_item__price clearfix col-xlg-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="price col-xlg-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">1379.00
								<i class="rouble">a</i>
							</div>
							<div class="old-price col-xlg-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">1399.00 <i
										class="rouble">a</i></div>
						</div>
					</div>
					<div class="row lower-price-bottom-row">
						<div class="col-xs-5 lower-price-stars">
							<i class="zmdi zmdi-star"></i> <i class="zmdi zmdi-star"></i> <i class="zmdi zmdi-star"></i>
							<i class="zmdi zmdi-star-half"></i> <i class="zmdi zmdi-star-outline"></i>
						</div>

						<div class="button-desc col-xs-6">
							<div class="text-compare">Сравнить</div>
							<div class="text-favorite">Избранное</div>
							<div class="text-params">Параметры</div>
						</div>
					</div>
					<div class="row">
						<div class="lower-price-CartButtons pCartButtons pCartButtons-line clearfix blk-product__basket col-xlg-12 col-lg-12 col-md-12">
							<div class="col-xs-6" style="position: relative;right: 10px;text-align: left">
								<div class="pCart-line clearfix">
									<div class="rollover grid-flipper">
										<div class="flip-side rollover__front">
											<a href="/basket/ajax/?id=459548" class="cat-incart basket-add" title="" data-picture="product_picture_459548"> В корзину </a>
										</div>
										<div class="flip-side rollover__bottom">
											<form>
												<input class="input-basket-count" data-href="/basket/ajax/?id=459548&amp;action=update" maxlength="7" type="text" data-product="459548" value="1">
												<button class="removeProduct" data-href="/basket/ajax/?id=459548&amp;action=deleteFast" data-product="459548" type="reset">
													<i class="zmdi zmdi-close-circle-o"></i></button>
											</form>
										</div>
									</div>
									<div class="pCartButtons pCartButtons-line clearfix">
										<a href="/auth/?backurl=%2Fsale%2F%3Ffavorite%3D361806" title="Избранное" class="catalog-favorite-toggle unauthorized"></a>
										<a href="#" class="j-animate-short j-add-to-compare" data-id="459548" title="Добавить в сравнение"></a>
									</div>
									<div class="pCartQuantity">
									</div>
								</div>
							</div>
							<div class="col-xs-6" style="padding: 10px 2px;text-align: right">
								<a href="/auth/?backurl=%2Fcatalog%2Flist.html%2F90000005%3Ffavorite%3D320310" data-id="459548" title="Избранное" class="favor catalog-favorite-toggle blk-product__ico unauthorized"><i class="zmdi zmdi-favorite-outline"></i></a>
								<a href="#" class="j-anim blk-product__ico catalog-params" data-id="459548" title="Характеристики товара"><span class="lnr lnr-cog"></span></a>
								<a href="#" class="j-animate-short j-add-to-compare blk-product__ico" data-id="459548" title="Добавить в сравнение"><i class="zmdi zmdi-sort-amount-desc zmdi-sort-amount-desc-rotate"></i></a>
							</div>
						</div>
					</div><!--/.row-->
				</div><!--/.inner-->
			</div>
			<div class="product_item product_item__padd col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xlg-2">
				<div class="inner">
					<h3 class="product_item__header"><a href="https://www.texenergo.ru/catalog/item.html/te00337473">Выключатель автоматический ВА57Ф35-340010 250А</a></h3><span
							class="product_item___disc">%</span>
					<div class="product_item__vendor-code">Артикул: <span class="vendor-code">SAV58-250</span></div>
					<div class="product_item__img">
            <a href="https://www.texenergo.ru/catalog/item.html/te00337473">
						<img class="img-responsive"
							 src="https://www.texenergo.ru/upload/resize_cache/thumbnail_photo/52/5253678a-905f-11e2-8daf-0025906565cb/photos_5253678a-905f-11e2-8daf-0025906565cb_001_130_130.jpg"
							 alt="Наше оборудование"
               id="product_picture_319150" />
            </a>
					</div>
					<div class="row">
						<div class="col-xs-6">
							Референс: <span class="reference">te00337473</span>
						</div>
						<div class="col-xs-6 round_nav_string" style="text-align: right;padding-top: 20px;">
							<div class="round_nav round_nav_left"><</div>
							<div class="round_nav round_nav_right">></div>
						</div>
					</div>
					<div class="row">
						<div class="product_item__price clearfix col-xlg-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="price col-xlg-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">1 977.33
								<i class="rouble">a</i>
							</div>
							<div class="old-price col-xlg-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">1 999.33 <i
										class="rouble">a</i></div>
						</div>
					</div>
					<div class="row lower-price-bottom-row">
						<div class="col-xs-5 lower-price-stars">
							<i class="zmdi zmdi-star"></i> <i class="zmdi zmdi-star"></i> <i class="zmdi zmdi-star"></i>
							<i class="zmdi zmdi-star-half"></i> <i class="zmdi zmdi-star-outline"></i>
						</div>

						<div class="button-desc col-xs-6">
							<div class="text-compare">Сравнить</div>
							<div class="text-favorite">Избранное</div>
							<div class="text-params">Параметры</div>
						</div>
					</div>
					<div class="row">
						<div class="lower-price-CartButtons pCartButtons pCartButtons-line clearfix blk-product__basket col-xlg-12 col-lg-12 col-md-12">
							<div class="col-xs-6" style="position: relative;right: 10px;text-align: left">
								<div class="pCart-line clearfix">
									<div class="rollover grid-flipper">
										<div class="flip-side rollover__front">
											<a href="/basket/ajax/?id=319150" class="cat-incart basket-add" title="" data-picture="product_picture_319150"> В корзину </a>
										</div>
										<div class="flip-side rollover__bottom">
											<form>
												<input class="input-basket-count" data-href="/basket/ajax/?id=319150&amp;action=update" maxlength="7" type="text" data-product="319150" value="1">
												<button class="removeProduct" data-href="/basket/ajax/?id=319150&amp;action=deleteFast" data-product="319150" type="reset">
													<i class="zmdi zmdi-close-circle-o"></i></button>
											</form>
										</div>
									</div>
									<div class="pCartButtons pCartButtons-line clearfix">
										<a href="/auth/?backurl=%2Fsale%2F%3Ffavorite%3D361806" title="Избранное" class="catalog-favorite-toggle unauthorized"></a>
										<a href="#" class="j-animate-short j-add-to-compare" data-id="361806" title="Добавить в сравнение"></a>
									</div>
									<div class="pCartQuantity">
									</div>
								</div>
							</div>
							<div class="col-xs-6" style="padding: 10px 2px;text-align: right">
								<a href="/auth/?backurl=%2Fcatalog%2Flist.html%2F90000005%3Ffavorite%3D320310" data-id="320310" title="Избранное" class="favor catalog-favorite-toggle blk-product__ico unauthorized"><i class="zmdi zmdi-favorite-outline"></i></a>
								<a href="#" class="j-anim blk-product__ico catalog-params" data-id="320310" title="Характеристики товара"><span class="lnr lnr-cog"></span></a>
								<a href="#" class="j-animate-short j-add-to-compare blk-product__ico" data-id="320310" title="Добавить в сравнение"><i class="zmdi zmdi-sort-amount-desc zmdi-sort-amount-desc-rotate"></i></a>
							</div>
						</div>
					</div><!--/.row-->
				</div><!--/.inner-->
			</div>
			<div class="clearfix visible-xs"></div>
			<!--        todo: в стили-->
			<div class="product_item product_item__padd hidden-xs hidden-sm col-md-3 col-lg-2 col-xlg-2">
				<div class="inner">
					<h3 class="product_item__header"><a href="https://www.texenergo.ru/catalog/item.html/te00115170">Электромагнитный пускатель ПМЛ 4100-65 230В 65А 1з+1р</a></h3><span
							class="product_item___disc">%</span>
					<div class="product_item__vendor-code">Артикул: <span class="vendor-code">PM1L65M</span></div>
					<div class="product_item__img">
            <a href="https://www.texenergo.ru/catalog/item.html/te00115170">
						<img class="img-responsive"
							 src="https://www.texenergo.ru/upload/resize_cache/thumbnail_photo/3f/3f11c361-51aa-42fa-be66-b8cf347c8fb6/photos_3f11c361-51aa-42fa-be66-b8cf347c8fb6_001_130_130.jpg"
							 alt="Наше оборудование"
               id="product_picture_353185" />
            </a>
					</div>
					<div class="row">
						<div class="col-xs-6">
							Референс: <span class="reference">te00115170</span>
						</div>
						<div class="col-xs-6 round_nav_string" style="text-align: right;padding-top: 20px;">
							<div class="round_nav round_nav_left"><</div>
							<div class="round_nav round_nav_right">></div>
						</div>
					</div>
					<div class="row">
						<div class="product_item__price clearfix col-xlg-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="price col-xlg-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">679.55
								<i class="rouble">a</i>
							</div>
							<div class="old-price col-xlg-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">699.55 <i
										class="rouble">a</i></div>
						</div>
					</div>
					<div class="row lower-price-bottom-row">
						<div class="col-xs-5 lower-price-stars">
							<i class="zmdi zmdi-star"></i> <i class="zmdi zmdi-star"></i> <i class="zmdi zmdi-star"></i>
							<i class="zmdi zmdi-star-half"></i> <i class="zmdi zmdi-star-outline"></i>
						</div>

						<div class="button-desc col-xs-6">
							<div class="text-compare">Сравнить</div>
							<div class="text-favorite">Избранное</div>
							<div class="text-params">Параметры</div>
						</div>
					</div>
					<div class="row">
						<div class="lower-price-CartButtons pCartButtons pCartButtons-line clearfix blk-product__basket col-xlg-12 col-lg-12 col-md-12">
							<div class="col-xs-6" style="position: relative;right: 10px;text-align: left">
								<div class="pCart-line clearfix">
									<div class="rollover grid-flipper">
										<div class="flip-side rollover__front">
											<a href="/basket/ajax/?id=353185" class="cat-incart basket-add" title="" data-picture="product_picture_353185"> В корзину </a>
										</div>
										<div class="flip-side rollover__bottom">
											<form>
												<input class="input-basket-count" data-href="/basket/ajax/?id=353185&amp;action=update" maxlength="7" type="text" data-product="353185" value="1">
												<button class="removeProduct" data-href="/basket/ajax/?id=353185&amp;action=deleteFast" data-product="353185" type="reset">
													<i class="zmdi zmdi-close-circle-o"></i></button>
											</form>
										</div>
									</div>
									<div class="pCartButtons pCartButtons-line clearfix">
										<a href="/auth/?backurl=%2Fsale%2F%3Ffavorite%3D361806" title="Избранное" class="catalog-favorite-toggle unauthorized"></a>
										<a href="#" class="j-animate-short j-add-to-compare" data-id="353185" title="Добавить в сравнение"></a>
									</div>
									<div class="pCartQuantity">
									</div>
								</div>
							</div>
							<div class="col-xs-6" style="padding: 10px 2px;text-align: right">
								<a href="/auth/?backurl=%2Fcatalog%2Flist.html%2F90000005%3Ffavorite%3D320310" data-id="353185" title="Избранное" class="favor catalog-favorite-toggle blk-product__ico unauthorized"><i class="zmdi zmdi-favorite-outline"></i></a>
								<a href="#" class="j-anim blk-product__ico catalog-params" data-id="353185" title="Характеристики товара"><span class="lnr lnr-cog"></span></a>
								<a href="#" class="j-animate-short j-add-to-compare blk-product__ico" data-id="353185" title="Добавить в сравнение"><i class="zmdi zmdi-sort-amount-desc zmdi-sort-amount-desc-rotate"></i></a>
							</div>
						</div>
					</div><!--/.row-->
				</div><!--/.inner-->
			</div>
			<div class="product_item product_item__padd hidden-xs hidden-sm hidden-md col-lg-2 col-xlg-2">
				<div class="inner">
					<h3 class="product_item__header"><a href="https://www.texenergo.ru/catalog/item.html/te00001205">Патрон Е27Н12П-01 подвесной (упаковка 20 шт.)</a></h3><span
							class="product_item___disc">%</span>
					<div class="product_item__vendor-code">Артикул: <span class="vendor-code">LE27N12P</span></div>
					<div class="product_item__img">
            <a href="https://www.texenergo.ru/catalog/item.html/te00001205">
						<img class="img-responsive"
							 src="https://www.texenergo.ru/upload/resize_cache/thumbnail_photo/26/26bc9bb2-fb73-48aa-9d38-73b9b01368d8/photos_26bc9bb2-fb73-48aa-9d38-73b9b01368d8_001_130_130.jpg"
							 alt="Наше оборудование"
               id="product_picture_355429" />
            </a>
					</div>
					<div class="row">
						<div class="col-xs-6">
							Референс: <span class="reference">te00001205</span>
						</div>
						<div class="col-xs-6 round_nav_string" style="text-align: right;padding-top: 20px;">
							<div class="round_nav round_nav_left"><</div>
							<div class="round_nav round_nav_right">></div>
						</div>
					</div>
					<div class="row">
						<div class="product_item__price clearfix col-xlg-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="price col-xlg-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">10.99
								<i class="rouble">a</i>
							</div>
							<div class="old-price col-xlg-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">11.99 <i
										class="rouble">a</i></div>
						</div>
					</div>
					<div class="row lower-price-bottom-row">
						<div class="col-xs-5 lower-price-stars">
							<i class="zmdi zmdi-star"></i> <i class="zmdi zmdi-star"></i> <i class="zmdi zmdi-star"></i>
							<i class="zmdi zmdi-star-half"></i> <i class="zmdi zmdi-star-outline"></i>
						</div>

						<div class="button-desc col-xs-6">
							<div class="text-compare">Сравнить</div>
							<div class="text-favorite">Избранное</div>
							<div class="text-params">Параметры</div>
						</div>
					</div>
					<div class="row">
						<div class="lower-price-CartButtons pCartButtons pCartButtons-line clearfix blk-product__basket col-xlg-12 col-lg-12 col-md-12">
							<div class="col-xs-6" style="position: relative;right: 10px;text-align: left">
								<div class="pCart-line clearfix">
									<div class="rollover grid-flipper">
										<div class="flip-side rollover__front">
											<a href="/basket/ajax/?id=355429" class="cat-incart basket-add" title="" data-picture="product_picture_355429"> В корзину </a>
										</div>
										<div class="flip-side rollover__bottom">
											<form>
												<input class="input-basket-count" data-href="/basket/ajax/?id=355429&amp;action=update" maxlength="7" type="text" data-product="355429" value="1">
												<button class="removeProduct" data-href="/basket/ajax/?id=355429&amp;action=deleteFast" data-product="361806" type="reset">
													<i class="zmdi zmdi-close-circle-o"></i></button>
											</form>
										</div>
									</div>
									<div class="pCartButtons pCartButtons-line clearfix">
										<a href="/auth/?backurl=%2Fsale%2F%3Ffavorite%3D361806" title="Избранное" class="catalog-favorite-toggle unauthorized"></a>
										<a href="#" class="j-animate-short j-add-to-compare" data-id="355429" title="Добавить в сравнение"></a>
									</div>
									<div class="pCartQuantity">
									</div>
								</div>
							</div>
							<div class="col-xs-6" style="padding: 10px 2px;text-align: right">
								<a href="/auth/?backurl=%2Fcatalog%2Flist.html%2F90000005%3Ffavorite%3D320310" data-id="355429" title="Избранное" class="favor catalog-favorite-toggle blk-product__ico unauthorized"><i class="zmdi zmdi-favorite-outline"></i></a>
								<a href="#" class="j-anim blk-product__ico catalog-params" data-id="355429" title="Характеристики товара"><span class="lnr lnr-cog"></span></a>
								<a href="#" class="j-animate-short j-add-to-compare blk-product__ico" data-id="355429" title="Добавить в сравнение"><i class="zmdi zmdi-sort-amount-desc zmdi-sort-amount-desc-rotate"></i></a>
							</div>
						</div>
					</div><!--/.row-->
				</div><!--/.inner-->
			</div>
			<div class="product_item product_item__padd col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xlg-2">
				<div class="inner">
					<h3 class="product_item__header"><a href="https://www.texenergo.ru/catalog/item.html/te00136595">Арматура светосигнальная AD22DS 230В зеленая</a></h3>
					<span class="product_item___disc">%</span>
					<div class="product_item__vendor-code">Артикул: <span class="vendor-code">MFK10-ADDS-230-06</span></div>
					<div class="product_item__img">
						<!--<div class="row">
							<div class="col-xs-6">-->
                <a href="https://www.texenergo.ru/catalog/item.html/te00136595">
								<img class="img-responsive"
									 src="https://www.texenergo.ru/upload/resize_cache/thumbnail_photo/1f/1fc83f59-df17-46a2-a611-6ae4fb07d834/photos_1fc83f59-df17-46a2-a611-6ae4fb07d834_001_130_130.jpg"
									 alt="Наше оборудование"
									 id="product_picture_357867" />
                </a>
							<!--</div>
							<div class="col-xs-6">
								<div class="img-mini img-responsive" id="product_picture_320310" style="background-image: url(https://www.texenergo.ru/upload/resize_cache/import/6901b51a-4454-11e5-945c-0025906565cb_preview.jpg)"></div>
								<div class="img-mini img-responsive" id="product_picture_320310" style="background-image: url(https://www.texenergo.ru/upload/resize_cache/import/6901b51b-4454-11e5-945c-0025906565cb_preview.jpg)"></div>
							</div>
						</div>-->
					</div>
					<div class="row">
						<div class="col-xs-6">
							Референс: <span class="reference">te00136595</span>
						</div>
						<div class="col-xs-6 round_nav_string" style="text-align: right;padding-top: 20px;">
							<div class="round_nav round_nav_left"><</div>
							<div class="round_nav round_nav_right">></div>
						</div>
					</div>
					<div class="row">
						<div class="product_item__price clearfix col-xlg-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="price col-xlg-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">25.83
								<i class="rouble">a</i>
							</div>
							<div class="old-price col-xlg-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">29.83 <i
										class="rouble">a</i></div>
						</div>
					</div>
					<div class="row lower-price-bottom-row">
						<div class="col-xs-5 lower-price-stars">
							<i class="zmdi zmdi-star"></i> <i class="zmdi zmdi-star"></i> <i class="zmdi zmdi-star"></i>
							<i class="zmdi zmdi-star-half"></i> <i class="zmdi zmdi-star-outline"></i>
						</div>

						<div class="button-desc col-xs-6">
							<div class="text-compare">Сравнить</div>
							<div class="text-favorite">Избранное</div>
							<div class="text-params">Параметры</div>
						</div>
					</div>
					<div class="row">
						<div class="lower-price-CartButtons pCartButtons pCartButtons-line clearfix blk-product__basket col-xlg-12 col-lg-12 col-md-12">
							<div class="col-xs-6" style="position: relative;right: 10px;text-align: left">
								<div class="pCart-line clearfix">
									<div class="rollover grid-flipper">
										<div class="flip-side rollover__front">
											<a href="/basket/ajax/?id=357867" class="cat-incart basket-add" title="" data-picture="product_picture_357867"> В корзину </a>
										</div>
										<div class="flip-side rollover__bottom">
											<form>
												<input class="input-basket-count" data-href="/basket/ajax/?id=357867&amp;action=update" maxlength="7" type="text" data-product="357867" value="1">
												<button class="removeProduct" data-href="/basket/ajax/?id=357867&amp;action=deleteFast" data-product="357867" type="reset">
													<i class="zmdi zmdi-close-circle-o"></i></button>
											</form>
										</div>
									</div>
									<div class="pCartButtons pCartButtons-line clearfix">
										<a href="/auth/?backurl=%2Fsale%2F%3Ffavorite%3D361806" title="Избранное" class="catalog-favorite-toggle unauthorized"></a>
										<a href="#" class="j-animate-short j-add-to-compare" data-id="357867" title="Добавить в сравнение"></a>
									</div>
									<div class="pCartQuantity">
									</div>
								</div>
							</div>
							<div class="col-xs-6" style="padding: 10px 2px;text-align: right">
								<a href="/auth/?backurl=%2Fcatalog%2Flist.html%2F90000005%3Ffavorite%3D320310" data-id="357867" title="Избранное" class="favor catalog-favorite-toggle blk-product__ico unauthorized"><i class="zmdi zmdi-favorite-outline"></i></a>
								<a href="#" class="j-anim blk-product__ico catalog-params" data-id="357867" title="Характеристики товара"><span class="lnr lnr-cog"></span></a>
								<a href="#" class="j-animate-short j-add-to-compare blk-product__ico" data-id="357867" title="Добавить в сравнение"><i class="zmdi zmdi-sort-amount-desc zmdi-sort-amount-desc-rotate"></i></a>
							</div>
						</div>
					</div><!--/.row-->
				</div><!--/.inner-->
			</div>
            <?endif;?>
			<!--<div class="lower-price-wrapper col-xs-12 col-sm-4 col-md-3 col-lg-4 col-xlg-4">-->
			<div class="lower-price-wrapper col-xlg-2 col-lg-2 col-md-4 col-sm-6 col-xs-12">
        <div class="lower-price-cycle">
  				<a href="https://www.texenergo.ru/catalog/list.html/90000114" class="slide"><img class="img-responsive"
  																								 src="/upload/sale/main/blp_1.jpg"
  																								 alt="Арматура светосигнальная" /></a>
<?if (false):?>
  				<a href="https://www.texenergo.ru/catalog/list.html/137617" class="slide"><img class="img-responsive"
  																							   src="/upload/sale/main/blp_2.jpg"
  																							   alt="Амперметры и вольтметры" /></a>

  				<a href="https://www.texenergo.ru/publication/statyi/sovershenno_novye_va5735_i_va57f35/" class="slide"><img
  							class="img-responsive" src="/upload/sale/main/blp_3.jpg" alt="Рекламное место 2" /></a>
<?endif;?>
        </div><!--/.lower-price-inner-->
			</div><!--/.lower-price-cycle-->
			<div class="lower-price__bottom_nav clearfix" style="">
				<nav>
					<ul class="pagination">
						<li class="owl-prev"><a href="#" aria-label="Previous" onclick="return false"><i class="zmdi zmdi-chevron-left"></i></a>
						</li>
						<li class="owl-next"><a href="#" aria-label="Next" onclick="return false"><i class="zmdi zmdi-chevron-right"></i></a></li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</div><!-- ./wrapper-main-lower-price-->

<div class="clearfix"></div>

<script>

	$(function(){

		function ShowHideElem( this_ob, selector_string ,hide=false){
			if(hide){
				$($(this_ob).parents()[3]).find(selector_string).hide();
			}else{
				$($(this_ob).parents()[3]).find(selector_string).css({'display':'inline-block'});
			}
		}

		$( '.lower-price-CartButtons .catalog-favorite-toggle' ).hover(
			function(){ShowHideElem(this,'.text-favorite',false);},
			function(){ShowHideElem(this,'.text-favorite',true);}
		);

		$( '.lower-price-CartButtons .catalog-params' ).hover(
			function(){ShowHideElem(this,'.text-params',false);},
			function(){ShowHideElem(this,'.text-params',true);}
		);

		$( '.lower-price-CartButtons .j-add-to-compare' ).hover(
			function(){ShowHideElem(this,'.text-compare',false);},
			function(){ShowHideElem(this,'.text-compare',true);}
		);

		////////////// перемещение банера вверх
		/*window.onresize = function (ev) {
			if (document.documentElement.clientWidth < 767) {
				$('#lower-price-main-container-row').prepend( $('.lower-price-cycle') )
			}else{
				$('.lower-price-cycle').insertBefore($('.lower-price__bottom_nav'));
			}
		};*/

	});

</script>