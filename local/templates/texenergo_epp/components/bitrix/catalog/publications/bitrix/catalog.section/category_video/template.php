<nav class="sort accountFilter">	
	<span class="text"></span>
	<div class="b-filterPeriod fl">
		<label>за период:</label>
		<em><?= $arParams['FILTER_DATE_FROM']; ?> - <?= $arParams['FILTER_DATE_TO']; ?></em>

		<div class="filter-dates" id="filter_dates" style="display:none;">
			<form action="<?=$APPLICATION->GetCurPage();?>" method="GET">
				<input type="hidden" name="date_from" id="orders_date_from"
					   value="<?= $arParams['FILTER_DATE_FROM']; ?>">
				<input type="hidden" name="date_to" id="orders_date_to" value="<?= $arParams['FILTER_DATE_TO']; ?>">				

				<div class="calendar"></div>
				<button class="button orange">Показать</button>
			</form>
		</div>
	</div>
	
	<!--
	<div class="sort-pager sort-pager-promo">
		<ul>
			<li><a class="pager-current" href="#">1</a></li>
			<li><a href="#">2</a></li>
			<li><a href="#">3</a></li>
			<li><a href="#">4</a></li>
			<li><a href="#">5</a></li>
			<li><a href="#">6</a></li>
			<li><a href="#">7</a></li>
			<li> ... </li>
			<li><a href="#">22</a></li>
		</ul>
	</div>
	-->
</nav>

<section class="sectionVideos videosLine">	

	<?$elemsInLine = 0;?>
	
	
	
	<?$chunks = array_chunk($arResult['ITEMS'], 4);?>
	
	<?foreach($chunks as $chunk):?>
	
		<ul class="clearfix">	
		
		<?foreach($chunk as $arElement):?>
			
			<li>			
				<?if($arElement['PROPERTIES']['VIDEO']['VALUE']):?>
					<?$videoParams = array('duration' => '');?>
					<?$ajaxUrl = $templateFolder.'/video_ajax.php?f='.$arElement['PROPERTIES']['VIDEO']['VALUE'];?>
				<?else:?>						
					<?$videoParams = aero\YoutubeVideo::getVideoParams($arElement['PROPERTIES']['YOUTUBE_VIDEO']['VALUE']);?>
					<?$ajaxUrl = $templateFolder.'/video_ajax.php?yt='.urlencode($arElement['PROPERTIES']['YOUTUBE_VIDEO']['VALUE']);?>
				<?endif;?>
				
				<a class="video_url img_container_video" href="#" data-ajax_url="<?=$ajaxUrl?>">
					<img src="<?=$arElement['PICTURE']?>" alt="<?=$arElement['NAME']?>">
					<div class="play_btn"></div>
				</a>
				<a href="#" class="videoTitle video_url" data-ajax_url="<?=$ajaxUrl?>">
					<h2><?=$arElement['NAME']?></h2>
				</a>
				
				<p><?=$arElement['PREVIEW_TEXT']?></p>
			</li>		

		<?endforeach;?>
		
		</ul>
		
	<?endforeach;?>

</section>

<?=$arResult['NAV_STRING']?>