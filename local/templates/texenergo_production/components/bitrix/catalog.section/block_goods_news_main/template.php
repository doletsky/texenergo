<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<h3 class="header orange goods-news">Новинки оборудования</h3>
<div class="goods-news">  
    <?$count = 1;?>
    <?foreach ($arResult['ITEMS'] as $arItem): ?>
      <?if ($count%2 <> 0):?><div class="goods-news-line"><?endif;?>
	<div class="goods-news-item grid-45 <?= ($count%2==0)?'even':'od'?>">
		<span class="date"><?=$arItem['DATE_ACTIVE_FROM_FORMATTED']?></span> 
		  <div class="goods-news-img grid-40 ">
			<a class="img_container" href="<?=$arItem['DETAIL_PAGE_URL']?>"><img src="<?=$arItem['PICTURE']?>" alt="<?=$arItem['NAME']?>"></a>
		  </div>
		  <div class="goods-news-text grid-60">
			<a class="news-link" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>							
		  </div>
	</div>
	      <?if ($count%2 == 0):?></div><?endif;?>
	<?$count++;?>
     <?endforeach; ?>			
</div>
