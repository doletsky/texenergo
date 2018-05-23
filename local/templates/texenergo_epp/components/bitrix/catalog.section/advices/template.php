<section class="b-contactsInCity">
	<section class="contacts">
	
		<section class="contactBody">
			<div class="b-contactBody">
							
				<?foreach($arResult['ITEMS'] as $arItem):?>
			
					<section class="helpLinks toggleThis">		
						<div class="questionHeadline">
							<h1>
								<a class="toggle_trigger" href="#"></a>
								<a class="help_href t_trigger" href="#"><?=$arItem['NAME']?></a>
							</h1>
						</div>
						<div class="questionDetails newsPr toggleThis" style="display:none;">
							<p><?=$arItem['~DETAIL_TEXT'];?></p>
						</div>
					</section>
			
				<?endforeach;?>			
			
			</div>
		</section>
	
		<?=$arResult['NAV_STRING']?>	
		
	</section>
</section>