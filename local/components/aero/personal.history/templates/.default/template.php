<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="container">
    <div class="twelve position">
        <h1 class="headline">Просмотренные</h1>


        <aside class="subControls">
            <?/*$APPLICATION->IncludeComponent(
                "bitrix:socserv.auth.split",
                "head",
                Array(
                    "SHOW_PROFILES" => "Y",
                    "ALLOW_DELETE" => "Y"
                ),
                false
            );*/?>
            <?/*
            <div class="subMetrics">
                <div class="subMetrics-bonuses subMetrics-divider">
                    <span>Бонусы:</span>
                    <em>600</em>
                </div>
                <div class="subMetrics-bonuses">
                    <span>Ваш кредит:</span>
                    <em>7 000 <i class="rouble">a</i> из 60 000 <i class="rouble">a</i></em>
                </div>
            </div>
            */?>
        </aside>

    </div>
</div>
<div class="container">
    <section class="catalog main main-favorites">
        <? if (!empty($arResult['IDS'])): ?>
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
            $GLOBALS['arFavoritesFilter'] = Array(
                'ID' => $arResult['IDS'],
            );
            ?>

            <?$APPLICATION->IncludeComponent(
                "texenergo:catalog.section",
                $arResult['CATALOG_SECTION_VIEW'],
                Array(
                    "AJAX_MODE" => "N",
                    "IBLOCK_TYPE" => "catalog",
                    "IBLOCK_ID" => IBLOCK_ID_CATALOG,
                    "SECTION_ID" => "",
                    "SECTION_CODE" => "",
                    "SECTION_USER_FIELDS" => array(),
                    "ELEMENT_SORT_FIELD" => $arResult["ELEMENT_SORT_FIELD"],
                    "ELEMENT_SORT_ORDER" => $arResult["ELEMENT_SORT_ORDER"],
                    "ELEMENT_SORT_FIELD2" => "id",
                    "ELEMENT_SORT_ORDER2" => "desc",
                    "FILTER_NAME" => "arFavoritesFilter",
                    "INCLUDE_SUBSECTIONS" => "Y",
                    "SHOW_ALL_WO_SECTION" => "N",
                    "SECTION_URL" => "",
                    "DETAIL_URL" => "",
                    "BASKET_URL" => "",
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
                    "SET_TITLE" => "Y",
                    "SET_STATUS_404" => "N",
                    "PAGE_ELEMENT_COUNT" => "10",
                    "LINE_ELEMENT_COUNT" => "5",
                    "PROPERTY_CODE" => array(),
                    "OFFERS_LIMIT" => "5",
                    "PRICE_CODE" => array("base_price"),
                    "USE_PRICE_COUNT" => "N",
                    "SHOW_PRICE_COUNT" => "1",
                    "PRICE_VAT_INCLUDE" => "Y",
                    "PRODUCT_PROPERTIES" => array(),
                    "USE_PRODUCT_QUANTITY" => "N",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "36000000",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "Y",
                    "PAGER_TEMPLATE" => "bottom-pagenavigation",
                    "DISPLAY_TOP_PAGER" => "N",
                    "DISPLAY_BOTTOM_PAGER" => "Y",
                    "PAGER_TITLE" => "Товары",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "HIDE_NOT_AVAILABLE" => "N",
                    "CONVERT_CURRENCY" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "AJAX_OPTION_HISTORY" => "N"
                ),
                false
            );?>
        <? else: ?>
            <p>История просмотров пуста.</p>
        <?endif; ?>
    </section>
</div>

