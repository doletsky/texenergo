<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

// echo '<pre style="font-size:12px">'; var_dump($arResult); echo '</pre>';

if(count($arResult["ITEMS"]) > 0) { ?>

<?$iterator = 1;?>

<ul>
	<?foreach ($arResult["ITEMS"] as $arElement ) :?>


	    <li class="product <? if ($iterator == 3) {?>last<?}?>">
	        <a href="/catalog/?SECTION_ID=<?=$arElement["IBLOCK_SECTION_ID"]?>&ELEMENT_ID=<?=$arElement["ID"]?>"><img src="<?=$arElement["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arElement["NAME"]?>"></a>
	        <a href="/catalog/?SECTION_ID=<?=$arElement["IBLOCK_SECTION_ID"]?>&ELEMENT_ID=<?=$arElement["ID"]?>" class="name"><?=$arElement["NAME"]?></a>
	        <span class="sku"><?=$arElement["PROPERTIES"]["REFERENCE"]["VALUE"]?></span>
	        <img src="<?=SITE_TEMPLATE_PATH?>/img/footer/rating.png" alt="Рэйтинг" class="rating">
	        <span class="price"><?=$arElement['price_info']["PRICE"]?> <i class="rouble">a</i></span>
	    </li>

		<?

		$iterator++;

		if ($iterator == 4)
			$iterator = 1;
		?>

    <? endforeach; ?>
</ul> 


<?php } ?>
