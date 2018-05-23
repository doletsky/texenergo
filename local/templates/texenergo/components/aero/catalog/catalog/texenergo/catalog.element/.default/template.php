<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$uri = $APPLICATION->GetCurUri();
    $bisTrustedUser = isTrustedUser(); // пользователю разрешено показ остатков
?>

<script src="<?= SITE_TEMPLATE_PATH ?>/js/dont_replace/product-page.js"></script>

<div class="twelve">
    <h1 class="headline headline-product">
        <?php
        echo $arResult["NAME"];
        /*if($arResult["PROPERTIES"]["GOODS_ART"]["VALUE"] != "") {
            echo " <b>".$arResult["PROPERTIES"]["GOODS_ART"]["VALUE"]."</b>";
        }*/
        ?>
    </h1>
    <aside class="share">
        <ul>
            <?
            if ($USER->IsAuthorized()) {
                if (isset($arResult["PROPERTIES"]["SUBSCRIBER_USER"]["VALUE"])) {
                    if (in_array($USER->GetId(), $arResult["PROPERTIES"]["SUBSCRIBER_USER"]["VALUE"])) {
                        echo "<li><a href='#' class='j-auth-delete-from-subscribe active' rel='" . $arResult['ID'] . "' title='Отписаться от товара'><img src='" . SITE_TEMPLATE_PATH . "/img/catalog/rss.png' alt='Отписаться от товара'></a></li>";
                    } else {
                        echo "<li><a href='#' class='j-auth-add-to-subscribe' rel='" . $arResult['ID'] . "' title='Подписаться на товар'><img src='" . SITE_TEMPLATE_PATH . "/img/catalog/rss.png' alt='Подписаться на товар'></a></li>";
                    }
                }
            } else {
                echo "<li><a href='#' title='Подписаться на товар'><img src='" . SITE_TEMPLATE_PATH . "/img/catalog/rss.png' alt='Подписаться на товар'></a></li>";
            }
            ?>
            <li><img src="<?= SITE_TEMPLATE_PATH ?>/img/catalog/share.png" alt="Поделиться с друзьями"></li>
        </ul>
        <?$APPLICATION->IncludeComponent("bitrix:main.share", ".default", array(
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
            false
        );?>
    </aside>
</div>
<div class="twelve">
<aside class="pSidebar">
<div class="pWarranty clearfix">
    <img src="<?= SITE_TEMPLATE_PATH ?>/img/product/good.png" alt="<?php echo GetMessage("SLOGAN_ITEM"); ?>"/>

    <div class="pWarrantyDiv">
        <span><?php echo GetMessage("WARRANTY_ITEM"); ?></span>
        <span><?php echo GetMessage("SLOGAN_ITEM"); ?></span>
    </div>
</div>
<div class="pRateBlock clearfix">
    <?php
    echo "<div class='cat-rating cat-rating-big cat-value-raiting";
    if ($arResult['PROPERTIES']['GOODS_RATE']['VALUE'] != "") {
        echo "-" . $arResult['PROPERTIES']['GOODS_RATE']['VALUE'];
    }
    echo "'></div>";
    if ($arResult['PROPERTIES']['BLOG_COMMENTS_CNT']['VALUE'] != "") {
        echo "<span><a href='#tReview'>" . $arResult['PROPERTIES']['BLOG_COMMENTS_CNT']['VALUE'] . " " . GetMessage("REVIEW_TEXT_" . wordForm($arResult['PROPERTIES']['BLOG_COMMENTS_CNT']['VALUE'])) . "</a></span>";
    }
    ?>
</div>
<div class="discounted-block hidden">
    Уцененный товар
</div>
<?php

if ($arResult['CATALOG_QUANTITY'] > 0) {
    global $arItemDataFilter;
    $arItemDataFilter = Array(
        "ACTIVE" => "Y",
        "PROPERTY_GOODS.ID" => $arResult['ID'],
    );
    $APPLICATION->IncludeComponent("texenergo:offer.list", "product-data", array(
            "IBLOCK_TYPE" => "shop_articles",
            "IBLOCK_ID" => "9",
            "NEWS_COUNT" => "1000",
            "SORT_BY1" => "ACTIVE_FROM",
            "SORT_ORDER1" => "DESC",
            "SORT_BY2" => "SORT",
            "SORT_ORDER2" => "ASC",
            "FILTER_NAME" => 'arItemDataFilter',
            "FIELD_CODE" => array(
                0 => "ID",
                1 => "DATE_ACTIVE_TO",
                2 => "ACTIVE_TO",
                3 => "",
            ),
            "PROPERTY_CODE" => array(
                0 => "",
                1 => "PROPERTY_GOODS",
                2 => "",
            ),
            "CHECK_DATES" => "Y",
            "DETAIL_URL" => "",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_FILTER" => "N",
            "CACHE_GROUPS" => "Y",
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
            "PAGER_TEMPLATE" => ".default",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "PAGER_TITLE" => "Новости",
            "PAGER_SHOW_ALWAYS" => "Y",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "Y",
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "Y",
            "AJAX_OPTION_ADDITIONAL" => ""
        ),
        false
    );
}
?>
<div class="pPriceBlock">
    <?php
    $price = CPrice::GetBasePrice($arResult['ID']);
    if ($USER->IsAuthorized()) {
        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();

        if ($price["PRICE"]) {
            if ($arUser["UF_DISCOUNT"] != "" && $arUser["UF_DISCOUNT"] > 0) {
                echo "<span class='pPriceBig'>" . $price["PRICE"] . "<i class='rouble'>a</i></span>";
                if ($arResult["PROPERTIES"]["SHOW_OLD_PRICE"]["VALUE"]) {
                    echo "<span class='pPriceSmall'>" . $arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"] . "Р</span>";
                }
            } else {
                echo "<span class='pPriceBig'>" . $price["PRICE"] . "<i class='rouble'>a</i></span>";
                if ($arResult["PROPERTIES"]["SHOW_OLD_PRICE"]["VALUE"]) {
                    echo "<span class='pPriceSmall'>" . $arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"] . "<i class='rouble'>a</i></span>";
                }
            }
        }
    } else {
        if ($price["PRICE"]) {
            echo "<span class='pPriceBig'>" . $price["PRICE"] . "<i class='rouble'>a</i></span>";
        }
    }
    if ($arResult["PROPERTIES"]["GOODS_BONUS"]["VALUE"] != "") {
        echo "<span class='pBonuses'>" . $arResult["PROPERTIES"]["GOODS_BONUS"]["VALUE"] . " " . GetMessage("BONUS_WORD_" . wordForm($arResult["PROPERTIES"]["GOODS_BONUS"]["VALUE"])) . "</span>";
    }
    ?>
</div>
<?php
if ($arResult['BUY_URL'] || $arResult['COMPARE_URL']) {
    echo "<div class='pCart clearfix'>";
    if ($arResult['BUY_URL']) {
        echo "<a href='" . $arResult['BUY_URL'] . "' class='pInCart clearfix' title='" . GetMessage("ADD_TO_CART") . "'>";
        echo "<span class='pInCartImg'></span><span>" . GetMessage("CT_BCE_CATALOG_ADD") . "</span>";
        echo "</a>";
    }
    if ($arResult['COMPARE_URL']) {
        echo "<div class='pCartButtons clearfix'>";
        if ($USER->IsAuthorized()) {
            if (isset($arResult["PROPERTIES"]["F_USER"]["VALUE"])) {
                if (in_array($USER->GetId(), $arResult["PROPERTIES"]["F_USER"]["VALUE"])) {
                    echo "<a href='#' class='j-auth-delete-from-favorite active' rel='" . $arResult['ID'] . "' title='" . GetMessage("DEL_RFOM_FAVORITES") . "'></a>";
                } else {
                    echo "<a href='#' class='j-auth-add-to-favorite' rel='" . $arResult['ID'] . "' title='" . GetMessage("ADD_TO_FAVORITES") . "'></a>";
                }
            }
        } else {
            echo "<a href='#' title='" . GetMessage("ADD_TO_FAVORITES") . "'></a>";
            /*$favoriteGoods = isset($_COOKIE['favorite_goods']) ? explode(',', $_COOKIE['favorite_goods']) : array();

            if(in_array($arResult['ID'], $favoriteGoods)){
                echo "<a href='#' class='j-delete-from-favorite active' rel='". $arResult['ID'] ."' title='".GetMessage("DEL_RFOM_FAVORITES")."'></a>";
            }
            else{
                echo "<a href='#' class='j-add-to-favorite' rel='". $arResult['ID'] ."' title='".GetMessage("ADD_TO_FAVORITES")."'></a>";
            }*/
        }
        if ($arResult['IN_COMPARE_LIST'] == "") {
            if ($arResult['COMPARE_URL']) {
                echo "<a class='j-img-animate-element-page j-add-to-compare' data-id='" . $arResult['ID'] . "' href='#' title='" . GetMessage("ADD_TO_COMPARE") . "'></a>";
            }
        } else {
            if ($arResult['COMPARE_DELETE_URL']) {
                echo "<a href='#' class='active j-delete-from-compare' data-id='" . $arResult['ID'] . "' title='" . GetMessage("DEL_FROM_COMPARE") . "'></a>";
            }
        }

//                         if($arResult['IN_COMPARE_LIST'] == "") {
//                             if($arResult['COMPARE_URL']) { echo "<a class='j-img-animate-element-page' href='".$arResult['COMPARE_URL']."' title='".GetMessage("ADD_TO_COMPARE")."'></a>"; }
//                         } else {
//                             if($arResult['COMPARE_DELETE_URL']) { echo "<a href='".$arResult['COMPARE_DELETE_URL']."' class='active' title='".GetMessage("DEL_FROM_COMPARE")."'></a>"; }
//                         }
        echo "</div>";
    }
    echo "<div class='pCartQuantity'><span class='text'>Остаток:</span>";
    if(isTrustedUser()){?>
    <div class=''><?//=$arResult['CATALOG_QUANTITY']?><?=getProductRestsInterval($arResult)?></div></div>
    <?}
    //else 
	{?>
    <div class=''><?//=getProductRestsInterval($arResult)?></div></div>
    <?}
    echo "</div>";
}
?>

<!-- Остаток -->
<!-- <span class='pCartQnt'>".GetMessage("QUANTITY_TEXT_".$arResult["PROPERTIES"]["REST_INDICATE"]["VALUE"])."</span>" -->

<nav class="pCartNav">
    <ul>
        <li><img src="<?= SITE_TEMPLATE_PATH ?>/img/product/mouse-icon.png" alt="заказать в один клик"><a href="#">Заказать
                в один клик</a></li>
        <li><img src="<?= SITE_TEMPLATE_PATH ?>/img/product/phone-icon.png" alt="заказать в один клик"><a href="#">Обратный
                звонок</a></li>
        <li><img src="<?= SITE_TEMPLATE_PATH ?>/img/product/car-icon.png" alt="заказать в один клик"><a href="#">Варианты
                доставки</a></li>
    </ul>
</nav>
<div class='pTechnicals'>
    <h3><?= GetMessage("FEATURES_TITLE"); ?></h3>
    <table class="pTechnicalsMain">
        <? foreach ($arResult['DISPLAY_PROPERTIES'] as $arProp): ?>
            <? if (empty($arProp['DISPLAY_VALUE'])) continue; ?>
            <tr>
                <th><?= $arProp['NAME']; ?></th>

                <td><?= strip_tags($arProp['DISPLAY_VALUE']); ?></td>
            </tr>
        <? endforeach; ?>

        <? if ($arResult["ADITIONAL_PARAMS"]["WEIGHT"]): ?>
            <tr>
                <th>Вес</th>
                <td><?= $arResult["ADITIONAL_PARAMS"]["WEIGHT"]; ?></td>
            </tr>
        <? endif; ?>
        <? if ($arResult["ADITIONAL_PARAMS"]["WIDTH"]): ?>
            <tr>
                <th>Ширина</th>
                <td><?= $arResult["ADITIONAL_PARAMS"]["WIDTH"]; ?></td>
            </tr>
        <? endif; ?>
        <? if ($arResult["ADITIONAL_PARAMS"]["LENGTH"]): ?>
            <tr>
                <th>Длина</th>
                <td><?= $arResult["ADITIONAL_PARAMS"]["LENGTH"]; ?></td>
            </tr>
        <? endif; ?>
        <? if ($arResult["ADITIONAL_PARAMS"]["HEIGHT"]): ?>
            <tr>
                <th>Высота</th>
                <td><?= $arResult["ADITIONAL_PARAMS"]["HEIGHT"]; ?></td>
            </tr>
        <? endif; ?>

    </table>
    <?php
    if ($arResult['PROPERTIES']['GOODS_FILTER_VALUE']['VALUE'] != "" || $arResult['PROPERTIES']['GOODS_ART']['VALUE'] != "" || $arResult['PROPERTIES']['GOODS_PRICE_OPT1']['VALUE'] != "" || $arResult['PROPERTIES']['GOODS_PRICE_OPT2']['VALUE'] != "" || $arResult['PROPERTIES']['GOODS_PRICE_OPT3']['VALUE'] != "" || $arResult['XML_ID'] != "" || $arResult['PROPERTIES']['GOODS_BAR_CODE']['VALUE'] != "") {

        // $test = CCatalogProduct::GetByIDEx($arResult["ID"]);
        // echo '<pre style="font-size:12px">'; var_dump($test); echo '</pre>';

        if ($arResult['PROPERTIES']['GOODS_ART']['VALUE'] != "" || $arResult['PROPERTIES']['GOODS_PRICE_OPT1']['VALUE'] != "" || $arResult['PROPERTIES']['GOODS_PRICE_OPT2']['VALUE'] != "" || $arResult['PROPERTIES']['GOODS_PRICE_OPT3']['VALUE'] != "" || $arResult['XML_ID'] != "") {
            echo "<ul class='pTechnicalsSecond'>";
            if ($arResult['PROPERTIES']['GOODS_ART']['VALUE'] != "") {
                echo "<li><span class='pSecondHeader'>" . GetMessage("ART_TITLE") . "</span><span class='pSecondValue'>" . $arResult['PROPERTIES']['GOODS_ART']['VALUE'] . "</span></li>";
            }
            if ($arResult['PROPERTIES']['REFERENCE']['VALUE'] != "") {
                echo "<li><span class='pSecondHeader'>" . GetMessage("REFERENCE_TITLE") . "</span><span class='pSecondValue'>" . $arResult['PROPERTIES']['REFERENCE']['VALUE'] . "</span></li>";
            }
            if ($arResult['PROPERTIES']['GOODS_PRICE_OPT1']['VALUE'] != "") {
                echo "<li><span class='pSecondHeader'>" . GetMessage("OPT1_TITLE") . "</span><span class='pSecondValue pSecondPrice'>" . $arResult['PROPERTIES']['GOODS_PRICE_OPT1']['VALUE'] . " <i class=rouble>a</i>.</span></li>";
            }
            if ($arResult['PROPERTIES']['GOODS_PRICE_OPT2']['VALUE'] != "") {
                echo "<li><span class='pSecondHeader'>" . GetMessage("OPT2_TITLE") . "</span><span class='pSecondValue pSecondPrice'>" . $arResult['PROPERTIES']['GOODS_PRICE_OPT2']['VALUE'] . " <i class='rouble'>a</i></span></li>";
            }
            if ($arResult['PROPERTIES']['GOODS_PRICE_OPT3']['VALUE'] != "") {
                echo "<li><span class='pSecondHeader'>" . GetMessage("OPT3_TITLE") . "</span><span class='pSecondValue pSecondPrice'>" . $arResult['PROPERTIES']['GOODS_PRICE_OPT3']['VALUE'] . " <i class='rouble'>a</i></span></li>";
            }
            echo "</ul>";
        }
        if ($arResult['PROPERTIES']['GOODS_BAR_CODE']['VALUE'] != "") {
            echo "<div class='pBarcodeCont'>";
            $barCode = (int)$arResult['PROPERTIES']['GOODS_BAR_CODE']['VALUE'];
            $APPLICATION->IncludeComponent(
                "coderoid:barcode",
                "",
                Array(
                    "CODE" => $arResult['PROPERTIES']['GOODS_BAR_CODE']['VALUE'], // штрикод, обязательное поле. Подставить 12 или 13 значный штрих код, например так $arResult['PROPERTIES']['BARCODE']['VALUE']
                    "CLASS" => "", // необязательное поле. Css класс.
                    "ID" => "", // необязательное поле. Id аттрибут.
                    "SCALE" => "1", // размер изображения, обязательное поле (значения от 1 до 7).
                    "MODE" => "png" // формат изображения, обязательное поле(значения: 'png', 'jpg',  'jpeg', 'gif'
                ),
                false
            );
            echo "</div>";
        }
    }
    ?>
</div>
<div class="pDilers j_dealer_block">
    <h3><span class="j_dealer_link"><?php echo GetMessage("FOR_DILLERS"); ?></span></h3>

    <div class="pDilers-info hidden j_dealer_toggle">
        <?php
        $APPLICATION->IncludeFile('/include/dealer_info_inc.php', array(),
            array(
                'MODE' => 'html',
                'TEMPLATE' => 'dealer_info_inc.php',
            )
        );
        ?>
    </div>
</div>
</aside>
<section class="pContent">
<div class="tabContainer" id="tabContainer">
<ul class="j_item_tab">
    <li><a class="tMain" href="#tMain"><?php echo GetMessage("VIEW_TITLE"); ?></a></li>
    <?php
    if (!empty($arResult['PROPERTIES']['SERIES']['VALUE'])) {
        echo "<li><a class='tLine' href='#tLine'>" . GetMessage("SERIA_TITLE") . "</a></li>";
    }

    if (!empty($arResult["PROPERTIES"]["ANALOGS"]["VALUE"])) {
        echo " <li><a class='tAnalogs' href='#tAnalogs'>" . GetMessage("ANALOG_TITLE") . "</a></li>";
    }
    if ($arResult["PROPERTIES"]["IS_PRICE_DOWN"]["VALUE"] == "Y") {
        echo " <li><a class='tDiscounts' href='#tMarkdown'>" . GetMessage("REJECT_TITLE") . "</a></li>";
    }
    ?>
</ul>
<section class="tMainSection clearfix" id="tMain">
<?php
echo "<section class='pPromoRow'>";
$APPLICATION->IncludeFile('/include/product_action_inc.php', array(),
    array(
        'MODE' => 'html',
        'TEMPLATE' => 'product_action_inc.php',
    )
);
echo "</section>";
/*global $arIdItemFilter;
$arIdItemFilter = Array(
    "ACTIVE" => "Y",
    "PROPERTY_GOODS.ID" => $arResult['ID'],
);
$APPLICATION->IncludeComponent("texenergo:offer.list", "action", array(
    "IBLOCK_TYPE" => "shop_articles",
    "IBLOCK_ID" => "9",
    "NEWS_COUNT" => "1000",
    "SORT_BY1" => "ACTIVE_FROM",
    "SORT_ORDER1" => "DESC",
    "SORT_BY2" => "SORT",
    "SORT_ORDER2" => "ASC",
    "FILTER_NAME" => 'arIdItemFilter',
    "FIELD_CODE" => array(
        0 => "ID",
        1 => "DATE_ACTIVE_TO",
        2 => "ACTIVE_TO",
        3 => "",
    ),
    "PROPERTY_CODE" => array(
        0 => "",
        1 => "PROPERTY_GOODS",
        2 => "",
    ),
    "CHECK_DATES" => "Y",
    "DETAIL_URL" => "",
    "AJAX_MODE" => "N",
    "AJAX_OPTION_JUMP" => "N",
    "AJAX_OPTION_STYLE" => "Y",
    "AJAX_OPTION_HISTORY" => "N",
    "CACHE_TYPE" => "A",
    "CACHE_TIME" => "36000000",
    "CACHE_FILTER" => "N",
    "CACHE_GROUPS" => "Y",
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
    "PAGER_TEMPLATE" => ".default",
    "DISPLAY_TOP_PAGER" => "N",
    "DISPLAY_BOTTOM_PAGER" => "Y",
    "PAGER_TITLE" => "Новости",
    "PAGER_SHOW_ALWAYS" => "Y",
    "PAGER_DESC_NUMBERING" => "N",
    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
    "PAGER_SHOW_ALL" => "Y",
    "DISPLAY_DATE" => "Y",
    "DISPLAY_NAME" => "Y",
    "DISPLAY_PICTURE" => "Y",
    "DISPLAY_PREVIEW_TEXT" => "Y",
    "AJAX_OPTION_ADDITIONAL" => ""
    ),
    false
);*/

echo "<section class='pView";

if (!empty($arResult["PROPERTIES"]["ACCESSORIES"]["VALUE"])) {
    echo " product-img-slider";
}
" clearfix";

if (empty($arResult["PROPERTIES"]["ACCESSORIES"]["VALUE"])) {
    echo " pView-big";
}

echo " j_switch_img_block product-img-block' ><figure class='pProductImage j-detail-product-img'>";

if ($arResult["PROPERTIES"]["GOODS_SHEME"]["VALUE"] != "") {
    echo "<div class='switch-grp'><div class='switch switch-inl view-switch j_switch_img_link'></div><span class='switch-label j_switch_img_item' data-switch='1'>" . GetMessage("SHEME_TITLE") . "</span><span class='switch-label hidden j_switch_img_item' data-switch='2'>" . GetMessage("FOTO_TITLE") . "</span></div>";
    echo "<img src='" . $arResult["PROPERTIES"]["GOODS_SHEME"]["VALUE"]["SRC"] . "' alt='" . $arResult["NAME"] . "' data-switch='2' class='hidden j_switch_img_item'>";
}
if ($arResult["DETAIL_PICTURE"]["SRC"]) {
    echo "<img src='" . $arResult["DETAIL_PICTURE"]["SRC"] . "' alt='" . $arResult["NAME"] . "' data-switch='1' class='j-image-to-animate j_switch_img_item j_preview_img_main_" . $arResult["ID"] . "'>";
} else {
    echo "<img class='j-image-to-animate' src='/local/templates/texenergo/img/catalog/no-image.png' alt='" . $arResult["NAME"] . "'>";
}

echo "</figure>";

if ($arResult["PROPERTIES"]["GOODS_PHOTOS"]["VALUE"] != "") {
    echo "<div class='pSlider clearfix'><ul class='owl-carousel j_owl_slider_4 j_switch_img_item' data-switch='1'>";
    echo "<li class='j_preview_img active' data-id='" . $arResult["ID"] . "'><img src='";
    if ($arResult["DETAIL_PICTURE"]["SRC"]) {
        echo $arResult["DETAIL_PICTURE"]["SRC"];
    }
    echo "' alt='" . $arResult["NAME"] . "'></li>";
    foreach ($arResult["PROPERTIES"]["GOODS_PHOTOS"]["VALUE"] as $foto) {

        echo "<li class='j_preview_img' data-id='" . $arResult["ID"] . "'><img src='" . $foto["SRC"] . "' alt='" . $arResult["NAME"] . "'></li>";
    }
    echo "</ul></div>";
}
echo "</section>";

// Put img in hidden div
echo '<div class="j-product-img-storage" style="display: none;">';

if ($arResult["PREVIEW_PICTURE"]["SRC"]) {
    echo "<img src='" . $arResult["DETAIL_PICTURE"]["SRC"] . "' alt='" . $arResult["NAME"] . "'>";
}

if ($arResult["PROPERTIES"]["GOODS_PHOTOS"]["VALUE"] != "") {
    foreach ($arResult["PROPERTIES"]["GOODS_PHOTOS"]["VALUE"] as $foto) {
        echo "<img src='" . $foto["SRC"] . "' alt='" . $arResult["NAME"] . "'>";
    }
}


echo '</div>';
if (!empty($arResult["PROPERTIES"]["ACCESSORIES"]["VALUE"])) {
    global $arAnalogFilter;
    $arAnalogFilter = Array(
        "ACTIVE" => "Y",
        "ID" => $arResult["PROPERTIES"]["ACCESSORIES"]["VALUE"],
    );
    $APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "accessory",
        Array(
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "N",
            "AJAX_MODE" => "N",
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => "1",
            "NEWS_COUNT" => "1000",
            "SORT_BY1" => "ACTIVE_FROM",
            "SORT_ORDER1" => "DESC",
            "SORT_BY2" => "SORT",
            "SORT_ORDER2" => "ASC",
            "FILTER_NAME" => "arAnalogFilter",
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
echo "<section class='pOverview clearfix'>";
echo "<section class='pCopy";
if ($arResult["PROPERTIES"]["GOODS_PUBLICATIONS"]["VALUE"] == "" && $arResult["PROPERTIES"]["GOODS_DOCS"]["VALUE"] == "") {
    echo " pCopy-big";
}
echo "'>" . $arResult["DETAIL_TEXT"] . "</section>";
if ($arResult["PROPERTIES"]["GOODS_PUBLICATIONS"]["VALUE"] != "" || $arResult["PROPERTIES"]["GOODS_DOCS"]["VALUE"] != "") {
    echo "<aside class='pPublications j_switch_img_block'>";
    echo "<div class='switch-sbgrp'>";
    if ($arResult["PROPERTIES"]["GOODS_PUBLICATIONS"]["VALUE"] != "" && $arResult["PROPERTIES"]["GOODS_DOCS"]["VALUE"] != "") {
        echo "<span class='switch-label float-left'>" . GetMessage("FILE_TITLE") . "</span>";
        echo "<div class='switch view-switch no-float j_switch_img_link'></div>";
        echo "<span class='switch-label'>" . GetMessage("PUBLICATION_TITLE") . "</span>";
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
        global $arPublicFilter;
        $arPublicFilter = Array(
            "ACTIVE" => "Y",
            "ID" => $arResult["PROPERTIES"]["GOODS_PUBLICATIONS"]["VALUE"],
        );
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

        echo "<div id='block-files' class='j_switch_img_item' data-switch='1'>";

        foreach ($arResult["PROPERTIES"]["GOODS_DOCS"]["VALUE"] as $file) {

            $type = end(explode(".", $file["FILE_NAME"]));

            $classMass = array();

            $classMass['doc'] = 'file-modification';

            $classMass['xls'] = 'file-technical';

            $classMass['pdf'] = 'file-instruction';

            echo "<a class='link-file " . $classMass[$type] . "' href='" . $file["SRC"] . "'>" . $file["ORIGINAL_NAME"] . "</a>";
        }
        echo "</div>";
    }
    echo "</aside>";
}
echo "</section>";
if (!empty($arResult["PROPERTIES"]["RELATED"]["VALUE"])) {
    global $arAssociatedFilter;
    $arAssociatedFilter = Array(
        "ACTIVE" => "Y",
        "ID" => $arResult["PROPERTIES"]["RELATED"]["VALUE"],
    );
    $APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "associated",
        Array(
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "N",
            "AJAX_MODE" => "N",
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => "1",
            "NEWS_COUNT" => "1000",
            "SORT_BY1" => "ACTIVE_FROM",
            "SORT_ORDER1" => "DESC",
            "SORT_BY2" => "SORT",
            "SORT_ORDER2" => "ASC",
            "FILTER_NAME" => "arAssociatedFilter",
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
?>
</section> <? //End tMain?>
<?php
if (isset($arResult['arSerial']) && !empty($arResult['arSerial'])) {
    echo "<section class='tLineSection' id='tLine'>";

    $id = $arResult['arSerial']['ID'];
    $parent = CIBlockElement::GetByID($id);
    $ar_res = $parent->GetNext();
    if ($ar_res) {
        echo "<header>";
        echo "<h1 class='pAdditionalHeader pAdditionalHeader-tline'>" . $ar_res["NAME"] . "</h1>";
        if ($ar_res["PREVIEW_TEXT"] != "") {
            echo "<div class='pText'>" . $ar_res["PREVIEW_TEXT"] . "</div>";
        }
        echo "</header>";
    }
    echo "<div class='pCompareLine clearfix'><h1 class='pAdditionalHeader pAdditionalHeader-tline left'>Другие товары этой серии</h1>";
    echo "<a href='/personal/compare.php?serial=" . $id . "' class='rounded rounded-reviews right center'>Сравнить всю серию</a>";
    echo "</div>";
    ?>
    <nav class="sort">
        <?php
        $goodsFullViewOn = isset($_COOKIE['goods_full_view']) ? (int)$_COOKIE['goods_full_view'] : 0;
        $views = array('0' => 'Кратко', '1' => 'Списком', '2' => 'Подробно');
        $types = getSortFieldArray();
        $sortId = isset($_COOKIE['catalog_sort']) ? (int)$_COOKIE['catalog_sort'] : 4;
        ?>
        <!--                         <span class="text"><?php echo GetMessage("SORT_TITLE"); ?></span>
                        <span class="selector"><?php echo GetMessage("SORT_" . $sortId); ?></span>
                        <span class="text"><?php echo GetMessage("VIEW_TITLE_2"); ?></span>
                        <span class="selector"><?php if ($goodsFullViewOn == 0) {
            echo GetMessage("VIEW_1");
        } else {
            echo GetMessage("VIEW_0");
        } ?></span>
                        <span id="seria-top-pagination"></span> -->
        <div class="clear"></div>
        <?php
        echo '<span class="text">' . GetMessage("SORT_TITLE") . '</span><select class="j-catalog-sort-id j-sort-select">';
        foreach ($types as $type) {
            $selected = '';
            if ($type['CODE'] == $sortId) {
                $selected = 'selected';
            }
            echo '<option value="' . $type['CODE'] . '" ' . $selected . '>' . $type['NAME'] . '</option>';
        }
        echo '</select>';

        $arParams["ELEMENT_SORT_FIELD"] = getSortFieldById($sortId);
        $arParams["ELEMENT_SORT_ORDER"] = getSortTypeById($sortId);;

        echo '<span class="text">' . GetMessage("VIEW_TITLE_2") . '</span><select class="j-goods-full-view j-sort-select">';
        foreach ($views as $key => $value) {
            $selected = ((int)$key == (int)$goodsFullViewOn) ? 'selected' : '';
            echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
        }
        echo '</select>';
        ?>
    </nav>

    <?php

    global $arSeriaItems;
    $arSeriaItems = Array(
        "ACTIVE" => "Y",
        "PROPERTY_SERIES" => $arResult['PROPERTIES']['SERIES']['VALUE'],
        "!%ID" => $arResult["ID"],
    );

    if ($goodsFullViewOn == 0) {
        $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "product-seria-full",
            Array(
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "N",
                "AJAX_MODE" => "N",
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_ID" => "1",
                "NEWS_COUNT" => "3",
                "SORT_BY1" => $arParams["ELEMENT_SORT_FIELD"],
                "SORT_ORDER1" => $arParams["ELEMENT_SORT_ORDER"],
                "SORT_BY2" => $arParams["ELEMENT_SORT_FIELD2"],
                "SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                "FILTER_NAME" => "arSeriaItems",
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
                "PAGER_TEMPLATE" => "top-pagenavigation",
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
    } else if ($goodsFullViewOn == 2) {
        $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "product-seria-short",
            Array(
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "N",
                "AJAX_MODE" => "N",
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_ID" => "1",
                "NEWS_COUNT" => "3",
                "SORT_BY1" => $arParams["ELEMENT_SORT_FIELD"],
                "SORT_ORDER1" => $arParams["ELEMENT_SORT_ORDER"],
                "SORT_BY2" => $arParams["ELEMENT_SORT_FIELD2"],
                "SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                "FILTER_NAME" => "arSeriaItems",
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
                "PAGER_TEMPLATE" => "top-pagenavigation",
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
    } else if ($goodsFullViewOn == 1) {

        $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "product-seria-list",
            Array(
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "N",
                "AJAX_MODE" => "N",
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_ID" => "1",
                "NEWS_COUNT" => "3",
                "SORT_BY1" => $arParams["ELEMENT_SORT_FIELD"],
                "SORT_ORDER1" => $arParams["ELEMENT_SORT_ORDER"],
                "SORT_BY2" => $arParams["ELEMENT_SORT_FIELD2"],
                "SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                "FILTER_NAME" => "arSeriaItems",
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
                "PAGER_TEMPLATE" => "top-pagenavigation",
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
    echo "</section>"; //End tLine
}
?>

<? if (!empty($arResult["PROPERTIES"]["ANALOGS"]["VALUE"])): ?>

    <section id='tAnalogs'>

        <?
        global $arAccessoriesFilter;

        $arAccessoriesFilter = Array(
            "ACTIVE" => "Y",
            "ID" => $arResult["PROPERTIES"]["ANALOGS"]["VALUE"],

        );
        $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "accessory-tab",
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
                "FILTER_NAME" => "arAccessoriesFilter",
                "FIELD_CODE" => array("DETAIL_PICTURE", "CATALOG_WEIGHT", "CATALOG_LENGTH", "CATALOG_MEASURE", "CATALOG_QUANTITY", "CATALOG_HEIGHT", "CATALOG_AVAILABLE", "CATALOG_WIDTH"),
                "PROPERTY_CODE" => array("GOODS_ART"),
                "CHECK_DATES" => "Y",
                "DETAIL_URL" => $arParams['DETAIL_URL'],
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

        ?>


    </section>

<? endif; ?>

<?
if ($arResult["PROPERTIES"]["GOODS_CUT_PRICE"]["VALUE"] == "Да") {
    echo "<section class='tMainSection clearfix' id='tMarkdown'>";
    echo "<section class='pPromoRow'>";
    $APPLICATION->IncludeFile('/include/product_action_inc.php', array(),
        array(
            'MODE' => 'html',
            'TEMPLATE' => 'product_action_inc.php',
        )
    );
    echo "</section>";
    /*$APPLICATION->IncludeComponent("texenergo:offer.list", "action", array(
        "IBLOCK_TYPE" => "shop_articles",
        "IBLOCK_ID" => "9",
        "NEWS_COUNT" => "1000",
        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_ORDER1" => "DESC",
        "SORT_BY2" => "SORT",
        "SORT_ORDER2" => "ASC",
        "FILTER_NAME" => 'arIdItemFilter',
        "FIELD_CODE" => array(
            0 => "ID",
            1 => "DATE_ACTIVE_TO",
            2 => "ACTIVE_TO",
            3 => "",
        ),
        "PROPERTY_CODE" => array(
            0 => "",
            1 => "PROPERTY_GOODS",
            2 => "",
        ),
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
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
        "PAGER_TEMPLATE" => ".default",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "PAGER_TITLE" => "Новости",
        "PAGER_SHOW_ALWAYS" => "Y",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "Y",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "AJAX_OPTION_ADDITIONAL" => ""
        ),
        false
    );*/


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
        if ($arResult["PROPERTIES"]["PRICE_DOWN_REASON"]["VALUE"] != "") {
            echo "<div class='markdownMessage'><span>" . $arResult["PROPERTIES"]["PRICE_DOWN_REASON"]["VALUE"] . "</span></div>";
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
    }
    if (!empty($arResult["PROPERTIES"]["ACCESSORIES"]["VALUE"])) {
        $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "accessory",
            Array(
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "N",
                "AJAX_MODE" => "N",
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_ID" => "1",
                "NEWS_COUNT" => "1000",
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_ORDER1" => "DESC",
                "SORT_BY2" => "SORT",
                "SORT_ORDER2" => "ASC",
                "FILTER_NAME" => "arAnalogFilter",
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
    echo "<section class='pOverview clearfix'>";
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
    if (!empty($arResult["PROPERTIES"]["RELATED"]["VALUE"])) {
        $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "associated",
            Array(
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "N",
                "AJAX_MODE" => "N",
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_ID" => "1",
                "NEWS_COUNT" => "1000",
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_ORDER1" => "DESC",
                "SORT_BY2" => "SORT",
                "SORT_ORDER2" => "ASC",
                "FILTER_NAME" => "arAssociatedFilter",
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
        echo "</section>"; //End tMarkdown
    }
}
if ('Y' == $arParams['USE_COMMENTS']) { //Start comment

    ?>
    <div class="j_comments_block">
        <?$APPLICATION->IncludeComponent(
            "bitrix:catalog.comments",
            "product",
            array(
                "ELEMENT_ID" => $arResult['ID'],
                "ELEMENT_CODE" => "",
                "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                "URL_TO_COMMENT" => "",
                "WIDTH" => "",
                "COMMENTS_COUNT" => "1000",
                "BLOG_USE" => $arParams['BLOG_USE'],
                "FB_USE" => $arParams['FB_USE'],
                "FB_APP_ID" => $arParams['FB_APP_ID'],
                "VK_USE" => $arParams['VK_USE'],
                "VK_API_ID" => $arParams['VK_API_ID'],
                "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                "CACHE_TIME" => $arParams['CACHE_TIME'],
                "BLOG_TITLE" => "",
                "BLOG_URL" => "",
                "PATH_TO_SMILE" => "/bitrix/images/blog/smile/",
                "EMAIL_NOTIFY" => "N",
                "AJAX_POST" => "Y",
                "SHOW_SPAM" => "Y",
                "SHOW_RATING" => "Y",
                "FB_TITLE" => "",
                "FB_USER_ADMIN_ID" => "",
                "FB_APP_ID" => $arParams['FB_APP_ID'],
                "FB_COLORSCHEME" => "light",
                "FB_ORDER_BY" => "reverse_time",
                "VK_TITLE" => "",
                "GOODS_RATE" => isset($arResult['PROPERTIES']['GOODS_RATE']) ? $arResult['PROPERTIES']['GOODS_RATE'] : ''
            ),
            $component,
            array("HIDE_ICONS" => "Y")
        );?>
    </div>
<?
} //End comment
?>
</div>
</section>
</div>