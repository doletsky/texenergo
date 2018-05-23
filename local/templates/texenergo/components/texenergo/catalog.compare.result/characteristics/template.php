<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<ul>
	<?foreach ($arResult['propertyTable'] as $property) {
		$diff = isset($property['diff']) ? $property['diff'] : 0;
		if(($diff && $arResult["DIFFERENT"]) || (! $arResult["DIFFERENT"])) {
	?>

		<li class="specCell specCell-header"><span><?=$property['NAME']?></span></li>

<?	} 

	}?>
</ul>
