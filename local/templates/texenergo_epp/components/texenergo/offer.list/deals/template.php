<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php
$catalogViewON = $_COOKIE['catalog_view'];
?>
<?php
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
<div class="twelve">
    <div class="cat-header">
        <h1><?php echo GetMessage("DEALS_TITLE");?></h1>
    </div>
    <?php
        if(isset($catalogViewON)) {
            if($catalogViewON == 1) {
                echo "<section class='cat-products catProducts-cart cat-products-list owl-slider j_owl_slider_5 clearfix'>";
            } else {
                echo "<section class='cat-products catProducts-cart cat-products-list cat-products-land owl-slider j_owl_slider_5 clearfix'>";
            }
        } else {
            echo "<section class='cat-products catProducts-cart cat-products-list cat-products-land owl-slider j_owl_slider_5 clearfix'>";
        }
    ?>
        <?
        $count = 0;
        foreach($arResult["ITEMS"] as $arItem):?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            if($arItem['PROPERTIES']['GOODS']['VALUE'] != '') {
                if($arItem['GOODS']['PRICE_PROPERTIES']['PRODUCT_QUANTITY'] > 0) {
                    echo "<div class='wrapper";
                    if($count == 0) { echo " wrapper-land"; }
                    echo " clearfix' id='".$this->GetEditAreaId($arItem['ID'])."'>";
                        echo "<article class='cat-product cat-product-list j_owl_slider_item";
                        if($arItem['GOODS']['PROPERTIES']['GOODS_IS_NEW']['VALUE'] != "") { echo " new"; }
                        echo "'><div class='product-image'>";
                            if($arItem['GOODS']['PROPERTIES']['GOODS_DELIVERY']['VALUE'] == "Да") {
                                if($arItem['GOODS']['PROPERTIES']['GOODS_CUT_PRICE']['VALUE'] == "Да") { echo "<div title='".GetMessage("CAR_DELIVERY")."' class='cat-delivery'></div>"; } else { echo "<div title='".GetMessage("CAR_DELIVERY")."' class='cat-delivery cat-delivery-only'></div>"; }
                            }
                            if($arItem['GOODS']['PROPERTIES']['GOODS_CUT_PRICE']['VALUE'] == "Да") { echo "<div  title='".GetMessage("CAR_DISCOUNT")."' class='cat-discount'></div>"; }
                            
                            
                            if($arItem['GOODS']['IBLOCK_SECTION_ID'] && $arItem['GOODS']['ID']) { echo "<a href='/catalog/?SECTION_ID=".$arItem['GOODS']['IBLOCK_SECTION_ID']."&ELEMENT_ID=".$arItem['GOODS']['ID']."'>"; }
                            if($arItem['GOODS']['PREVIEW_PICTURE']['SRC'] != "") {
                                echo "<img class='list-items-img' src='".$arItem['GOODS']['PREVIEW_PICTURE']['SRC']."' alt='".$arItem['GOODS']['NAME']."'>";
                            } else {
                                if($arItem['GOODS']['DETAIL_PICTURE']['SRC'] != "") {
                                    echo "<img class='list-items-img' src='".$arItem['GOODS']['DETAIL_PICTURE']['SRC']."' alt='".$arItem['GOODS']['NAME']."'>";
                                }
                            }
                            if($arItem['GOODS']['IBLOCK_SECTION_ID'] && $arItem['GOODS']['ID']) { echo "</a>"; }
                            
                            
                            echo "<div class='product-info'>";
                                if($arItem['DATE_ACTIVE_TO'] != "") {
                                    echo "<div class='row clearfix'><div class='cat-promo'>";
                                        echo "<div class='cat-promo-time'>";
                                            echo "<span class='text'>".GetMessage("DATE_ACTIVE_TO")."</span>";
                                            echo datePeriod($arItem['DATE_ACTIVE_TO']);
                                        echo "</div>";
                                        echo "<div class='cat-promo-quantity'>";
                                            echo "<span class='text'>".GetMessage("MORE")."</span>";
                                            echo "<div class='digits cat-quantity'>".$arItem['GOODS']['PRICE_PROPERTIES']['PRODUCT_QUANTITY']."</div>";
                                        echo "</div>";
                                        echo "<ul class='cat-promo-captions'><li>".GetMessage("DAY")."</li><li>".GetMessage("HOUR")."</li><li>".GetMessage("MINUTE")."</li><li>".GetMessage("COUNT")."</li></ul>";
                                    echo "</div></div>";
                                }
                                
                                
                                if($arItem['GOODS']['IBLOCK_SECTION_ID'] && $arItem['GOODS']['ID']) { echo "<a href='/catalog/?SECTION_ID=".$arItem['GOODS']['IBLOCK_SECTION_ID']."&ELEMENT_ID=".$arItem['GOODS']['ID']."' class='product-name'>"; }
                                    echo $arItem['GOODS']['NAME'];
                                if($arItem['GOODS']['IBLOCK_SECTION_ID'] && $arItem['GOODS']['ID']) { echo "</a>"; }
                                if($arItem['GOODS']['PROPERTIES']['GOODS_ART']['VALUE'] != "") { echo "<span class='product-number'>".$arItem['GOODS']['PROPERTIES']['GOODS_ART']['VALUE']."</span>"; }
                            echo "</div>";
                            echo "<div class='cat-rating cat-value-raiting";
                            if($arItem['GOODS']['PROPERTIES']['GOODS_RATE']['VALUE'] != "") {
                                echo "-".$arItem['GOODS']['PROPERTIES']['GOODS_RATE']['VALUE'];
                            }
                            echo "'></div>";
                            if($arItem['GOODS']['PRICE_PROPERTIES']['PRICE'] != "") { echo "<span class='cat-price'>".$arItem['GOODS']['PRICE_PROPERTIES']['PRICE']." <i class='rouble'>a</i></span>"; }
                        echo "</div></article>";
                    echo "</div>";
                    $count++;
                }
            }
            ?>
        <?endforeach;?>
    </section>
    <?php
        if(isset($catalogViewON)) {
            if($catalogViewON == 1) {
                echo "<footer class='catFooter-cart cat-footer-landing'>";
                $APPLICATION->IncludeFile('/include/deal_link_inc.php', array(),
                    array(
                        'MODE'      => 'html',
                        'TEMPLATE'  => 'deal_link_inc.php',
                    )
                );
                echo "</footer>";
            }
        }
    ?>
</div>
