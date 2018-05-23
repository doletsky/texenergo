<aside class="cartAside">
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
                        <em class="cartSerial"><?= $arProduct['SKU']; ?></em>
                    </div>
                    <div class="cartSubtotal cartSubtotal-payment">
                        <span class="cartSum"><?= priceFormat($arProduct['PRICE']); ?><i class="rouble">a</i></span>
                        <? if ($arProduct['OLD_PRICE'] > 0): ?>
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
    <div class="asideCourier">
        <span>Доставка курьером</span>
        <em>0 <i class="rouble">a</i></em>
    </div>
    <div class="asideTotal">
        <span>Итого: <em><?= priceFormat($arResult['ORDER_PRICE']); ?> <i class="rouble">a</i></em></span>
        <small>без учета доставки</small>
    </div>
    <div class="asideOne">
        <span>заказать в один клик</span>
    </div>
</aside>