<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<style>
</style>
<h3 class="header orange library-news">Библиотека электротехника</h3>
<div class="library-news">  
<?
$count 		= 0;
$counts 	= count($arResult['ITEMS']);
$count_column 	= 6;
$type_library   = '';
?>
  <div class="library-line">    
    <div class="library-column grid-30">
	<img src="/upload/library/library.jpg"/>
    </div>
    <div class="library-column grid-35 border-right">
    <?foreach ($arResult['ITEMS'] as $arItem): ?>	
	<? if (($arItem['~IBLOCK_SECTION_ID'] == '20928') or ($arItem['~IBLOCK_SECTION_ID'] == '20999')) $type_library = 'book'; else continue;?>
  	<div class="library-item">
		<?if(false):?><a class="img_container" href="<?=$arItem['DETAIL_PAGE_URL']?>"><img width="100" src="<?=$arItem['PICTURE']?>" alt="<?=$arItem['NAME']?>"></a><?endif;?>
		<?if(false):?><span class="date grid-25"><?=$arItem['DATE_ACTIVE_FROM_FORMATTED']?></span><?endif;?>
		<a class="news-link grid-95 <?=$type_library?>" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
		<?if(false):?><a class="news-link" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['PREVIEW_TEXT']?></a><?endif;?>
	</div>
        <? //if ($count == $count_column) echo '</div><div class="library-column grid-35">';?>
	<?$count++; 
	if ($count >= $count_column) break;?>
     <?endforeach; ?>			
    </div>
    <?$count=0;?>
    <div class="library-column grid-35 last">
    <?foreach ($arResult['ITEMS'] as $arItem): ?>	
	<? if (($arItem['~IBLOCK_SECTION_ID'] == '20928') or ($arItem['~IBLOCK_SECTION_ID'] == '20999')) continue; else $type_library = 'gost';?>
  	<div class="library-item">
		<a class="news-link grid-95 <?=$type_library?>" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
	</div>
	<?$count++; 
	if ($count >= $count_column) break;?>
    <?endforeach; ?>
    </div>
  </div>
</div>
