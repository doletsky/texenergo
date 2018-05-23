<? if ($arResult['PROFILE'] && $arResult['PROFILE']['ACTIVE'] == 'N'): ?>
    <section class="form-block">
        <header class="formHeader">
            <h1>Реквизиты компании</h1>
        </header>
        <section class="formBody">
            <div class="formRow clearfix notetext">
                Отправлен запрос на подключение к аккаунту <?= $arResult['PROFILE']['COMPANY']; ?>
                <br>
                После подтверждения администратором аккаунт будет доступен. <br>
                Если вы допустили ошибку в ИНН, отмените
                запрос.
            </div>
            <div class="formRow clearfix">
                <a href="?cancel_company_request=yes" class="button">Отменить запрос</a>
            </div>
        </section>
    </section>
<? else: ?>
    <section class="form-block">
        <section class="formBody">
            <? if ($arResult['CHANGES'] == 'Y'): ?>
                <div class="formRow clearfix notetext">
                    Изменения вступят в силу после проверки модератором.
                </div>
            <? endif; ?>

            <div class="formRow clearfix">
                <div class="formParent formParent-full clearfix">
                    <div class="formChild form-100">
                        <span class="formLabel">Название компании</span>
                        <input class="formInput required" name="PROFILE[COMPANY]" type="text"
                               value="<?= $arResult['PROFILE']['COMPANY']; ?>">
                    </div>
                </div>
            </div>

            <div class="formRow clearfix">
                <div class="formParent clearfix">
                    <div class="formChild form-50">
                        <span class="formLabel">Номер телефона компании</span>
                        <input class="formInput phone-mask required" type="text" name="PROFILE[PHONE]"
                               value="<?= $arResult['PROFILE']['PHONE']; ?>">
                    </div>
                </div>
                <div class="formParent no-margin clearfix">
                    <div class="formChild form-50">
                        <span class="formLabel">Факс</span>
                        <input class="formInput phone-mask" type="text" name="PROFILE[FAX]"
                               value="<?= $arResult['PROFILE']['FAX']; ?>">
                    </div>
                </div>
            </div>

        </section>
    </section>

    <section class="form-block">
        <header class="formHeader">
            <h1>Реквизиты компании</h1>
        </header>
        <section class="formBody">
            <div class="cartForm-group clearfix">
                <div class="cartForm-field form-100">
                    <span class="cartForm-span">ОГРН</span>
                    <input type="text" class="cartForm-input required" name="PROFILE[OGRN]"
                           value="<?= $arResult['PROFILE']['OGRN']; ?>" data-title="ОГРН" data-rules="integer|exact_length[13]">
                </div>
                <div class="clearfix"></div>
                <div class="cartForm-field">
                    <span class="cartForm-span">Наименование банка</span>
                    <input type="text" class="cartForm-input required" name="PROFILE[BANK]"
                           value="<?= $arResult['PROFILE']['BANK']; ?>">
                </div>
                <div class="cartForm-field no-margin">
                    <span class="cartForm-span">БИК</span>
                    <input type="text" class="cartForm-input required" name="PROFILE[BIK]"
                           value="<?= $arResult['PROFILE']['BIK']; ?>" data-rules="integer|exact_length[9]"
                           data-title="БИК">
                </div>
                <div class="cartForm-field">
                    <span class="cartForm-span">ИНН</span>
                    <input type="text" class="cartForm-input required" name="PROFILE[INN]"
                           value="<?= $arResult['PROFILE']['INN']; ?>" data-rules="integer|exact_length[10]"
                           data-title="ИНН">
                </div>
                <div class="cartForm-field no-margin">
                    <span class="cartForm-span">Корреспондентский счет</span>
                    <input type="text" class="cartForm-input required" name="PROFILE[ACCOUNT_COR]"
                           value="<?= $arResult['PROFILE']['ACCOUNT_COR']; ?>"
                           data-rules="integer|exact_length[20]"
                           data-title="Корреспондентский счет">
                </div>
                <div class="cartForm-field">
                    <span class="cartForm-span">КПП</span>
                    <input type="text" class="cartForm-input required" name="PROFILE[KPP]"
                           value="<?= $arResult['PROFILE']['KPP']; ?>" data-rules="integer|exact_length[9]"
                           data-title="КПП">
                </div>
                <div class="cartForm-field no-margin">
                    <span class="cartForm-span">Расчетный счет</span>
                    <input type="text" class="cartForm-input required" name="PROFILE[ACCOUNT]"
                           value="<?= $arResult['PROFILE']['ACCOUNT']; ?>" data-rules="integer|exact_length[20]"
                           data-title="Расчетный счет">
                </div>
            </div>
        </section>
    </section>

    <section class="form-block">
        <header class="formHeader">
            <h1>Юридический адрес</h1>
        </header>
        <section class="formBody">

            <?
            $GLOBALS["APPLICATION"]->IncludeComponent(
                "aero:sale.ajax.locations",
                'popup',
                array(
                    "AJAX_CALL" => "N",
                    "CITY_INPUT_NAME" => 'PROFILE[LOCATION_LEGAL]',
                    "ZIP_INPUT_NAME" => 'PROFILE[ZIP_LEGAL]',
                    "LOCATION_VALUE" => $arResult['PROFILE']['LOCATION_LEGAL'],
                    "ZIP_VALUE" => $arResult['PROFILE']['ZIP_LEGAL'],
                    "ORDER_PROPS_ID" => 'LOCATION_LEGAL',
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
                        <input class="formInput required" type="text" name="PROFILE[STREET_LEGAL]"
                               value="<?= $arResult['PROFILE']['STREET_LEGAL']; ?>">
                    </div>
                </div>
                <div class="formParent no-margin clearfix">
                    <div class="formChild form-10">
                        <span class="formLabel">Дом</span>
                        <input class="formInput required" type="text" name="PROFILE[HOUSE_LEGAL]"
                               value="<?= $arResult['PROFILE']['HOUSE_LEGAL']; ?>">
                    </div>
                    <div class="formChild form-10">
                        <span class="formLabel">Корп.</span>
                        <input class="formInput" type="text" name="PROFILE[HOUSING_LEGAL]"
                               value="<?= $arResult['PROFILE']['HOUSING_LEGAL']; ?>">
                    </div>
                    <div class="formChild form-10">
                        <span class="formLabel">Офис</span>
                        <input class="formInput" type="text" name="PROFILE[OFFICE_LEGAL]"
                               value="<?= $arResult['PROFILE']['OFFICE_LEGAL']; ?>">
                    </div>
                    <div class="formChild form-10 no-margin">
                        <span class="formLabel">Этаж</span>
                        <input class="formInput" data-rules="integer" type="text" data-title="Этаж"
                               name="PROFILE[STAGE_LEGAL]"
                               value="<?= $arResult['PROFILE']['STAGE_LEGAL']; ?>">
                    </div>
                </div>
            </div>
            <div class="formRow clearfix">
                <div class="formParent formParent-full clearfix">
                    <div class="formChild form-100">
                        <span class="formLabel">Дополнительная информация</span>
                        <textarea class="formInput formInput-field" name="PROFILE[COMMENT_LEGAL]" cols="30"
                                  rows="10"><?= $arResult['PROFILE']['COMMENT_LEGAL']; ?></textarea>
                    </div>
                </div>
            </div>
        </section>
    </section>

    <section class="form-block">
        <header class="formHeader">
            <h1>Почтовый адрес</h1>
            <input type="checkbox" class="delivery-toggle" name="PROFILE[ACTUAL_EQUALS_LEGAL]"
                   value="Y"<? if ($arResult['PROFILE']['ACTUAL_EQUALS_LEGAL'] == 'Y'): ?> checked<? endif; ?>>
            <span class="checkboxLabel">Совпадает с юридическим</span>
        </header>

        <section
            class="formBody delivery-address"<? if ($arResult['PROFILE']['ACTUAL_EQUALS_LEGAL'] == 'Y'): ?> style="display: none;"<? endif; ?>>

            <?
            $GLOBALS["APPLICATION"]->IncludeComponent(
                "aero:sale.ajax.locations",
                'popup',
                array(
                    "AJAX_CALL" => "N",
                    "CITY_INPUT_NAME" => 'PROFILE[LOCATION_ACTUAL]',
                    "ZIP_INPUT_NAME" => 'PROFILE[ZIP_ACTUAL]',
                    "LOCATION_VALUE" => $arResult['PROFILE']['LOCATION_ACTUAL'],
                    "ZIP_VALUE" => $arResult['PROFILE']['ZIP_ACTUAL'],
                    "ORDER_PROPS_ID" => 'LOCATION_ACTUAL',
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
                        <input class="formInput" type="text" name="PROFILE[STREET_ACTUAL]"
                               value="<?= $arResult['PROFILE']['STREET_ACTUAL']; ?>">
                    </div>
                </div>
                <div class="formParent no-margin clearfix">
                    <div class="formChild form-10">
                        <span class="formLabel">Дом</span>
                        <input class="formInput" type="text" name="PROFILE[HOUSE_ACTUAL]"
                               value="<?= $arResult['PROFILE']['HOUSE_ACTUAL']; ?>">
                    </div>
                    <div class="formChild form-10">
                        <span class="formLabel">Корп.</span>
                        <input class="formInput" type="text" name="PROFILE[HOUSING_ACTUAL]"
                               value="<?= $arResult['PROFILE']['HOUSING_ACTUAL']; ?>">
                    </div>
                    <div class="formChild form-10">
                        <span class="formLabel">Офис</span>
                        <input class="formInput" type="text" name="PROFILE[OFFICE_ACTUAL]"
                               value="<?= $arResult['PROFILE']['OFFICE_ACTUAL']; ?>">
                    </div>
                    <div class="formChild form-10 no-margin">
                        <span class="formLabel">Этаж</span>
                        <input class="formInput" data-rules="integer" type="text" data-title="Этаж"
                               name="PROFILE[STAGE_ACTUAL]"
                               value="<?= $arResult['PROFILE']['STAGE_ACTUAL']; ?>">
                    </div>
                </div>
            </div>
            <div class="formRow clearfix">
                <div class="formParent formParent-full clearfix">
                    <div class="formChild form-100">
                        <span class="formLabel">Дополнительная информация</span>
                        <textarea class="formInput formInput-field" name="PROFILE[COMMENT_ACTUAL]" cols="30"
                                  rows="10"><?= $arResult['PROFILE']['COMMENT_ACTUAL']; ?></textarea>
                    </div>
                </div>
            </div>
        </section>
    </section>
<?endif; ?>