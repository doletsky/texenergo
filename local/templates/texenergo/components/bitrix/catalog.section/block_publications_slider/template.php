<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $exclude_section = array(
	//19030,//Акции
	//18994,//Новости Texenergo
	//18987,//Новые поступления
	//18988,//Новости партнеров
	//18991,//Библиотека электротехника
	//18985,//Тендеры
  );	
global $current_publication_id;
 ?>
<style>
.article-item {
   position:relative;
}
.article-item .img_container{
    /*margin-top:30px;*/
}
.article-item .substrate {
  position:absolute;
  top:75px;
  width:200px;
  height:110px;
  opacity:0.6;
  background-color:#333;
}
.article-item .header {
   position:absolute;
   top:80px;
   left:0;
   font-size:14px;
   color:#fff;
}
</style>
<ul id="publications-slider" class="news-items mainpage-news">

	<?foreach ($arResult['ITEMS'] as $arItem): ?>
	        <? if ($current_publication_id == $arItem['ID']) continue;?>
		<li>
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
				
					<span class="date"><?=$arItem['DATE_ACTIVE_FROM_FORMATTED']?></span>
					<span class="tag"><?=$arResult['ROOT_SECTIONS'][$arItem['IBLOCK_SECTION_ID']]['NAME']?></span>
					
						<a class="video_url" href="#" data-ajax_url="<?=$ajaxUrl?>">
							<?=$arItem['NAME']?>
						</a>
					
				
			
			<?else:?>
			   <div class="article-item">		
				
				<a class="img_container" href="<?=$arItem['DETAIL_PAGE_URL']?>"><img src="<?=$arItem['PICTURE']?>" alt="<?=$arItem['NAME']?>"></a>
				<div class="substrate"></div>
				<a class="header" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
				<!--<span>Текущая публикация:<?=$current_publication_id?></span>
				<span>ID: <?=$arItem['ID']?></span>-->

				
			  </div>			
			<?endif;?>
			
		</li>
		
		
		
		
	<? endforeach; ?>

</ul>