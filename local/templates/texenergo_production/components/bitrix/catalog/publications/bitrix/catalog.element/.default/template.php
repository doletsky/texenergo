		<div class="share-block publication-element" id="share_block">
			<a class="share-social share-fb"
				onclick="Share.facebook(window.location.href, '<?=$arResult['NAME']?>', '<?=$arResult['OG_PHOTO']?>', '<?=$arResult['OG_DESCRIPTION']?>')">
			</a>
			<a class="share-social share-vk"
				onclick="Share.vkontakte(window.location.href, '<?=$arResult['NAME']?>', '<?=$arResult['OG_PHOTO']?>', '<?=$arResult['OG_DESCRIPTION']?>')">
			</a>
			<a class="share-social share-tw"
				onclick="Share.twitter(window.location.href, '<?=$arResult['NAME']?>')">
			</a>
			<a class="share-social share-od"
				onclick="Share.odnoklassniki(window.location.href, '')">
			</a>
			<div class="publication-view">Прочитали <?=$arResult["SHOW_COUNTER"]?> <?=$arResult["SHOW_COUNTER_END"]?></div>
		</div>

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