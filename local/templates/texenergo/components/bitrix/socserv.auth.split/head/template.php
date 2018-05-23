<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<? if (!empty($arResult["AUTH_SERVICES"])): ?>
    <?
    if ($arResult['ERROR_MESSAGE'])
        ShowMessage($arResult['ERROR_MESSAGE']);
    ?>
    <?
    $APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "head",
        array(
            "AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
            "CURRENT_SERVICE" => $arResult["CURRENT_SERVICE"],
            "AUTH_URL" => $arResult['CURRENTURL'],
            "POST" => $arResult["POST"],
            "SHOW_TITLES" => 'N',
            "FOR_SPLIT" => 'Y',
            "AUTH_LINE" => 'N',
        ),
        $component,
        array("HIDE_ICONS" => "Y")
    );
    ?>
<? endif; ?>