<section class="promo-slider">
	<ul id="promo-slider">
		
		<?foreach($arResult['ITEMS'] as $arElement):?>
		
		<li>
			<a href="<?=$arElement['DETAIL_PAGE_URL']?>">			
			<img class="sliderFeatured" src="<?=$arElement['PICTURE']?>" alt="<?=$arElement['NAME']?>">
			</a>
			<a href="<?=$arElement['DETAIL_PAGE_URL']?>">
			<h1 class="tagline-promo"><?=$arElement['NAME']?></h1>
			</a>
			<a href="<?=$arElement['DETAIL_PAGE_URL']?>">
			<span class="subline-promo"><?=$arElement['PREVIEW_TEXT']?></span>
			</a>
		</li>
		
		<?endforeach;?>		
	</ul>
</section>