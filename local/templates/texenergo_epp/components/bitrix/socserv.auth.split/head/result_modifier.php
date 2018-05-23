<?php

if (CModule::IncludeModule("socialservices")) {
    $oAuthManager = new CSocServAuthManager();
    $arServices = $oAuthManager->GetActiveAuthServices($arResult);

    if (!empty($arServices)) {
        $arResult["AUTH_SERVICES"] = $arServices;
        if (isset($_REQUEST["auth_service_id"]) && $_REQUEST["auth_service_id"] <> '' && isset($arResult["AUTH_SERVICES"][$_REQUEST["auth_service_id"]])) {
            $arResult["CURRENT_SERVICE"] = $_REQUEST["auth_service_id"];
            if (isset($_REQUEST["auth_service_error"]) && $_REQUEST["auth_service_error"] <> '') {
                $arResult['ERROR_MESSAGE'] = $oAuthManager->GetError($arResult["CURRENT_SERVICE"], $_REQUEST["auth_service_error"]);
            } elseif (!$oAuthManager->Authorize($_REQUEST["auth_service_id"])) {
                $ex = $APPLICATION->GetException();
                if ($ex)
                    $arResult['ERROR_MESSAGE'] = $ex->GetString();
            }
        }
    }
}

foreach ($arResult['AUTH_SERVICES'] as $serviceID => &$arService) {
    foreach ($arResult['DB_SOCSERV_USER'] as $arDBService) {
        if ($arDBService['EXTERNAL_AUTH_ID'] == $serviceID) {
            $arService['ACTIVE'] = 'Y';
            $arService['DB_USER_ID'] = $arDBService['ID'];
            break;
        }
    }
}