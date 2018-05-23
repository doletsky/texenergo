<?

echo "<section class='pPromoRow'>";
$APPLICATION->IncludeFile('/include/product_action_inc.php', array(),
    array(
        'MODE' => 'html',
        'TEMPLATE' => 'product_action_inc.php',
    )
);
echo "</section>";


if ($arResult["DETAIL_PICTURE"]["SRC"] || $arResult["PREVIEW_PICTURE"]["SRC"]) {

    echo "<section class='pView clearfix";
    if (empty($arResult["PROPERTIES"]["ACCESSORIES"]["VALUE"])) {
        echo " pView-big";
    }
    echo " j_switch_img_block'><figure class='pProductImage'>";

    if ($arResult["PROPERTIES"]["GOODS_SHEME"]["VALUE"] != "") {
        echo "<div class='switch-grp'><div class='switch switch-inl view-switch j_switch_img_link'></div><span class='switch-label j_switch_img_item' data-switch='1'>" . GetMessage("SHEME_TITLE") . "</span><span class='switch-label hidden j_switch_img_item' data-switch='2'>" . GetMessage("FOTO_TITLE") . "</span></div>";
        echo "<img src='" . $arResult["PROPERTIES"]["GOODS_SHEME"]["VALUE"]["SRC"] . "' alt='" . $arResult["NAME"] . "' data-switch='2' class='hidden j_switch_img_item'>";
    }
    if ($arResult["DETAIL_PICTURE"]["SRC"]) {
        echo "<img src='" . $arResult["DETAIL_PICTURE"]["SRC"] . "' alt='" . $arResult["NAME"] . "' data-switch='1' class='j_switch_img_item j_preview_img_main_" . $arResult["ID"] . "'>";
    } else {
        if ($arResult["PREVIEW_PICTURE"]["SRC"]) {
            echo "<img src='" . $arResult["PREVIEW_PICTURE"]["SRC"] . "' alt='" . $arResult["NAME"] . "' data-switch='1' class='j_switch_img_item j_preview_img_main_" . $arResult["ID"] . "'>";
        }
    }
    if (!empty($arResult["PROPERTIES"]["PRICE_DOWN_REASON"]["VALUE"])) {
        echo "<div class='markdownMessage'><span>" . $arResult["PROPERTIES"]["PRICE_DOWN_REASON"]["~VALUE"] . "</span></div>";
    }

    echo "</figure>";
    if ($arResult["PROPERTIES"]["GOODS_PHOTOS"]["VALUE"] != "") {
        echo "<div class='pSlider clearfix'><ul class='owl-carousel j_owl_slider_4 j_switch_img_item' data-switch='1'>";
        echo "<li class='j_preview_img active' data-id='" . $arResult["ID"] . "'><img src='";
        if ($arResult["DETAIL_PICTURE"]["SRC"]) {
            echo $arResult["DETAIL_PICTURE"]["SRC"];
        } else {
            if ($arResult["PREVIEW_PICTURE"]["SRC"]) {
                echo $arResult["PREVIEW_PICTURE"]["SRC"];
            }
        }
        echo "' alt='" . $arResult["NAME"] . "'></li>";
        foreach ($arResult["PROPERTIES"]["GOODS_PHOTOS"]["VALUE"] as $foto) {
            echo "<li class='j_preview_img' data-id='" . $arResult["ID"] . "'><img src='" . $foto["SRC"] . "' alt='" . $arResult["NAME"] . "'></li>";
        }
        echo "</ul></div>";
    }
    echo "</section>";
}?>
<? if (!empty($arResult["PROPERTIES"]["ANALOGS"]["VALUE"])): ?>
    <?
    global $arAnalogFilter;
    if (!empty($arResult["PROPERTIES"]["ANALOGS"]["VALUE"])) {
        $arAnalogFilter = Array(
            "ACTIVE" => "Y",
            "ID" => $arResult["PROPERTIES"]["ANALOGS"]["VALUE"],
        );
    } else {
        $arAnalogFilter = Array(
            "ACTIVE" => "Y",
            "ID" => "-1",
        );
    }
    ?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "product-analogs",
        Array(
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "N",
            "AJAX_MODE" => "N",
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => $arParams['IBLOCK_ID'],
            "NEWS_COUNT" => "1000",
            "SORT_BY1" => "ACTIVE_FROM",
            "SORT_ORDER1" => "DESC",
            "SORT_BY2" => "SORT",
            "SORT_ORDER2" => "ASC",
            "FILTER_NAME" => "arAnalogFilter",
            "FIELD_CODE" => array('DETAIL_PICTURE'),
            "PROPERTY_CODE" => array("SKU"),
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
    );?>
