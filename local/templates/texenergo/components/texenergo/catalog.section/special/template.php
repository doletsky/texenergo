<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult['ITEMS'])): ?>
    <div class="cat-products cat-products-land cat-products-list clearfix">
        <? if ($arParams['FEATURED_TITLE']): ?>
            <div class="cat-header">
                <h2><?= $arParams['FEATURED_TITLE']; ?></h2>
            </div>
        <? endif; ?>
        <div class="wrapper wrapper-land j_owl_slider_5 owl-theme owl-slider">
            <?
			foreach ($arResult['ITEMS'] as $arItem): ?>
                <div
                    class="cat-product cat-product-list <? if ($arItem['PROPERTIES']['IS_NEW']['VALUE'] == 'Y'): ?>new<? endif; ?>">
                    <div class="product-image sp-image">
                        <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="picture no-popup"
                           style="background-image: url('<?= $arItem['PICTURE']; ?>');">
                            <img src="<?= $arItem['PICTURE']; ?>" alt="<?= $arItem['NAME']; ?>">
                        </a>
                        <div class="product-info">
                            <div class="row clearfix">
                                <? if ($arParams['HIDE_TIMER'] !== 'Y' && !empty($arItem['SPECIAL_TIMER'])): ?>
                                    <div class="cat-promo">
                                        <div class="cat-promo-time">
                                            <span class="text">До конца распродажи</span>

                                            <div class="digits cat-day"><?= $arItem['SPECIAL_TIMER']['DAYS']; ?></div>
                                            <span class="two-dots">:</span>

                                            <div class="digits cat-hour"><?= $arItem['SPECIAL_TIMER']['HOURS']; ?></div>
                                            <span class="two-dots">:</span>

                                            <div
                                                class="digits cat-min"><?= $arItem['SPECIAL_TIMER']['MINUTES']; ?></div>
                                        </div>
                                        <ul class="cat-promo-captions">
                                            <li>день</li>
                                            <li>час</li>
                                            <li>мин</li>
                                        </ul>
                                    </div>
                                <? endif; ?>
                            </div>
                            <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>"
                               class="product-name no-popup"
                               title="<?= $arItem['NAME']; ?>"><?= TruncateText($arItem['NAME'], 50); ?></a>

                            <? if (!empty($arItem['PROPERTIES']['REFERENCE']['VALUE'])): ?>
                                <span class="product-number">Референс: <?= $arItem['PROPERTIES']['REFERENCE']['VALUE']; ?></span>
                            <? endif; ?>
                            <? if (!empty($arItem['PROPERTIES']['SKU']['VALUE'])): ?>
                                <span class="product-number">Артикул: <?= $arItem['PROPERTIES']['SKU']['VALUE']; ?></span>
                            <? endif; ?>
                        </div>
                        <div class="cat-rating"></div>
                        <span class="cat-price">
                            <?if($arItem['PRICE'] <= 0){?>
                                <span class="request-price">Цена по запросу</span>
                            <?}
                            else {?>
                                <span><?=priceFormat($arItem['PRICE']); ?> <i class='rouble'>a</i></span>
                            <?}?>
                        </span>
                    </div>
                    <? /*
                    <div class="cat-delivery"></div>
                    <div class="cat-discount"></div>
*/
                    ?>
                </div>
            <? endforeach; ?>
        </div>
        <? if ($arParams['SHOW_MORE'] == 'Y'): ?>
            <footer class="catFooter-cart cat-footer-landing">
                <?if(!empty($arParams['PROMO_LINK'])):?>
					<a href="<?=$arParams['PROMO_LINK']?>" class="show show-promo no-arrow">показать еще</a>
				<?else:?>
					<a href="#" class="show show-promo">показать еще</a>
				<?endif;?>
            </footer>
        <? endif; ?>
    </div>

<? endif; ?>