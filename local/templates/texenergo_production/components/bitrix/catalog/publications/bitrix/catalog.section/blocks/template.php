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
                   <figure class="relatedPubs-figure">
						
						<?if($arItem['PROPERTIES']['VIDEO']['VALUE'] || $arItem['PROPERTIES']['YOUTUBE_VIDEO']['VALUE']):?>
							
							<?if($arItem['PROPERTIES']['VIDEO']['VALUE']):?>
								<?$ajaxUrl = $templateFolder.'/video_ajax.php?f='.$arItem['PROPERTIES']['VIDEO']['VALUE'];?>
							<?else:?>						
								<?$ajaxUrl = $templateFolder.'/video_ajax.php?yt='.urlencode($arItem['PROPERTIES']['YOUTUBE_VIDEO']['VALUE']);?>
							<?endif;?>
							
							<a class="video_url img_container" href="#" data-ajax_url="<?=$ajaxUrl?>">
								<img src="<?=$arItem['PICTURE']?>" alt="<?=$arItem['NAME']?>">
								<div class="play_btn"></div>
							</a>
							<figcaption>
								<span class="date"><?=$arItem['DATE_ACTIVE_FROM_FORMATTED']?></span>
								<span class="tag"><?=$arResult['ROOT_SECTIONS'][$arItem['IBLOCK_SECTION_ID']]['NAME']?></span>
								<p class="pubsText">
									<a class="video_url" href="#" data-ajax_url="<?=$ajaxUrl?>">
										<?=$arItem['NAME']?>
									</a>
								</p>
							</figcaption>
						
						<?else:?>
						
							<a class="img_container" href="<?=$arItem['DETAIL_PAGE_URL']?>"><img src="<?=$arItem['PICTURE']?>" alt="<?=$arItem['NAME']?>"></a>
							<figcaption>
								<span class="date"><?=$arItem['ACTIVE_FROM_FORMATTED']?></span>
								<span class="tag"><?=$arResult['NAME']?></span>
								<p class="pubsText"><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a></p>
							</figcaption>
						
						<?endif;?>					
						
						
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


