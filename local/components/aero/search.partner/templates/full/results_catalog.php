<? if (!empty($arResult['ITEMS'])): ?>
    <nav class="sort">
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
    $arProductIDS = Array();
    $arReferences = Array();
    $sSectionFilterName = 'arSearchProducts';
    foreach ($arResult['NAV_ITEMS'] as $arItem) {
        $arReferences[] = $arItem['REFERENCE'];
        $arProductIDS[] = $arItem['ID'];
    }
    $GLOBALS[$sSectionFilterName] = Array(
        '=ID' => $arProductIDS,
    );
    ?>
    <?$APPLICATION->IncludeComponent(
        "texenergo:catalog.section",
        $arResult['CATALOG_SECTION_VIEW'],
        array(
            "IBLOCK_TYPE" => 'catalog',
            "IBLOCK_ID" => IBLOCK_ID_CATALOG,
            "FIELD_CODE" => Array("DETAIL_PICTURE"),
            "PROPERTY_CODE" => array("VOBOLOCHKEBEZKNOPOK", "VOBOLOCHKESKNOPKAMIP", "VIDKNOPKI", "VIDPEREKLYUCHATELYA", "VIDSIGNALIZACII", "VIDUSTANOVKI", "GRUPPAKONTAKTOV", "IZGOTOVITEL", "ISPOLNENIE", "KOLICHESTVOKONTAKTNY", "KOLICHESTVOPOLYUSOV", "KOLICHESTVOTARIFOV", "KOLICHESTVOFAZ", "KOLLICHESTVOKONTAKTO", "KONTAKTY", "KORPUS", "KORPUSASHKAFOVBOKSY", "KREPLENIE", "MAKSIMALNYYTOKNAGRUZ", "MARKA", "MARKAPREDOHRANITELYA", "MOSCHNOST", "NALICHIEKORPUSA", "NALICHIERELE", "NAPRYAZHENIE", "NAPRYAZHENIEKATUSHKI", "NOMNAPRYAZHPOSTTOKA", "NOMINRABNAPRNAPOSTTO", "NOMINALNOENAPRYAZHEN", "NOMINALNYYTOK", "OTKRYTYE", "POSADOCHNYYDIAMETR", "POSADOCHNYYDIAMETRVP", "RUKOYATKA", "STEPENZASCHITY", "TEPLOVOYTOK", "TIPPROVODA", "TIPRELE", "TIPSIGNALA", "TIPSCHETCHIKA", "TIPUCHITYVAEMOYENERG", "TOK", "TOKPUSKATELYA", "TOKRELE", "CVET", "CVETSVECHENIYA", "CHISLOFAZ"),
            "INCLUDE_SUBSECTIONS" => 'Y',
            "BY_LINK" => 'Y',
            "BASKET_URL" => '/basket/',
            "ACTION_VARIABLE" => 'action',
            "FILTER_NAME" => $sSectionFilterName,
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_FILTER" => "Y",
            "CACHE_GROUPS" => "Y",
            "SET_TITLE" => "Y",
            "SET_STATUS_404" => 'N',
            "DISPLAY_COMPARE" => 'Y',
            "PAGE_ELEMENT_COUNT" => count($arProductIDS),
            "LINE_ELEMENT_COUNT" => 4,
            "PRICE_CODE" => array("base_price", "price_ws1", "price_ws2", "price_ws3"),
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
            'SORT_REFERENCES' => $arReferences
        ),
        $component
    );?>
<? endif; ?>