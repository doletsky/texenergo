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
			<a class="img_container fl" href="<?=$arElement['DETAIL_PAGE_URL']?>"><img src="<?=$arElement['PICTURE']?>" alt="<?=$arElement['NAME']?>"></a>
			<div class="b-articleText">
				<span class="date"><?=$arElement['ACTIVE_FROM_FORMATTED']?></span>
				<h1><a class="heading" href="<?=$arElement['DETAIL_PAGE_URL']?>"><?=$arElement['NAME']?></a></h1>
				<p><?=$arElement['PREVIEW_TEXT']?></p>
			</div>
		</div>
	</article>

<?endforeach;?>

</section>

<?=$arResult['NAV_STRING']?>