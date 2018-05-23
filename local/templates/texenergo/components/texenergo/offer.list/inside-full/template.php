<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$bisTrustedUser = isTrustedUser(); // пользователю разрешено показ остатков

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


    <?if ($curDir == '/catalog/' && !(isset($_GET['SECTION_ID']))) {
        $class = 'catalog-special-offer';
    }?>



    <?echo "<div class='cat-block ".$class."'>";?>

            <header class="cat-header">
                <?php
                echo "<h1>Специальные предложения</h1>";
                ?>
            </header>

        <?echo "<div class='cat-products clearfix j_cat_product_list'>";

            $strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
            $strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
            $arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));

            $count = 0;
            foreach ($arResult['ITEMS'] as $key => $arItem)
            {
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
                $strMainID = $this->GetEditAreaId($arItem['ID']);

                if($arItem['GOODS_LIST']) {
                    foreach ($arItem['GOODS_LIST'] as $arActionProduct) {
                        if($arActionProduct['PRICE_PROPERTIES']['PRODUCT_QUANTITY'] > 0) {?>


                            <?if ($curDir == '/catalog/' && isset($_GET['SECTION_ID'])) {

                                if ($count > 0 && $count % 4 == 0) {

                                    echo "</div><div class='cat-products clearfix j_cat_product_list'>";

                                }

                            }?>

                            <div class="cell" id="<? echo $strMainID; ?>">
                                <div class="wrapper clearfix">
                                    <?php
                                    echo "<div class='cat-product j_cat_product_card";
                                    if ($count > 0 && $count % 3 == 0) { echo " last"; }
                                    if($arActionProduct['PROPERTIES']['GOODS_PHOTOS']['VALUE'] != "") { echo " j-with-photos";}
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
                                        if($arActionProduct['PROPERTIES']['GOODS_PHOTOS']['VALUE'] != "") {
                                            echo "<div class='thumbnails hidden special-offer-thumbnails'><ul class='owl-slider j_owl_slider_3'>";
                                                echo "<li data-id='".$arActionProduct["ID"]."' class='j_preview_img active j_preview_img_main_".$arActionProduct['ID']."''><img src='";
                                                if($arActionProduct['PREVIEW_PICTURE']['SRC'] != "") {
                                                    echo $arActionProduct['PREVIEW_PICTURE']['SRC'];
                                                } else {
                                                    if($arActionProduct['DETAIL_PICTURE']['SRC'] != "") {
                                                        echo $arActionProduct['DETAIL_PICTURE']['SRC'];
                                                    }
                                                }
                                                echo "'></li>";
                                                $photosCount = count($arActionProduct["PROPERTIES"]["GOODS_PHOTOS"]["VALUE"]);

                                                for ($i=0; $i < $photosCount; $i++) {
                                                    $photo = CFile::GetFileArray($arActionProduct["PROPERTIES"]["GOODS_PHOTOS"]["VALUE"][$i]);

                                                    echo "<li class='j_preview_img' data-id='".$arActionProduct["ID"]."'><img src='".$photo["SRC"]."' alt='".$arActionProduct["NAME"]."'></li>";

                                                }
                                            echo "</ul></div>";
                                        }
                                    echo "</div></div>";
                                    echo "<div class='cat-hoverbox hidden last-full-cat-hoverbox'>";
                                        if($arActionProduct["PROPERTIES"]["GOODS_ANALOG_LINKS"]["VALUE"] != "") {
                                            echo "<div class='box-analog'>";
                                            if($arActionProduct['IBLOCK_SECTION_ID'] && $arActionProduct['ID']) { echo "<a href='/catalog/?SECTION_ID=".$arActionProduct['IBLOCK_SECTION_ID']."&ELEMENT_ID=".$arActionProduct['ID']."#tAnalogs' class='analog-title'>"; }
                                                echo GetMessage("ANALOG_TITLE");
                                            if($arActionProduct['IBLOCK_SECTION_ID'] && $arActionProduct['ID']) { echo "</a>"; }
                                            echo "</div>";
                                        }
                                        echo "<div class='row clearfix'>";
                                            if($arActionProduct['BUY_URL']) { echo "<a href='".$arActionProduct['BUY_URL']."' class='cat-incart'>".GetMessage("CATALOG_ADD")."</a>"; }
                                            echo "<div class='like-block'>";
                                                if ($USER->IsAuthorized())
                                                {
                                                    if (isset($arActionProduct["PROPERTIES"]["F_USER"]["VALUE"])) {
                                                        if(in_array($USER->GetId(),$arActionProduct["PROPERTIES"]["F_USER"]["VALUE"])){
                                                            echo "<a href='#' class='cat-like j-auth-delete-from-favorite active' rel='". $arActionProduct['ID'] ."' title='".GetMessage("DEL_RFOM_FAVORITES")."'></a>";
                                                        }
                                                        else{
                                                            echo "<a href='#' class='cat-like j-auth-add-to-favorite' rel='". $arActionProduct['ID'] ."' title='".GetMessage("ADD_TO_FAVORITES")."'></a>";
                                                        }
                                                    }
                                                }else {
                                                    echo "<a href='#' class='cat-like' title='".GetMessage("ADD_TO_FAVORITES")."'></a>";
                                                    /*$favoriteGoods = isset($_COOKIE['favorite_goods']) ? explode(',', $_COOKIE['favorite_goods']) : array();

                                                    if(in_array($arActionProduct['ID'], $favoriteGoods)){
                                                        echo "<a href='#' class='cat-like j-delete-from-favorite active' rel='". $arActionProduct['ID'] ."' title='".GetMessage("DEL_RFOM_FAVORITES")."'></a>";
                                                    }
                                                    else{
                                                        echo "<a href='#' class='cat-like j-add-to-favorite' rel='". $arActionProduct['ID'] ."' title='".GetMessage("ADD_TO_FAVORITES")."'></a>";
                                                    }*/
                                                }
                                                if($arActionProduct['IN_COMPARE_LIST'] == "") {
                                                    if($arActionProduct['COMPARE_URL']) { echo "<a href='#' data-id='".$arActionProduct['ID']."' class='cat-compare j-animate-img-full j-add-to-compare' title='".GetMessage("ADD_TO_COMPARE")."'></a>"; }
                                                } else {
                                                    if($arActionProduct['COMPARE_DELETE_URL']) { echo "<a href='#' data-id='".$arActionProduct['ID']."' class='cat-compare active j-delete-from-compare' title='".GetMessage("DEL_FROM_COMPARE")."'></a>"; }
                                                }
                                            echo "</div>";
                                        echo "</div>";

                                        echo "<div class='row center row-rest'>";
                                            echo "<span class='cat-instock'>".GetMessage("REST")."</span>";
                                            if($bisTrustedUser) {
                                                ?>
                                                <?=getProductRestsInterval($arItem)?><?//=$arItem['CATALOG_QUANTITY']?>
                                            <?
                                            }
                                            else { ?>
                                                <?//=getProductRestsInterval($arItem)?>
                                            <?}
                                        echo "</div>";
                                        if($arActionProduct['PROPERTIES']['GOODS_FILTER_VALUE']['VALUE'] != "") {
                                            echo "<div class='row'><div class='cat-technicals'><ul>";
                                                foreach ($arActionProduct['PROPERTIES']['GOODS_FILTER_VALUE']['VALUE_NAMES'] as $filterValue) {
                                                    echo "<li>".$filterValue["FILTER_VARS"]["NAME"].": <b>".$filterValue["NAME"]."</b></li>";
                                                }
                                            echo "</ul></div></div>";
                                        }
                                    echo "</div>";
                                    ?>
                                </div>
                            </div>
                            <?$count++;?>
                        <?}
                    }
                }
            }
        echo "</div>";
        echo $arResult["NAV_STRING"];
    echo "</div>";?>
<?php
}
?>
