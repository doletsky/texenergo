<form method="post" id="register_form_yur" data-messages="yes" class="form" action="<?= $arResult["AUTH_URL"] ?>#cartForm-2"
      name="bform" autocomplete="off" data-persist="garlic">
<input style="display:none" type="text" name="fakeusernameremembered">
<input style="display:none" type="password" name="fakepasswordremembered">

<?
if (strlen($arResult["BACKURL"]) > 0) {
    ?>
    <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
<?
}
?>
<input type="hidden" name="AUTH_FORM" value="Y"/>
<input type="hidden" name="TYPE" value="REGISTRATION"/>
<input type="hidden" name="PERSON_TYPE" value="<?= SALE_PERSON_YUR; ?>"/>

<? ShowMessage($arParams["~AUTH_RESULT"], "form-error"); ?>

<div class="cartForm-group clearfix">


    <div class="cartForm-field">
        <span class="cartForm-span">Имя</span>
        <input type="text" name="USER_NAME" value="<?= $arResult["USER_NAME"] ?>"
               class="cartForm-input cartForm-input-user required">
    </div>
    <div class="cartForm-field no-margin">
        <span class="cartForm-span">Фамилия</span>
        <input type="text" name="USER_LAST_NAME" value="<?= $arResult["USER_LAST_NAME"] ?>"
               class="cartForm-input cartForm-input-user required">
    </div>

    <div class="cartForm-field">
        <span class="cartForm-span">Ваш e-mail</span>
        <input type="text" name="USER_EMAIL" value="<?= $arResult["USER_EMAIL"] ?>"
               class="cartForm-input-user required" data-rules="valid_email">
    </div>
    <div class="cartForm-field no-margin">
        <span class="cartForm-span">Название компании</span>
        <input type="text" class="cartForm-input required" name="PROFILE[COMPANY]"
               value="<?= $arResult['PROFILE']['COMPANY']; ?>">
    </div>
    <div class="cartForm-field">
        <span class="cartForm-span">Номер телефона</span>
        <input type="text" class="cartForm-input phone-mask required" name="PROFILE[PHONE]"
               value="<?= $arResult['PROFILE']['PHONE']; ?>">
    </div>
    <div class="cartForm-field no-margin">
        <span class="cartForm-span">Факс</span>
        <input type="text" class="cartForm-input phone-mask" name="PROFILE[FAX]"
               value="<?= $arResult['PROFILE']['FAX']; ?>">
    </div>
</div>
<div class="cartForm-group clearfix">
    <div class="cartForm-field form-100">
        <span class="cartForm-span">ОГРН</span>
        <input type="text" class="cartForm-input required" name="PROFILE[OGRN]"
               value="<?= $arResult['PROFILE']['OGRN']; ?>" data-title="ОГРН" data-rules="integer|exact_length[13]">
    </div>
    <div class="cartForm-field">
        <span class="cartForm-span">Наименование банка</span>
        <input type="text" class="cartForm-input required" name="PROFILE[BANK]"
               value="<?= $arResult['PROFILE']['BANK']; ?>">
    </div>
    <div class="cartForm-field no-margin">
        <span class="cartForm-span">БИК</span>
        <input type="text" class="cartForm-input required" name="PROFILE[BIK]"
               value="<?= $arResult['PROFILE']['BIK']; ?>" data-rules="integer|exact_length[9]" data-title="БИК">
    </div>
    <div class="cartForm-field">
        <span class="cartForm-span">ИНН</span>
        <input type="text" class="cartForm-input required" name="PROFILE[INN]"
               value="<?= $arResult['PROFILE']['INN']; ?>" data-rules="integer|exact_length[10]" data-title="ИНН">
    </div>
    <div class="cartForm-field no-margin">
        <span class="cartForm-span">Корреспондентский счет</span>
        <input type="text" class="cartForm-input required" name="PROFILE[ACCOUNT_COR]"
               value="<?= $arResult['PROFILE']['ACCOUNT_COR']; ?>" data-rules="integer|exact_length[20]"
               data-title="Корреспондентский счет">
    </div>
    <div class="cartForm-field">
        <span class="cartForm-span">КПП</span>
        <input type="text" class="cartForm-input required" name="PROFILE[KPP]"
               value="<?= $arResult['PROFILE']['KPP']; ?>" data-rules="integer|exact_length[9]" data-title="КПП">
    </div>
    <div class="cartForm-field no-margin">
        <span class="cartForm-span">Расчетный счет</span>
        <input type="text" class="cartForm-input required" name="PROFILE[ACCOUNT]"
               value="<?= $arResult['PROFILE']['ACCOUNT']; ?>" data-rules="integer|exact_length[20]"
               data-title="Расчетный счет">
    </div>

    <div class="clear"></div>
    <header class="formHeader">
        <h1>Юридический адрес</h1>
    </header>
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
            "INPUT_CLASS" => "cartForm-input required",
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
                <input class="formInput" data-rules="integer" type="text" name="PROFILE[STAGE_LEGAL]"
                       value="<?= $arResult['PROFILE']['STAGE_LEGAL']; ?>" data-title="Этаж">
            </div>
        </div>
    </div>


    <div class="clear"></div>

	<?
	$display = "block";
	$checked = "";
	if($_REQUEST['PROFILE']['ACTUAL_EQUALS_LEGAL'] == 'Y'){
		$display = "none";
		$checked = "checked";
	}?>


	<header class="formHeader">
        <h1>Почтовый адрес</h1>
        <input type="checkbox" class="delivery-toggle" name="PROFILE[ACTUAL_EQUALS_LEGAL]" value="Y" <?=$checked?>>
        <span class="checkboxLabel">Совпадает с юридическим</span>
    </header>

	<div class="delivery-address" style="display:<?=$display?>">
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
                "INPUT_CLASS" => "cartForm-input",
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
                    <input class="formInput" data-rules="integer" type="text" name="PROFILE[STAGE_ACTUAL]"
                           value="<?= $arResult['PROFILE']['STAGE_ACTUAL']; ?>" data-title="Этаж">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="cartForm-group cartForm-group-pass clearfix">
    <div class="cartForm-field">
        <span class="cartForm-span">Пароль</span>
        <input type="password" class="cartForm-input required" name="USER_PASSWORD">
    </div>
    <div class="cartForm-field no-margin">
        <span class="cartForm-span">Пароль еще раз</span>
        <input type="password" class="cartForm-input required" name="USER_CONFIRM_PASSWORD"
               data-rules="matches[USER_PASSWORD]">
    </div>
</div>


<? if ($arResult["USE_CAPTCHA"] == "Y"): ?>
    <span class="cartForm-span">Защита от автоматической регистрации</span>
    <div class="cartForm-group clearfix">
        <div class="cartForm-field">
            <input type="text" name="captcha_word" maxlength="50"
                   class="cartForm-input cartForm-input-user required">
        </div>
        <div class="cartForm-field no-margin">
            <input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
            <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>"
                 width="180" height="40" alt="CAPTCHA">
        </div>
    </div>
<? endif; ?>

<div class="cartForm-buttons">
    <button class="button orange">зарегистрироваться и
        войти
    </button>
    <a href="<?= $arResult["AUTH_AUTH_URL"] ?>" rel="nofollow" class="cartForm-exist">
        Вход для зарегистрированных пользователей
    </a>
    <p class="cartForm-text">
    При возникновении вопросов, обращайтесь<br>
    в тех.поддержку сайта на странице <a href="/about/contacts/" target="_blank">"Контакты"</a>
    </p>
</div>
</form>