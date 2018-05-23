<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

// echo '<pre style="font-size:12px">'; var_dump($arResult); echo '</pre>';

if(count($arResult["ITEMS"]) > 0) { ?>

<?$iterator = 1;?>

<ul>
	<?foreach ($arResult["ITEMS"] as $arElement ) :?>


        <?

        	$arMass = CCatalogProduct::GetByIDEx($arElement["PROPERTIES"]["GOODS"]["VALUE"]);

        	$name = $arMass["NAME"];
        	$sectionId = $arMass["IBLOCK_SECTION_ID"];
        	$id = $arMass["ID"];
    		$previewPicture = CFile::GetFileArray($arMass["PREVIEW_PICTURE"]);
    		$reference = $arMass["PROPERTIES"]["REFERENCE"]["VALUE"];
    		$price = $arMass["PRICES"]["1"]["PRICE"];

		
		?>

	    <li class="product <? if ($iterator == 3) {?>last<?}?>">
	        <a href="/catalog/?SECTION_ID=<?=$sectionId?>&ELEMENT_ID=<?=$id?>"><img src="<?=$previewPicture["SRC"]?>" alt="<?=$name?>"></a>
	        <a href="/catalog/?SECTION_ID=<?=$sectionId?>&ELEMENT_ID=<?=$id?>" class="name"><?=$name?></a>
	        <span class="sku"><?=$reference?></span>
	        <img src="<?=SITE_TEMPLATE_PATH?>/img/footer/rating.png" alt="Рэйтинг" class="rating">
	        <span class="price"><?=$price?> <i class="rouble">a</i></span>
	    </li>

		<?

			$iterator++;

			if ($iterator == 4)
				$iterator = 1;

		?>

    <? endforeach; ?>
</ul> 


<?php } ?>
