<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>



<div class="container">
    <div class="twelve">

        <!-- main -->

        <section class="main main-account">
            <div class="cartLogin">


                <header class="cartLogin-header">
                    <h1>Восстановление пароля</h1>
                </header>
                <div class="cartLogin-form">


                    <form name="bform" id="forgot_form" data-messages="yes" class="form" method="post" target="_top"
                          action="<?= $arResult["AUTH_URL"] ?>">
                        <?
                        if (strlen($arResult["BACKURL"]) > 0) {
                            ?>
                            <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                        <?
                        }
                        ?>
                        <input type="hidden" name="AUTH_FORM" value="Y">
                        <input type="hidden" name="TYPE" value="SEND_PWD">

                        <div class="cartForm-field-login">							
							<? $arParams["~AUTH_RESULT"]  = str_replace('E-Mail', 'e-mail', $arParams["~AUTH_RESULT"]);?>
                            <? ShowMessage($arParams["~AUTH_RESULT"], "form-error"); ?>
                        </div>
                        
						<?if($arParams["~AUTH_RESULT"]["TYPE"] != "OK"):?>
						
						<div class="cartLogin-form">
                            <div class="notetext cartForm-field cartForm-field-login" style="color: #36393f">
                                
								<?if($arParams["~AUTH_RESULT"]["TYPE"] == "ERROR"):?>
								
									Вы можете <a href="/personal/?register=yes">зарегистрироваться на сайте</a>
								
								<?else:?>
								
									<?= GetMessage("AUTH_FORGOT_PASSWORD_1") ?>
									
								<?endif;?>
								
                            </div>

                            <div class="cartForm-field cartForm-field-login">
                                <span class="cartForm-span">Ваш e-mail</span>
                                <input type="text" name="USER_EMAIL" class="cartForm-input-user required"
                                       data-rules="valid_email">
                            </div>

                            <div class="cartForm-buttons">
                                <button class="button orange">
                                    Выслать письмо
                                </button>
                                <a href="<?= $arResult["AUTH_AUTH_URL"] ?>" rel="nofollow" class="cartForm-exist">вход
                                    по
                                    существующему паролю</a>
                            </div>
                        </div>

						<?endif;?>

                    </form>
                    <script type="text/javascript">
                        document.bform.USER_LOGIN.focus();
                    </script>

                </div>
            </div>

        </section>
    </div>
</div>
