<section class="form-block">
    <header class="formHeader">
        <h1>Адрес доставки</h1>
        <? if ($arResult['PAYER_TYPE'] == SALE_PERSON_YUR): ?>
            <input type="checkbox">
            <span class="checkboxLabel">Совпадает с юридическим</span>
        <? endif; ?>
    </header>
    <section class="formBody">

        <?
        $GLOBALS["APPLICATION"]->IncludeComponent(
            "aero:sale.ajax.locations",
            'popup',
            array(
                "AJAX_CALL" => "N",
                "CITY_INPUT_NAME" => 'PROFILE[LOCATION_DELIVERY]',
                "ZIP_INPUT_NAME" => 'PROFILE[ZIP_DELIVERY]',
                "LOCATION_VALUE" => $arResult['PROFILE']['LOCATION_DELIVERY'],
                "ZIP_VALUE" => $arResult['PROFILE']['ZIP_DELIVERY'],
                "ORDER_PROPS_ID" => 'LOCATION_DELIVERY',
                "ONCITYCHANGE" => "",
                "SIZE1" => 100,
                "INPUT_CLASS" => "formInput",
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
                           value="<?= $arResult['PROFILE']['STREET_DELIVERY']; ?>">
                </div>
            </div>
            <div class="formParent no-margin clearfix">
                <div class="formChild form-10">
                    <span class="formLabel">Дом</span>
                    <input class="formInput required" type="text" name="PROFILE[HOUSE_DELIVERY]"
                           value="<?= $arResult['PROFILE']['HOUSE_DELIVERY']; ?>">
                </div>
                <div class="formChild form-10">
                    <span class="formLabel">Корп.</span>
                    <input class="formInput" type="text" name="PROFILE[HOUSING_DELIVERY]"
                           value="<?= $arResult['PROFILE']['HOUSING_DELIVERY']; ?>">
                </div>
                <div class="formChild form-10">
                    <span class="formLabel">Кв.</span>
                    <input class="formInput required" type="text" name="PROFILE[OFFICE_DELIVERY]"
                           value="<?= $arResult['PROFILE']['OFFICE_DELIVERY']; ?>">
                </div>
                <div class="formChild form-10 no-margin">
                    <span class="formLabel">Этаж</span>
                    <input class="formInput" data-rules="integer" type="text" data-title="Этаж"
                           name="PROFILE[STAGE_DELIVERY]"
                           value="<?= $arResult['PROFILE']['STAGE_DELIVERY']; ?>">
                </div>
            </div>
        </div>
        <div class="formRow clearfix">
            <div class="formParent formParent-full clearfix">
                <div class="formChild form-100">
                    <span class="formLabel">Дополнительная информация</span>
                    <textarea class="formInput formInput-field" name="PROFILE[COMMENT_DELIVERY]" cols="30"
                              rows="10"><?= $arResult['PROFILE']['COMMENT_DELIVERY']; ?></textarea>
                </div>
            </div>
        </div>
    </section>
    <? /*
    <section class="logisiticCompany">
        <header class="logisticHeader">
            <span>Мы осуществляем доставку только до дверей логистической компании</span>
        </header>
        <section class="logisticSelector">
            <ul class="clearfix">
                <li class="logisticBox logisticBox-pochta">
                    <div class="b-logisticImage">
                        <img src="<?= SITE_TEMPLATE_PATH; ?>/img/profile/pochta.png" alt="Почта России">
                    </div>
                    <div class="b-logisticRadio">
                        <input type="radio" name="logistic-company">
                        <span>Почта России</span>
                    </div>
                    <em>200 Р</em>
                </li>
                <li class="logisticBox logisticBox-ems logisticBox-selected">
                    <div class="b-logisticImage">
                        <img src="<?= SITE_TEMPLATE_PATH; ?>/img/profile/ems.png" alt="Почта России">
                    </div>
                    <div class="b-logisticRadio">
                        <input type="radio" name="logistic-company">
                        <span>EMS Почта России</span>
                    </div>
                    <em>500 Р</em>
                </li>
                <li class="logisticBox logisticBox-dhl">
                    <div class="b-logisticImage">
                        <img src="<?= SITE_TEMPLATE_PATH; ?>/img/profile/dhl.png" alt="Почта России">
                    </div>
                    <div class="b-logisticRadio">
                        <input type="radio" name="logistic-company">
                        <span>DHL</span>
                    </div>
                    <em>500 Р</em>
                </li>
            </ul>
        </section>
    </section>
    */
    ?>
    <? /*
    <section class="formBody">
        <div class="formRow clearfix">
            <div class="formParent formParent-full clearfix">
                <div class="formChild form-100">
                    <button class="rounded rounded-shipping rounded-contract rounded-form">Добавить еще один адрес
                    </button>
                </div>
            </div>
        </div>
    </section>
*/
    ?>
</section>
