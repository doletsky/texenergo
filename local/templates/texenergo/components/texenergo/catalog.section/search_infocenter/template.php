<section class="helpItem helpItem-shipping">
	<header class="clearfix nochildren">		
		<h1><i class=""></i>Результаты поиска</h1>
	</header>
	
	<?if(count($arResult['ITEMS']) > 0):?>
	
		<?foreach($arResult['ITEMS'] as $arElement):?>
		
		<section class="helpLinks toggleThis">
			<div class="questionHeadline">
				<h1 class="nochildren bld"><a href="<?=$arElement['DETAIL_PAGE_URL']?>"><?=$arElement['NAME']?></a></h1>
			</div>
			<div class="questionDetails toggleThis" style="display:block;">
				<p><?=$arElement['~PREVIEW_TEXT'];?></p>
			</div>
		</section>
		
		<?endforeach;?>
	
	<?else:?>
		
		<div class=" helpLinks search-result-empty">По вашему запросу ничего не найдено</div>
	
	<?endif;?>
	
	<!--<button class="rounded rounded-shipping expand-all">Развернуть все</button>-->
	<br/><br/>
</section>

<?=$arResult['NAV_STRING'];?>