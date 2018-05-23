<div class="relatedPubs">

<? if (!empty($arResult['ITEMS'])): ?>
    <section class="cat-products cat-products-land cat-products-list clearfix publications">
        
		<div class="relatedPubs-header">
			<h1><?=$arResult['NAME']?></h1>
		</div>
       
        <div class="wrapper wrapper-land j_owl_slider_3 related_pub mainpage owl-theme owl-slider">
            <? 												
			foreach ($arResult['ITEMS'] as $arItem): ?>
                <article class="cat-product cat-product-list">
                   <figure class="relatedPubs-figure special-figure">
						<a class="img_container" href="<?=$arItem['DETAIL_PAGE_URL']?>"><img src="<?=$arItem['PICTURE']?>" alt="<?=$arItem['NAME']?>"></a>
						<div class="row clearfix">
							<? if (!empty($arItem['SPECIAL_TIMER'])): ?>
								<div class="cat-promo">
									<div class="cat-promo-time">
										<span class="text">До конца акции</span>

										<div
											class="digits cat-day"><?= $arItem['SPECIAL_TIMER']['DAYS']; ?></div>
										<span class="two-dots">:</span>

										<div
											class="digits cat-hour"><?= $arItem['SPECIAL_TIMER']['HOURS']; ?></div>
										<span class="two-dots">:</span>

										<div
											class="digits cat-min"><?= $arItem['SPECIAL_TIMER']['MINUTES']; ?></div>
									</div>
									<ul class="cat-promo-captions">
										<li>день</li>
										<li>час</li>
										<li>мин</li>
									</ul>
								</div>
							<? endif; ?>
						</div>
						<figcaption>
							<span class="date"><?//=$arItem['ACTIVE_FROM_FORMATTED']?></span>
							<span class="tag"><?=$arResult['NAME']?></span>
							<p class="pubsText"><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a></p>
						</figcaption>
					</figure>
                </article>
				
            <? endforeach; ?>
			
        </div> 
		<footer>
			<a href="<?=$arResult['SECTION_PAGE_URL']?>" class="show">все <?=$arResult['NAME']?></a>
		</footer>		
    </section>

<? endif; ?>

</div>


