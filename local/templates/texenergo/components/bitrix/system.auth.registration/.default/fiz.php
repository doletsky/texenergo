<form method="post" id="register_form_fiz" data-messages="yes" class="form" action="<?= $arResult["AUTH_URL"] ?>#cartForm-1"
      name="bform" data-persist="garlic">
    <?
    if (strlen($arResult["BACKURL"]) > 0) {
        ?>
        <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
    <?
    }
    ?>
    <input type="hidden" name="AUTH_FORM" value="Y"/>
    <input type="hidden" name="TYPE" value="REGISTRATION"/>
    <input type="hidden" name="PERSON_TYPE" value="<?= SALE_PERSON_FIZ; ?>"/>

    <?if($arParams["~AUTH_RESULT"]['TYPE'] == 'ERROR'):?>

	<div class="field-error-message"><?=$arParams["~AUTH_RESULT"]["MESSAGE"]?></div>

	<?else:?>

		<? ShowMessage($arParams["~AUTH_RESULT"], "form-error"); ?>

	<?endif;?>

    <div class="cartForm-group cartForm-group-user clearfix">
        <div class="cartForm-field">
            <span class="cartForm-span">Ваш e-mail</span>
            <input type="text" name="USER_EMAIL" value="<?= $arResult["USER_EMAIL"] ?>"
                   class="cartForm-input-user required" data-rules="valid_email">
        </div>
    </div>

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
    </div>

    <div class="cartForm-group clearfix">
        <div class="cartForm-field">
            <span class="cartForm-span">Пароль</span>
            <input type="password" name="USER_PASSWORD" class="cartForm-input cartForm-input-user required">
        </div>
        <div class="cartForm-field no-margin">
            <span class="cartForm-span">Пароль еще раз</span>
            <input type="password" name="USER_CONFIRM_PASSWORD" class="cartForm-input cartForm-input-user required"
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
        <button class="button orange">Зарегистрироваться и войти</button>
        <a href="<?= $arResult["AUTH_AUTH_URL"] ?>" rel="nofollow" class="cartForm-exist">
            Вход для зарегистрированных пользователей
        </a>
    </div>
    <? if ($arResult["AUTH_SERVICES"]): ?>
        <?
        $APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "",
            array(
                "AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
                "CURRENT_SERVICE" => $arResult["CURRENT_SERVICE"],
                "AUTH_URL" => $arResult["AUTH_URL"],
                "POST" => $arResult["POST"],
                "SHOW_TITLES" => $arResult["FOR_INTRANET"] ? 'N' : 'Y',
                "FOR_SPLIT" => $arResult["FOR_INTRANET"] ? 'Y' : 'N',
                "AUTH_LINE" => $arResult["FOR_INTRANET"] ? 'N' : 'Y',
            ),
            $component,
            array("HIDE_ICONS" => "Y")
        );
        ?>
    <? endif ?>
</form>