<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="substrate"></div>
<h3 class="header orange">Тендеры компании</h3>
<div class="goods-news">  
    <?$count = 1;?>
   <div class="tenders-line">    
    <?foreach ($arResult['ITEMS'] as $arItem): ?>
<?//pr($arItem);?>
  	<div class="tender-item">
		<!--<a class="img_container" href="<?=$arItem['DETAIL_PAGE_URL']?>"><img width="160" src="<?=$arItem['PICTURE']?>" alt="<?=$arItem['NAME']?>"></a>-->
		<div class="tender-status <?=$arItem['STATUS_TENDER']?> grid-15"><?=$arItem['STATUS_TENDER_TEXT']?></div>
		<div class="tender-content grid-70">
		  <a class="news-link title-anons" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
		  <a class="news-link preview-text" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['PREVIEW_TEXT']?></a>
		<?//pr($arItem);?>
		</div>
		<div class="wrapper-date grid-15">
			<span class="">Размещено: <?=$arItem['DATE_ACTIVE_FROM_FORMATTED']?></span>
			<span class="">Обновлено: <?=$arItem['DATE_ACTIVE_UPDATE_FORMATTED']?></span>
			<!--<span class="">До: <?=$arItem['DATE_IS_SPECIAL_TO_FORMATTED']?></span>	
			<span class="">Статус: <?=$arItem['STATUS_TENDER']?></span>	
			<span class="">ДатаЗакрытия: <?=$arItem['dataSpecialTo']?></span>	
			<span class="">Сегодня: <?=$arItem['todayDate']?></span>-->
		</div>
	</div>
	<?$count++;?>
     <?endforeach; ?>			
  </div>
</div>
