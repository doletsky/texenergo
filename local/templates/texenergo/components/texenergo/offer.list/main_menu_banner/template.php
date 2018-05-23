<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(count($arResult["ITEMS"]) > 0) {?>

        <? foreach ($arResult["ITEMS"] as $arElement) {?>

        	<?
				$pic = CFile::ResizeImageGet($arElement["PREVIEW_PICTURE"], array('width'=>211, 'height'=>155), BX_RESIZE_IMAGE_PROPORTIONAL, true);   										
        	?>

			<h1><?=$arElement["NAME"]?></h1>
			<a href="<?=$arElement["PROPERTIES"]["BANNER_LINK"]["VALUE"]?>"><img src="<?=$pic["src"]?>" alt="<?=$arElement["NAME"]?>" class="category-image"></a>
			<a href="<?=$arElement["PROPERTIES"]["BANNER_LINK"]["VALUE"]?>" class="catalog-link">перейти в каталог</a>


        <?}?>

<?php
}
?>
