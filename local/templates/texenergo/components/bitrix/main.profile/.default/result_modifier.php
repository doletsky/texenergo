<?php

if ($arResult['DATA_SAVED'] == 'Y') {
    LocalRedirect($APPLICATION->GetCurPageParam('saved=yes', Array('saved')));
}

$arUser = CUser::GetByID($USER->GetID())->Fetch();
global $USER_FIELD_MANAGER;
CModule::IncludeModule('iblock');

/**
 * Проверяем, была ли попытка изменить Email
 * Если да, то выводим сообщение
 * Если передан код проверки, проверяем его корректность и обновляем адрес
 */

if (strlen($arUser['UF_NEW_EMAIL']) > 0 && strlen($arUser['UF_NEW_EMAIL_CODE']) > 0) {
    $arResult['IS_NEW_EMAIL'] = 'Y';
    $arResult['NEW_EMAIL'] = $arUser['UF_NEW_EMAIL'];

    if ($_GET['confirm_new_email'] == 'yes') {

        $sCode = trim($_GET['code']);
        if ($arUser['UF_NEW_EMAIL_CODE'] == $sCode) {

            $obj = new CUser();			
            if (!$obj->Update($USER->GetID(), array('EMAIL' => $arUser['UF_NEW_EMAIL'], 'FORCE_UPD' => true))) {
                $arResult["strProfileError"] = $obj->LAST_ERROR;				
            } else {
                $USER_FIELD_MANAGER->Update('USER', $USER->GetID(), Array(
                    'UF_NEW_EMAIL' => '',
                    'UF_NEW_EMAIL_CODE' => '',
                ));
                $arResult['IS_NEW_EMAIL'] = 'N';
                $arResult['IS_NEW_EMAIL_CONFIRMED'] = 'Y';
                $arResult["arUser"]["EMAIL"] = $arUser['UF_NEW_EMAIL'];
            }
        }
    }
}


/**
 * Определяем тип плательщика
 */
$arResult['PAYER_TYPE'] = ($arUser['UF_PAYER_TYPE'] ? $arUser['UF_PAYER_TYPE'] : SALE_PERSON_FIZ);

/**
 * Выбираем значения свойств контрагента
 */
$arResult['PROFILE'] = Array();
if (strlen($arUser['UF_COMPANY_ID']) > 0) {
    $arProfile = CIBlockElement::GetList(
        Array(),
        Array('=ID' => $arUser['UF_COMPANY_ID'], 'IBLOCK_ID' => IBLOCK_ID_AGENTS),
        false, Array('nTopCount'),
        Array(
            'ID',
            'ACTIVE',
            'NAME',
            // юридический адрес
            'PROPERTY_CITY_LEGAL',
            'PROPERTY_LOCATION_LEGAL',
            'PROPERTY_STREET_LEGAL',
            'PROPERTY_ZIP_LEGAL',
            'PROPERTY_HOUSE_LEGAL',
            'PROPERTY_HOUSING_LEGAL',
            'PROPERTY_OFFICE_LEGAL',
            'PROPERTY_STAGE_LEGAL',
            'PROPERTY_COMMENT_LEGAL',

            // фактический адрес
            'PROPERTY_ACTUAL_EQUALS_LEGAL',
            'PROPERTY_CITY_ACTUAL',
            'PROPERTY_LOCATION_ACTUAL',
            'PROPERTY_STREET_ACTUAL',
            'PROPERTY_ZIP_ACTUAL',
            'PROPERTY_HOUSE_ACTUAL',
            'PROPERTY_HOUSING_ACTUAL',
            'PROPERTY_OFFICE_ACTUAL',
            'PROPERTY_STAGE_ACTUAL',
            'PROPERTY_COMMENT_ACTUAL',

            // реквизиты
            'PROPERTY_BANK',
            'PROPERTY_BIK',
            'PROPERTY_INN',
            'PROPERTY_KPP',
            'PROPERTY_ACCOUNT_COR',
            'PROPERTY_ACCOUNT',
            'PROPERTY_OGRN',

            // контакты
            'PROPERTY_PHONE',
            'PROPERTY_FAX',
            'PROPERTY_EMAIL',
            'WF_STATUS_ID',
        )
    )->GetNext(false, false);
}

if ($arProfile['ID'] > 0) {
    /**
     * Запрос на отмену модерации компании
     * Пользователь отвязывается от компании, компания удаляется
     */
    if ($arProfile['ACTIVE'] != 'Y' && $_GET['cancel_company_request'] == 'yes') {
        $USER_FIELD_MANAGER->Update('USER', $arUser['ID'], Array(
            'UF_COMPANY_ID' => '',
        ));
        CIBlockElement::Delete($arProfile['ID']);
        LocalRedirect($APPLICATION->GetCurPageParam('', Array('cancel_company_request')));
    }


    $arResult['PROFILE_ID'] = $arProfile['ID'];
    $arResult['PROFILE']['COMPANY'] = $arProfile['NAME'];
    $arResult['PROFILE']['ACTIVE'] = $arProfile['ACTIVE'];
    foreach ($arProfile as $code => $value) {
        $key = $code;
        if (preg_match('/^PROPERTY_(.+)_VALUE$/', $key)) {
            $key = preg_replace('/^PROPERTY_(.+)_VALUE$/', '$1', $key);
            $arResult['PROFILE'][$key] = htmlspecialcharsEx($value);
        }
    }

    if ($arProfile['ACTIVE'] == 'Y') {
        $rsChanges = CIBlockElement::GetList(Array('timestamp_x' => 'desc'), Array('IBLOCK_ID' => IBLOCK_ID_AGENTS, 'WF_STATUS' => 3, 'SHOW_HISTORY' => 'Y', 'WF_PARENT_ELEMENT_ID' => $arProfile['ID']));
        if ($rsChanges->SelectedRowsCount() > 0) {
            $arResult['CHANGES'] = 'Y';
        }
    }

}

/**
 * Если возникла ошибка валидации, подменяем значения св-в на актуальные из _POST
 */
if ($arResult['DATA_SAVED'] != 'Y' && is_array($_POST['PROFILE'])) {
    foreach ($_POST['PROFILE'] as $sCode => $sValue) {
        $arResult['PROFILE'][$sCode] = htmlspecialcharsEx($sValue);
    }
}

//echo '<pre>' . print_r($arUser, true) . '</pre>';