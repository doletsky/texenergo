<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$bisTrustedUser = isTrustedUser(); // пользователю разрешено показ остатков


if(count($arResult["ITEMS"]) > 0) {



?>
    <section class="cat-block j_cat_block">
        <header class="cat-header level-two-full-header">
            <?php
            echo "<h1>Специальные предложения</h1>";
            ?>
        </header>

        <?
        $count = 1;
        foreach ($arResult['ITEMS'] as $key => $arItem)
        {
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
            $strMainID = $this->GetEditAreaId($arItem['ID']);

                foreach ($arItem['GOODS_LIST'] as $arActionProduct) {

                ?>

                <article class="pProductInLine clearfix second-level-product-line" id="<? echo $strMainID; ?>">
                    <?if($arActionProduct['PREVIEW_PICTURE']['SRC'] != "" || $arActionProduct['DETAIL_PICTURE']['SRC'] != ""):?>
                        <section class="pLineImage">
                            <figure class="pProductImage pProductImage-line">
                                <?php
                                if($arActionProduct["PROPERTIES"]["GOODS_PHOTOS"]["VALUE"] != "") {

                                    if($arActionProduct["PREVIEW_PICTURE"]["SRC"]) {
                                        echo "<img src='".$arActionProduct["PREVIEW_PICTURE"]["SRC"]."' alt='".$arActionProduct["NAME"]."' class='j-image-to-animate j_preview_img_main_".$arActionProduct["ID"]."' />";
                                    } else {
                                        echo "<img src='/local/templates/texenergo/img/catalog/no-image.png' alt='".$arActionProduct['NAME']."' class='j-image-to-animate j_preview_img_main_".$arActionProduct["ID"]."' />";
                                    }

                                } else {

                                    if($arActionProduct["DETAIL_PICTURE"]["SRC"]) {
                                        echo "<img src='".$arActionProduct["DETAIL_PICTURE"]["SRC"]."' alt='".$arActionProduct["NAME"]."' class='j-image-to-animate j_preview_img_main_".$arActionProduct["ID"]." big-last-full-img' />";
                                    } else {
                                        echo "<img src='/local/templates/texenergo/img/catalog/no-image.png' alt='".$arActionProduct['NAME']."' class='j-image-to-animate j_preview_img_main_".$arActionProduct["ID"]."' />";
                                    }

                                }
                                ?>
                            </figure>
                            <?php
                            if($arActionProduct["PROPERTIES"]["GOODS_PHOTOS"]["VALUE"] != "") {
                                echo "<div class='pSlider inside-full-slider pSlider-line clearfix'><ul class='owl-slider j_owl_slider_3'>";
                                    echo "<li class='j_preview_img active' data-id='".$arActionProduct["ID"]."'><img src='";
                                    if($arActionProduct["PREVIEW_PICTURE"]["SRC"]) {
                                        echo $arActionProduct["PREVIEW_PICTURE"]["SRC"];
                                    } else {
                                        if($arActionProduct["DETAIL_PICTURE"]["SRC"]) {
                                            echo $arActionProduct["DETAIL_PICTURE"]["SRC"];
                                        }
                                    }
                                    echo "' alt='".$arActionProduct["NAME"]."'></li>";

                                    $photosCount = count($arActionProduct["PROPERTIES"]["GOODS_PHOTOS"]["VALUE"]);

                                    for ($i=0; $i < $photosCount; $i++) {
                                        $photo = CFile::GetFileArray($arActionProduct["PROPERTIES"]["GOODS_PHOTOS"]["VALUE"][$i]);

                                        echo "<li class='j_preview_img' data-id='".$arActionProduct["ID"]."'><img src='".$photo["SRC"]."' alt='".$arActionProduct["NAME"]."'></li>";

                                    }

                                echo "</ul></div>";
                            }
                            ?>
                        </section>
                    <?endif?>
                    <section class="pLineOverview">
                        <a href="/catalog/?SECTION_ID=<?=$arActionProduct["IBLOCK_SECTION_ID"]?>&ELEMENT_ID=<?=$arActionProduct["ID"]?>" class="name name-pline"><?=$arActionProduct["NAME"]?></a>
                        <?php
                        if($arActionProduct["PROPERTIES"]["GOODS_ART"]["VALUE"] != "") { echo "<span class='sku sku-pline'>".$arActionProduct["PROPERTIES"]["GOODS_ART"]["VALUE"]."</span>"; }
                        echo "<div class='cat-rating last-full-rating cat-value-raiting";
                        if($arActionProduct['PROPERTIES']['GOODS_RATE']['VALUE'] != "") {
                            echo "-".$arActionProduct['PROPERTIES']['GOODS_RATE']['VALUE'];
                        }
                        echo "'></div>";
                        if($arActionProduct["PREVIEW_TEXT"] != "") { echo "<div class='copy-pline'>".$arActionProduct["PREVIEW_TEXT"]."</div>"; }
                        if($arActionProduct['PROPERTIES']['GOODS_FILTER_VALUE']['VALUE'] != "") {
                            foreach ($arActionProduct['PROPERTIES']['GOODS_FILTER_VALUE']['VALUE_NAMES'] as $filterValue) {
                                echo "<div class='row-pline'><span class='spanFirst'>".$filterValue["FILTER_VARS"]["NAME"]."</span><span class='spanSecond'>".$filterValue["NAME"]."</span></div>";
                            }
                        }
                        ?>
                    </section>
                    <section class="pLineCart">
                        <?php
                        if($arActionProduct['PROPERTIES']['BLOG_COMMENTS_CNT']['VALUE'] != "") { echo "<span>".$arActionProduct['PROPERTIES']['BLOG_COMMENTS_CNT']['VALUE']." ".GetMessage("REVIEWS_".wordForm($arActionProduct['PROPERTIES']['BLOG_COMMENTS_CNT']['VALUE']))."</span>"; }
                        ?>
                        <div class="pPriceBlock">
                            <?php
                            $price = CPrice::GetBasePrice( $arActionProduct['ID']);
                            if ($USER->IsAuthorized()) {
                                $rsUser = CUser::GetByID($USER->GetID());
                                $arUser = $rsUser->Fetch();

                                if($price["PRICE"]) {
                                    $oldPrice = $arActionProduct["PROPERTIES"]["OLD_PRICE"]["VALUE"];
                                    if($arUser["UF_DISCOUNT"] != "" && $arUser["UF_DISCOUNT"] > 0) {
                                        echo "<span class='pLinePrice'>".$price["PRICE"]."<i class='rouble'>a</i></span>";
                                        if ($oldPrice) {
                                            echo "<span class='pLineOld'>".$oldPrice."Р</span>";
                                        }
                                    } else {
                                        echo "<span class='pLinePrice'>".$price["PRICE"]."<i class='rouble'>a</i></span>";
                                    }
                                }
                            } else {
                                if($price["PRICE"]) {
                                    echo "<span class='pLinePrice'>".$price["PRICE"]."<i class='rouble'>a</i></span>";
                                }
                            }
                            if($arActionProduct["PROPERTIES"]["GOODS_BONUS"]["VALUE"] != "") { echo "<span class='pBonuses'>".$arActionProduct["PROPERTIES"]["GOODS_BONUS"]["VALUE"]." ".GetMessage("BONUS_WORD_".wordForm($arActionProduct["PROPERTIES"]["GOODS_BONUS"]["VALUE"]))."</span>"; }
                            ?>
                        </div>
                        <div class="pCart-line clearfix">
                            <?php
                            if($arActionProduct['BUY_URL']) {
                                echo "<a href='".$arActionProduct['BUY_URL']."' class='pInCart pInCart-line clearfix'><img class='pCartIcon-line' src='".SITE_TEMPLATE_PATH."/img/product/cart-mini.png' width='20' alt='В корзину'> <span class='pPutInBasket'>В корзину</span></a>";
                            }
                            ?>
                            <div class="pCartButtons pCartButtons-line clearfix">
                                <?php
                                if ($USER->IsAuthorized())
                                {
                                    if (isset($arActionProduct["PROPERTIES"]["F_USER"]["VALUE"])) {
                                        if(in_array($USER->GetId(),$arActionProduct["PROPERTIES"]["F_USER"]["VALUE"])){
                                            echo "<a href='#' class='j-auth-delete-from-favorite active' rel='". $arActionProduct['ID'] ."' title='".GetMessage("DEL_RFOM_FAVORITES")."'></a>";
                                        }
                                        else{
                                            echo "<a href='#' class='j-auth-add-to-favorite' rel='". $arActionProduct['ID'] ."' title='".GetMessage("ADD_TO_FAVORITES")."'></a>";
                                        }
                                    }
                                }else {
                                    echo "<a href='#' title='".GetMessage("ADD_TO_FAVORITES")."'></a>";
                                }
                                if($arActionProduct['IN_COMPARE_LIST'] == "") {
                                    if($arActionProduct['COMPARE_URL']) { echo "<a href='#' class='j-animate-short j-add-to-compare' data-id='".$arActionProduct['ID']."' title='".GetMessage("ADD_TO_COMPARE")."'></a>"; }
                                } else {
                                    if($arActionProduct['COMPARE_DELETE_URL']) { echo "<a href='#' data-id='".$arActionProduct['ID']."' class='active j-delete-from-compare' title='".GetMessage("DEL_FROM_COMPARE")."'></a>"; }
                                }
                                ?>
                            </div>
                            <div class="pCartQuantity">
                                <?php
                                if($arActionProduct["PROPERTIES"]["GOODS_ANALOG_LINKS"]["VALUE"] != "") {
                                    echo "<span class='pAnalog'>";
                                    if($arActionProduct['IBLOCK_SECTION_ID'] && $arActionProduct['ID']) { echo "<a href='/catalog/?SECTION_ID=".$arActionProduct['IBLOCK_SECTION_ID']."&ELEMENT_ID=".$arActionProduct['ID']."#tAnalogs' class='analog-title'>"; }
                                        echo "Аналоги";
                                    if($arActionProduct['IBLOCK_SECTION_ID'] && $arActionProduct['ID']) { echo "</a>"; }
                                    echo "</span>";
                                }
                                echo "<em>Остаток: </em>";
                                if($bisTrustedUser){?>
                                    <div class=''><?//=$arItem['CATALOG_QUANTITY']?><?=getProductRestsInterval($arItem)?></div></div>
                                <?}
                                else {?>
                                <div class=''><?=getProductRestsInterval($arItem)?></div></div>
                                <?}?>
                            </div>
                        </div>
                    </section>
                </article>

                <?
                    if($count == 8) {
                        echo "<div class='clear'></div><div class='banner-sale'>";
                        $APPLICATION->IncludeFile('/include/text_catalog_inc.php', array(),
                            array(
                                'MODE'      => 'html',
                                'TEMPLATE'  => 'text_catalog_inc.php',
                            )
                        );
                        echo "</div><div class='clear'></div>";
                }
                    $count++;
            }
        }
        echo $arResult["NAV_STRING"];
            ?>



    </section>
<?php
}
?>
