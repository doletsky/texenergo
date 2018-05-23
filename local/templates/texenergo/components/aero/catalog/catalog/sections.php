<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="twelve">


<input type='checkbox' name='texenergoVendor' id='texenergoVendor' class='catalog-input-hide j-texenergo-vendor'
       <? if ($arResult['FILTER_BY_VENDOR']): ?>checked='checked'<? endif; ?>>
<input type='checkbox' name='catalogView' id='catalogView' class='catalog-input-hide j-catalag-view-mode'
       <? if ($arResult['CATALOG_LANDING_VIEW'] == 'detail'): ?>checked='checked'<? endif; ?>>

<h1 class="headline"><?= "Продукция" //$APPLICATION->ShowTitle(); ?></h1>

<div class="group-switch group-switch-catalog">
    <div class='subSwitch j_catalog_view <? if ($arResult['FILTER_BY_VENDOR']): ?>subSwitch-on<? endif; ?>'
         data-param='texenergoVendor'></div>
    <span>Только товары TEXENERGO</span>
</div>
<div class="group-switch group-switch-catalog">
    <span>Подробно</span>

    <div
        class='subSwitch subSwitch-between j_catalog_view <? if ($arResult['CATALOG_LANDING_VIEW'] == 'detail'): ?>subSwitch-on<? endif; ?>'
        data-param='catalogView'></div>
    <span>Наглядно</span>
</div>
<aside class="share">


	<ul>
        <? /* if ($USER->isAuthorized()): ?>
            <li>
                <? if ($arResult['USER_IS_SUBSCRIBED']): ?>
                    <a href="#" class="active j-delete-from-goods-subscribe" title="Отписаться от рассылки"><img
                            src="<?= SITE_TEMPLATE_PATH; ?>/img/catalog/rss.png"
                            alt="Отписаться от рассылки"></a>
                <? else: ?>
                    <a href="#" class="j-add-to-goods-subscribe" title="Подписаться на рассылку"><img
                            src="<?= SITE_TEMPLATE_PATH; ?>/img/catalog/rss.png" alt="Подписаться на рассылку"></a>
                <? endif; ?>
            </li>
        <? else: ?>
            <li>
                <a href="#" title="Подписаться на рассылку"><img
                        src="<?= SITE_TEMPLATE_PATH; ?>/img/catalog/rss.png" alt="Подписаться на рассылку"></a>
            </li>
        <?endif;  */?>

        <?/*<li><img src="<?= SITE_TEMPLATE_PATH ?>/img/catalog/share.png" alt="Поделиться с друзьями"></li>*/?>
    </ul>

    <?/*$APPLICATION->IncludeComponent("bitrix:main.share", ".default", array(
            "HIDE" => "N",
            "HANDLERS" => array(
                0 => "facebook",
                1 => "twitter",
                2 => "vk",
            ),
            "PAGE_URL" => $uri,
            "PAGE_TITLE" => "",
            "SHORTEN_URL_LOGIN" => "",
            "SHORTEN_URL_KEY" => ""
        ),
        false,
        array(
            "ACTIVE_COMPONENT" => "N"
        )
    );*/?>

</aside>


<div class="clear"></div>
<div class="system-description">
<p>Продукцию компании можно заказать через электронную систему заказов. Система заказов создана для удобства взаимодействия специалистов различных отраслей, которые ценят свое время.
Система содержит необходимый инструментарий для полноценного обслуживания юридических лиц.</p>
<p class="orange allotted">Пройдите <a class="orange" href="/personal/?register=yes"><u>регистрацию</u></a> и получите доступ к остаткам склада прямо сейчас.</p>
<p>Желаем приятной работы!</p>
</div>

