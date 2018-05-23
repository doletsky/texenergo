<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<input type="hidden" class="max_tree_index" value="<?=$arResult['MAX_INDEX']?>" />

<!-- left column -->
<div class="aboutLeft clearfix">
	
	<div>&nbsp;</div>
	
	<?foreach($arResult['LEFT_COL_ITEMS'] as $arItem):?>
		
	<article id="tItem-<?=$arItem['TREE_INDEX']?>" class="storyArticle timelineItem">
		
		<?if($arItem['PICTURE']):?>
		
			<img alt="<?=$arItem['NAME']?>" src="<?=$arItem['PICTURE']?>" height="<?=$arItem['PICTURE_DATA']['height'];?>">
		
		<?endif;?>
		
		<p><?=$arItem['~PREVIEW_TEXT']?></p>
		<div class="hangerRight">
			<span class="hangerDate hangerDate-right"><?=$arItem['DATE_DAYMONTH']?></span>
		</div>
	</article>
	
	<?endforeach;?>	
	
</div>

<!-- storyline -->

<div class="aboutStory">
	<div class="storyVertical">
		
		<?foreach($arResult['YEARS'] as $arYear):?>
		
			<?if($arYear['YEAR']):?>
			
				<span id="tItem-<?=$arYear['TREE_INDEX']?>" class="storyYear timelineItem"><?=$arYear['YEAR']?></span>
			
			<?else:?>
			
				<span id="tItem-<?=$arYear['TREE_INDEX']?>" class="storyYear hidden timelineItem" style="display:none;"></span>
			
			<?endif;?>
		
		<?endforeach;?>
		
	</div>
</div>

<!-- right column -->

<div class="aboutRight clearfix">
	
	<div>&nbsp;</div>
	
	<?foreach($arResult['RIGHT_COL_ITEMS'] as $arItem):?>
		
	<article id="tItem-<?=$arItem['TREE_INDEX']?>" class="storyArticle timelineItem">
		
		<?if($arItem['PICTURE']):?>
		
			<img alt="<?=$arItem['NAME']?>" src="<?=$arItem['PICTURE']?>" height="<?=$arItem['PICTURE_DATA']['height'];?>">
		
		<?endif;?>
		
		<p><?=$arItem['~PREVIEW_TEXT']?></p>
		<div class="hangerLeft">
			<span class="hangerDate hangerDate-left"><?=$arItem['DATE_DAYMONTH']?></span>
		</div>
	</article>
	
	<?endforeach;?>	
	
</div>