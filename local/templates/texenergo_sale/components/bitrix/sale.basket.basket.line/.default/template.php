<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<a href="/basket/">
    <span class="lnr lnr-cart header-middle-menu__basket-ico"></span> 
<?if ($arResult["NUM_PRODUCTS"]) :?>
    Товаров: <i class="icon-cart"></i><span id="cart-quantity" class="cart-quantity"><?=IntVal($arResult["NUM_PRODUCTS"]);?></span>
    <div class="price orange"><?=priceFormat($arResult["TOTAL_PRICE"]);?><i class="rouble">a</i></div>
<?else:?>
    <span>Корзина пуста</span>
<?endif; ?>
</a>
