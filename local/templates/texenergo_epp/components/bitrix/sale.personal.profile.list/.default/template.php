<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();


?><h1 class="headline inside-headline">Ранее использованные вами варианты доставки и оплаты</h1>
<div class="optionsWrapper profileList">
    <section class="shippingOptions clearfix padded-bot">

<?if(strlen($arResult["ERROR_MESSAGE"])>0)
	ShowError($arResult["ERROR_MESSAGE"]);?>

<? if (!empty($arResult['PROFILES'])): ?>

            <? foreach ($arResult['PROFILES'] as $k => $arProfile): ?>

                <div
                    class="shippingWrapper orderProfile <? if (($k + 1) % 3 == 0): ?> no-margin<? endif; ?>"
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
                            <? if ($arProfile['PROPS']['STAGE_DELIVERY']['VALUE']): ?>
                                <nobr>этаж <?= $arProfile['PROPS']['STAGE_DELIVERY']['VALUE']; ?>,</nobr>
                            <? endif; ?>
                            <? if ($arProfile['PROPS']['OFFICE_DELIVERY']['VALUE']): ?>
                                <? if ($arProfile['PERSON_TYPE_ID'] == SALE_PERSON_FIZ): ?>
                                    <nobr>квартира <?= $arProfile['PROPS']['OFFICE_DELIVERY']['VALUE']; ?></nobr>
                                <? else: ?>
                                    <nobr>офис <?= $arProfile['PROPS']['OFFICE_DELIVERY']['VALUE']; ?></nobr>
                                <?endif; ?>
                            <? endif; ?>
                            </p>
                            <p>
                                <? if ($arProfile['PROPS']['COMMENT_DELIVERY']['VALUE']): ?>
                                    <?= $arProfile['PROPS']['COMMENT_DELIVERY']['VALUE']; ?>
                                <? endif; ?>
                        </p>
                        <? endif; ?>
                        <p><?= $arProfile['DELIVERY']; ?></p>

                        <p><?= $arProfile['PAYMENT']; ?></p>
                        <a class="close-item" href="#" data-id="64112" title="Убрать из корзины">dd</a>

						<a data-id="<?= $arProfile['ID']; ?>" title="Удалить" class="profileDel" href="javascript:if(confirm('Удалить?')) window.location='<?=$arProfile["URL_TO_DETELE"]?>'">
                            <img alt="удалить" src="/local/templates/texenergo/img/cart/button-close.png">
                        </a>

                    </div>

                </div>

            <? endforeach; ?>



<? endif; ?>
        </section>

</div>
<?if(strlen($arResult["NAV_STRING"]) > 0):?>
	<p><?=$arResult["NAV_STRING"]?></p>
<?endif?>