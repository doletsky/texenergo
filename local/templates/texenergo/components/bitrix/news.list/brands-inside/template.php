<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<section class="cat-block">
    <header class="cat-header border-bottom">
        <h1><?php echo GetMessage("BRAND_TITLE"); ?></h1>
    </header>
    <div class="brands owl-slider j_owl_slider_1">
        <div><ul>
            <?
            $count = 1;
            foreach($arResult["ITEMS"] as $arItem):?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <li id="<?=$this->GetEditAreaId($arItem['ID']);?>"<?php if($count%4 == 0) { echo " style='position:relative; left:8px' class='last'"; }?>>
                    <?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
                        <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"/></a>
                        <?else:?>
                            <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"/>
                        <?endif;?>
                    <?endif?>
                </li>
                <?php
                if($count != count($arResult['ITEMS'])) {
                    if($count%4 == 0) {
                        echo "</ul></div><div><ul>";
                    }
                }
                $count++;
                ?>
            <?endforeach;?>
        </ul></div>
    </div>
</section>
