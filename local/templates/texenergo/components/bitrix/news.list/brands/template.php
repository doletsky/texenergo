<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php
$texenergoON = $_COOKIE['texenergo_vendor'];
$catalogViewON = $_COOKIE['catalog_view'];
?>
<div class="twelve">
    <?php
        if(isset($catalogViewON)) {
            if($catalogViewON == 1) {
                echo "<section class='brand-block'>";
            } else {
                echo "<section class='brand-block brand-block-list'>";
            }
        } else {
            echo "<section class='brand-block brand-block-list'>";
        }
    ?>
        <ul>
            <?foreach($arResult["ITEMS"] as $arItem):?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <li id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                    <?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
                        <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"/></a>
                        <?else:?>
                            <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"/>
                        <?endif;?>
                    <?endif?>
                </li>
            <?endforeach;?>
        </ul>
    </section>
</div>
