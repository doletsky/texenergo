<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="container">
    <div class="twelve">

        <!-- main -->

        <section class="main main-account">
            <div class="cartLogin">
                <form name="form_auth" id="form_auth" data-messages="yes" class="form" method="post" target="_top"
                      action="<?= $arResult["AUTH_URL"] ?>">

                    <input type="hidden" name="AUTH_FORM" value="Y"/>
                    <input type="hidden" name="TYPE" value="AUTH"/>
                    <? if (strlen($arResult["BACKURL"]) > 0): ?>
                        <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                    <? endif ?>
                    <? foreach ($arResult["POST"] as $key => $value): ?>
                        <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
                    <? endforeach ?>
                    <header class="cartLogin-header">
                        <h1>Вход при помощи аккаунта</h1>
                    </header>
                    <div class="cartLogin-form">

                        <div class="cartForm-field cartForm-field-message cartForm-field-login">
                            <?
                            $arParams["~AUTH_RESULT"]["MESSAGE"] = str_replace($_REQUEST['USER_LOGIN'], '<nobr>'.$_REQUEST['USER_LOGIN'].'</nobr>', $arParams["~AUTH_RESULT"]["MESSAGE"]);
							
							if($arParams["~AUTH_RESULT"]["TYPE"] == "ERROR"):?>
							
								<div class="errortext"><?=$arParams["~AUTH_RESULT"]["MESSAGE"]?></div>
							
							<?else:?>
							
								<div><?=$arParams["~AUTH_RESULT"]["MESSAGE"]?></div>
								
							<?endif;?>
																	
                            <?ShowMessage($arResult['ERROR_MESSAGE']);?>
							
                            <? if ($_GET['confirm_registration'] == 'yes'): ?>
                                <div class="notetext">
                                    Теперь вы можете войти на сайт, используя свой Email и пароль.
                                </div>
                            <? endif; ?>
                        </div>

                        <div class="cartForm-group cartForm-group-login clearfix">
                            <div class="cartForm-field cartForm-field-login">
                                <span class="cartForm-span">Ваш e-mail</span>
                                <input type="text" name="USER_LOGIN" class="cartForm-input-user required"
                                       data-rules="valid_email" value="<?=$arResult['LAST_LOGIN'];?>">
                            </div>
                            <div class="cartForm-field cartForm-field-login">
                                <span class="cartForm-span cartForm-span-inline">Пароль</span>
                                <a href="<?= $arResult["AUTH_FORGOT_PASSWORD_URL"] ?>" rel="nofollow"
                                   class="cartLink-forget">Забыли пароль?</a>
                                <input type="password" name="USER_PASSWORD" class="cartForm-input-user required">
                            </div>
                            <div class="cartForm-buttons cartForm-buttons-login clearfix">
                                <a href="<?= $arResult["AUTH_REGISTER_URL"] ?>" rel="nofollow"
                                   class="cartForm-exist cartForm-exist-login">регистрация</a>
                                <button name="Login" class="button orange">
                                    войти
                                </button>
                            </div>
			
    				<p class="cartForm-text">
    				При возникновении вопросов, обращайтесь<br>
    				в тех.поддержку сайта на странице <a href="/about/contacts/" target="_blank">"Контакты"</a>
    				</p>                        
                        </div>
                        <? /* if ($arResult["AUTH_SERVICES"]): ?>
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
                        <? endif  */?>
                    </div>
                </form>
            </div>

        </section>
    </div>
</div>