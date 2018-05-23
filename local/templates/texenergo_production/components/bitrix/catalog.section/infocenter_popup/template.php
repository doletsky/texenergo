<div class="manCategories clearfix">
	
	<?$i = 0;?>
	<?foreach($arResult['ITEMS'] as $arElement):?>
		
		<a href="<?=$arElement['PROPERTIES']['LINK']['VALUE']?>">
			<article class="manCategory clearfix <?if($i++%2 != 0):?>h-noMargin-right<?endif;?>">
			<img alt="производство" src="<?=$arElement['IMAGE']?>">
			<div class="b-manCategory-copy">
				<h2 class="thin"><?=$arElement['PROPERTIES']['CAPTION']['VALUE']?></h2>
				<p><?=$arElement['PROPERTIES']['BRIEF_INFO']['~VALUE']['TEXT']?></p>
			</div>
			</article>
		</a>
		
	<?endforeach;?>
		
</div>