<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? 
$news = array(1,2,3,4,5);
//shuffle($news);
$news_first = $news[0];
?>
<h3 class="header orange">Новости компании</h3>
<div id="news-first" class="news-items mainpage-news grid-35">
    <?$count = 1;?>
    <?foreach ($arResult['ITEMS'] as $arItem): ?>
	<?if ($count == $news_first) :?>
<?//pr($arItem);?>
		<span class="date"><?=$arItem['DATE_ACTIVE_FROM_FORMATTED']?></span> 
		<a class="img_container" href="<?=$arItem['DETAIL_PAGE_URL']?>"><img src="<?=$arItem['PICTURE']?>" alt="<?=$arItem['NAME']?>"></a>
		<a class="news-link" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>	
		<a class="preview-text" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['PREVIEW_TEXT']?></a>						
        <?endif; ?>	
	<?$count++;?>
     <?endforeach; ?>			
</div>
<ul class="news-list grid-65">
    <?$count = 1;?>
    <?foreach ($arResult['ITEMS'] as $arItem): ?>
        <?if ($count <> $news_first) :?>
        <li class="news-list-item">
            <span class="date grid-15"><?=$arItem['DATE_ACTIVE_FROM_FORMATTED']?></span> 
            <a class="news-link grid-95" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>							
	</li>
        <?endif; ?>
	<?$count++;?>
     <?endforeach; ?>			
</ul>