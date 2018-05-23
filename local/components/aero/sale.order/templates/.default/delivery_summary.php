<header class="h-cartAside">
    <h1 class="cartIcon">Доставка</h1>
    <a href="#" class="cartChange go-delivery">Изменить</a>
</header>
<section class="b-cartAside">
    <div class="asideSection">
        <? if ($arResult['COMPANY']): ?>
            <h1 class="clientName"><?= $arResult['COMPANY']['COMPANY']; ?></h1>
        <? else: ?>
            <h1 class="clientName"><?=$arResult['PROPS_VALUES']['NAME']?> <?=$arResult['PROPS_VALUES']['LAST_NAME']?></h1>
        <?endif; ?>
        <? if ($arResult['PROPS_VALUES']['PHONE']): ?>
            <span class="asideText-bold asideText-phone">
                                    Тел. <?= $arResult['PROPS_VALUES']['PHONE']; ?>
                                </span>
        <? endif; ?>
        <? if ($arResult['PROPS_VALUES']['LOCATION_DELIVERY']): ?>
        <span
            class="asideText-normal">
                                <?
                                $arLocation = CSaleLocation::GetByID($arResult['PROPS_VALUES']['LOCATION_DELIVERY']);
                                ?>
            <nobr>г. <?= $arLocation['CITY_NAME']; ?>,</nobr>
            <?if(!empty($arResult['PROPS_VALUES']['STREET_DELIVERY'])):?>
				<nobr>ул. <?= $arResult['PROPS_VALUES']['STREET_DELIVERY']; ?>,</nobr>
			<?endif;?>				
			<?if(!empty($arResult['PROPS_VALUES']['HOUSE_DELIVERY'])):?>
				<nobr>дом <?= $arResult['PROPS_VALUES']['HOUSE_DELIVERY']; ?>,</nobr>
			<?endif;?>
			
            <? if ($arResult['PROPS_VALUES']['HOUSING_DELIVERY']): ?>
                <nobr>корпус <?= $arResult['PROPS_VALUES']['HOUSING_DELIVERY']; ?>,</nobr>
            <? endif; ?>
            <? if ($arResult['PROPS_VALUES']['STAGE_DELIVERY']): ?>
                <nobr>этаж <?= $arResult['PROPS_VALUES']['STAGE_DELIVERY']; ?>,</nobr>
            <? endif; ?>
            <? if ($arResult['PROPS_VALUES']['OFFICE_DELIVERY']): ?>
                <? if ($arResult['USER']['UF_PAYER_TYPE'] == SALE_PERSON_YUR): ?>
                    <nobr>офис <?= $arResult['PROPS_VALUES']['OFFICE_DELIVERY']; ?></nobr>
                <? else: ?>
                    <nobr>квартира <?= $arResult['PROPS_VALUES']['OFFICE_DELIVERY']; ?></nobr>
                <?endif; ?>
            <? endif; ?>
            <? endif; ?>
                            </span>
        <? if (empty($arResult['ORDER']['DELIVERY_ID'])): ?>
            <span class="asideText-bold">Способ доставки не выбран</span>
        <? else: ?>
            <span class="asideText-bold">Доставка:</span>
            <?
            $arDeilveryID = explode(':', $arResult['ORDER']['DELIVERY_ID']);
            $arDelivery = $arResult['DELIVERY'][$arDeilveryID[0]];
            $arDeliveryProfile = $arDelivery['PROFILES'][$arDeilveryID[1]];
            ?>
            <span class="asideText-bold">
                                        <?= $arDelivery['NAME']; ?>
                                    </span>
            <span class="asideText-normal">
                                         <?= $arDeliveryProfile['TITLE']; ?>
                <?= priceFormat($arResult['ORDER']['PRICE_DELIVERY']); ?> <i
                    class="rouble">a</i>
                                    </span>
        <?endif; ?>

        <span class="asideText-bold">Оплата:</span>
        <span class="asideText-bold">
            <?= $arResult['PAYMENT'][$arResult['ORDER']['PAY_SYSTEM_ID']]['NAME']; ?>
        </span>
    </div>
    <? /*
                        <div class="asideSection">
                            <span class="asideText-bold asideAdd">1. Название_файла.doc</span>
                            <a href="#" class="deliveryAdd">Прикрепить еще один файл</a>
                            <small class="asideSmall">Для сопроводительных документов</small>
                        </div>*/
    ?>
    <div class="asideSection">
        <span class="asideText-bold">Комментарии к заказу<br> (не более 250 символов):</span>
        <textarea class="asideComment" name="USER_DESCRIPTION" id="delcomment" maxlength="250" cols="30"
                  rows="10"><?= $arResult['ORDER']['USER_DESCRIPTION']; ?></textarea>
    </div>
</section>