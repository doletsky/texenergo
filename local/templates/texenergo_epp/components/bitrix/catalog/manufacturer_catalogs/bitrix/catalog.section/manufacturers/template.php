<header class="priceHeader">
	<p>	
	 <?$APPLICATION->IncludeComponent(
			"bitrix:main.include",
			"",
			Array(
				"AREA_FILE_SHOW" => "file",
				"AREA_FILE_SUFFIX" => "inc",
				"EDIT_TEMPLATE" => "",
				"PATH" => "/include/manufacturer_catalog_text.php"
			)
		);?>
	</p>
</header>
<section class="priceBody priceBody-catalog clearfix">
	<!--
	<ul class="catalogBrands">
		
		<?$i = 1;?>
		
		<?foreach($arResult['ITEMS'] as $arItem):?>
		
			<li class="<?if($i % 4 == 0):?>last<?endif;?>">
				<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
					
					<?if($arItem['PICTURE']):?>
						
						<img src="<?=$arItem['PICTURE']?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>">
						
					<? else:?>
					
						<p class="name"><?=$arItem['NAME']?></p>
					
					<?endif;?>
					
				</a>
			</li>

			<?$i++;?>	
		
		<?endforeach;?>
		
	</ul>
	-->	
		
	<table class="manufacturer-table" width="100%">
		
		<?$rows = array_chunk($arResult['ITEMS'], 4);?>
		
		<?foreach($rows as $row):?>
		
			<tr>
			
				<?$i = 0?>
				
				<?foreach($row as $arItem):?>
				
					<td>
						<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
						<?if($arItem['PICTURE']):?>
							
							<img src="<?=$arItem['PICTURE']?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>">
							
						<? else:?>
						
							<p class="name"><?=$arItem['NAME']?></p>
						
						<?endif;?>
						</a>						
					</td>
					
					<?$i++;?>
				
				<?endforeach;?>
				
				<?=str_repeat('<td class="empty"></td>', 4 - $i);?>
			
			</tr>
		
		<?endforeach;?>
	
	</table>
</section>