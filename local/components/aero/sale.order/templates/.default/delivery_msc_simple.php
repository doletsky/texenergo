<form method="POST" name="order_later_form" id="order_later_form" class="form" data-messages="yes">

    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="create_order" value="yes">
    <input type="hidden" name="DELIVERY_ID" id="DELIVERY_ID" value="<?= $arDelivery['SID']; ?>:<?= $profileID; ?>">

    <section class="form-block">

        <div class="contact_preson_wrap">
		
		<header class="formHeader">
            <h1>Контактное лицо</h1>
        </header>

        <section class="formBody">
            <div class="formRow clearfix">
                <div class="formParent clearfix">
                    <div class="formChild form-50">
                        <span class="formLabel">Имя</span>
                        <input class="formInput required" type="text" name="PROFILE[NAME]"
                               value="<?= $arResult["USER"]["NAME"] ?>">
                    </div>
                </div>
                <div class="formParent no-margin clearfix">
                    <div class="formChild form-50">
                        <span class="formLabel">Фамилия</span>
                        <input class="formInput required" type="text" name="PROFILE[LAST_NAME]"
                               value="<?= $arResult["USER"]["LAST_NAME"] ?>">
                    </div>
                </div>
            </div>


            <div class="formRow clearfix">
                <div class="formParent clearfix">
                    <div class="formChild form-50">
                        <span class="formLabel">Номер телефона</span>
                        <input class="formInput phone-mask required" type="text" name="PROFILE[PHONE]"
                               value="<?= $arResult["USER"]["PERSONAL_PHONE"] ?>">
                    </div>
                </div>
            </div>
        </section>
		
		</div>

        <header class="formHeader">
            <h1>Адрес доставки</h1>
        </header>
        <section class="formBody deliveryForm">

            <?
            $GLOBALS["APPLICATION"]->IncludeComponent(
                "aero:sale.ajax.locations",
                'popup',
                array(
                    "AJAX_CALL" => "N",
                    "CITY_INPUT_NAME" => 'PROFILE[LOCATION_DELIVERY]',
                    "ZIP_INPUT_NAME" => 'PROFILE[ZIP_DELIVERY]',
                    "LOCATION_VALUE" => $arResult['PROPS_VALUES']['LOCATION_DELIVERY'],
                    "ZIP_VALUE" => $arResult['PROPS_VALUES']['ZIP_DELIVERY'],
                    "LOCATION_GROUP_ID" => 1,
                    "ORDER_PROPS_ID" => 'LOCATION_DELIVERY',
                    "ONCITYCHANGE" => "",
                    "SIZE1" => 100,
                    "INPUT_CLASS" => "formInput required",
                ),
                null,
                array('HIDE_ICONS' => 'Y')
            );
            ?>

            <div class="formRow clearfix">
                <div class="formParent clearfix">
                    <div class="formChild form-50">
                        <span class="formLabel">Улица</span>
                        <input class="formInput required" type="text" name="PROFILE[STREET_DELIVERY]"
                               value="<?= $arResult['PROPS_VALUES']['STREET_DELIVERY']; ?>">
                    </div>
                </div>
                <div class="formParent no-margin clearfix">
                    <div class="formChild form-10">
                        <span class="formLabel">Дом</span>
                        <input class="formInput required" type="text" name="PROFILE[HOUSE_DELIVERY]"
                               value="<?= $arResult['PROPS_VALUES']['HOUSE_DELIVERY']; ?>">
                    </div>
                    <div class="formChild form-10">
                        <span class="formLabel">Корп.</span>
                        <input class="formInput" type="text" name="PROFILE[HOUSING_DELIVERY]"
                               value="<?= $arResult['PROPS_VALUES']['HOUSING_DELIVERY']; ?>">
                    </div>
                    <div class="formChild form-10">
                        <? if ($arResult['PERSON_TYPE_ID'] == SALE_PERSON_YUR): ?>
                            <span class="formLabel">Офис</span>							
                        <? else: ?>
                            <span class="formLabel">Кв.</span>							
                        <?endif; ?>                        
						<input class="formInput" type="text" name="PROFILE[OFFICE_DELIVERY]"
                               value="<?= $arResult['PROPS_VALUES']['OFFICE_DELIVERY']; ?>">
                    </div>
                    <div class="formChild form-10 no-margin">
                        <span class="formLabel">Этаж</span>
                        <input class="formInput" data-rules="integer" type="text" data-title="Этаж"
                               name="PROFILE[STAGE_DELIVERY]"
                               value="<?= $arResult['PROPS_VALUES']['STAGE_DELIVERY']; ?>">
                    </div>
                </div>
            </div>
            <div class="formRow clearfix">
                <a href="#zones" class="zones-button popup button">Показать на
                    карте</a>

                <div class="current-zone-price"></div>
            </div>
			
			<div class="formRow clearfix calcInvisible">				
				<div class="formChild form-100 clearfix">
					<span class="formLabel">Комментарий</span>
					<textarea name="USER_DESCRIPTION" class="formInput widearea"><?=$arResult['ORDER']['USER_DESCRIPTION']?></textarea>					
				</div>				
			</div>
        </section>

    </section>

    <footer class="order-buttons clearfix">
        <a href="/basket/" class="button prev">вернуться в корзину</a>
        <button class="button orange next">перейти к оплате</button>
    </footer>
</form>

