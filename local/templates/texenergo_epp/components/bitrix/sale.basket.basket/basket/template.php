<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? //='<pre>'.print_r($arResult['ITEMS']['AnDelCanBuy'], true).'</pre>';?>
<div class="container">
    <div class="twelve">
        <h1 class="headline">оформление заказа</h1>
        <nav class="cartCrumbs">
            <li><a href="/basket/" class="crumbsActive">корзина</a></li>
            <? if (!$USER->isAuthorized()): ?>
                <li><a href="/order/">вход</a></li>
                <li><span>доставка</span></li>
            <? else: ?>
                <li><a href="/order/">доставка</a></li>
            <? endif; ?>
            <li><span>способ оплаты</span></li>
            <li><span class="cartCrumbs-last">подтверждение и оплата</span></li>
        </nav>
    </div>
</div>

<div class="container">
    <div class="twelve">

        <!-- main -->

        <section class="main main-account main-cart cart-hover" id="order_basket">
            <? if (isAjaxRequest() && $_REQUEST['basket_refresh'] == 'yes') $APPLICATION->RestartBuffer(); ?>
            <section class="cartItems">
                <header class="blockHeader">
                    <h1>У вас в корзине</h1>
                </header>
                <? $arItems = $arResult['BASKET']; // товары в наличии?>
                <? include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/basket_items.php"); ?>
            </section>
            <? if (isAjaxRequest() && $_REQUEST['basket_refresh'] == 'yes'):?>
				
				<script>
				var js = document.createElement("script");
				js.type = "text/javascript";
				js.src = "/local/templates/texenergo/js/modals.js";
				document.body.appendChild(js);
				</script>
				
				<?die();?>
			<?endif;?>
        </section>
    </div>
</div>

<? if (!empty($arResult['ACCESSORIES']) || $arResult['HAVE_SPECIALS']): ?>
    <div id="promoTabs" class="tabContainer promoTabs basketPromo">
        <ul>
            <? if (!empty($arResult['ACCESSORIES'])): ?>
                <li><a href="#promoTabs-1">аксессуары</a></li>
            <? endif; ?>
            <? if ($arResult['HAVE_SPECIALS']): ?>
                <li><a href="#promoTabs-2">спецпредложения</a></li>
            <? endif; ?>
        </ul>

        <? if (!empty($arResult['ACCESSORIES'])): ?>
            <div id="promoTabs-1">
                <? include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/accessories.php"); ?>
            </div>
        <? endif; ?>

        <? if ($arResult['HAVE_SPECIALS']): ?>
            <div id="promoTabs-2">
                <? include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/special.php"); ?>
            </div>
        <? endif; ?>
    </div>
<? endif; ?>

