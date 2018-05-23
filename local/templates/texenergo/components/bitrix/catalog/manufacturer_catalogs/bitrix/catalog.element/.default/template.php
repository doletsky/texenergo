<header class="priceHeader">

	<?if($arResult['PICTURE']):?>
		<img src="<?=$arResult['PICTURE']?>" alt="<?=$arResult['NAME']?>">
	<?endif;?>

    <section class="manufacturer-description"><?=$arResult['DETAIL_TEXT']?></section>
</header>

<?if(count($arResult['FILES']) > 0):?>

<section class="priceBody clearfix">

	 <?$i = 0;?>

	 <? foreach ($arResult['FILES'] as $arFile): ?>

		<?
		if($i % 2 != 0)
			$cl = 'no-margin';
		else
			$cl = '';
		?>

		<div class="priceList priceList-catalog <?=$cl?>">

			<?if($arFile['ICON']):?>

				<a href="<?= $arFile['SRC']; ?>" target="_blank">
					<img class="priceImage" src="<?= $arFile['ICON'];?>" alt="<?= $arFile['ORIGINAL_NAME'];?>">
				</a>

			<?endif;?>

			<div class="priceList-caption caption-production">
				<a href="<?= $arFile['SRC']; ?>" target="_blank"><h3 class="production-header"><?= $arFile['ORIGINAL_NAME'];?></h3></a>
			</div>
		</div>

		<?$i++;?>

	<? endforeach; ?>

</section>

<?endif;?>