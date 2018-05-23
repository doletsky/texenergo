<?
/**
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>

<div class="container">
    <div class="twelve position">
        <h1 class="headline">мои данные</h1>
        <? /*
        <aside class="profileSelector">
            <? if ($arResult['PAYER_TYPE'] == SALE_PERSON_YUR): ?>
                <a href="/personal/contacts/?set_user_type=yes&id=<?= SALE_PERSON_FIZ; ?>"
                   class="switch-socials switchSocials-order is-switchSocials-on"></a>
            <? else: ?>
                <a href="/personal/contacts/?set_user_type=yes&id=<?= SALE_PERSON_YUR; ?>"
                   class="switch-socials switchSocials-order"></a>
            <? endif; ?>
            <span>юридическое лицо</span>
        </aside>
*/
        ?>
    </div>
</div>

<div class="container">

    <? require $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/sidebar.php'; ?>

    <section class="main main-account main-profile main-floated">


        <form method="post" name="profile_form" id="profile_form" class="form" data-messages="yes"
              action="<?= $arResult["FORM_TARGET"] ?>" autocomplete="off">
            <?= $arResult["BX_SESSION_CHECK"] ?>
            <input type="hidden" name="lang" value="<?= LANG ?>">
            <input type="hidden" name="ID" value="<?= $arResult["ID"] ?>">
            <input type="hidden" name="LOGIN" value="yes">
            <input type="hidden" name="PERSON_TYPE" value="<?= $arResult['PAYER_TYPE']; ?>">
            <input type="hidden" name="PROFILE_ID" value="<?= $arResult['PROFILE_ID']; ?>">

            <input style="display:none" type="text" name="fakeusernameremembered">
            <input style="display:none" type="password" name="fakepasswordremembered">

            <section class="form-block">


                <section class="formBody">

                    <? ShowError($arResult["strProfileError"]); ?>
                    <?
                    if ($_REQUEST['saved'] == 'yes')
                        ShowNote(GetMessage('PROFILE_DATA_SAVED'));
                    ?>

                    <? if ($arResult['IS_NEW_EMAIL'] == 'Y'): ?>
                        <div class="notetext">
                            По адресу <?= $arResult['NEW_EMAIL']; ?> было выслано письмо с инструкциями для
                            подтверждения
                            нового
                            адреса. Адрес будет изменен только после подтверждения.
                        </div>
                    <? endif; ?>

                    <? if ($arResult['IS_NEW_EMAIL_CONFIRMED'] == 'Y'): ?>
                        <div class="notetext">
                            Новый адрес <?= $arResult['NEW_EMAIL']; ?> успешно подтвержден.
                        </div>
                    <? endif; ?>


                    <div class="formRow clearfix">
                        <div class="formParent clearfix">
                            <div class="formChild form-50">
                                <span class="formLabel">Имя</span>
                                <input class="formInput required" type="text" name="NAME"
                                       value="<?= $arResult["arUser"]["NAME"] ?>">
                            </div>
                        </div>
                        <div class="formParent no-margin clearfix">
                            <div class="formChild form-50">
                                <span class="formLabel">Фамилия</span>
                                <input class="formInput required" type="text" name="LAST_NAME"
                                       value="<?= $arResult["arUser"]["LAST_NAME"] ?>">
                            </div>
                        </div>
                    </div>


                    <div class="formRow clearfix">
                        <div class="formParent clearfix">
                            <div class="formChild form-50">
                                <span class="formLabel">Номер телефона</span>
                                <input class="formInput phone-mask" type="text" name="PERSONAL_PHONE"
                                       value="<?= $arResult["arUser"]["PERSONAL_PHONE"] ?>">
                            </div>
                        </div>
                        <div class="formParent no-margin clearfix">
                            <div class="formChild form-50">
                                <span class="formLabel">Ваш email (логин)</span>
                                <input class="formInput required" data-rules="valid_email" type="text" name="EMAIL"
                                       value="<?= $arResult["arUser"]["EMAIL"] ?>">
                            </div>
                        </div>
                    </div>


                </section>

            </section>

            <?$file = '';

            switch ($arResult['PAYER_TYPE']) {
                case SALE_PERSON_YUR: $file = 'yur_fields.php'; break;
                case SALE_PERSON_IP: $file = 'ip_fields.php'; break;
            }

            if (strlen($file) > 0)
                require "{$_SERVER['DOCUMENT_ROOT']}{$templateFolder}/{$file}";?>

            <? // require $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/delivery_fields.php'; ?>


            <section class="form-block">
                <header class="formHeader">
                    <h1>Изменить пароль</h1>
                </header>

                <section class="formBody">
                    <div class="formChild form-100">
                        <span class="passwordLabel">Укажите новый пароль, если хотите поменять</span>
                    </div>
                    <div class="formRow clearfix">
                        <div class="formParent clearfix">
                            <div class="formChild form-50">
                                <span class="formLabel">Пароль</span>
                                <input class="formInput" type="password" name="NEW_PASSWORD" autocomplete="off">
                            </div>
                        </div>
                        <div class="formParent no-margin clearfix">
                            <div class="formChild form-50">
                                <span class="formLabel">Пароль еще раз</span>
                                <input class="formInput" type="password" name="NEW_PASSWORD_CONFIRM"
                                       data-rules="matches[NEW_PASSWORD]" autocomplete="off">
                            </div>
                        </div>
                    </div>

                </section>
            </section>


            <div class="formBottom">
                <button class="button orange" name="save" value="yes">сохранить</button>
                <a href="" class="formUndo">отменить изменения</a>
            </div>
        </form>
    </section>

</div>
