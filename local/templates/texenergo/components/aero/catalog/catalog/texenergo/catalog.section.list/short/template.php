<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="twelve landing-sections">
    <? foreach ($arResult['SECTIONS'] as $arSection): ?>
        <div class="bar">
            <h1>
                <a href="<?= $arSection['SECTION_PAGE_URL']; ?>">
                    <?= $arSection['NAME']; ?>
                </a>
            </h1>
            <ul class="ul-bar-parent">
                <? foreach ($arSection['SECTIONS'] as $arSubsection): ?>
                    <li class="li-bar-parent<? if (!empty($arSubsection['SECTIONS'])): ?> has-child<? endif; ?>">

						<? if (!empty($arSubsection['SECTIONS'])): ?>
                            <span class="list-toggle closed"></span>
                        <? endif; ?>

						<?
						$showBanner = false;
						$product = false;
						if(array_key_exists($arSubsection['ID'], $arResult['BANNER_ELEMNTS']))
							$product = $arResult['BANNER_ELEMNTS'][$arSubsection['ID']];

						if($product && empty($arSubsection['SECTIONS']))
							$showBanner = true;
						?>

						<?if($showBanner):?><div class="wrap-bar-child"><?endif;?>

						<a href="<?= $arSubsection['SECTION_PAGE_URL']; ?>"><?= $arSubsection['NAME']; ?>
                            <em>(<?= $arSubsection['ELEMENT_CNT']; ?>)</em></a>


						<?if($showBanner):?>

							<div class="block-bar-promo group promo-white">
								<a href="<?=$product['DETAIL_PAGE_URL']?>"><img alt="" src="<?=$product['PICTURE']?>"></a>
								<div class="caption wide">
									<a class="name-bar" href="<?=$product['DETAIL_PAGE_URL']?>"><?=$product['NAME']?></a>
									<span class="sku-bar"><?=$product['PROPERTY_SKU_VALUE']?></span>
									<span class="price-bar">
                                        <?if($product['CATALOG_PRICE_1'] <= 0){?>
                                            <span class="request-price nowrap">Цена по запросу</span>
                                        <?}
                                        else {?>
                                            <?=$product['CATALOG_PRICE_1']?> <i class="rouble">a</i>
                                        <?}?>
                                    </span>
								</div>
							</div>

							</div>

						<?endif;?>

						<? if (!empty($arSubsection['SECTIONS'])): ?>
                            <ul class="ul-bar-child">
                                <? foreach ($arSubsection['SECTIONS'] as $arSubsection2): ?>

									<?if(array_key_exists($arSubsection2['ID'], $arResult['BANNER_ELEMNTS'])){
										$product = $arResult['BANNER_ELEMNTS'][$arSubsection2['ID']];
									}else{
										$product = false;
									}?>



									<li class="li-bar-child">

										<?if($product):?>

											<div class="wrap-bar-child">

										<?endif;?>

										<a href="<?= $arSubsection2['SECTION_PAGE_URL']; ?>"><?= $arSubsection2['NAME']; ?>
                                            <em>(<?= $arSubsection2['ELEMENT_CNT']; ?>)</em></a>

										<?if($product):?>

											<div class="block-bar-promo group">
												<a href="<?=$product['DETAIL_PAGE_URL']?>"><img alt="" src="<?=$product['PICTURE']?>"></a>
												<div class="caption wide">
													<a class="name-bar" href="<?=$product['DETAIL_PAGE_URL']?>"><?=$product['NAME']?></a>
													<span class="sku-bar"><?=$product['PROPERTY_SKU_VALUE']?></span>
													<span class="price-bar">
                                                    <?if($product['CATALOG_PRICE_1'] <= 0){?>
                                                        <span class="request-price nowrap">Цена по запросу</span>
                                                    <?}
                                                    else {?>
                                                        <?=$product['CATALOG_PRICE_1']?> <i class="rouble">a</i>
                                                    <?}?>
                                                    </span>
												</div>
											</div>

											</div>

										<?endif;?>
                                    </li>

								<? endforeach; ?>

                            </ul>
                        <? endif; ?>
                    </li>
                <? endforeach; ?>
            </ul>
        </div>
    <? endforeach; ?>
</div>