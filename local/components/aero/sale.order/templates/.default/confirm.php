<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/head.php"); ?>
<form method="POST" action="" id="order_form_confirm" name="order_form_confirm">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="confirm_order" value="yes">
    <input type="hidden" name="PROFILE[LOCATION_DELIVERY]"
           value="<?= $arResult['PROPS_VALUES']['LOCATION_DELIVERY']; ?>">
    <input type="hidden" name="PROFILE[ZIP_DELIVERY]"
           value="<?= $arResult['PROPS_VALUES']['ZIP_DELIVERY']; ?>">
    <input type="hidden" name="PROFILE[STREET_DELIVERY]"
           value="<?= $arResult['PROPS_VALUES']['STREET_DELIVERY']; ?>">
    <input type="hidden" name="PROFILE[HOUSE_DELIVERY]"
           value="<?= $arResult['PROPS_VALUES']['HOUSE_DELIVERY']; ?>">
    <input type="hidden" name="PROFILE[HOUSING_DELIVERY]"
           value="<?= $arResult['PROPS_VALUES']['HOUSING_DELIVERY']; ?>">
    <input type="hidden" name="PROFILE[OFFICE_DELIVERY]"
           value="<?= $arResult['PROPS_VALUES']['OFFICE_DELIVERY']; ?>">
    <input type="hidden" name="PROFILE[STAGE_DELIVERY]"
           value="<?= $arResult['PROPS_VALUES']['STAGE_DELIVERY']; ?>">
	<input type="hidden" name="PROFILE[NAME]"
           value="<?= $arResult['PROPS_VALUES']['NAME']; ?>">
	<input type="hidden" name="PROFILE[LAST_NAME]"
			   value="<?= $arResult['PROPS_VALUES']['LAST_NAME']; ?>">
    <input type="hidden" name="PROFILE[PHONE]"
           value="<?= $arResult['PROPS_VALUES']['PHONE']; ?>">

    <input type="hidden" name="PROFILE_ID" value="<?= $arResult['PROFILE']['ID']; ?>">
    <input type="hidden" name="PAYMENT_ID" value="<?= $arResult['ORDER']['PAY_SYSTEM_ID']; ?>">
    <input type="hidden" name="DELIVERY_ID" value="<?= $arResult['ORDER']['DELIVERY_ID']; ?>">

    <div class="container">
        <div class="twelve">

            <section class="main main-account main-cart clearfix">

                <section class="cartItems cartItems-approve">
                    <header class="blockHeader">
                        <h1>Ваш заказ</h1>
                        <a href="/basket/" class="cartChange">Изменить</a>
                    </header>

                    <? foreach ($arResult['BASKET'] as $arItem): ?>

                        <div class="cartItem cartItem-approve clearfix">
                            <table>
                                <tr>
                                    <td>
                                        <div class="b-cartImage">
                                            <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" target="_blank"
                                               class="picture"
                                               style="background-image: url('<?= $arItem['PICTURE']; ?>');"></a>
                                            <? if ($arItem['OLD_PRICE'] > 0 && $arItem['PRICE'] > 0): ?>
                                                <div class="cat-discount catDiscount-cart"></div>
                                            <? endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="b-cartName">
                                            <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" target="_blank"
                                               class="cartName"><?= $arItem['NAME']; ?></a>
			    		    <?if ($arItem['REFERENCE'] != ""):?>
                                            <em class="cartSerial">Референс: <?= $arItem['REFERENCE']; ?></em>
			    		    <? endif; ?>
			    		    <?if ($arItem['SKU'] != ""):?>
                                            <em class="cartSerial">Артикул: <?= $arItem['SKU']; ?></em>
			    		    <? endif; ?>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="cartSubtotal">
                                            <?if($arItem['PRICE'] <= 0){}
                                            else {?>
                                                <?= priceFormat($arItem['PRICE']); ?><i
                                                    class="rouble">a</i>&nbsp;&cross;&nbsp;
                                            <?}?>
                                            <?= $arItem['QUANTITY']; ?>
                                            <span
                                                class="cartSum">
                                                <?if($arItem['PRICE'] <= 0){?>
                                                    <nobr>по запросу</nobr>
                                                <?}
                                                else {?>
                                                    <?= priceFormat($arItem['PRICE'] * $arItem['QUANTITY']); ?>
                                                    <i class="rouble">a</i>
                                                <?}?>
                                                </span>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                        </div>
                    <? endforeach; ?>

                    <footer class="cartFooter">
                        <? /*
                    <div class="cartBonus cartBonus-approve">
                        <h1>Оплатить бонусами: </h1>
                        <em>– 1000</em>
                    </div>
                    */
                        ?>
                        <div class="b-cartTotals clearfix">
                            <div class="cartTotals clearfix">
                                <? /*
                            <div class="totalsDiscount totalsDiscount-approve">
                                <span>Персональная скидка: </span><em>3%</em>
                                <em class="discountAmount">0 <i class="rouble">a</i></em>
                            </div>
                            */
                                ?>
                                <div class="totalSum totalSum-approve">
                                    <span class="text">Итого к оплате:</span>
                                <span class="cartTotal">
                                    <?if((int)$arResult['ORDER']['PRICE'] == 0){?>
                                        <nobr>по запросу</nobr>
                                    <?}
                                    else {?>
                                        <?= priceFormat($arResult['ORDER']['PRICE']); ?> <i
                                            class="rouble">a</i>
                                    <?}?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="cartButtons">
                            <a class="button prev fl" href="/order/">вернуться назад</a>
							<button class="button orange rounded-order fr">
                                подтвердить заказ и перейти к оплате
                            </button>
                        </div>
                    </footer>
                </section>


                <aside class="cartAside">
                    <? include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/delivery_summary.php"); ?>
                </aside>


            </section>
        </div>
    </div>
</form>
