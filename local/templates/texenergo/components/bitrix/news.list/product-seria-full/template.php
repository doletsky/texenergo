<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
    $bisTrustedUser = isTrustedUser(); // пользователю разрешено показ остатков
?>




<?=$arResult["NAV_STRING"]?>

<?
echo "<div class='cat-products clearfix j_cat_product_list'>";
    $count = 1;
    foreach($arResult["ITEMS"] as $arItem) {
    ?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="cell" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <div class="wrapper clearfix">
                <?php
                echo "<div class='cat-product j_cat_product_card";
                if($count%3 == 0) { echo " last"; }
                echo "'><div class='product-image'>";
                    if($arItem['IBLOCK_SECTION_ID'] && $arItem['ID']) { echo "<a href='/catalog/?SECTION_ID=".$arItem['IBLOCK_SECTION_ID']."&ELEMENT_ID=".$arItem['ID']."'>"; }
                    if($arItem['PREVIEW_PICTURE']['SRC'] != "") {
                        echo "<img class='list-items-img j_preview_img_main_".$arItem['ID']."' src='".$arItem['PREVIEW_PICTURE']['SRC']."' alt='".$arItem['NAME']."'>";
                    } else {
                        if($arItem['DETAIL_PICTURE']['SRC'] != "") {
                            echo "<img class='list-items-img j_preview_img_main_".$arItem['ID']."' src='".$arItem['DETAIL_PICTURE']['SRC']."' alt='".$arItem['NAME']."'>";
                        } else {
                            echo "<img class='list-items-img j_preview_img_main_".$arItem['ID']."' src='/local/templates/texenergo/img/catalog/no-image.png' alt='".$arItem['NAME']."'>";
                        }
                    }
                    if($arItem['IBLOCK_SECTION_ID'] && $arItem['ID']) { echo "</a>"; }

                    echo "<div class='product-info'>";
                        if($arItem['IBLOCK_SECTION_ID'] && $arItem['ID']) { echo "<a href='/catalog/?SECTION_ID=".$arItem['IBLOCK_SECTION_ID']."&ELEMENT_ID=".$arItem['ID']."' class='product-name'>"; }
                            echo $arItem['NAME'];
                        if($arItem['IBLOCK_SECTION_ID'] && $arItem['ID']) { echo "</a>"; }
                        if($arItem['PROPERTIES']['GOODS_ART']['VALUE'] != "") { echo "<span class='product-number'>".$arItem['PROPERTIES']['GOODS_ART']['VALUE']."</span>"; }
                    echo "</div>";

                    echo "<div class='cat-rating cat-value-raiting";
                    if($arItem['PROPERTIES']['GOODS_RATE']['VALUE'] != "") {
                        echo "-".$arItem['PROPERTIES']['GOODS_RATE']['VALUE'];
                    }
                    echo "'></div>";
                    $price = CPrice::GetBasePrice( $arItem['ID']);
                    $oldPrice = $arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"];
                    if ($USER->IsAuthorized()) {
                        $rsUser = CUser::GetByID($USER->GetID());
                        $arUser = $rsUser->Fetch();

                        if($price["PRICE"]) {
                            if($arUser["UF_DISCOUNT"] != "" && $arUser["UF_DISCOUNT"] > 0) {
                                echo "<span class='cat-price'>".$price["PRICE"]." <i class='rouble'>a</i></span>";
                            } else {
                                echo "<span class='cat-price'>".$price["PRICE"]." <i class='rouble'>a</i></span>";
                            }
                        }
                    } else {
                        if($price["PRICE"]) {
                            echo "<span class='cat-price'>".$price["PRICE"]." <i class='rouble'>a</i></span>";
                        }
                    }
                    if ($arItem["PROPERTIES"]["SHOW_OLD_PRICE"]["VALUE"]) {
                        echo '<span class="pPriceSmall">'.$oldPrice.'Р</span>';
                    }
                    if($arItem['PROPERTIES']['GOODS_PHOTOS']['VALUE'] != "") {
                        echo "<div class='thumbnails hidden'><ul class='owl-slider j_owl_slider_3'>";
                            echo "<li class='j_preview_img active' data-id='".$arItem["ID"]."'><img src='";
                            if($arItem['PREVIEW_PICTURE']['SRC'] != "") {
                                echo $arItem['PREVIEW_PICTURE']['SRC'];
                            } else {
                                if($arItem['DETAIL_PICTURE']['SRC'] != "") {
                                    echo $arItem['DETAIL_PICTURE']['SRC'];
                                }
                            }
                            echo "'></li>";
                            foreach($arItem["PROPERTIES"]["GOODS_PHOTOS"]["VALUE"] as $foto) {
                                echo "<li class='j_preview_img' data-id='".$arItem["ID"]."'><img src='".$foto["SRC"]."'></li>";
                            }
                        echo "</ul></div>";
                    }
                echo "</div></div>";
                echo "<div class='cat-hoverbox hidden'>";
                    if($arItem["PROPERTIES"]["GOODS_ANALOG_LINKS"]["VALUE"] != "") {
                        echo "<div class='box-analog'>";
                        if($arItem['IBLOCK_SECTION_ID'] && $arItem['ID']) { echo "<a href='/catalog/?SECTION_ID=".$arItem['IBLOCK_SECTION_ID']."&ELEMENT_ID=".$arItem['ID']."#tAnalogs' class='analog-title'>"; }
                            echo GetMessage("ANALOG_TITLE");
                        if($arItem['IBLOCK_SECTION_ID'] && $arItem['ID']) { echo "</a>"; }
                        echo "</div>";
                    }
                    echo "<div class='row clearfix'>";
                        if($arItem['BUY_URL']) { echo "<a href='".$arItem['BUY_URL']."' class='cat-incart'>В корзину</a>"; }
                        echo "<div class='like-block'>";
                            if ($USER->IsAuthorized())
                            {
                                if (isset($arItem["PROPERTIES"]["F_USER"]["VALUE"])) {
                                    if(in_array($USER->GetId(),$arItem["PROPERTIES"]["F_USER"]["VALUE"])){
                                        echo "<a href='#' class='cat-like j-auth-delete-from-favorite active' rel='". $arItem['ID'] ."' title='".GetMessage("DEL_RFOM_FAVORITES")."'></a>";
                                    }
                                    else{
                                        echo "<a href='#' class='cat-like j-auth-add-to-favorite' rel='". $arItem['ID'] ."' title='".GetMessage("ADD_TO_FAVORITES")."'></a>";
                                    }
                                }
                            }else {
                                echo "<a href='#' class='cat-like' title='".GetMessage("ADD_TO_FAVORITES")."'></a>";
                                /*$favoriteGoods = isset($_COOKIE['favorite_goods']) ? explode(',', $_COOKIE['favorite_goods']) : array();

                                if(in_array($arItem['ID'], $favoriteGoods)){
                                    echo "<a href='#' class='cat-like j-delete-from-favorite active' rel='". $arItem['ID'] ."' title='".GetMessage("DEL_RFOM_FAVORITES")."'></a>";
                                }
                                else{
                                    echo "<a href='#' class='cat-like j-add-to-favorite' rel='". $arItem['ID'] ."' title='".GetMessage("ADD_TO_FAVORITES")."'></a>";
                                }*/
                            }
                            if($arItem['IN_COMPARE_LIST'] == "") {
                                if($arItem['COMPARE_URL']) { echo "<a href='#' data-id='".$arItem['ID']."' class='cat-compare j-add-to-compare' title='".GetMessage("ADD_TO_COMPARE")."'></a>"; }
                            } else {
                                if($arItem['COMPARE_DELETE_URL']) { echo "<a href='#' data-id='".$arItem['ID']."' class='cat-compare active j-delete-from-compare' title='".GetMessage("DEL_FROM_COMPARE")."'></a>"; }
                            }
                        echo "</div>";
                    echo "</div>";

                    echo "<div class='row center row-rest'>";
                        echo "<span class='cat-instock'>Остаток:</span>";
                        if($bisTrustedUser){?>
                            <div class=""><?=getProductRestsInterval($arItem)?><?//=$arItem['CATALOG_QUANTITY']?></div>
                        <?}
                        else {?>
                            <div class=""><?//=getProductRestsInterval($arItem)?></div>
                        <?}
                    echo "</div>";
                    if($arItem['PROPERTIES']['GOODS_FILTER_VALUE']['VALUE'] != "") {
                        echo "<div class='row'><div class='cat-technicals'><ul>";
                            foreach ($arItem['PROPERTIES']['GOODS_FILTER_VALUE']['VALUE_NAMES'] as $filterValue) {
                                echo "<li>".$filterValue["FILTER_VARS"]["NAME"].": <b>".$filterValue["NAME"]."</b></li>";
                            }
                        echo "</ul></div></div>";
                    }
                echo "</div>";
                ?>
            </div>
        </div>
        <?php
        $count++;
    }
echo "</div>";
?>

