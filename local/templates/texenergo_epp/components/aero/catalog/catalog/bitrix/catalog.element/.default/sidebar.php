<aside class="pSidebar">
    <div class="pWarranty clearfix">
        <img src="<?= SITE_TEMPLATE_PATH; ?>/img/product/good.png" alt="Гарантия - 2 года">

        <div class="pWarrantyDiv">
            <? if ($arResult['PROPERTIES']['IS_OWN']['VALUE'] == 'Y'): ?>
                <?$APPLICATION->IncludeFile(
                    $APPLICATION->GetTemplatePath("/include/product_own.php"),
                    Array(),
                    Array("MODE" => "html")
                );?>
            <? else: ?>
                <?$APPLICATION->IncludeFile(
                    $APPLICATION->GetTemplatePath("/include/product_not_own.php"),
                    Array(),
                    Array("MODE" => "html")
                );?>
            <? endif; ?>
        </div>
    </div>


    <div class="pRateBlock clearfix">
        <div class="rating block_raiting_big">
            <div class="item<?if (($arResult["PROPERTIES"]["RAITING"]["VALUE"] >= 1)):?> active<?endif;?>"></div>
            <div class="item<?if (($arResult["PROPERTIES"]["RAITING"]["VALUE"] >= 2)):?> active<?endif;?>"></div>
            <div class="item<?if (($arResult["PROPERTIES"]["RAITING"]["VALUE"] >= 3)):?> active<?endif;?>"></div>
            <div class="item<?if (($arResult["PROPERTIES"]["RAITING"]["VALUE"] >= 4)):?> active<?endif;?>"></div>
            <div class="item<?if (($arResult["PROPERTIES"]["RAITING"]["VALUE"] == 5)):?> active<?endif;?>"></div>
        </div>
        <!-- img src="<?= SITE_TEMPLATE_PATH; ?>/img/product/rating-big.png" alt="Рейтинг продукта" -->
        <span><a href="#b-reviews">
                <?= IntVal($arResult['PROPERTIES']['COMMENTS_CNT']['VALUE']) . " " . GetMessage("REVIEW_TEXT_" . wordForm($arResult['PROPERTIES']['COMMENTS_CNT']['VALUE'])); ?>
            </a></span>
    </div>

    <? if ($arResult['PROPERTIES']['IS_PRICE_DOWN']['VALUE'] == 'Y'): ?>
        <div class="discounted-block">
            Уцененный товар
        </div>
    <? endif; ?>

    <? if (!empty($arResult['SPECIAL_TIMER'])): ?>
        <div class="pPromotionBlock">
            <span>До конца акции осталось</span>

            <div class="pTimeBlock">
                <div class="pDays"><?= $arResult['SPECIAL_TIMER']['DAYS']; ?></div>
                <div class="pHours"><?= $arResult['SPECIAL_TIMER']['HOURS']; ?></div>
                <div class="pMinutes"><?= $arResult['SPECIAL_TIMER']['MINUTES']; ?></div>
            </div>
            <ul>
                <li>Дней</li>
                <li>Часов</li>
                <li>Минут</li>
            </ul>
        </div>
    <? endif; ?>

    <? if ($arResult['CAT_PRICES']['base_price']['PRICE']): ?>
        <div class="pPriceBlock" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
            <span class="pPriceBig">
                <?if($arResult['CAT_PRICES']['base_price']['PRICE'] <= 0){?>                    
		    <span itemprop="price" content="0.00">
		    <nobr class="request-price">Цена по запросу</nobr>
		    </span>
		    <meta itemprop="priceCurrency" content="RUB">
                <?}
                else {?>
		    <span itemprop="price">
		    <?= priceFormat($arResult['CAT_PRICES']['base_price']['PRICE']); ?>
                    </span><i class="rouble">a</i>
		    <meta itemprop="priceCurrency" content="RUB">
                <?}?>
                </span>
            <? if ($arResult['OLD_PRICE'] && (int)$arResult['CAT_PRICES']['base_price']['PRICE'] > 0): ?>
                <span class="pPriceSmall"><?= priceFormat($arResult['OLD_PRICE']); ?>
                    <i class="rouble">a</i></span>
            <? endif; ?>
            <? /*<span class="pBonuses">10 бонусов</span>*/ ?>
        </div>
    <? endif; ?>
    <div class="pCart clearfix">
        <div class="rollover<?=$arResult['IN_BASKET'] ? ' active' : ''?> grid-flipper">
            <div class="flip-side rollover__front">
                <a href="/basket/ajax/?id=<?= $arResult['ID']; ?>"
                   class="cat-incart basket-add"
                   title="<?=GetMessage("ADD_TO_CART");?>"
                   data-picture="product_picture_<?= $arResult['ID']; ?>">
                    В корзину
                </a>
            </div>
            <div class="flip-side rollover__bottom">
                <form>
                    <input class="input-basket-count" data-href="/basket/ajax/?id=<?= $arResult['ID']; ?>&action=update" maxlength="7" type="text" data-product="<?= $arResult['ID']; ?>" value="1">
                    <button class="removeProduct" data-href="/basket/ajax/?id=<?= $arResult['ID']; ?>&action=deleteFast" data-product="<?= $arResult['ID']; ?>" type="reset"></button>
                </form>
            </div>
        </div><!--
        --><div class="pCartButtons clearfix">
            <? if ($USER->isAuthorized()): ?>
                <a href="#" class="catalog-favorite-toggle<? if ($arResult['IN_FAVORITES']): ?> active<? endif; ?>"
                   data-id="<?= $arResult['ID']; ?>">Избранное</a>
            <? else:
                $authUrl = "/auth/?backurl=".urlencode($APPLICATION->GetCurPageParam("favorite={$arResult['ID']}", array("favorite")));?>
                <a href="<?=$authUrl?>">Избранное</a>
            <?endif; ?>
            <a href="#" class="j-add-to-compare j-img-animate-element-page" data-id="<?= $arResult['ID']; ?>">Сравнить</a>
        </div>
            <?if($bisTrustedUser){?>
        <div class="pCartQuantity">
            <span class="text">Остаток:</span>

                <span class="pCartQnt"><?=getProductRestsInterval($arResult)//$arResult['CATALOG_QUANTITY']?></span>
        </div>
            <?}?>
    </div>

    <nav class="pCartNav">
        <ul>
            <li><img src="<?= SITE_TEMPLATE_PATH; ?>/img/product/phone-icon.png" alt="обратный звонок"><a href="/ajax/callback.php" class="popup popup_cb" data-width="300">Обратный
                    звонок</a></li>
            <li><img src="<?= SITE_TEMPLATE_PATH; ?>/img/product/car-icon.png" alt="варианты доставки"><a id="delivery_variants" href="/eshop/delivery/">Варианты
                    доставки</a></li>
        </ul>
    </nav>

    <div class="pTechnicals">
        <h3>Характеристики</h3>
        <table class="pTechnicalsMain">
            <? foreach ($arResult['DISPLAY_PROPERTIES'] as $arProp): ?>
                <? if (empty($arProp['DISPLAY_VALUE'])) continue; ?>
                <? if (!$arProp['IS_PACKING']): ?>
                <tr>
                    <th><?= $arProp['NAME']; ?></th>
                    <td><?= strip_tags($arProp['DISPLAY_VALUE']); ?></td>
                </tr>
                <?endif;?>
            <? endforeach; ?>
            <? foreach ($arResult['DISPLAY_PROPERTIES'] as $arProp): ?>
                <? if (empty($arProp['DISPLAY_VALUE'])) continue; ?>
                <? if ($arProp['IS_PACKING']):?>
                    <?if (isset($arProp['VALUE_PRINT'])):?>
                        <tr>
                            <th colspan="2" style="text-align: center"><?= $arProp['NAME']; ?></th>
                        </tr>
                        <? foreach ($arProp['VALUE_PRINT'] as $arValue): ?>
                            <tr>
                                <th><?= mb_ucfirst($arValue[0]); ?></th>
                                <td><?= $arValue[1]; ?></td>
                            </tr>
                        <? endforeach; ?>
                    <?endif;?>
                <?endif;?>
            <? endforeach; ?>
        </table>
        <ul class="pTechnicalsSecond prodTechnical">
            <? if ($arResult['PROPERTIES']['REFERENCE']['VALUE']): ?>
                <li>
                    <span class="pSecondHeader">Референс</span>
                    <span class="pSecondValue"><?= $arResult['PROPERTIES']['REFERENCE']['VALUE']; ?></span>
                </li>
            <? endif; ?>
            <li class="clear"></li>
            <? if ($arResult['CAT_PRICES']['price_ws1']['PRICE'] > 0): ?>
                <li>
                    <span class="pSecondHeader">Оптовая цена 1</span>
                    <span class="pSecondValue pSecondPrice"><?= $arResult['CAT_PRICES']['price_ws1']['PRICE']; ?> <i
                            class="rouble">a</i></span>
                </li>
            <? endif; ?>
            <? if ($arResult['CAT_PRICES']['price_ws2']['PRICE'] > 0): ?>
                <li>
                    <span class="pSecondHeader">Оптовая цена 2</span>
                    <span class="pSecondValue pSecondPrice"><?= $arResult['CAT_PRICES']['price_ws2']['PRICE']; ?> <i
                            class="rouble">a</i></span>
                </li>
            <? endif; ?>
            <? if ($arResult['CAT_PRICES']['price_ws3']['PRICE'] > 0): ?>
                <li>
                    <span class="pSecondHeader">Оптовая цена 3</span>
                    <span class="pSecondValue pSecondPrice"><?= $arResult['CAT_PRICES']['price_ws3']['PRICE']; ?> <i
                            class="rouble">a</i></span>
                </li>
            <? endif; ?>

        </ul>
        <div class="pBarcodeCont">
            <? if (!empty($arResult['PROPERTIES']['BARCODE']['VALUE'])): ?>
                <? echo "Штрих-код:<br>".$arResult['PROPERTIES']['BARCODE']['VALUE']."<br>";
		$APPLICATION->IncludeComponent(
                    "coderoid:barcode",
                    "",
                    Array(
                        "CODE" => str_pad(substr($arResult['PROPERTIES']['BARCODE']['VALUE'], 0, 13), 13, '0', STR_PAD_LEFT), // штрикод, обязательное поле. Подставить 12 или 13 значный штрих код, например так $arResult['PROPERTIES']['BARCODE']['VALUE']
                        "CLASS" => "", // необязательное поле. Css класс.
                        "ID" => "", // необязательное поле. Id аттрибут.
                        "SCALE" => "1.2", // размер изображения, обязательное поле (значения от 1 до 7).
                        "MODE" => "png" // формат изображения, обязательное поле(значения: 'png', 'jpg',  'jpeg', 'gif'
                    ),
                    false
                );?>
            <? endif; ?>
        </div>
    </div>

    <div class="pDilers">
        <h3>
		<?$APPLICATION->IncludeComponent(
			"bitrix:main.include",
			"",
			Array(
				"AREA_FILE_SHOW" => "file",
				"AREA_FILE_SUFFIX" => "inc",
				"EDIT_TEMPLATE" => "",
				"PATH" => "/include/product_sidebar_link.php"
			),
			$component
		);?>
		</h3>
    </div>

</aside>