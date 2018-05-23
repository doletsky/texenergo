<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<style>
  .main-ads {
 /*background-color:#e8e8e8;*/
 overflow:hidden;
 margin-bottom:20px;
}
 .main-ads .header {
  text-align:center;
  margin:8px 4px 6px 10px;
}
 .wrapper-ads-news-main {
  margin:3px 4px;
  background-color:#fff;
  padding-top:10px;
}
 .ads-news-main .date {
  margin-top:0;
  padding:3px 8px;
  background-color: #f15c22;
  color:#fff;
  width:90px;

}
  .block-ads-news-main {
  /*background-color:#e8e8e8;*/
  overflow:hidden;
}
 .ads-news-main .news-link {
  font-size:14px;
  color:#231f20;
  margin-left:20px;
  padding:20px 0;
}
  .main-ads .all-pubs {
  width:100%;
  padding-top:16px;
  text-align:left;
  margin-bottom:5px;
}
 .main-ads .show  {
  margin-right:10px;
}
.ads-item {
  padding-left:0;
}
</style>
<h3 class="header orange ads-news prefix-10">Объявления</h3>
<div class="block-ads-news-main">
<div class="wrapper-ads-news-main">
  <div class="ads-news-main">  
    <?$count = 1;?>
    <?foreach ($arResult['ITEMS'] as $arItem): ?>
  	<div class="ads-item grid-100 slide" <?if ($count!=1) echo 'style="visibility: hidden;"';?>>
		<!--<a class="img_container" href="<?=$arItem['DETAIL_PAGE_URL']?>"><img width="100" src="<?=$arItem['PICTURE']?>" alt="<?=$arItem['NAME']?>"></a>-->
		<span class="date grid-15"><?=$arItem['DATE_ACTIVE_FROM_FORMATTED']?></span> 
		<!--<a class="news-link grid-90" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>-->
		<a class="news-link grid-95" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['PREVIEW_TEXT']?></a>
	</div>
	<?$count++;?>
     <?endforeach; ?>			
  </div>
</div>
</div>
<div class="all-pubs">
    <a href="/publication/obyavleniya/" class="show">Все объявления</a>
</div>