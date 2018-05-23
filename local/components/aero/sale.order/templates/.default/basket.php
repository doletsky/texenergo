
    <header class="h-cartAside">
        <h1 class="cartIcon">Ваш заказ</h1>
        <a href="/basket/" class="cartChange">Изменить</a>
    </header>
    <section class="b-cartAside">
        <? if (!empty($arResult['BASKET'])): ?>
            <? foreach ($arResult['BASKET'] as $arProduct): ?>
                <div class="asideItem clearfix">
                    <div class="asideCaption">
                        <a href="<?= $arProduct['DETAIL_PAGE_URL']; ?>" class="cartName"><?= $arProduct['NAME']; ?></a>
			<?if ($arProduct['REFERENCE'] != ""):?>
                        <em class="cartSerial">Референс: <?= $arProduct['REFERENCE']; ?></em>
			<? endif; ?>
			<?if ($arProduct['SKU'] != ""):?>
                        <em class="cartSerial">Артикул: <?= $arProduct['SKU']; ?></em>
			<? endif; ?>
                    </div>
                    <div class="cartSubtotal cartSubtotal-payment">
                        <span>
                            <?if($arProduct['PRICE'] <= 0){}
                            else {?>
                                <?= priceFormat($arProduct['PRICE']); ?><i
                                    class="rouble">a</i>&nbsp;x&nbsp;
                            <?}?>
                            <?=intval($arProduct['QUANTITY']);?>шт.</span>
                        <span class="cartSum">
                            <?if(($arProduct['PRICE']) <= 0){?>
                                <nobr>по запросу</nobr>
                            <?}
                            else {?>
                                <?= priceFormat($arProduct['PRICE'] * $arProduct['QUANTITY']); ?><i
                                    class="rouble">a</i>
                            <?}?>
                            </span>
                        <? if ($arProduct['OLD_PRICE'] > 0 && $arProduct['PRICE'] > 0): ?>
                            <span class="cartRegular"><?= priceFormat($arProduct['OLD_PRICE']); ?> Р</span>
                        <? endif; ?>
                    </div>
                </div>
            <? endforeach; ?>
        <? endif; ?>
    </section>
    <? /*<div class="asideBonus">
        <span>Оплата бонусами</span>
        <em>– 1 000 <i class="rouble">a</i></em>
    </div>*/
    ?>
    <div class="asideCourier" style="display: none;">
        <div>Доставка:</div>
        <span id="deliveryTitle">Самовывоз</span>
        <em><span id="deliveryPrice">0</span> <i class="rouble">a</i></em>
    </div>
    <div class="asideTotal asideFixed">
        <span>Итого: <em><?if($arResult['ORDER_PRICE'] <= 0){?>
                    <nobr>по запросу</nobr>
                <?}
                else {?>
                    <?= priceFormat($arResult['ORDER_PRICE']); ?> <i class="rouble">a</i>
                <?}?></em></span>
        <small>без учета доставки</small>
    </div>
    <?/*<div class="asideOne">
        <span>заказать в один клик</span>
    </div>*/?>
