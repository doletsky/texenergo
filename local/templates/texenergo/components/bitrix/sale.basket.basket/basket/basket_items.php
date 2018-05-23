<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<? if(!empty($arItems)):
    $hasZeroPrices = false;
    $total = 0;

    ?>
    <div class="cartHeader clearfix">
        <table>
            <tr>
                <td class="b-cartImage col-img">&nbsp;</td>
                <td class="b-cartName col-name<?= ($USER->isAuthorized() ? '-rest':'')?>">
                    <div class="col-name-inside">Товар</div>
                </td>
		<? if ($USER->isAuthorized()): ?>
                <td class="col-rest">Остаток</td>
		<?endif;?>
                <td class="col-price">Цена</td>
                <td class="col-quant">Количество</td>
                <td class="cartSubtotal">Стоимость</td>
            </tr>
        </table>
    </div>
    <? foreach($arItems as $arItem): ?>
        <div class="cartItem clearfix">
            <table>
                <tr>
                    <td class="col-img">
                        <div class="b-cartImage">
                            <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="picture"
                               style="background-image: url('<?= $arItem['PICTURE']; ?>');"></a>

                            <? if($arItem['OLD_PRICE'] > 0): ?>
                                <div class="cat-discount catDiscount-cart"></div>
                            <? endif; ?>
                        </div>
                    </td>
                    <td class="col-name<?= ($USER->isAuthorized() ? '-rest':'')?>">
                        <div class="b-cartName">
                            <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="cartName"><?= $arItem['NAME']; ?></a>
			    <?if ($arItem['REFERENCE'] != ""):?>
                            <em class="cartSerial">Референс: <?= $arItem['REFERENCE']; ?></em>
			    <? endif; ?>
			    <?if ($arItem['SKU'] != ""):?>
                            <em class="cartSerial">Артикул: <?= $arItem['SKU']; ?></em>
			    <? endif; ?>
                        </div>
                    </td>
		<? if ($USER->isAuthorized()): ?>
		   <td class="col-rest">
                        <div class="cartSubtotal">                            
		 	<? if ($bisTrustedUser) :?>        
	                	<span class="cartRest"><?=$arItem['CATALOG_QUANTITY']?></span>
			<? else: ?>
				<span class="cartRestInterval"><?=getProductRestsInterval($arItem)?></span>
        		<? endif; ?>        
                        </div>
		  </td>
		<?endif;?>
                    <td class="col-price">
                        <div class="cartPrice">
                            <span class="cartSum">
                                <?if($arItem['PRICE'] <= 0){
                                    $hasZeroPrices = true;
                                    ?>
                                    <nobr>По запросу</nobr>
                                <?}
                                else {?>
                                    <nobr><?=priceFormat($arItem['PRICE']); ?> <i class='rouble'>a</i></nobr>
                                <?}?>
                            </span>
                            <? if($arItem['OLD_PRICE'] > 0 && (int)$arItem['PRICE'] > 0): ?>
                                <span class="cartRegular"><?= priceFormat($arItem['OLD_PRICE']); ?> Р</span>
                            <? endif; ?>
                        </div>
                    </td>
                    <td class="col-quant">
                        <input type="number" class="quantity" value="<?= $arItem['QUANTITY']; ?>" min="1" max="999"
                               data-id="<?= $arItem['ID']; ?>">
                    </td>
                    <td>
                        <div class="cartSubtotal">
                            <span class="cartSum">
                                <?if($arItem['SUBTOTAL'] <= 0){?>
                                    <nobr>По запросу</nobr>
                                <?}
                                else {?>
                                    <nobr><?=priceFormat($arItem['SUBTOTAL']); ?> <i class='rouble'>a</i></nobr>
                                <?}?>
                            </span>
                        </div>
                    </td>
                </tr>
            </table>


            <a class="close-item" href="#" data-id="<?= $arItem['ID']; ?>" title="Убрать из корзины"></a>
        </div>
    <? endforeach; ?>

    <footer class="cartFooter">
        <? /*<div class="cartBonus">
            <h1>Оплатить бонусами: <em>На счету</em> 3 300 бонуса </h1>

            <div class="b-cartBonus-field">
                <input type="text" placeholder="максимум">
                <button class="bonusButton">ок</button>
            </div>
        </div>*/
        ?>
        <div class="b-cartTotals clearfix">
<!--            <div class="agreement">-->
<!--                <input type="checkbox" id="sale_agreement_accept" checked/>-->
<!--                Нажимая «Оформить заказ», я соглашаюсь с <a target="_blank" href="/eshop/sale/">Условиями продажи</a>-->
<!--            </div>-->
            <div class="cartTotals clearfix">
                <? /*<div class="totalsDiscount">
                    <span>Персональная скидка: </span><em>3%</em>
                    <span class="discountAmount">– 1 000 <i class="rouble">a</i></span>
                </div>*/
                ?>
                <div class="totalSum">
                    <span class="text">Итого сумма заказа:</span>
                    <span class="cartTotal">
                        <?if($arResult['TOTAL'] <= 0){?>
                            <nobr>По запросу</nobr>
                        <?}
                        else {?>
                            <nobr><?=priceFormat($arResult['TOTAL']); ?> <i class='rouble'>a</i></nobr>
                        <?}?>
                    </span>
                    <?if($hasZeroPrices){?>
                    <span class="zero-note">Стоимость товаров с ценой «По запросу» будет уточнена при выставлении счета.</span>
                    <?}?>
                </div>
            </div>

            <? if($arResult['SHOW_PRICE_MSG']): ?>

                <div class="b-cartNote clearfix">

                    <? ob_start(); ?>
                    <? $APPLICATION->IncludeComponent("aero:staticText", "", Array("ELEMENT"   => "basket_bot_text"),$component); ?>
                    <? $html = ob_get_clean();
                    echo str_replace(array('#SUM#',
                                           '#PERCENT#'), array($arResult['WHOLESALE_SUM'],
                                                               $arResult['WHOLESALE_DISCOUNT']
                                                               .($arResult['WHOLESALE_TYPE'] == "Perc" ? "%"
                                                                   : " руб"),), $html);

                    ?>
                </div>

            <? endif; ?>

        </div>
        <div class="cartButtons">

            <? if($arResult['ORDER_ALLOWED']): ?>
                <a href="/order/" class="button orange doOrderBtn">оформить заказ</a>
            <? else: ?>
                <span class="min-price-note">Минимальная сумма заказа <?= $arResult['MIN_ORDER_PRICE'] ?> р.</span>
            <? endif; ?>

        </div>
    </footer>



<? else: ?>
    <div class="cartItem clearfix">
        <figure class="clearfix">
            <p class="b-emptyCart">Нет товаров</p>
        </figure>
    </div>
<? endif; ?>