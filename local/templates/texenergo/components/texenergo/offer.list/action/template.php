<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$count = 0;
foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	if($count == 0)
    {
    ?>
        <section class="pPromoRow" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <a href="/offer/?ELEMENT_ID=<?=$arItem['ID']?>" class="pPromoTag"><?php echo GetMessage("ACTION_TITLE"); ?></a><div><?php echo $arItem['PREVIEW_TEXT']; ?></div>
        </section>
    <?php
    }
    $count++;
    ?>
<?endforeach;?>
