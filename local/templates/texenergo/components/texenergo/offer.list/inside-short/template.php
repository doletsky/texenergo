<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


if(count($arResult["ITEMS"]) > 0) {
$totalCount = 0;
foreach($arResult["ITEMS"] as $arItem)
{
    if($arItem['PROPERTIES']['GOODS']['VALUE'] != '') { $totalCount++; }
}


function datePeriod($date) {
    $oneMinute = 60; //in seconds
    $oneHour = 3600; //in seconds
    $oneDay = 86400; //in seconds
    $periodItog = "";
    
    $period = strtotime($date) - time();
    if($period > 0) {
        $dayItog = floor($period/$oneDay);
        $hourItog = floor(($period%$oneDay)/$oneHour);
        $minuteItog = floor(($period%$oneHour)/$oneMinute);
        
        $periodItog .= "<div class='digits cat-day'>";
        if($dayItog > 0) {
            if($dayItog < 10) {
                $periodItog .= "0".$dayItog;
            } else {
                $periodItog .= $dayItog;
            }
        } else {
            $periodItog .= "00";
        }
        $periodItog .= "</div><span class='two-dots'>:</span><div class='digits cat-hour'>";
        if($hourItog > 0) {
            if($hourItog < 10) {
                $periodItog .= "0".$hourItog;
            } else {
                $periodItog .= $hourItog;
            }
        } else {
            $periodItog .= "00";
        }
        $periodItog .= "</div><span class='two-dots'>:</span><div class='digits cat-min'>";
        if($minuteItog > 0) {
            if($minuteItog < 10) {
                $periodItog .= "0".$minuteItog;
            } else {
                $periodItog .= $minuteItog;
            }
        } else {
            $periodItog .= "00";
        }
        $periodItog .= "</div>";
        
    }
    
    return $periodItog;
}
?>



    <section class="cat-block">
        <header class="cat-header">
            <?php
            echo "<h1>Специальные предложения</h1>";
            ?>
        </header>
        <div class="col-headings actions-headings">
            <ul>
                <li><?php echo GetMessage("MES_NAME"); ?></li>
                <li>&nbsp;</li>
                <li><?php echo GetMessage("MES_PRICE"); ?></li>
                <li class="rest-list"><?php echo GetMessage("MES_REST"); ?></li>
                <li><?php echo GetMessage("MES_BUY"); ?></li>
            </ul>
        </div>
        <div class="owl-slider owl-slider-inside-short j_owl_slider_1">
            <div class="clearfix">
                <?
                $count = 0;
                $count2 = 1;
                foreach($arResult["ITEMS"] as $arItem)
                {
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                        foreach ($arItem['GOODS_LIST'] as $arActionProduct) {


                            if($arActionProduct['PRICE_PROPERTIES']['PRODUCT_QUANTITY'] > 0) {

                                ?>

                                <article class="cat-product cat-product-line clearfix<?php if($count == 0) { echo " first";} ?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                                    <?php
                                    if($arActionProduct["PROPERTIES"]["GOODS_IS_NEW"]["VALUE"] == "Да") { echo "<img src='".SITE_TEMPLATE_PATH."/img/catalog/new.png' alt='".$arActionProduct["PROPERTIES"]["GOODS_IS_NEW"]["NAME"]."' class='new-in-img'>"; }
                                    ?>
                                    <div class="product-descr-wrap">
                                        <div class="col line-thumbnail">
                                            <?php
                                            if($arActionProduct["IBLOCK_SECTION_ID"] && $arActionProduct["ID"]) {
                                                echo "<a href='/catalog/?SECTION_ID=".$arActionProduct["IBLOCK_SECTION_ID"]."&ELEMENT_ID=".$arActionProduct["ID"]."'>";
                                            }
                                            if(is_array($arActionProduct["PREVIEW_PICTURE"]))
                                            {
                                                echo "<img class='j-image-to-animate' src='".$arActionProduct["PREVIEW_PICTURE"]["SRC"]."' alt='".$arActionProduct["NAME"]."'/>";
                                            } else {
                                                if(is_array($arActionProduct["DETAIL_PICTURE"]))
                                                {
                                                    echo "<img class='j-image-to-animate' src='".$arActionProduct["DETAIL_PICTURE"]["SRC"]."' alt='".$arActionProduct["NAME"]."'/>";
                                                } else {
                                                    echo "<img class='j-image-to-animate' src='/local/templates/texenergo/img/catalog/no-image.png' alt='".$arActionProduct["NAME"]."'/>";
                                                }
                                            }
                                            if($arActionProduct["IBLOCK_SECTION_ID"] && $arActionProduct["ID"]) {
                                                echo "</a>";
                                            }
                                            ?>
                                        </div>
                                        <div class="col line-product-info">
                                            <?php
                                            if($arActionProduct["IBLOCK_SECTION_ID"] && $arActionProduct["ID"]) {
                                                echo "<a href='/catalog/?SECTION_ID=".$arActionProduct["IBLOCK_SECTION_ID"]."&ELEMENT_ID=".$arActionProduct["ID"]."' class='product-name product-name-line'>";
                                            }
                                            echo $arActionProduct["NAME"];
                                            if($arActionProduct["IBLOCK_SECTION_ID"] && $arActionProduct["ID"]) {
                                                echo "</a>";
                                            }
                                            if($arActionProduct["PROPERTIES"]["GOODS_ART"]["VALUE"] != "") { echo "<span class='product-number'>".$arActionProduct["PROPERTIES"]["GOODS_ART"]["VALUE"]."</span>"; }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col sale-block">
                                        <?
                                            if($arItem['DATE_ACTIVE_TO'] != "") {
                                                echo "<div class='row clearfix actions-time'><div class='cat-promo'>";
                                                    echo "<div class='cat-promo-time'>";
                                                        echo "<span class='text'>".GetMessage("DATE_ACTIVE_TO")."</span>";
                                                        echo datePeriod($arItem['DATE_ACTIVE_TO']);
                                                    echo "</div>";

                                                    echo "<ul class='cat-promo-captions'><li>".GetMessage("DAY")."</li><li>".GetMessage("HOUR")."</li><li>".GetMessage("MINUTE")."</li></ul>";
                                                echo "</div></div>";
                                            }
                                        ?>
                                    </div>
                                    <div class="col price-col last-list-price-col">
                                        <?php
                                        $price = CPrice::GetBasePrice( $arActionProduct['ID']);
                                        $oldPrice = $arActionProduct["PROPERTIES"]["OLD_PRICE"]["VALUE"];
                                        if($price["PRICE"]) {
                                            echo "<span class='cat-price cat-price-line'>".$price["PRICE"]." <i class='rouble'>a</i></span>";
                                        }
                                        if ($oldPrice) {
                                            echo "<span class='pLineOld old-price-last-list'>".$oldPrice."Р</span>";
                                        }
                                        ?>
                                    </div>
                                    <div class="col product-rest">
                                        <div class="cat-indicator cat-white-indicator cat-value-indicator<?if($arActionProduct['PROPERTIES']['REST_INDICATE']['VALUE'] != "") { echo "-".$arActionProduct['PROPERTIES']['REST_INDICATE']['VALUE']; }?>"></div>
                                    </div>
                                    <div class="col cat-line-icons actions-icons">
                                        <?php
                                        // $arActionProduct['BUY_URL'] = "httsdfsdfsfdsd";
                                        if($arActionProduct['BUY_URL']) { echo "<a href='".$arActionProduct['BUY_URL']."' class='cart-line' title='".GetMessage("ADD_TO_CART")."'><img src='".SITE_TEMPLATE_PATH."/img/catalog/cart-line.png' alt='".GetMessage("ADD_TO_CART")."'></a>"; }
                                        if ($USER->IsAuthorized())
                                        {
                                            if (isset($arActionProduct["PROPERTIES"]["F_USER"]["VALUE"])) {
                                                if(in_array($USER->GetId(),$arActionProduct["PROPERTIES"]["F_USER"]["VALUE"])){
                                                    echo "<a href='#' class='j-auth-delete-from-favorite active' rel='". $arActionProduct['ID'] ."' title='".GetMessage("DEL_RFOM_FAVORITES")."'><img src='".SITE_TEMPLATE_PATH."/img/catalog/like-gray.png' alt='".GetMessage("DEL_RFOM_FAVORITES")."'></a>";
                                                }
                                                else{
                                                    echo "<a href='#' class='j-auth-add-to-favorite' rel='". $arActionProduct['ID'] ."' title='".GetMessage("ADD_TO_FAVORITES")."'><img src='".SITE_TEMPLATE_PATH."/img/catalog/like-gray.png' alt='".GetMessage("ADD_TO_FAVORITES")."'></a>";
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
                                        // $arActionProduct['COMPARE_URL'] = "httsdfsdfsfdsd";

                                        if($arActionProduct['IN_COMPARE_LIST'] == "") {
                                            if($arActionProduct['COMPARE_URL']) { echo "<a href='#' data-id='".$arActionProduct['ID']."' class='j-img-animate j-add-to-compare' title='".GetMessage("ADD_TO_COMPARE")."'><img src='".SITE_TEMPLATE_PATH."/img/catalog/bars-gray.png' alt='".GetMessage("ADD_TO_COMPARE")."'></a>"; }
                                        } else {
                                            if($arActionProduct['COMPARE_DELETE_URL']) { echo "<a href='#' data-id='".$arActionProduct['ID']."' class='active j-delete-from-compare' title='".GetMessage("DEL_FROM_COMPARE")."'><img src='".SITE_TEMPLATE_PATH."/img/catalog/bars-gray.png' alt='".GetMessage("ADD_TO_COMPARE")."'></a>"; }
                                        }
                                        ?>
                                    </div>
                                </article>
                                <?php
                                if($count2 != $totalCount) {
                                    if($count2%4 == 0) {
                                        echo "</div><div class='clearfix'>";
                                       $count=-1; 
                                    }
                                }
                                $count++;
                                $count2++;
                            }                         


                        }
                }
                ?>
            </div>
        </div>
    </section>
<?php
}
?>
