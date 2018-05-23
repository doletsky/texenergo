
<div class="newsContent">
	<figure class="newsFigure">
		<img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt="<?=$arResult['NAME']?>">
		<figcaption class="newsFigure-caption"><span class="date date-news"><?=$arResult['ACTIVE_FROM_FORMATTED']?></span></figcaption>
	</figure>
	
	<? if (!empty($arResult['SPECIAL_TIMER'])): ?>
		<div class="pPromotionBlock pubPromotion">
			<span>До конца акции осталось</span>

			<div class="pTimeBlock">
				<div class="pDays"><?= $arResult['SPECIAL_TIMER']['DAYS']; ?></div>
				<div class="pHours"><?= $arResult['SPECIAL_TIMER']['HOURS']; ?></div>
				<div class="pMinutes"><?= $arResult['SPECIAL_TIMER']['MINUTES']; ?></div>
			</div>
			<ul>
				<li>Дней</li>
				<li>Часов</li>
				<li>Минут</li>
			</ul>
		</div>
	<? endif; ?>
	
	<div class="newsPr"><?=$arResult['DETAIL_TEXT']?></div>
	
</div>