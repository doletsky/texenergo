<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


if (!empty($arResult['ITEMS']))

echo $arResult["NAV_STRING"];

{
	$strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
	$strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
	$arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));
    ?>
    <section class="cat-block">
        <header class="cat-header">
            <?php
            $UF_NEW_ITEMS = isset($arResult['SECTION']['UF_NEW_ITEMS']) && count($arResult['SECTION']['UF_NEW_ITEMS']) ? $arResult['SECTION']['UF_NEW_ITEMS'] : 0;
            $UF_LEADER_ITEMS = isset($arResult['SECTION']['UF_LEADER_ITEMS']) && count($arResult['SECTION']['UF_LEADER_ITEMS']) ? $arResult['SECTION']['UF_LEADER_ITEMS'] : 0;
            
           // if($arResult["ITEMS"][0]["DISPLAY_PROPERTIES"]["GOODS_IS_NEW"]["VALUE"] == "Да") { echo "<h1>".GetMessage("NEW_ITEM_TITLE")."</h1>"; }
            //if($arResult["ITEMS"][0]["DISPLAY_PROPERTIES"]["GOODS_BEST_SELLER"]["VALUE"] == "Да") { echo "<h1>".GetMessage("LEADER_ITEM_TITLE")."</h1>"; }
            ?>
        </header>
        <div class="col-headings">
            <ul>
                <li><?php echo GetMessage("MES_NAME"); ?></li>
                <li class="headings-rate"><?php echo GetMessage("MES_RATE"); ?></li>
                <li class="headings-price"><?php echo GetMessage("MES_PRICE"); ?></li>
                <li class="headings-rest"><?php echo GetMessage("MES_REST"); ?></li>
                <li><?php echo GetMessage("MES_BUY"); ?></li>
            </ul>
        </div>
        <div class="owl-slider owl-slider-inside-short j_owl_slider_1">
            <div class="clearfix">
                <?php
                $count = 0;
                $count2 = 1;
                foreach ($arResult['ITEMS'] as $key => $arItem)
                {
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
                    $strMainID = $this->GetEditAreaId($arItem['ID']);
                    ?>
                    <article class="cat-product cat-product-line clearfix<?php if($count == 0) { echo " first";} ?>" id="<?=$strMainID?>">
                        <?php
                        if($arItem["PROPERTIES"]["GOODS_IS_NEW"]["VALUE"] == "Да") { echo "<img src='".SITE_TEMPLATE_PATH."/img/catalog/new.png' alt='".$arItem["PROPERTIES"]["GOODS_IS_NEW"]["NAME"]."' class='new-in-img'>"; }
                        ?>
                        <div class="col line-thumbnail">
                            <?php
                            if($arItem["IBLOCK_SECTION_ID"] && $arItem["ID"]) {
                                echo "<a href='/catalog/?SECTION_ID=".$arItem["IBLOCK_SECTION_ID"]."&ELEMENT_ID=".$arItem["ID"]."'>";
                            }
                            if(is_array($arItem["PREVIEW_PICTURE"]))
                            {
                                echo "<img src='".$arItem["PREVIEW_PICTURE"]["SRC"]."' alt='".$arItem["NAME"]."'/>";
                            } else {
                                if(is_array($arItem["DETAIL_PICTURE"]))
                                {
                                    echo "<img src='".$arItem["DETAIL_PICTURE"]["SRC"]."' alt='".$arItem["NAME"]."'/>";
                                } else {
                                    echo "<img src='/local/templates/texenergo/img/catalog/no-image.png' alt='".$arItem['NAME']."'>";
                                }
                            }
                            if($arItem["IBLOCK_SECTION_ID"] && $arItem["ID"]) {
                                echo "</a>";
                            }
                            ?>
                        </div>
                        <div class="col line-product-info">
                            <?php
                            if($arItem["IBLOCK_SECTION_ID"] && $arItem["ID"]) {
                                echo "<a href='/catalog/?SECTION_ID=".$arItem["IBLOCK_SECTION_ID"]."&ELEMENT_ID=".$arItem["ID"]."' class='product-name product-name-line'>";
                            }
                            echo $arItem["NAME"];
                            if($arItem["IBLOCK_SECTION_ID"] && $arItem["ID"]) {
                                echo "</a>";
                            }
                            if($arItem["PROPERTIES"]["GOODS_ART"]["VALUE"] != "") { echo "<span class='product-number'>".$arItem["PROPERTIES"]["GOODS_ART"]["VALUE"]."</span>"; }
                            ?>
                        </div>
                        <div class="col rating-col">
                            <div
                                class="cat-rating cat-value-raiting<? if (!empty($arItem['PROPERTIES']['RAITING']['VALUE'])): ?>-<?= $arItem['PROPERTIES']['RAITING']['VALUE']; ?><? endif; ?>"></div>
                        </div>
                        <div class="col last-list-price-col">
                            <?php
                            $price = CPrice::GetBasePrice( $arItem['ID']);
                            $oldPrice = $arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"];
                            if($price["PRICE"]) {
                                echo "<span class='cat-price cat-price-line'>".$price["PRICE"]." <i class='rouble'>a</i></span>";
                            }
                            if ($oldPrice) {
                                echo "<span class='pLineOld old-price-last-list'>".$oldPrice."Р</span>";
                            }
                            ?>
                        </div>
                        <div class="col product-rest bottom-rest">
                            <div class="cat-indicator cat-white-indicator cat-value-indicator<?if($arItem['PROPERTIES']['REST_INDICATE']['VALUE'] != "") { echo "-".$arItem['PROPERTIES']['REST_INDICATE']['VALUE']; }?>"></div>
                        </div>
                        <div class="col cat-line-icons">
                            <?php
                            if($arItem['BUY_URL']) { echo "<a href='".$arItem['BUY_URL']."' class='cart-line' title='".GetMessage("ADD_TO_CART")."'><img src='".SITE_TEMPLATE_PATH."/img/catalog/cart-line.png' alt='".GetMessage("ADD_TO_CART")."'></a>"; }
                            if ($USER->IsAuthorized())
                            {
                                if (isset($arItem["PROPERTIES"]["F_USER"]["VALUE"])) {
                                    if(in_array($USER->GetId(),$arItem["PROPERTIES"]["F_USER"]["VALUE"])){
                                        echo "<a href='#' class='j-auth-delete-from-favorite active' rel='". $arItem['ID'] ."' title='".GetMessage("DEL_RFOM_FAVORITES")."'><img src='".SITE_TEMPLATE_PATH."/img/catalog/like-gray.png' alt='".GetMessage("DEL_RFOM_FAVORITES")."'></a>";
                                    }
                                    else{
                                        echo "<a href='#' class='j-auth-add-to-favorite' rel='". $arItem['ID'] ."' title='".GetMessage("ADD_TO_FAVORITES")."'><img src='".SITE_TEMPLATE_PATH."/img/catalog/like-gray.png' alt='".GetMessage("ADD_TO_FAVORITES")."'></a>";
                                    }
                                }
                            }else {
                                echo "<a href='#' title='".GetMessage("ADD_TO_FAVORITES")."'><img src='".SITE_TEMPLATE_PATH."/img/catalog/like-gray.png' alt='".GetMessage("ADD_TO_FAVORITES")."'></a>";
                                /*$favoriteGoods = isset($_COOKIE['favorite_goods']) ? explode(',', $_COOKIE['favorite_goods']) : array();

                                if(in_array($arItem['ID'], $favoriteGoods)){
                                    echo "<a href='#' class='j-delete-from-favorite active' rel='". $arItem['ID'] ."' title='".GetMessage("DEL_RFOM_FAVORITES")."'><img src='".SITE_TEMPLATE_PATH."/img/catalog/like-gray.png' alt='".GetMessage("DEL_RFOM_FAVORITES")."'></a>";
                                }
                                else{
                                    echo "<a href='#' class='j-add-to-favorite' rel='". $arItem['ID'] ."' title='".GetMessage("ADD_TO_FAVORITES")."'><img src='".SITE_TEMPLATE_PATH."/img/catalog/like-gray.png' alt='".GetMessage("ADD_TO_FAVORITES")."'></a>";
                                }*/
                            }
                            if($arItem['COMPARE_URL']) { echo "<a href='#' class='j-add-to-compare' data-id='".$arItem['ID']."' title='".GetMessage("ADD_TO_COMPARE")."'><img src='".SITE_TEMPLATE_PATH."/img/catalog/bars-gray.png' alt='".GetMessage("ADD_TO_COMPARE")."'></a>"; }
                            ?>
                        </div>
                    </article>
                    <?
                    if($count2 != count($arResult['ITEMS'])) {
                        if($count2%4 == 0) {
                            echo "</div><div class='clearfix'>";
                        }
                    }
                    $count++;
                    $count2++;
                }
                ?>
            </div>
        </div>
        <? echo $arResult["NAV_STRING"]; ?>
    </section>
    <?
}
?>
