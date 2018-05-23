<section class="helpItem helpItem-shipping">
	<header class="clearfix">		
		<h1><i class="<?=$arParams['ICON_CLASS']?>"></i><?=$arResult['NAME'];?></h1>
	</header>
	
	<?
	if($arResult['XML_ID'] == 'delivery'){
		$showCalculator = true;
	}
	?>
	
	<?foreach($arResult['ITEMS'] as $arElement):?>
	
	<?/* if($showCalculator):?>
		
		<section class="helpLinks toggleThis">		
			<div class="questionHeadline" code="delivery_calculator">
				<h1>
					<a class="toggle_trigger" href="#"></a>
					<a class="help_href" href="<?=$arElement['DETAIL_PAGE_URL']?>"><?=$arElement['NAME']?></a>
				</h1>
			</div>
			<div class="questionDetails newsPr toggleThis" style="display:none;">
				<p><?=$arElement['~DETAIL_TEXT'];?></p>
			</div>
		</section>
		
		<?$showCalculator = false;?>
		
	<?endif; */?>
	
	<section class="helpLinks toggleThis">		
		<div class="questionHeadline" code="<?=$arElement['CODE']?>">
			<h1>
				<a class="toggle_trigger" href="#"><!--<img src="<?=SITE_TEMPLATE_PATH?>/img/catalog/item-open.png">--></a>
				<a class="help_href" href="<?=$arElement['DETAIL_PAGE_URL']?>"><?=$arElement['NAME']?></a>
			</h1>
		</div>
		<div class="questionDetails newsPr toggleThis" style="display:none;">
			<p><?=$arElement['~DETAIL_TEXT'];?></p>
		</div>
	</section>
	
	<?endforeach;?>
	
	<button class="rounded rounded-shipping expand-all collapsed">Развернуть все</button>
</section>

<script>
$(function(){
	var hash = window.location.hash;
	hash = hash.substr(1);
	if(hash.length > 0){		
		$('.questionHeadline').each(function(){
			var code = $(this).attr('code');
			if(code == hash){
				$('h1', this).trigger('click');
				return false;
			}
		});
	}
});
</script>