<?
/**
 * @var array $arDelivery поля обработчика доставки
 */
?>
<form method="POST" name="order_later_form" id="order_later_form" class="form" data-messages="yes">

    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="create_order" value="yes">
    <input type="hidden" name="DELIVERY_ID" id="DELIVERY_ID" value="<?= $arDelivery['SID']; ?>:<?= $profileID; ?>">

    <section class="logisiticCompany">
        <header class="logisticHeader">
            <span class="logistic-heading">Мы осуществляем доставку только до дверей логистической компании</span>
        </header>
        <section class="logisticSelector">
            <ul class="clearfix">
                <? 
				$numCol = 1;								
				foreach ($arDelivery['PROFILES'] as $sid => $arProfile): ?>
					<?
					$class = '';
					if($numCol % 3 == 0)
						$class = "lastcol";
					
					$numCol++;
					?>
					
                    <li class="logisticBox <? if ($arProfile['SELECTED'] == 'Y'): ?> active<? endif; ?> <?=$class?>"
                        data-price="<?= IntVal($arDelivery['CONFIG']['CONFIG']['price_' . $sid]['VALUE']); ?>"
                        data-title="<?= $arProfile['TITLE']; ?>">
                        <div class="b-logisticImage">
                            <? if ($arProfile['LOGO']): ?>
                                <img src="<?=$arProfile['LOGO'];?>" alt="<?= $arProfile['TITLE']; ?>">
                            <? endif; ?>
                        </div>
                        <div class="b-logisticRadio">
                            <input type="radio" name="DELIVERY_ID"
                                   value="<?= $arDelivery['SID']; ?>:<?= $sid; ?>"<? if ($arProfile['SELECTED'] == 'Y'): ?> checked<? endif; ?>>
                            <span><?= $arProfile['TITLE']; ?></span>
                        </div>
                        <em><?= $arDelivery['CONFIG']['CONFIG']['price_' . $sid]['VALUE']; ?> <i class="rouble">a</i></em>
                    </li>
                <? endforeach; ?>
            </ul>
        </section>
    </section>

    <section class="form-block">

		<div class="contact_preson_wrap" id="other_contact_person_block">
		
        <header class="formHeader">
            <label class="delivery-variant-wrap">
				<input type="radio" name="oter-delivery-type-selector" value="person" checked />			
				<h1>Контактное лицо, получающее груз в ТК</h1>						
			</label>
        </header>

        <section class="formBody other_type_wrap">
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
                        <input class="formInput phone-mask  required" type="text" name="PROFILE[PHONE]"
                               value="<?= $arResult["USER"]["PERSONAL_PHONE"] ?>">
                    </div>
                </div>
				<div class="formParent no-margin clearfix address-row" id="dcalclocations1">
				 <?
				$GLOBALS["APPLICATION"]->IncludeComponent(
					"aero:sale.ajax.locations",
					'popup',
					array(
						"HIDE_WRAP" => true,
						"HIDE_INDEX" => true,
						"CONTAINER_ID" => "dcalclocations1",
						"AJAX_CALL" => "N",
						"CITY_INPUT_NAME" => 'PROFILE[LOCATION_DELIVERY]',
						"ZIP_INPUT_NAME" => 'PROFILE[ZIP_DELIVERY]',
						"LOCATION_VALUE" => $arResult['PROPS_VALUES']['LOCATION_DELIVERY'],
						"ZIP_VALUE" => $arResult['PROPS_VALUES']['ZIP_DELIVERY'],
						"LOCATION_GROUP_ID" => 2,
						"ORDER_PROPS_ID" => 'LOCATION_DELIVERY',
						"ONCITYCHANGE" => "",
						"SIZE1" => 100,
						"INPUT_CLASS" => "formInput required",
					),
					null,
					array('HIDE_ICONS' => 'Y')
				);
				?>
				</div>
            </div>
        </section>		 
		
		</div>

        <div class="calcInvisible">
		
		<header class="formHeader">
            <label class="delivery-variant-wrap">
				<input type="radio" name="oter-delivery-type-selector" value="door" />			
				<h1>Адрес доставки до двери клиента</h1>
			</label>			
        </header>
        <section class="formBody deliveryForm other_type_wrap" id="other_door_delivery_address">			
            
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
                        <input class="formInput phone-mask  required" type="text" name="PROFILE[PHONE]"
                               value="<?= $arResult["USER"]["PERSONAL_PHONE"] ?>">
                    </div>
                </div>				
            </div>
			
			<?
            $GLOBALS["APPLICATION"]->IncludeComponent(
                "aero:sale.ajax.locations",
                'popup',
                array(
                    "CONTAINER_ID" => "dcalclocations",
					"AJAX_CALL" => "N",
                    "CITY_INPUT_NAME" => 'PROFILE[LOCATION_DELIVERY]',
                    "ZIP_INPUT_NAME" => 'PROFILE[ZIP_DELIVERY]',
                    "LOCATION_VALUE" => $arResult['PROPS_VALUES']['LOCATION_DELIVERY'],
                    "ZIP_VALUE" => $arResult['PROPS_VALUES']['ZIP_DELIVERY'],
                    "LOCATION_GROUP_ID" => 2,
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
							<input class="formInput" type="text" name="PROFILE[OFFICE_DELIVERY]"
                               value="<?= $arResult['PROPS_VALUES']['OFFICE_DELIVERY']; ?>">
                        <? else: ?>
                            <span class="formLabel">Кв.</span>
							<input class="formInput required" type="text" name="PROFILE[OFFICE_DELIVERY]"
                               value="<?= $arResult['PROPS_VALUES']['OFFICE_DELIVERY']; ?>">
                        <?endif; ?>
                        
                    </div>
                    <div class="formChild form-10 no-margin">
                        <span class="formLabel">Этаж</span>
                        <input class="formInput" data-rules="integer" type="text" data-title="Этаж"
                               name="PROFILE[STAGE_DELIVERY]"
                               value="<?= $arResult['PROPS_VALUES']['STAGE_DELIVERY']; ?>">
                    </div>
                </div>
            </div>
			<div class="formRow clearfix calcInvisible">				
				<div class="formChild form-100 clearfix">
					<span class="formLabel">Комментарий</span>
					<textarea name="USER_DESCRIPTION" class="formInput widearea"><?=$arResult['ORDER']['USER_DESCRIPTION']?></textarea>					
				</div>				
			</div>
        </section>
		
		</div>

    </section>
    <footer class="order-buttons clearfix">
        <a href="/basket/" class="button prev">вернуться в корзину</a>
        <button class="button orange next">перейти к оплате</button>
    </footer>
</form>