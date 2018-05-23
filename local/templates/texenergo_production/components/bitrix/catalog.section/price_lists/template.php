<section class="priceBody clearfix">
	
	<?$i = 0;?>

	<?foreach($arResult['ITEMS'] as $arItem):?>

		<?
		if($i % 2 != 0)
			$cl = 'no-margin';
		else
			$cl = '';
		?>
		
		<div class="priceList <?=$cl?>">
			
			<?if($arItem['ICON']):?>
			
				<a href="<?=$arItem['LINK'];?>" target="_blank">
					<img class="priceImage" src="<?=$arItem['ICON'];?>" alt="<?=$arItem['NAME']?>">
				</a>
			
			<?endif;?>
			
			<div class="priceList-caption caption-production">
				<a href="<?=$arItem['LINK'];?>" target="_blank">
					<h3>
						<?=$arItem['NAME'];?>
					</h3>
				</a>
				<a href="<?=$arItem['LINK'];?>" target="_blank"><span><?=$arItem['PREVIEW_TEXT']?></span></a>
			</div>
		</div>

		<?$i++;?>	
			
	<?endforeach;?>
	
</section>