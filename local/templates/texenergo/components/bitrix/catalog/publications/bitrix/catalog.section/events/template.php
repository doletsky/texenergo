<?if(count($arResult['ITEMS']) > 0):?>

<section class="promo-slider full-slider">
	<ul id="promo-slider">
		
		<?foreach($arResult['ITEMS'] as $arElement):?>
		
		<li>
			<a href="<?=$arElement['PROPERTIES']['LINK']['VALUE'];?>">
				<img class="sliderFeatured" src="<?=$arElement['PICTURE']?>" alt="<?=$arElement['NAME']?>">				
			</a>
		</li>
		
		<?endforeach;?>		
	</ul>
</section>

<?endif;?>