<?
if ($arResult['FILTER_BY_VENDOR']) {
    $sFilterName = 'arLandingVendorFilterOwn';
    $GLOBALS[$sFilterName]['PROPERTY_IS_OWN'] = PROP_IS_OWN_Y_ID;
}
else {
    $sFilterName = 'arLandingVendorFilter';
}
//++
//pr($sFilterName);
//pr($arResult['CATALOG_LANDING_VIEW']);
//--
?>
<?$APPLICATION->IncludeComponent(
    "texenergo:catalog.section.list",
    $arResult['CATALOG_LANDING_VIEW'],
    array(
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        //"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_TIME" => 3600,
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "COUNT_ELEMENTS" => "N",
        "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
        "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
        "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
        "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
        "SECTION_USER_FIELDS" => array('UF_*'),
        "FILTER_NAME" => $sFilterName,
    ),
    $component
);?>

<div class="clear"></div>
<?
$GLOBALS['arCatalogStartBrandFilter'] = Array(
    'PROPERTY_VENDOR_POPULAR_ON_VALUE' => 'Y',
);
?>
<?
$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "brands-start",
    Array(
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "AJAX_MODE" => "N",
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => IBLOCK_ID_BRANDS,
        "NEWS_COUNT" => "5",
        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_ORDER1" => "DESC",
        "SORT_BY2" => "SORT",
        "SORT_ORDER2" => "ASC",
        "FILTER_NAME" => "arCatalogStartBrandFilter",
        "FIELD_CODE" => array("DETAIL_PICTURE"),
        "PROPERTY_CODE" => array("SHOW_VENDOR_IN_CATALOG"),
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "SET_TITLE" => "N",
        "SET_STATUS_404" => "N",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
        "ADD_SECTIONS_CHAIN" => "Y",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "INCLUDE_SUBSECTIONS" => "Y",
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => "Y",
        "PAGER_TEMPLATE" => ".default",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TITLE" => "Новости",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N"
    ),
    $component
);
?>

<div class="clear"></div>


<?$specialFrom = mktime(date("H"), 0, 0);
$specialTo = $specialFrom + (60*60);

$GLOBALS['catalogSpecialFilter'] = $GLOBALS['f'];
$GLOBALS['catalogSpecialFilter']['PROPERTY_IS_SPECIAL_VALUE'] = 'Y';
$GLOBALS['catalogSpecialFilter']['<=PROPERTY_IS_SPECIAL_FROM'] = date('Y-m-d H:i:s', $specialFrom);
$GLOBALS['catalogSpecialFilter']['>PROPERTY_IS_SPECIAL_TO'] = date('Y-m-d H:i:s', $specialTo);

?>
<?/*
$APPLICATION->IncludeComponent(
    "texenergo:catalog.section",
    "special",
    array(
		"PROMO_LINK" => '/catalog/special/',
        "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
        "IBLOCK_ID" => $arParams['IBLOCK_ID'],
        "ELEMENT_SORT_FIELD" => 'PROPERTY_IS_SPECIAL_TO',
        "ELEMENT_SORT_ORDER" => 'ASC',
        "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
        "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
        "PROPERTY_CODE" => $arParams['LIST_PROPERTY_CODE'],
        "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
        "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
        "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
        "INCLUDE_SUBSECTIONS" => 'Y', //$arParams["INCLUDE_SUBSECTIONS"],
        "BASKET_URL" => $arParams["BASKET_URL"],
        "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
        "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
        "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
        "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
        "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
        "FILTER_NAME" => 'catalogSpecialFilter',
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => 3600,
        "CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "SET_TITLE" => "N", //$arParams["SET_TITLE"],
        "SET_STATUS_404" => $arParams["SET_STATUS_404"],
        "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
        "PAGE_ELEMENT_COUNT" => 30, //$arParams["PAGE_ELEMENT_COUNT"],
        "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
        "PRICE_CODE" => $arParams["PRICE_CODE"],
        "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
        "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

        "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
        "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
        "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

        "DISPLAY_TOP_PAGER" => "N", //$arParams["DISPLAY_TOP_PAGER"],
        "DISPLAY_BOTTOM_PAGER" => "N", //$arParams["DISPLAY_BOTTOM_PAGER"],
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

        "SECTION_ID" => 0,
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
        'FEATURED_TITLE' => 'Распродажа', // заколовок блока
        'BY_LINK' => 'Y',
        'SHOW_MORE' => 'Y',
    ),
    $component
);
*/
?>


</div> <!-- twelve -->
