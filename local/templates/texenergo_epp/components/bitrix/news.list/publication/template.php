<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (count($arResult["ITEMS"]) > 0): ?>
    <ul class="owl-carousel owl-theme j_pub_carousel j_switch_img_item j-publications-block" data-switch="1">
        <? foreach ($arResult["ITEMS"] as $arItem): ?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

            ?>
            <li class="pSingle" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <? if ($arItem["PICTURE"]): ?>
                    <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
                        <img class="pPublicationsImg" src="<?= $arItem["PICTURE"] ?>"
                             alt="<?= $arItem["NAME"] ?>">
                    </a>
                <? endif ?>
                
				<p class="date-container-pub">
				
				<? if (!empty($arItem["DISPLAY_ACTIVE_FROM"])): ?>
                    <span class="date">
                        <?= $arItem["DISPLAY_ACTIVE_FROM"]; ?>
                        <? if ($arItem['SECTION']): ?>, <? endif; ?>
                    </span>
                <? endif ?>
                <? if ($arItem['SECTION']): ?>
                    <span class="tag"><?= $arItem['SECTION']['NAME']; ?></span>
                <? endif; ?>
				
				</p>
				
                <p><a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><?= $arItem["NAME"]; ?></a></p>
            </li>
        <? endforeach; ?>
    </ul>
    <? if (count($arResult["ITEMS"]) > 1): ?>
        <b class="pPubPages j_switch_img_item" data-switch="1"><span class="j_pub_carousel_num">1</span>
            из <?= count($arResult["ITEMS"]) ?></b>
    <? endif; ?>
<? endif; ?>