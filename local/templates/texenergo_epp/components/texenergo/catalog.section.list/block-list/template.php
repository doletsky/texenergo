<?

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$strDepthSym = '>';

if (0 < $arResult["SECTIONS_COUNT"])
{
    $arrayParent = Array();
    $countArray = 0;
    foreach ($arResult['SECTIONS'] as $arSection) {
        if($arSection["DEPTH_LEVEL"] < 3) {
            if($arSection["IBLOCK_SECTION_ID"]) {
                if(in_array($arSection["IBLOCK_SECTION_ID"],$arrayParent) == false) {
                    $arrayParent[$countArray] = $arSection["IBLOCK_SECTION_ID"];
                    $countArray++;
                }
            }
        }
    }
    $lastParent = array_reverse($arrayParent);
    $lastParent = $lastParent[0];
    
?>
    <div class="twelve">
        <section class="catalog">
            <?php 
            $count = 0;
            $count2 = 0;
            $count3 = 0;
            $parentDepth = "";
            $currentDepth = 1;
            foreach ($arResult['SECTIONS'] as $arSection)
            {
                if($arSection["DEPTH_LEVEL"] == 1) {


                    if($count3 == 0) {
                        echo "<div class='row row-category'>";
                    }
                    
                    if($count3 == 2) {
                        echo "<article class='cat-unit last clearfix'>";
                    } else {
                        echo "<article class='cat-unit clearfix'>";
                    }

                    $picture = CFile::GetFileArray($arSection["PICTURE"]);

                    if($arSection["PICTURE"]["SRC"]) {
                        echo "<figure><img src='".$picture["SRC"]."' alt='".$arSection['NAME']."'><figcaption><a href='".$arSection['SECTION_PAGE_URL']."'>".$arSection['NAME'];
                    } else {
                        echo "<figure><figcaption><a href='".$arSection['SECTION_PAGE_URL']."'>".$arSection['NAME'];
                    }
                    // if ($arSection["ELEMENT_CNT"])
                    // {
                    //     echo "&nbsp;<span class='total-items'>(".$arSection["ELEMENT_CNT"].")</span>";
                    // }
                    echo "</a></figcaption></figure></article>";
                    
                    $count3++;
                    if($count3 > 2) {
                        
                        echo "</div>";
                        $count3 = 0;
                    }


                    // if($currentDepth == $arSection["DEPTH_LEVEL"]) {
                    //     if(in_array($arSection['ID'],$arrayParent)) {
                    //         if($count2 == 0) {
                    //             echo "<section class='product-category'><h1 class='cat-subheading'>".$arSection['NAME']."</h1>";
                    //             $count2++;
                    //         } else {
                    //             if($count3 != 0) {
                    //                 echo "</div>";
                    //                 $count3 = 0;
                    //             }
                    //             if($arSection['ID'] == $lastParent) {
                    //                 echo "</section><section class='product-category last'><h1 class='cat-subheading'>".$arSection['NAME']."</h1>";
                    //             } else {
                    //                 echo "</section><section class='product-category'><h1 class='cat-subheading'>".$arSection['NAME']."</h1>";
                    //             }
                    //         }
                    //     }
                    // }
                    // if($currentDepth > $arSection["DEPTH_LEVEL"])
                    // {
                    //     if(in_array($arSection['ID'],$arrayParent)) {
                    //         if($count3 != 0) {
                    //             echo "</div>";
                    //             $count3 = 0;
                    //         }
                    //         if($arSection['ID'] == $lastParent) {
                    //             echo "</section><section class='product-category last'><h1 class='cat-subheading'>".$arSection['NAME']."</h1>";
                    //         } else {
                    //             echo "</section><section class='product-category'><h1 class='cat-subheading'>".$arSection['NAME']."</h1>";
                    //         }
                    //     }
                    // }
                } 
                // else {
                //     if($parentDepth != "") {
                //         if($arSection["DEPTH_LEVEL"] == 2) {
                //             if($count3 == 0) {
                //                 echo "<div class='row row-category'>";
                //             }
                            
                //             if($count3 == 2) {
                //                 echo "<article class='cat-unit last clearfix'>";
                //             } else {
                //                 echo "<article class='cat-unit clearfix'>";
                //             }

                //             $picture = CFile::GetFileArray($arSection["PICTURE"]);

                //             if($arSection["PICTURE"]["SRC"]) {
                //                 echo "<figure><img src='".$picture["SRC"]."' alt='".$arSection['NAME']."'><figcaption><a href='".$arSection['SECTION_PAGE_URL']."'>".$arSection['NAME'];
                //             } else {
                //                 echo "<figure><figcaption><a href='".$arSection['SECTION_PAGE_URL']."'>".$arSection['NAME'];
                //             }
                //             if ($arSection["ELEMENT_CNT"])
                //             {
                //                 echo "&nbsp;<span class='total-items'>(".$arSection["ELEMENT_CNT"].")</span>";
                //             }
                //             echo "</a></figcaption></figure></article>";
                            
                //             $count3++;
                //             if($count3 > 2) {
                                
                //                 echo "</div>";
                //                 $count3 = 0;
                //             }
                //         }
                //     }
                // }
                
                if($count == ($arResult["SECTIONS_COUNT"]-1)) {
                    if($count3 != 0) {
                        echo "</div>";
                        $count3 = 0;
                    }
                    echo "</section>";
                }
                if($arSection['DEPTH_LEVEL'] == 1) {
                    if(in_array($arSection['ID'],$arrayParent)) {
                        $parentDepth = $arSection['ID'];
                    } else {
                        $parentDepth = "";
                    }
                }
                $currentDepth = $arSection['DEPTH_LEVEL'];
                $count++;
            }?>
        </section>
    </div>
<?php
}
?>