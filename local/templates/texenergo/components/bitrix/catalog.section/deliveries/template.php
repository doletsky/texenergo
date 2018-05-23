<?foreach($arResult['ITEMS'] as $arItem):?>

<section class="orderItem">
	<header>
		<h1>
			<span class="orderName">Доставка №<?=$arItem['ID']?></span>
			<span class="orderDate"> от <?=$arItem['DATE_CREATE']?></span>
		</h1>

	</header>
 </section>

<?endforeach;?>
