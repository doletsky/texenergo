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

<section class="sectionArticles">

<?foreach($arResult['ITEMS'] as $arElement):?>
	<article class="articleItem clearfix">
		<div class="b-articleItem">
			<div class="section-special-wrap">
				<a class="img_container fl" href="<?=$arElement['DETAIL_PAGE_URL']?>"><img class="center" src="<?=$arElement['PICTURE']?>" alt="<?=$arElement['NAME']?>"></a>
				
				<? if (!empty($arElement['SPECIAL_TIMER'])): ?>					
					<div class="cat-promo" style="">
						<div class="cat-promo-time">
							<span class="text">До конца акции</span>

							<div
								class="digits cat-day"><?= $arElement['SPECIAL_TIMER']['DAYS']; ?></div>
							<span class="two-dots">:</span>

							<div
								class="digits cat-hour"><?= $arElement['SPECIAL_TIMER']['HOURS']; ?></div>
							<span class="two-dots">:</span>

							<div
								class="digits cat-min"><?= $arElement['SPECIAL_TIMER']['MINUTES']; ?></div>
						</div>
						<ul class="cat-promo-captions">
							<li>день</li>
							<li>час</li>
							<li>мин</li>
						</ul>
					</div>
					
				<? endif; ?>
				
			</div>			
			
			<div class="b-articleText">
				<span class="date"><?//=$arElement['ACTIVE_FROM_FORMATTED']?></span>
				<h1><a class="heading" href="<?=$arElement['DETAIL_PAGE_URL']?>"><?=$arElement['NAME']?></a></h1>
				<p><?=$arElement['PREVIEW_TEXT']?></p>
			</div>
		</div>
	</article>

<?endforeach;?>

</section>

<?=$arResult['NAV_STRING']?>