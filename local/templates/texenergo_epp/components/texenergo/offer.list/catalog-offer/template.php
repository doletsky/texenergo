<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



$curDir = $APPLICATION->GetCurDir(true);


if(count($arResult["ITEMS"]) > 0) {
$totalCount = 0;
foreach($arResult["ITEMS"] as $arItem)
{
    if($arItem['GOODS_LIST']) {

        foreach ($arItem['GOODS_LIST'] as $arActionProduct) {

            if($arActionProduct['PRICE_PROPERTIES']['PRODUCT_QUANTITY'] > 0) { $totalCount++; }

        }

    }
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


    <?echo "<div class='cat-block catalog-special-offer'>";?>

            <header class="cat-header">
                <h1>Специальные предложения</h1>
                <? if ($totalCount > 5) {?>
                    <a href="" class="j-offer-left offer-left"></a>
                    <a href="" class="j-offer-right offer-right"></a>
                <?}?>
            </header>

        <?echo "<div class='cat-products clearfix j_cat_product_list catalog-offer j-catalog-offer'>";

            $strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
            $strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
            $arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));

            $count = 0;?>

            <div class="item">

                <?foreach ($arResult['ITEMS'] as $key => $arItem) {
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
                    $strMainID = $this->GetEditAreaId($arItem['ID']);

                    if($arItem['GOODS_LIST']) {
                        foreach ($arItem['GOODS_LIST'] as $arActionProduct) {
                            if($arActionProduct['PRICE_PROPERTIES']['PRODUCT_QUANTITY'] > 0) {?>

                                <?if ($count > 0 && $count % 5 == 0 ) {
                                    echo "</div><div class='item'>";
                                }?>


                                <div class="cell" id="<? echo $strMainID; ?>">
                                    <div class="wrapper clearfix">
                                        <?php
                                        echo "<div class='cat-product j_cat_product_card";
                                        if ($count > 0 && $count % 4 == 0) { echo " last"; }
                                        echo "'><div class='product-image'>";
                                            if($arActionProduct['IBLOCK_SECTION_ID'] && $arActionProduct['ID']) { echo "<a href='/catalog/?SECTION_ID=".$arActionProduct['IBLOCK_SECTION_ID']."&ELEMENT_ID=".$arActionProduct['ID']."'>"; }
                                            if($arActionProduct['PREVIEW_PICTURE']['SRC'] != "") {
                                                echo "<img class='j-image-to-animate list-items-img j_preview_img_main_".$arActionProduct['ID']."' src='".$arActionProduct['PREVIEW_PICTURE']['SRC']."' alt='".$arActionProduct['NAME']."'>";
                                            } else {
                                                if($arActionProduct['DETAIL_PICTURE']['SRC'] != "") {
                                                    echo "<img class='j-image-to-animate list-items-img j_preview_img_main_".$arActionProduct['ID']."' src='".$arActionProduct['DETAIL_PICTURE']['SRC']."' alt='".$arActionProduct['NAME']."'>";
                                                } else {
                                                    echo "<img class='j-image-to-animate list-items-img j_preview_img_main_".$arActionProduct['ID']."' src='/local/templates/texenergo/img/catalog/no-image.png' alt='".$arActionProduct['NAME']."'>";
                                                }
                                            }
                                            if($arActionProduct['IBLOCK_SECTION_ID'] && $arActionProduct['ID']) { echo "</a>"; }
                                            
                                            echo "<div class='product-info'>";

                                                if($arItem['DATE_ACTIVE_TO'] != "") {
                                                    echo "<div class='row clearfix'><div class='cat-promo'>";
                                                        echo "<div class='cat-promo-time'>";
                                                            echo "<span class='text'>".GetMessage("DATE_ACTIVE_TO")."</span>";
                                                            echo datePeriod($arItem['DATE_ACTIVE_TO']);
                                                        echo "</div>";
                                                        echo "<div class='cat-promo-quantity'>";
                                                            echo "<span class='text'>".GetMessage("MORE")."</span>";
                                                            echo "<div class='digits cat-quantity'>".$arActionProduct['PRICE_PROPERTIES']['PRODUCT_QUANTITY']."</div>";
                                                        echo "</div>";
                                                        echo "<ul class='cat-promo-captions'><li>".GetMessage("DAY")."</li><li>".GetMessage("HOUR")."</li><li>".GetMessage("MINUTE")."</li><li>".GetMessage("COUNT")."</li></ul>";
                                                    echo "</div></div>";
                                                }

                                                if($arActionProduct['IBLOCK_SECTION_ID'] && $arActionProduct['ID']) { echo "<a href='/catalog/?SECTION_ID=".$arActionProduct['IBLOCK_SECTION_ID']."&ELEMENT_ID=".$arActionProduct['ID']."' class='product-name'>"; }
                                                    echo $arActionProduct['NAME'];
                                                if($arActionProduct['IBLOCK_SECTION_ID'] && $arActionProduct['ID']) { echo "</a>"; }
                                                if($arActionProduct['PROPERTIES']['GOODS_ART']['VALUE'] != "") { echo "<span class='product-number'>".$arActionProduct['PROPERTIES']['GOODS_ART']['VALUE']."</span>"; }
                                            echo "</div>";
                                            
                                            echo "<div class='cat-rating cat-value-raiting";
                                            if($arActionProduct['PROPERTIES']['GOODS_RATE']['VALUE'] != "") {
                                                echo "-".$arActionProduct['PROPERTIES']['GOODS_RATE']['VALUE'];
                                            }
                                            echo "'></div>";
                                            $price = CPrice::GetBasePrice( $arActionProduct['ID']);
                                            $oldPrice = $arActionProduct["PROPERTIES"]["OLD_PRICE"]["VALUE"];
                                            if($price["PRICE"]) {
                                                echo "<span class='cat-price'>".$price["PRICE"]." <i class='rouble'>a</i></span>";
                                                if ($oldPrice) {
                                                    echo "<span class='pLineOld old-pirce-catalog-full-last'>".$oldPrice."Р</span>";
                                                }
                                            }
                                            echo "</div>";?>

                                            <div class="pCart catalog-offer-basket-buttons">
                                                <a href="<?=$arActionProduct['BUY_URL']?>" class="pInCart" title="Добавить в корзину"><i></i>в корзину</a>
                                                <div class="pCartButtons">
                                                    <a href="#" class="j-auth-add-to-favorite" rel="" title="Добавить в избранное"></a>
                                                    <a href="#" class="j-img-animate-last-list j-add-to-compare" data-id="<?=$arActionProduct['ID']; ?>" title="Добавить в сравнение"></a>
                                                </div>
                                            </div>

                                            <?"</div>";
                                        ?>
                                    </div>
                                </div>
                                <?$count++;?>
                            <?}
                        }
                    }
                }?>
                
            </div>

        <?
        echo "</div>";
    echo "</div>";?>
<?php
}
?>
