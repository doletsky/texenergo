<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<?
/**
 * при подключении компонента из template.php стили из шаблона подключаемого компонента подтягиваются через раз
 * подключаем их вручную
 */
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/components/texenergo/catalog.section/list/style.css', true);
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/components/texenergo/catalog.section/grid/style.css', true);
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/components/texenergo/catalog.section/detail/style.css', true);
?>

<?
/**
 * Товар можно открыть, передав в урле код любого из разделов инфоблока - получаются дубли
 * Чтобы этого избежать, выполняем дополнительную проверку
 */
/*$iElementID = IntVal($arResult["VARIABLES"]["ELEMENT_ID"]);
$sElementCode = $arResult["VARIABLES"]["ELEMENT_CODE"];


$arElement = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => $arParams['IBLOCK_ID'], '=CODE' => $sElementCode), false, Array('nTopCount' => 1), Array('IBLOCK_SECTION_ID'))->Fetch();
/*
$arSection = CIBlockSection::GetList(Array(), Array(
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
    //'ID' => $arResult['VARIABLES']['SECTION_ID'],
    '=CODE' => $arResult['VARIABLES']['SECTION_CODE'],
), false, Array('ID'))->Fetch();

if (!$arElement || $arSection['ID'] !== $arElement['IBLOCK_SECTION_ID']) {
    ShowError('Товар не найден');
    @define("ERROR_404", "Y");
    if ($arParams["SET_STATUS_404"] === "Y")
        CHTTP::SetStatus("404 Not Found");
    return;
}*/
global $USER;
?>

<? include('linked_publications.php'); ?>
<div class="twelve" itemscope itemtype="http://schema.org/Product">
    <h1 class="headline headline-product"><span itemprop="name">
        <?= $APPLICATION->ShowTitle(false);?>
    </span></h1>
<?$APPLICATION->IncludeComponent(
    "bitrix:catalog.element",
    "",
    array(
        "PUBLICATIONS_HTML" => $publicationHtml,
		"ADD_SECTIONS_CHAIN" => "N",
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
        "META_KEYWORDS" => $arParams["DETAIL_META_KEYWORDS"],
        "META_DESCRIPTION" => $arParams["DETAIL_META_DESCRIPTION"],
        "BROWSER_TITLE" => $arParams["DETAIL_BROWSER_TITLE"],
        "BASKET_URL" => $arParams["BASKET_URL"],
        "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
        "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
        "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
        "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
        "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "SET_TITLE" => $arParams["SET_TITLE"],
        "SET_STATUS_404" => $arParams["SET_STATUS_404"],
        "PRICE_CODE" => getPricesArray(true),
        "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
        "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
        "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
        "PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
        "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
        "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
        "LINK_IBLOCK_TYPE" => $arParams["LINK_IBLOCK_TYPE"],
        "LINK_IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"],
        "LINK_PROPERTY_SID" => $arParams["LINK_PROPERTY_SID"],
        "LINK_ELEMENTS_URL" => $arParams["LINK_ELEMENTS_URL"],

        "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
        "OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
        "OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
        "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
        "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
        "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
        "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],

        "ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
        "ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
        "SECTION_ID" => $arSection['ID'], //$arResult["VARIABLES"]["SECTION_ID"],
        //"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
        "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
        "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
        'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
        'CURRENCY_ID' => $arParams['CURRENCY_ID'],
        'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
        'USE_ELEMENT_COUNTER' => $arParams['USE_ELEMENT_COUNTER'],

        'LABEL_PROP' => $arParams['LABEL_PROP'],
        'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
        'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
        'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
        'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
        'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
        'SHOW_MAX_QUANTITY' => $arParams['DETAIL_SHOW_MAX_QUANTITY'],
        'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
        'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
        'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
        'MESS_BTN_COMPARE' => $arParams['MESS_BTN_COMPARE'],
        'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
        'USE_VOTE_RATING' => $arParams['DETAIL_USE_VOTE_RATING'],
        'VOTE_DISPLAY_AS_RATING' => (isset($arParams['DETAIL_VOTE_DISPLAY_AS_RATING']) ? $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'] : ''),
        'USE_COMMENTS' => $arParams['DETAIL_USE_COMMENTS'],
        'BLOG_USE' => (isset($arParams['DETAIL_BLOG_USE']) ? $arParams['DETAIL_BLOG_USE'] : ''),
        'VK_USE' => (isset($arParams['DETAIL_VK_USE']) ? $arParams['DETAIL_VK_USE'] : ''),
        'VK_API_ID' => (isset($arParams['DETAIL_VK_API_ID']) ? $arParams['DETAIL_VK_API_ID'] : 'API_ID'),
        'FB_USE' => (isset($arParams['DETAIL_FB_USE']) ? $arParams['DETAIL_FB_USE'] : ''),
        'FB_APP_ID' => (isset($arParams['DETAIL_FB_APP_ID']) ? $arParams['DETAIL_FB_APP_ID'] : ''),
        'BRAND_USE' => $arParams['DETAIL_BRAND_USE'],
        'BRAND_PROP_CODE' => $arParams['DETAIL_BRAND_PROP_CODE'],

        // пробрасываем параметры для вкладки Серия
        'CATALOG_SECTION_VIEWS' => $arResult['CATALOG_SECTION_VIEWS'],
        'CATALOG_SECTION_VIEW' => $arResult['CATALOG_SECTION_VIEW'],
        'SORT_FIELDS' => $arResult['SORT_FIELDS'],
        'SORT_FIELD' => $arResult['SORT_FSORT_FIELDIELDS'],
        'ELEMENT_SORT_FIELD' => $arResult['ELEMENT_SORT_FIELD'],
        'ELEMENT_SORT_ORDER' => $arResult['ELEMENT_SORT_ORDER'],
        'PARENT_COMPONENT' => $component,
    ),
    $component
);
?>
</div>

