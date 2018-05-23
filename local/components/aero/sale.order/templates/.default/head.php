<? if (!$USER->isAuthorized()): ?>
    <div class="twelve order-head-wrap calcInvisible">
        <h1 class="headline">оформление заказа</h1>


        <nav class="cartCrumbs">
            <li><a href="/basket/" class="crumbsComplete">корзина</a></li>
            <li><a href="/order/" class="crumbsActive crumbsDisabled">вход</a></li>
            <li><a href="/order/" class="crumbsDisabled">доставка</a></li>
            <? if (count($arResult['PAYMENT']) > 1): ?>
                <li><a href="/order/" class="crumbsDisabled">способ оплаты</a></li>
            <? endif; ?>

            <?if(count($arResult['PAYMENT']) <= 1):?>
			<li><a href="/order/" class="cartCrumbs-last crumbsDisabled">подтверждение и оплата</a>
			<?endif;?>
			
            </li>
        </nav>
    </div>

<? else: ?>

    <div class="twelve order-head-wrap calcInvisible">
        <h1 class="headline">
		<?if($arParams['ONLY_CALCULATOR'] == 'Y'):?>
			расчет стоимости доставки
		<?else:?>
			оформление заказа
		<?endif;?>
		</h1>


        <nav class="cartCrumbs">
            <li><a href="/basket/" class="crumbsComplete">корзина</a></li>
           
            <? if ($arResult['STEP'] == 'delivery'): ?>
                <li><a href="/order/" class="crumbsActive crumbsDisabled crubmsDelivery">доставка</a></li>
                <? if (count($arResult['PAYMENT']) > 1): ?>
                    <li><a href="/order/" class="crumbsDisabled">способ оплаты</a></li>
                <? endif; ?>
                <?if(count($arResult['PAYMENT']) <= 1):?>
				<li><a href="/order/" class="cartCrumbs-last crumbsDisabled">подтверждение и оплата</a></li>
				<?endif;?>
            <? endif; ?>
            
			<? if ($arResult['STEP'] == 'payment'): ?>
                <li><a href="/order/" class="crumbsComplete crubmsDelivery">доставка</a></li>
                <? if (count($arResult['PAYMENT']) > 1): ?>
                    <li><a href="/order/" class="crumbsActive crumbsDisabled">способ оплаты</a></li>
                <? endif; ?>
                <?if(count($arResult['PAYMENT']) <= 1):?>
				<li><a href="/order/" class="cartCrumbs-last crumbsDisabled">подтверждение и оплата</a></li>
				<?endif;?>
            <? endif; ?>
            
			<? if ($arResult['STEP'] == 'confirm'): ?>
                <li><a href="/order/" class="crumbsComplete crubmsDelivery">доставка</a></li>
                <? if (count($arResult['PAYMENT']) > 1): ?>
                    <li><a href="/order/" class="crumbsComplete">способ оплаты</a></li>
                <? endif; ?>
				<?if(count($arResult['PAYMENT']) <= 1):?>
                <li><a href="/order/" class="cartCrumbs-last crumbsActive crumbsDisabled">подтверждение и оплата</a></li>
				<?endif;?>
            <? endif; ?>
        </nav>
    </div>

<? endif; ?>
