<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>

<ul>

<?

$first = true;
$previousLevel = 0;
foreach($arResult as $arItem):
?>
	<?if ($previousLevel && $arItem["DEPTH_LEVEL"] <= $previousLevel):?>
		<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
	<?endif?>

	<?$style = '';?>
	
	<?if($first && $arItem["DEPTH_LEVEL"] == 2):
		$first = false;
		$style = "";
		if($arParams["FIRST_ITEM_COLOR"])
			$style = 'color:'.$arParams["FIRST_ITEM_COLOR"];
	endif;?>
	
	<?if ($arItem["IS_PARENT"]):?>
	
			<li class="<? if($arItem["DEPTH_LEVEL"] == 1) echo "menu"; ?>" >								
				<a style="<?=$style?>" class="<? if($arItem["DEPTH_LEVEL"] == 1) echo "side-category"; ?><? if($arItem["SELECTED"] || $arItem["CHILD_SELECTED"]) echo " is-opened"; ?><? if($arItem["SELECTED"] && $arItem["DEPTH_LEVEL"] == 2) echo " active"; ?>" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?>
				
				<?if($arItem['DEPTH_LEVEL'] == 1):?>
					<span class="more-icon <? if($arItem["SELECTED"]) echo " is-opened"; ?>"></span>
				<?endif;?>
				
				</a>
				
				<ul class="submenu" <? if(!$arItem["SELECTED"]) echo 'style="display:none;"'; else echo 'style="display:block;"'; ?>>

	<?else:?>
		
		<li class="<? if($arItem["DEPTH_LEVEL"] == 1) echo "menu"; ?>" >													
			<a style="<?=$style?>"  class="<?if($arItem["PARAMS"]["no_infocenter_ajax"] == "y"):?>no-popup <?endif;?><? if($arItem["SELECTED"] == 1):?>active<?endif;?> <? if($arItem["DEPTH_LEVEL"] == 1) echo "side-single"; ?> <?if($arItem["PARAMS"]["new_window"] == "y"):?>target-blank<?endif;?>" href="<?=$arItem["LINK"]?>" <?if($arItem["PARAMS"]["new_window"] == "y"):?>target="_blank"<?endif;?>><?=$arItem["TEXT"]?><?if($arItem["PARAMS"]["new_window"] == "y"):?><span class="icon-target-blank"></span><?endif;?></a>
		</li>		

	<?endif?>

	<?$previousLevel = $arItem["DEPTH_LEVEL"];?>

<?endforeach?>

<?if ($previousLevel > 1)://close last item tags?>
	<?=str_repeat("</ul></li>", ($previousLevel-1) );?>
<?endif?>

</ul>

<?endif?>