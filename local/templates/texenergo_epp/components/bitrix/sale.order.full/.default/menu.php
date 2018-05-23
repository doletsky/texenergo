<nav class="cartCrumbs">
    <li><a href="/basket/" class="crumbsComplete">корзина</a></li>
    <? if (!$USER->isAuthorized()): ?>
        <li><a href="/order/" class="crumbsComplete">вход</a></li>
    <? endif; ?>
    <li><a href="/order/" onclick="orderGoStep(2); return false;" class="crumbsActive">доставка</a></li>
    <li><a href="/order/" onclick="orderGoStep(3); return false;">способ оплаты</a></li>
    <li><a href="/order/" onclick="orderGoStep(4); return false;" class="cartCrumbs-last">подтверждение и оплата</a>
    </li>
</nav>
