<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult['PROFILES'])): ?>
    <div class="optionsWrapper">
        <header class="blockHeader">
            <h1>Предыдущие варианты доставки и оплаты</h1>
        </header>

        <section class="shippingOptions clearfix">
            <? foreach ($arResult['PROFILES'] as $k => $arProfile): ?>

                <div
                    class="shippingWrapper orderProfile <? if (($k + 1) % 2 == 0): ?> no-margin<? endif; ?> <? if ($arProfile['SELECTED'] == 'Y'): ?> selected<? endif; ?>"
                    data-id="<?= $arProfile['ID']; ?>">
                    <div class="shippingOption">
                        <h3><?= $arProfile['NAME']; ?></h3>
                        <small><?= $arProfile['PERSON']['NAME']; ?></small>

                        <? if ($arProfile['PROPS']['PHONE']['VALUE']): ?>
                            <span>Тел. <?= $arProfile['PROPS']['PHONE']['VALUE']; ?></span>
                        <? elseif ($arResult['USER']['PERSONAL_PHONE']): ?>
                            <span>Тел. <?= $arResult['USER']['PERSONAL_PHONE']; ?></span>
                        <? endif; ?>
                        <? if ($arProfile['PROPS']['LOCATION_DELIVERY']['VALUE']): ?>
                            <p>
                            <? if ($arProfile['PROPS']['ZIP_DELIVERY']['VALUE']): ?>
                                <?= $arProfile['PROPS']['ZIP_DELIVERY']['VALUE']; ?>
                            <? endif; ?>
                            <?= $arProfile['PROPS']['LOCATION_DELIVERY']['LOCATION']['CITY_NAME']; ?>,
                            <? if ($arProfile['PROPS']['STREET_DELIVERY']['VALUE']): ?>
                                <nobr>ул. <?= $arProfile['PROPS']['STREET_DELIVERY']['VALUE']; ?>,</nobr>
                            <? endif; ?>
                            <? if ($arProfile['PROPS']['HOUSE_DELIVERY']['VALUE']): ?>
                                <nobr>дом <?= $arProfile['PROPS']['HOUSE_DELIVERY']['VALUE']; ?>,</nobr>
                            <? endif; ?>
                            <? if ($arProfile['PROPS']['HOUSING_DELIVERY']['VALUE']): ?>
                                <nobr>корпус <?= $arProfile['PROPS']['HOUSING_DELIVERY']['VALUE']; ?>,</nobr>
                            <? endif; ?>
                            <? if ($arProfile['PROPS']['OFFICE_DELIVERY']['VALUE']): ?>
                                <? if ($arProfile['PERSON_TYPE_ID'] == SALE_PERSON_FIZ): ?>
                                    <nobr>квартира <?= $arProfile['PROPS']['OFFICE_DELIVERY']['VALUE']; ?></nobr>
                                <? else: ?>
                                    <nobr>офис <?= $arProfile['PROPS']['OFFICE_DELIVERY']['VALUE']; ?></nobr>
                                <?endif; ?>
                            <? endif; ?>
                            </p>
                        <? endif; ?>
                        <p><?= $arProfile['DELIVERY']; ?></p>

                        <p><?= $arProfile['PAYMENT']; ?></p>

                        <? /*<span>Оплата банковской картой</span>*/ ?>
                    </div>

                    <div class="buttonWrapper">
                        <a href="#"
                           class="button selectProfile <? if ($arProfile['SELECTED'] == 'Y'): ?> orange<? endif; ?>">
                            выбрать этот вариант
                        </a>
                        <a href="#" class="shippingClose" title="Удалить" data-id="<?= $arProfile['ID']; ?>">
                            <img src="<?= SITE_TEMPLATE_PATH; ?>/img/cart/button-close.png" alt="удалить">
                        </a>
                    </div>
                </div>

            <? endforeach; ?>
        </section>
    </div>
    <div id="profilesForm" <? if ($arResult['PROFILE']['ID'] <= 0): ?> style="display: none;"<? endif; ?>>
        <form method="POST" name="order_profile_form" id="order_profile_form">
            <?= bitrix_sessid_post() ?>
            <input type="hidden" name="create_order" value="yes">
            <input type="hidden" name="PROFILE_ID" id="profile_id" value="<?= $arResult['PROFILE_ID']; ?>">

            <footer class="order-buttons clearfix">
                <a href="/basket/" class="button prev">вернуться в корзину</a>

                <button class="button orange next">перейти к оплате</button>
            </footer>
        </form>
    </div>

<? endif; ?>

