<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>



<div class="container">
    <div class="twelve">

        <!-- main -->

        <section class="main main-account">
            <div class="cartLogin">


                <header class="cartLogin-header">
                    <h1>Смена пароля</h1>
                </header>
                <div class="cartLogin-form">
                    <form method="post" class="form" data-messages="yes" id="change_form"
                          action="<?= $arResult["AUTH_FORM"] ?>" name="bform">
                        <? if (strlen($arResult["BACKURL"]) > 0): ?>
                            <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                        <? endif ?>
                        <input type="hidden" name="AUTH_FORM" value="Y">
                        <input type="hidden" name="TYPE" value="CHANGE_PWD">
                        <? if ($arParams["~AUTH_RESULT"]): ?>
                            <div class="cartForm-field-login">
                                <?$arParams["~AUTH_RESULT"] = str_replace('E-Mail', 'e-mail', $arParams["~AUTH_RESULT"]);?>
								<? ShowMessage($arParams["~AUTH_RESULT"], "form-error"); ?>
                            </div>
                        <? else: ?>
                            <div class="cartForm-field cartForm-field-login">
                                <div class="notetext">
                                    <? echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"]; ?>
                                </div>
                            </div>
                        <?endif; ?>

                        <div class="cartLogin-form">
                            <div class="cartForm-group cartForm-group-login clearfix">
                                <div class="cartForm-field cartForm-field-login">
                                    <span class="cartForm-span">Ваш e-mail</span>
                                    <input type="text" name="USER_LOGIN" class="cartForm-input-user required"
                                           data-rules="valid_email" value="<?= $arResult["LAST_LOGIN"] ?>">
                                </div>

                                <div class="cartForm-field cartForm-field-login">
                                    <span class="cartForm-span"><?= GetMessage("AUTH_CHECKWORD") ?></span>
                                    <input type="text" name="USER_CHECKWORD" class="cartForm-input-user required"
                                           value="<?= $arResult["USER_CHECKWORD"] ?>">
                                </div>

                                <div class="cartForm-field cartForm-field-login">
                                    <span class="cartForm-span">Пароль</span>
                                    <input type="password" name="USER_PASSWORD"
                                           class="cartForm-input cartForm-input-user required"
                                           data-rules="min_lengthpass[6]" data-title="Пароль">
                                </div>
                                <div class="cartForm-field cartForm-field-login">
                                    <span class="cartForm-span">Пароль еще раз</span>
                                    <input type="password" name="USER_CONFIRM_PASSWORD"
                                           class="cartForm-input cartForm-input-user required"
                                           data-rules="matches[USER_PASSWORD]">
                                </div>
                                <div class="cartForm-buttons">
                                    <button name="change_pwd" class="button orange">
                                        Сменить пароль
                                    </button>
                                    <a href="/auth/?forgot_password=yes" rel="nofollow" class="cartForm-exist">
                                        Запросить письмо повторно
                                    </a>
                                    <a href="/personal/" rel="nofollow" class="cartForm-exist">
                                        Вход на сайт
                                    </a>
                                </div>
                            </div>
                        </div>


                    </form>

                    <script type="text/javascript">
                        document.bform.USER_LOGIN.focus();
                    </script>

                </div>
            </div>

        </section>
    </div>
</div>
