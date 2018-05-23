<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2013 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<div class="container">
    <div class="twelve">

        <!-- main -->

        <section class="main main-account">
            <div class="cartRegister">

                <? if ($arResult["USE_EMAIL_CONFIRMATION"] === "Y" && is_array($arParams["AUTH_RESULT"]) && $arParams["AUTH_RESULT"]["TYPE"] === "OK"): ?>
					<div class="cartLogin">
                        <div class="formBody">
                            <div class="notetext"><? echo GetMessage("AUTH_EMAIL_SENT") ?></div>
                            <div class="cartForm-buttons">
                                <a href="/auth/index.php?confirm_registration=yes&confirm_user_id=<?=$arParams['AUTH_RESULT']['ID']?>" rel="nofollow"
                                   class="cartForm-exist button orange">
                                    Ввести код подтверждения
                                </a>
                            </div>
                        </div>
                    </div>
                <? else: ?>
                    <div id="formTabs">
                        <ul class="formTabs-ul clearfix">
                            <li class="formTab">
                                <a href="#cartForm-2">Юридическое лицо</a>
                            </li>
                            <li class="formTab">
                                <a href="#cartForm-3">ИП</a>
                            </li>                           
                        </ul>                       
                        <div id="cartForm-2" class="cartForm">
                            <? require_once $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/yur.php'; ?>
                        </div>
                        <div id="cartForm-3" class="cartForm">
                            <? require_once $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/ip.php'; ?>
                        </div>
                    </div>
                <?endif; ?>
            </div>
        </section>
    </div>
</div>
