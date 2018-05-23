<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
    $bisTrustedUser = isTrustedUser(); // пользователю разрешено показ остатков
?>

<?=$arResult["NAV_STRING"]?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<article class="pProductInLine clearfix" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<section class="pLineImage seria-pLineImage">
            <figure class="pProductImage pProductImage-line">
                <?php
                if($arItem["PREVIEW_PICTURE"]["SRC"]) {
                    echo "<img src='".$arItem["PREVIEW_PICTURE"]["SRC"]."' alt='".$arItem["NAME"]."' class='j_preview_img_main_".$arItem["ID"]."' />";
                } else {
                    if($arItem["DETAIL_PICTURE"]["SRC"]) {
                        echo "<img src='".$arItem["DETAIL_PICTURE"]["SRC"]."' alt='".$arItem["NAME"]."' class='j_preview_img_main_".$arItem["ID"]."' />";
                    } else {
                        echo "<img src='/local/templates/texenergo/img/catalog/no-image.png' alt='".$arItem["NAME"]."' class='j_preview_img_main_".$arItem["ID"]."' />";
                    }
                }
                ?>
            </figure>
            <?php
            if($arItem["PROPERTIES"]["GOODS_PHOTOS"]["VALUE"] != "") {
                echo "<div class='pSlider pSlider-line clearfix'><ul class='owl-slider j_owl_slider_3'>";
                    echo "<li class='j_preview_img active' data-id='".$arItem["ID"]."'><img src='";
                    if($arItem["PREVIEW_PICTURE"]["SRC"]) {
                        echo $arItem["PREVIEW_PICTURE"]["SRC"];
                    } else {
                        if($arItem["DETAIL_PICTURE"]["SRC"]) {
                            echo $arItem["DETAIL_PICTURE"]["SRC"];
                        }
                    }
                    echo "' alt='".$arItem["NAME"]."'></li>";
                    foreach($arItem["PROPERTIES"]["GOODS_PHOTOS"]["VALUE"] as $foto) {
                        echo "<li class='j_preview_img' data-id='".$arItem["ID"]."'><img src='".$foto["SRC"]."' alt='".$arItem["NAME"]."'></li>";
                    }
                echo "</ul></div>";
            }
            ?>
        </section>
        <section class="pLineOverview">
            <a href="/catalog/?SECTION_ID=<?=$arItem["IBLOCK_SECTION_ID"]?>&ELEMENT_ID=<?=$arItem["ID"]?>" class="name name-pline"><?=$arItem["NAME"]?></a>
            <?php
            if($arItem["PROPERTIES"]["GOODS_ART"]["VALUE"] != "") { echo "<span class='sku sku-pline'>".$arItem["PROPERTIES"]["GOODS_ART"]["VALUE"]."</span>"; }
            if($arItem["PREVIEW_TEXT"] != "") { echo "<div class='copy-pline'>".$arItem["PREVIEW_TEXT"]."</div>"; }

            if($arItem['PROPERTIES']['GOODS_FILTER_VALUE']['VALUE'] != "") {
                foreach ($arItem['PROPERTIES']['GOODS_FILTER_VALUE']['VALUE_NAMES'] as $filterValue) {
                    echo "<div class='row-pline'><span class='spanFirst'>".$filterValue["FILTER_VARS"]["NAME"].":</span><span class='spanSecond'>".$filterValue["NAME"]."</span></div>";
                }
            }
            ?>
        </section>
        <section class="pLineCart">
            <?php
            echo "<div class='cat-rating cat-value-raiting";
            if($arItem['PROPERTIES']['GOODS_RATE']['VALUE'] != "") {
                echo "-".$arItem['PROPERTIES']['GOODS_RATE']['VALUE'];
            }
            echo "'></div>";
            if($arItem['PROPERTIES']['BLOG_COMMENTS_CNT']['VALUE'] != "") { echo "<span>".$arItem['PROPERTIES']['BLOG_COMMENTS_CNT']['VALUE']." ".GetMessage("REVIEWS_".wordForm($arItem['PROPERTIES']['BLOG_COMMENTS_CNT']['VALUE']))."</span>"; }
            ?>
            <div class="pPriceBlock">
                <?php
                $price = CPrice::GetBasePrice( $arItem['ID']);
                $oldPrice = $arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"];
                if ($USER->IsAuthorized()) {
                    $rsUser = CUser::GetByID($USER->GetID());
                    $arUser = $rsUser->Fetch();

                    if($price["PRICE"]) {
                        if($arUser["UF_DISCOUNT"] != "" && $arUser["UF_DISCOUNT"] > 0) {
                            echo "<span class='pLinePrice'>".$price["PRICE"]."<i class='rouble'>a</i></span>";
                        } else {
                            echo "<span class='pLinePrice'>".$price["PRICE"]."<i class='rouble'>a</i></span>";
                        }
                    }
                } else {
                    if($price["PRICE"]) {
                        echo "<span class='pLinePrice'>".$price["PRICE"]."<i class='rouble'>a</i></span>";
                    }
                }
                if ($arItem["PROPERTIES"]["SHOW_OLD_PRICE"]["VALUE"] == "Да") {
                    echo "<span class='pLineOld'>".$oldPrice."</span>";
                }
                if($arItem["PROPERTIES"]["GOODS_BONUS"]["VALUE"] != "") { echo "<span class='pBonuses'>".$arItem["PROPERTIES"]["GOODS_BONUS"]["VALUE"]." ".GetMessage("BONUS_WORD_".wordForm($arItem["PROPERTIES"]["GOODS_BONUS"]["VALUE"]))."</span>"; }
                ?>
            </div>
            <div class="pCart-line clearfix">
                <?php
                if($arItem['BUY_URL']) {
                    echo "<a href='".$arItem['BUY_URL']."' class='pInCart pInCart-line clearfix'><img class='pCartIcon-line' src='".SITE_TEMPLATE_PATH."/img/product/cart-mini.png' width='20' alt='В корзину'> <span class='pPutInBasket'>В корзину</span></a>";
                }
                ?>
                <div class="pCartButtons pCartButtons-line clearfix">
                    <?php
                    if ($USER->IsAuthorized())
                    {
                        if (isset($arItem["PROPERTIES"]["F_USER"]["VALUE"])) {
                            if(in_array($USER->GetId(),$arItem["PROPERTIES"]["F_USER"]["VALUE"])){
                                echo "<a href='#' class='j-auth-delete-from-favorite active' rel='". $arItem['ID'] ."' title='".GetMessage("DEL_RFOM_FAVORITES")."'></a>";
                            }
                            else{
                                echo "<a href='#' class='j-auth-add-to-favorite' rel='". $arItem['ID'] ."' title='".GetMessage("ADD_TO_FAVORITES")."'></a>";
                            }
                        }
                    }else {
                        echo "<a href='#' title='".GetMessage("ADD_TO_FAVORITES")."'></a>";
                    }
                    if($arItem['IN_COMPARE_LIST'] == "") {
                        if($arItem['COMPARE_URL']) { echo "<a href='#' class='j-add-to-compare' data-id='".$arItem['ID']."' title='".GetMessage("ADD_TO_COMPARE")."'></a>"; }
                    } else {
                        if($arItem['COMPARE_DELETE_URL']) { echo "<a href='#' data-id='".$arItem['ID']."' class='active j-delete-from-compare' title='".GetMessage("DEL_FROM_COMPARE")."'></a>"; }
                    }
                    ?>
                </div>
                <div class="pCartQuantity">
                    <?php
                    if($arItem["PROPERTIES"]["GOODS_ANALOG_LINKS"]["VALUE"] != "") {
                        echo "<span class='pAnalog'>";
                        if($arItem['IBLOCK_SECTION_ID'] && $arItem['ID']) { echo "<a href='/catalog/?SECTION_ID=".$arItem['IBLOCK_SECTION_ID']."&ELEMENT_ID=".$arItem['ID']."#tAnalogs' class='analog-title'>"; }
                            echo "Аналоги";
                        if($arItem['IBLOCK_SECTION_ID'] && $arItem['ID']) { echo "</a>"; }
                        echo "</span>";
                    }
                    echo "<em>Остаток: </em>";
                    if($bisTrustedUser){?>
                        <div class=''><?=getProductRestsInterval($arItem)?><?//=$arItem['CATALOG_QUANTITY']?></div></div>
                    <?}
                    else {?>
                        <div class=''><?//=getProductRestsInterval($arItem)?></div></div>
                    <?}?>
                </div>
            </div>
        </section>
	</article>
<?endforeach;?>

