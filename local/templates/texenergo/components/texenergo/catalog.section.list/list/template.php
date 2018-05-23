<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$strDepthSym = '>';

if (0 < $arResult["SECTIONS_COUNT"])
{
    $arrayParent = Array();
    $countArray = 0;
    foreach ($arResult['SECTIONS'] as $arSection) {
        if($arSection["IBLOCK_SECTION_ID"]) {
            if(in_array($arSection["IBLOCK_SECTION_ID"],$arrayParent) == false) {
                $arrayParent[$countArray] = $arSection["IBLOCK_SECTION_ID"];
                $countArray++;
            }
        }
    }
    $arrayChild = Array();
    $arrayChild2 = Array();
    $countArray = 0;
    $countArray2 = 0;
    $currentDepth = 1;
    foreach ($arResult['SECTIONS'] as $arSection) {
        if($currentDepth > $arSection["DEPTH_LEVEL"]) {
            if($currentDepth == ($arSection["DEPTH_LEVEL"]+1)) {
                if(in_array($arSection["ID"],$arrayChild) == false) {
                    $arrayChild[$countArray] = $arSection["ID"];
                    $countArray++;
                }
            } else {
                if(in_array($arSection["ID"],$arrayChild2) == false) {
                    $arrayChild2[$countArray2] = $arSection["ID"];
                    $countArray2++;
                }
            }
        }
        $currentDepth = $arSection['DEPTH_LEVEL'];
    }
    
    function levelBanner($arSection) {
        global $APPLICATION;
        if($arSection["UF_GOODS_BANNER_VARS"]) {
            echo "<div class='block-bar-promo group'>";
                if($arSection["UF_GOODS_BANNER_VARS"]["PREVIEW_PICTURE"]["SRC"] || $arSection["UF_GOODS_BANNER_VARS"]["DETAIL_PICTURE"]["SRC"]) {
                    if($arSection["UF_GOODS_BANNER_VARS"]["IBLOCK_SECTION_ID"] != "" && $arSection["UF_GOODS_BANNER_VARS"]["ID"] != "") {
                        echo "<a href='/catalog/?SECTION_ID=".$arSection["UF_GOODS_BANNER_VARS"]["IBLOCK_SECTION_ID"]."&ELEMENT_ID=".$arSection["UF_GOODS_BANNER_VARS"]["ID"]."'>";
                    }
                    if($arSection["UF_GOODS_BANNER_VARS"]["PREVIEW_PICTURE"]["SRC"]) {
                        echo "<img src='".$arSection["UF_GOODS_BANNER_VARS"]["PREVIEW_PICTURE"]["SRC"]."' alt='".$arSection["UF_GOODS_BANNER_VARS"]["NAME"]."'>";
                    } else {
                        echo "<img src='".$arSection["UF_GOODS_BANNER_VARS"]["DETAIL_PICTURE"]["SRC"]."' alt='".$arSection["UF_GOODS_BANNER_VARS"]["NAME"]."'>";
                    }
                    if($arSection["UF_GOODS_BANNER_VARS"]["IBLOCK_SECTION_ID"] != "" && $arSection["UF_GOODS_BANNER_VARS"]["ID"] != "") {
                        echo "</a>";
                    }
                }
                echo "<div class='caption'>";
                    if($arSection["UF_GOODS_BANNER_VARS"]["IBLOCK_SECTION_ID"] != "" && $arSection["UF_GOODS_BANNER_VARS"]["ID"] != "") {
                        echo "<a href='/catalog/?SECTION_ID=".$arSection["UF_GOODS_BANNER_VARS"]["IBLOCK_SECTION_ID"]."&ELEMENT_ID=".$arSection["UF_GOODS_BANNER_VARS"]["ID"]."' class='name-bar'>";
                    }
                        echo $arSection["UF_GOODS_BANNER_VARS"]["NAME"];
                    if($arSection["UF_GOODS_BANNER_VARS"]["IBLOCK_SECTION_ID"] != "" && $arSection["UF_GOODS_BANNER_VARS"]["ID"] != "") {
                        echo "</a>";
                    }
                    if($arSection["UF_GOODS_BANNER_VARS"]["PROPERTY_GOODS_ART_VALUE"] != "") {
                        echo "<span class='sku-bar'>".$arSection["UF_GOODS_BANNER_VARS"]["PROPERTY_GOODS_ART_VALUE"]."</span>";
                    }
                    echo "<div class='cat-rating cat-value-raiting";
                    if($arSection['UF_GOODS_BANNER_VARS']['PROPERTY_GOODS_RATE_VALUE'] != "") {
                        echo "-".$arSection['UF_GOODS_BANNER_VARS']['PROPERTY_GOODS_RATE_VALUE'];
                    }
                    echo "'></div>";
                    if($arSection['UF_GOODS_BANNER_VARS']['PRICE_PROPERTIES']['PRICE'] != "") {
                        echo "<span class='price-bar'>".$arSection['UF_GOODS_BANNER_VARS']['PRICE_PROPERTIES']['PRICE']." <i class='rouble'>a</i></span>";
                    }
                echo "</div>";
            echo "</div>";
        } else {
            if($arSection["UF_SERIAL_BANNER_VARS"] && is_array($arSection["UF_SERIAL_BANNER_VARS"])) {
                echo '<div class="block-bar-promo group"><ul>';
                foreach($arSection["UF_SERIAL_BANNER_VARS"] as $vars) {
                    if ( $vars["PREVIEW_PICTURE"]["SRC"] ) {
                        $resizeBigPic = CFile::ResizeImageGet($vars["PREVIEW_PICTURE"], Array("width" => 58, "height" => 58), true);
                        echo "<li><img src='".$resizeBigPic["src"]."' alt='".$vars["NAME"]."'></li>";
                    }
                }
                echo "</ul>";
                if ( ($arSection["UF_SERIAL_BANNER_COUNT"] - 3) > 3 ) {
                    echo "<a class='block-bar-other' href='/catalog/?SECTION_ID=".$arSection["ID"]."'>Еще ". ((int)$arSection["UF_SERIAL_BANNER_COUNT"] - 3) ." товаров</a>";
                }
                echo "</div>";
            }
        }
    }
    
    function catView($arSection,$currentDepth,$arrayChild,$arrayChild2,$arrayParent) {
        if(in_array($arSection['ID'],$arrayParent)) {
            if($currentDepth > $arSection["DEPTH_LEVEL"])
            {
                if(in_array($arSection['ID'],$arrayChild)) {
                    echo "</ul></li>";
                } elseif(in_array($arSection['ID'],$arrayChild2)) {
                    for($i=0;$i<($currentDepth - $arSection["DEPTH_LEVEL"] - 1);$i++) {
                        echo "</ul></li>";
                    }
                    echo "</ul></li>";
                }
            }
            if($arSection["DEPTH_LEVEL"] == 2) {
                echo "<li class='li-bar-parent has-child'>";
            } else {
                echo "<li class='li-bar-child has-child'>";
            }
            if($arSection["UF_GOODS_BANNER_VARS"] || $arSection["UF_SERIAL_BANNER_VARS"]) { echo "<div class='wrap-bar-child'>"; }
            echo "<a href='".$arSection['SECTION_PAGE_URL']."'>".$arSection['NAME'];
            if ($arSection["ELEMENT_CNT"])
            {
                echo " <em>(".$arSection["ELEMENT_CNT"].")</em>";
            }
            echo "</a>";
            if($arSection["UF_GOODS_BANNER_VARS"] || $arSection["UF_SERIAL_BANNER_VARS"]) { echo "</div>"; }
            echo "<ul class='ul-bar-child'>";
            levelBanner($arSection);
        } else {
            if($currentDepth > $arSection["DEPTH_LEVEL"]) 
            {
                if(in_array($arSection['ID'],$arrayChild)) {
                    echo "</ul></li>";
                } elseif(in_array($arSection['ID'],$arrayChild2)) {
                    for($i=0;$i<($currentDepth - $arSection["DEPTH_LEVEL"] - 1);$i++) {
                        echo "</ul></li>";
                    }
                    echo "</ul></li>";
                }
            }
            if($arSection["DEPTH_LEVEL"] == 2) {
                echo "<li class='li-bar-parent'>";
            } else {
                echo "<li class='li-bar-child'>";
            }
            if($arSection["UF_GOODS_BANNER_VARS"] || $arSection["UF_SERIAL_BANNER_VARS"]) { echo "<div class='wrap-bar-child'>"; }
            echo "<a href='".$arSection['SECTION_PAGE_URL']."'>".$arSection['NAME'];
            if ($arSection["ELEMENT_CNT"])
            {
                echo " <em>(".$arSection["ELEMENT_CNT"].")</em>";
            }
            echo "</a>";
            levelBanner($arSection);
            if($arSection["UF_GOODS_BANNER_VARS"] || $arSection["UF_SERIAL_BANNER_VARS"]) { echo "</div>"; }
            echo "</li>";
        }
    }
    
    function catViewLast($arSection,$arrayChild,$arrayChild2) {
        if(in_array($arSection['ID'],$arrayChild)) {
        } else {
            if(in_array($arSection['ID'],$arrayChild2)) {
            } else {
                if($arSection["DEPTH_LEVEL"] > 1) {
                    for($i=0;$i<($arSection["DEPTH_LEVEL"]-2);$i++) {
                        echo "</ul></li>";
                    }
                }
            }
        }
    }
    
?>
    <div class="twelve">
        <div class="bar-column">
            <?php 
            $count = 0;
            $count2 = 1;
            $count3 = 0;
            $count4 = 0;
            $currentDepth = 1;
            $parentDepth = "";
            foreach ($arResult['SECTIONS'] as $arSection)
            {
                if($arSection["DEPTH_LEVEL"] == 1) {
                    if((3*$count+1) == $count2) {
                        if($count == 0) {
                            echo "<div class='bar'><h1>".$arSection['NAME']."</h1><ul class='ul-bar-parent'>";
                            $count4++;
                        } else {
                            echo "</ul></div><div class='bar'><h1>".$arSection['NAME']."</h1><ul class='ul-bar-parent'>";
                        }
                    }
                } else {
                    if($parentDepth != "") {
                        catView($arSection,$currentDepth,$arrayChild,$arrayChild2,$arrayParent);
                    }
                }
                
                if($count3 == ($arResult["SECTIONS_COUNT"]-1)) {
                    if($parentDepth != "") {
                        catViewLast($arSection,$arrayChild,$arrayChild2);
                    }
                    if($count4 > 0) { echo "</ul></div>"; }
                }
                if($arSection['DEPTH_LEVEL'] == 1) {
                    if((3*$count+1) == $count2) {
                        $count++;
                        $parentDepth = $arSection['ID'];
                    } else {
                        $parentDepth = "";
                    }
                    $count2++;
                }
                $currentDepth = $arSection['DEPTH_LEVEL'];
                $count3++;
            }?>
        </div>
        <div class="bar-column">
            <?php 
            $count = 1;
            $count2 = 1;
            $count3 = 0;
            $count4 = 0;
            $currentDepth = 1;
            $parentDepth = "";
            foreach ($arResult['SECTIONS'] as $arSection)
            {
                if($arSection["DEPTH_LEVEL"] == 1) {
                    if((2*$count + ($count-1)) == $count2) {
                        if($count == 1) {
                            echo "<div class='bar'><h1>".$arSection['NAME']."</h1><ul class='ul-bar-parent'>";
                            $count4++;
                        } else {
                            echo "</ul></div><div class='bar'><h1>".$arSection['NAME']."</h1><ul class='ul-bar-parent'>";
                        }
                    }
                } else {
                    if($parentDepth != "") {
                        catView($arSection,$currentDepth,$arrayChild,$arrayChild2,$arrayParent);
                    }
                }
                
                if($count3 == ($arResult["SECTIONS_COUNT"]-1)) {
                    if($parentDepth != "") {
                        catViewLast($arSection,$arrayChild,$arrayChild2);
                    }
                    if($count4 > 0) { echo "</ul></div>"; }
                }
                if($arSection['DEPTH_LEVEL'] == 1) {
                    if((2*$count + ($count-1)) == $count2) {
                        $count++;
                        $parentDepth = $arSection['ID'];
                    } else {
                        $parentDepth = "";
                    }
                    $count2++;
                }
                $currentDepth = $arSection['DEPTH_LEVEL'];
                $count3++;
            }?>
        </div>
        <div class="bar-column">
            <?php 
            $count = 1;
            $count2 = 0;
            $count3 = 0;
            $currentDepth = 1;
            $parentDepth = "";
            foreach ($arResult['SECTIONS'] as $arSection)
            {
                if($arSection["DEPTH_LEVEL"] == 1) {
                    if(($count%3) == 0) {
                        if($count2 == 0) {
                            echo "<div class='bar'><h1>".$arSection['NAME']."</h1><ul class='ul-bar-parent'>";
                            $count2++;
                        } else {
                            echo "</ul></div><div class='bar'><h1>".$arSection['NAME']."</h1><ul class='ul-bar-parent'>";
                        }
                    }
                } else {
                    if($parentDepth != "") {
                        catView($arSection,$currentDepth,$arrayChild,$arrayChild2,$arrayParent);
                    }
                }
                
                if($count3 == ($arResult["SECTIONS_COUNT"]-1)) {
                    if($parentDepth != "") {
                        catViewLast($arSection,$arrayChild,$arrayChild2);
                    }
                    if($count2 > 0) { echo "</ul></div>"; }
                }
                if($arSection['DEPTH_LEVEL'] == 1) {
                    if(($count%3) == 0) {
                        $parentDepth = $arSection['ID'];
                    } else {
                        $parentDepth = "";
                    }
                    $count++;
                }
                $currentDepth = $arSection['DEPTH_LEVEL'];
                $count3++;
            }?>
        </div>
    </div>
<?php
}
?>