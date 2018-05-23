<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<section class="cartItems cartItems-approve cartItems-payment">
    <div class="optionsWrapper">
        <header class="blockHeader">
            <h1>Предыдущие варианты доставки и оплаты</h1>
        </header>
        <section class="shippingOptions clearfix">
            <div class="shippingWrapper">
                <div class="shippingOption">
                    <h3>Антон</h3>
                    <span>Тел. 8 (926) 322-95-52</span>
                    <p>Москва, Кавказский бульвар, дом 51, корпус 1, квартира 133</p>
                    <span>Оплата банковской картой</span>
                </div>

                <div class="buttonWrapper">
                    <button class="rounded rounded-cart rounded-order rounded-ship">выбрать этот вариант</button>
                    <a href="#" class="shippingClose"><img src="<?=SITE_TEMPLATE_PATH;?>/img/cart/button-close.png" alt="удалить"></a>
                </div>
            </div>
            <div class="shippingWrapper no-margin">
                <div class="shippingOption">
                    <h3>Сергей Иванов</h3>
                    <small>Юридическое лицо</small>
                    <span class="shippingOrg">АО "Газпром"</span>
                    <span>Тел.   8 (926) 322-95-52</span>
                    <span>Факс 8 (926) 322-95-52</span>
                    <p>Москва, Кавказский бульвар, дом 51, корпус 1, квартира 133</p>
                    <span>Оплата банковской картой</span>
                </div>
                <div class="buttonWrapper">
                    <button class="rounded rounded-cart rounded-order rounded-ship">выбрать этот вариант</button>
                    <a href="#" class="shippingClose"><img src="<?=SITE_TEMPLATE_PATH;?>/img/cart/button-close.png" alt="удалить"></a>
                </div>
            </div>
        </section>
    </div>
    <div class="optionsWrapper">
        <header class="blockHeader">
            <h1>Выбор нового способа доставки</h1>
        </header>
        <section class="shippingSelectors clearfix">
            <div class="shippingSelector shippingSelector-oneline">
                <h3>москва и область</h3>
            </div>
            <div class="shippingSelector shippingSelector-oneline">
                <h3>другой город</h3>
            </div>
            <div class="shippingSelector shippingSelector-twolines shippingSelected no-margin">
                <h3>выбрать способ доставки позже</h3>
            </div>
        </section>
    </div>
    <footer class="f-cartPayment f-shippingOptions clearfix">
        <button class="rounded rounded-cart rounded-click">вернуться в корзину</button>
        <button class="rounded rounded-cart rounded-order">перейти к оплате</button>
    </footer>
</section>