<? endif; ?>

<?echo "<section class='pOverview clearfix'>";
echo "<section class='pCopy";
if ($arResult["PROPERTIES"]["GOODS_PUBLICATIONS"]["VALUE"] == "" && $arResult["PROPERTIES"]["GOODS_DOCS"]["VALUE"] == "") {
    echo " pCopy-big";
}
echo "'>" . $arResult["DETAIL_TEXT"] . "</section>";
if ($arResult["PROPERTIES"]["GOODS_PUBLICATIONS"]["VALUE"] != "" || $arResult["PROPERTIES"]["GOODS_DOCS"]["VALUE"] != "") {
    echo "<aside class='pPublications j_switch_img_block'>";
    echo "<div class='switch-sbgrp'>";
    if ($arResult["PROPERTIES"]["GOODS_PUBLICATIONS"]["VALUE"] != "" && $arResult["PROPERTIES"]["GOODS_DOCS"]["VALUE"] != "") {
        echo "<span class='switch-label float-left'>" . GetMessage("PUBLICATION_TITLE") . "</span>";
        echo "<div class='switch view-switch no-float j_switch_img_link'></div>";
        echo "<span class='switch-label'>" . GetMessage("FILE_TITLE") . "</span>";
    } else {
        if ($arResult["PROPERTIES"]["GOODS_PUBLICATIONS"]["VALUE"] != "") {
            echo "<span class='switch-label float-left'>" . GetMessage("PUBLICATION_TITLE") . "</span>";
        }
        if ($arResult["PROPERTIES"]["GOODS_DOCS"]["VALUE"] != "") {
            echo "<span class='switch-label float-left'>" . GetMessage("FILE_TITLE") . "</span>";
        }
    }
    echo "</div>";

    if ($arResult["PROPERTIES"]["GOODS_PUBLICATIONS"]["VALUE"] != "") {
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
                "IBLOCK_ID" => "3",
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
    }
    if ($arResult["PROPERTIES"]["GOODS_DOCS"]["VALUE"] != "") {
        echo "<ul class='hidden j_switch_img_item' data-switch='2'>";
        foreach ($arResult["PROPERTIES"]["GOODS_DOCS"]["VALUE"] as $file) {
            echo "<li><a href='" . $file["SRC"] . "'>" . $file["ORIGINAL_NAME"] . "</a></li><br/>";
        }
        echo "</ul>";
    }
    echo "</aside>";
}
echo "</section>";
if (!empty($arResult["PROPERTIES"]["RELATED"]["VALUE"])) :?>
    <?
    $GLOBALS['arRelatedFilter'] = Array(
        '=ID' => $arResult["PROPERTIES"]["RELATED"]["VALUE"],
    );
    ?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "product-related",
        Array(
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "N",
            "AJAX_MODE" => "N",
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => $arParams['IBLOCK_ID'],
            "NEWS_COUNT" => "1000",
            "SORT_BY1" => "ACTIVE_FROM",
            "SORT_ORDER1" => "DESC",
            "SORT_BY2" => "SORT",
            "SORT_ORDER2" => "ASC",
            "FILTER_NAME" => "arRelatedFilter",
            "FIELD_CODE" => array('DETAIL_PICTURE'),
            "PROPERTY_CODE" => array("SKU"),
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
    );?>
<? endif; ?>