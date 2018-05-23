<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?     $bisTrustedUser = isTrustedUser(); // пользователю разрешено показ остатков ?>
<div class="accessory-wrap">
<? if (count($arResult["ITEMS"]) > 0): ?>

    <?
    $count = 0;
    ?>



    <div class="j-accessory-carousel">
        <div class="item">
            <div class="accessory-list">

                <? foreach ($arResult["ITEMS"] as $arElement) : ?>

                    <?
                    if ($count > 0 && $count % 3 == 0) {
                        echo '</div></div><div class="item"><div class="accessory-list">';
                    }

                    ?>
                    <div class="single-accessories">
                        <div class="manufacturer-wrap">
                            <? if ($arElement["PROPERTIES"]["IZGOTOVITEL"]["VALUE"]): ?>
                                <div class="manufacturer">
                                    <? if ($arElement['BRAND_LOGO']): ?>
                                        <img src="<?= $manufacturerPic["SRC"] ?>" alt="">
                                    <? else: ?>
                                        <?= $arElement['BRAND_NAME']; ?>
                                    <?endif; ?>
                                </div>
                            <? endif; ?>
                        </div>
                        <div class="clear"></div>
                        <div class="shipping-icons">
                            <? if ($arElement["PROPERTIES"]["GOODS_DELIVERY"]["VALUE"] == "Да") : ?>
                                <a class="shipping" title="Бесплатная доставка"></a>
                            <? endif; ?>
                            <!-- <a href="#" class="sale-procent"></a> -->
                        </div>
                        <div class="clear"></div>
                        <div class="img-wrap">
                            <div class="img-wrap-inner">
                                <a href="<?= $arElement['DETAIL_PAGE_URL']; ?>">
                                    <img src="<?= $arElement['PICTURE'] ?>" class="j-image-to-animate" alt="<?= $arElement['NAME']; ?>" id="product_picture_<?=$arElement['ID']?>">
                                </a>
                            </div>
                        </div>
                        <div class="j-name-wrap">
                            <p class="accessory-name"><a
                                    href="<?= $arElement['DETAIL_PAGE_URL']; ?>"><?= $arElement["NAME"] ?></a>?
                            </p>

                            <p class="accessory-article"><?= $arElement["PROPERTIES"]["GOODS_ART"]["VALUE"] ?></p>
                        </div>
                        <div
                            class="rating-wrap <? if ($arElement["PROPERTIES"]["BLOG_COMMENTS_CNT"]["VALUE"]) { ?>with-reviews<? } ?>">
                            <div
                                class="accessoty-rating cat-rating rating cat-value-raiting<? if ($arElement['PROPERTIES']['GOODS_RATE']['VALUE']) : ?>-<?= $arElement['PROPERTIES']['GOODS_RATE']['VALUE'] ?><? endif; ?>"></div>
                            <? if ($arElement["PROPERTIES"]["BLOG_COMMENTS_CNT"]["VALUE"]) : ?>
                                <span><?= $arElement["PROPERTIES"]["BLOG_COMMENTS_CNT"]["VALUE"] ?> отзыва</span>
                            <? endif; ?>
                        </div>
                        <? $price = CPrice::GetBasePrice($arElement['ID']); ?>
                        <div class="j-price-wrap">
                            <span class="price"><?= priceFormat($price["PRICE"]) ?> <i class="rouble">a</i></span>
                            <? if ($arElement["PROPERTIES"]["SHOW_OLD_PRICE"]["VALUE"] == "Да") : ?>
                                <p class="old-pirce"><?= priceFormat($arElement["PROPERTIES"]["OLD_PRICE"]["VALUE"]) ?> <i
                                        class="rouble">a</i></p>
                            <? endif; ?>
                        </div>
                        <div class="pCart">
                            <a data-picture="product_picture_<?=$arElement['ID']?>" class="pInCart basket-add" href="/basket/ajax/?id=<?=$arElement['ID']?>" title="Добавить в корзину">
								<i></i>в корзину</a>

                            <div class="pCartButtons">
                                <a href="/auth/" data-id="<?=$arElement['ID']?>" class="j-auth-add-to-favorite favorite-accessories catalog-favorite-toggle <?if(!$GLOBALS['USER']->IsAuthorized()):?>unauthorized<?endif;?>" rel="" title="Добавить в избранное"></a>
                                <a href="#" class="j-add-to-compare favorite-compare j-animate-img-full" data-id="<?= $arElement['ID'] ?>"
                                   title="Добавить в сравнение"></a>
                            </div>
                            <div class="pCartQuantity">
                                <?if($bisTrustedUser){?>
                                <span class="text">Остаток:</span>
                                    <div class=""><?=getProductRestsInterval($arElement)//=$arElement['CATALOG_QUANTITY']?></div>
                                <?}
                                else {?>
                                    <div class=""><?//=getProductRestsInterval($arElement)?></div>
                                <?}?>
                            </div>
                        </div>
                        <nav class="pCartNav">
                            <ul>
                                <li><img src="<?= SITE_TEMPLATE_PATH; ?>/img/product/mouse-icon.png"
                                         alt="заказать в один клик"><a href="#">Заказать в один клик</a></li>
                                <li><img src="<?= SITE_TEMPLATE_PATH; ?>/img/product/phone-icon.png"
                                         alt="заказать в один клик"><a href="#">Обратный звонок</a></li>
                                <li><img src="<?= SITE_TEMPLATE_PATH; ?>/img/product/car-icon.png"
                                         alt="заказать в один клик"><a href="#">Варианты доставки</a></li>
                            </ul>
                        </nav>
                        <div class="characteristics"></div>
                        <div class="pTechnicals">

                            <div class="j-pTechnicals">

                                <?

                                echo "<ul class='pTechnicalsMain'>";

                                if ($arElement["CATALOG_WEIGHT"]) {
                                    $weight = $arElement["CATALOG_WEIGHT"];
                                } else {
                                    $weight = '-';
                                }

                                if ($arElement["CATALOG_WIDTH"]) {
                                    $width = $arElement["CATALOG_WIDTH"];
                                } else {
                                    $width = '-';
                                }

                                if ($arElement["CATALOG_LENGTH"]) {
                                    $length = $arElement["CATALOG_LENGTH"];
                                } else {
                                    $length = '-';
                                }

                                if ($arElement["CATALOG_HEIGHT"]) {
                                    $height = $arElement["CATALOG_HEIGHT"];
                                } else {
                                    $height = '-';
                                }

                                echo "<li class='clearfix'>
                                            <span class='pTechnical'>Вес</span>
                                            <div class='pTechnicalValueCont'><span class='pTechnicalValue'>" . $weight . "</span></div>
                                        </li>";
                                echo "<li class='clearfix'>
                                            <span class='pTechnical'>Ширина</span>
                                            <div class='pTechnicalValueCont'><span class='pTechnicalValue'>" . $width . "</span></div>
                                        </li>";
                                echo "<li class='clearfix'>
                                            <span class='pTechnical'>Длина</span>
                                            <div class='pTechnicalValueCont'><span class='pTechnicalValue'>" . $length . "</span></div>
                                        </li>";
                                echo "<li class='clearfix'>
                                            <span class='pTechnical'>Высота</span>
                                            <div class='pTechnicalValueCont'><span class='pTechnicalValue'>" . $height . "</span></div>
                                        </li>";
                                echo "</ul>";

                                echo "<ul class='pTechnicalsMain additional-params'>";
                                foreach ($arElement["PROPERTIES"]["GOODS_FILTER_VALUE"]["VALUE_NAMES"] as $filterValue) {
                                    echo "<li class='clearfix'><span class='pTechnical";
                                    if ($filterValue["FILTER_VARS"]["UF_FILTER_DESC"]) {
                                        echo " pQuestion";
                                    }
                                    echo "'>";
                                    echo $filterValue["FILTER_VARS"]["NAME"];
                                    echo "</span><div class='pTechnicalValueCont'><span class='pTechnicalValue";
                                    if ($filterValue["PROPERTY_VALUE_DESCRIPTION_VALUE"]) {
                                        echo " pQuestion";
                                    }
                                    echo "'>";
                                    echo $filterValue["NAME"];
                                    echo "</span></div></li>";
                                }
                                echo "</ul>";


                                ?>

                            </div>


                            <ul class="pTechnicalsSecond">
                                <li><span class="pSecondHeader">Артикул</span><span
                                        class="pSecondValue"><?= $arElement["PROPERTIES"]["GOODS_ART"]["VALUE"] ?></span>
                                </li>
                                <li><span class="pSecondHeader">Референс</span><span
                                        class="pSecondValue"><?= $arElement["PROPERTIES"]["REFERENCE"]["VALUE"] ?></span>
                                </li>
                            </ul>
                        </div>
                        <div class="bar-code">
                            <?
                            if ($arElement['PROPERTIES']['GOODS_BAR_CODE']['VALUE'] != "") {
                                echo "<div class='pBarcodeCont'>";
                                $barCode = (int)$arElement['PROPERTIES']['GOODS_BAR_CODE']['VALUE'];
                                $APPLICATION->IncludeComponent(
                                    "coderoid:barcode",
                                    "",
                                    Array(
                                        "CODE" => $arElement['PROPERTIES']['GOODS_BAR_CODE']['VALUE'], // штрикод, обязательное поле. Подставить 12 или 13 значный штрих код, например так $arResult['PROPERTIES']['BARCODE']['VALUE']
                                        "CLASS" => "", // необязательное поле. Css класс.
                                        "ID" => "", // необязательное поле. Id аттрибут.
                                        "SCALE" => "1", // размер изображения, обязательное поле (значения от 1 до 7).
                                        "MODE" => "png" // формат изображения, обязательное поле(значения: 'png', 'jpg',  'jpeg', 'gif'
                                    ),
                                    false
                                );
                                echo "</div>";
                            }
                            ?>
                        </div>

                        <? $count++; ?>
                    </div>
                <? endforeach; ?>

            </div>
        </div>
    </div>
<? else: ?>
    <div class="label-not-found">
        <p>Аксессуары не найдены</p>
    </div>
<?endif; ?>
</div>
