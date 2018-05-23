<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $exclude_section = array(
	19030,//Акции
	18994,//Новости Texenergo
	18987,//Новые поступления
	18988,//Новости партнеров
	18991,//Библиотека электротехника
	18985,//Тендеры
  );	
 ?>
<ul id="news-slider" class="news-items mainpage-news">

	<?foreach ($arResult['ITEMS'] as $arItem): ?>
	        <? if (in_array($arItem['IBLOCK_SECTION_ID'],$exclude_section)) continue;?>
		<li>
		<?//=$arItem['IBLOCK_SECTION_ID']?>
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
				<div class="wrapper-date"><span class="date"><?=$arItem['DATE_ACTIVE_FROM_FORMATTED']?></span></div>
				<a class="img_container" href="<?=$arItem['DETAIL_PAGE_URL']?>"><img src="<?=$arItem['PICTURE']?>" alt="<?=$arItem['NAME']?>"></a>
				

					<!--<span class="tag"><?=$arResult['ROOT_SECTIONS'][$arItem['IBLOCK_SECTION_ID']]['NAME']?></span>-->
				<div class="article-content">
					<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="header"><?=$arItem['NAME']?></a>
					<a class="news-link preview-text" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=TruncateText($arItem['PREVIEW_TEXT'],150)?></a>
				</div>
				
			  </div>			
			<?endif;?>
			
		</li>
		
		
		
		
	<? endforeach; ?>

</ul>