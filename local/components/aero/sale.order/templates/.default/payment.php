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
                <section class="cartItems cartItems-approve cartItems-payment">
                    <header class="blockHeader">
                        <h1>Способ оплаты</h1>
                    </header>
                    <section class="b-cartPayment">
                        <h1 class="payHeadline">Всего к оплате с учетом доставки:
                            <em><?= priceFormat($arResult['ORDER']['PRICE']); ?> <i class="rouble">a</i></em>
                        </h1>
                        <? $bFirst = true; ?>
                        <? foreach ($arResult['PAYMENT'] as $arPayment): ?>
                            <div class="radioRow">
                                <input type="radio" id="payCheck_<?=$arPayment['ID'];?>" name="PAYMENT_ID" value="<?= $arPayment['ID']; ?>"
                                       class="payRadio"<? if ($bFirst): ?> checked<? endif; ?>>
                                <label for="payCheck_<?=$arPayment['ID'];?>" class="radioCaption"><?= $arPayment['NAME']; ?></label>
                            </div>
                            <? $bFirst = false; ?>
                        <? endforeach; ?>

                    </section>
                    <footer class="f-cartPayment clearfix">
                        <a class="button" href="/order/">вернуться назад</a>
                        <button class="button orange rounded-order">перейти к оплате</button>
                    </footer>
                </section>

                <aside class="cartAside">
                    <? include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/basket.php"); ?>
                    <? include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/delivery_summary.php"); ?>
                </aside>

            </section>
        </div>
    </div>
</form>
