<?php
/**
 * User: timokhin
 * Date: 30.09.13
 * Time: 10:13
 */

CModule::IncludeModule('sale');

class CDeliveryOther
{

    function Init()
    {
        return array(
            /* Основное описание */
            'SID' => 'other',
            'NAME' => 'Доставка до транспортной компании',
            'DESCRIPTION' => '',
            'DESCRIPTION_INNER' => 'Доставка до транспортной компании',
            'BASE_CURRENCY' => COption::GetOptionString('sale', 'default_currency', 'RUB'),

            'HANDLER' => __FILE__,

            /* Методы обработчика */
            'DBGETSETTINGS' => array('CDeliveryOther', 'GetSettings'),
            'DBSETSETTINGS' => array('CDeliveryOther', 'SetSettings'),
            'GETCONFIG' => array('CDeliveryOther', 'GetConfig'),

            'COMPABILITY' => array('CDeliveryOther', 'Compability'),
            'CALCULATOR' => array('CDeliveryOther', 'Calculate'),

            /* Список профилей доставки */
            'PROFILES' => CDeliveryOther::__GetProfiles(),
        );
    }

    function GetConfig()
    {

        $arConfig = array(
            'CONFIG_GROUPS' => array(),
            'CONFIG' => array(),
        );

        $arProfiles = CDeliveryOther::__GetProfiles();
        foreach ($arProfiles as $sid => $arProfile) {
            $arConfig['CONFIG_GROUPS'][$sid] = $arProfile['TITLE'];

            $arConfig['CONFIG']['price_' . $sid] = Array(
                'TYPE' => 'STRING',
                'TITLE' => 'Стоимость доставки',
                'DEFAULT' => '0',
                'GROUP' => $sid,
            );
        }

        return $arConfig;
    }

    function GetSettings($strSettings)
    {
        return unserialize($strSettings);
    }

    function SetSettings($arSettings)
    {
        foreach ($arSettings as $key => $value) {
            if (!empty($value))
                $arSettings[$key] = $value;
            else
                unset($arSettings[$key]);
        }
        return serialize($arSettings);
    }

    function __GetProfiles()
    {
        CModule::IncludeModule('iblock');
        $rsCompanies = CIBlockElement::GetList(Array('sort' => 'asc', 'name' => 'asc'), Array('IBLOCK_ID' => IBLOCK_ID_DELIVERY_COMPANIES, 'ACTIVE' => 'Y'), false, false, Array('ID', 'CODE', 'NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE'));
        $arProfiles = Array();
        while ($arCompany = $rsCompanies->Fetch()) {
            $arProfiles[$arCompany['CODE']] = Array(
                'TITLE' => $arCompany['NAME'],
                'DESCRIPTION' => $arCompany['PREVIEW_TEXT'],
                'RESTRICTIONS_WEIGHT' => array(0), // без ограничений
                'RESTRICTIONS_SUM' => array(0), // без ограничений
            );
        }
        return $arProfiles;
    }

    function Calculate($profileID, $arConfig, $arOrder, $STEP, $TEMP = false)
    {

        $arProfiles = CDeliveryOther::__GetProfiles();
        if (!array_key_exists($profileID, $arProfiles)) {
            return array(
                "RESULT" => "ERROR",
                "TEXT" => "Не найден профиль доставки",
            );
        }

        return array(
            "RESULT" => "OK",
            "VALUE" => $arConfig['price_' . $profileID]['VALUE'],
            "TRANSIT" => "",
        );
    }

    function Compability($arOrder, $arConfig)
    {
        $arProfiles = CDeliveryOther::__GetProfiles();

        return array_keys($arProfiles);
    }

}

AddEventHandler('sale', 'onSaleDeliveryHandlersBuildList', array('CDeliveryOther', 'Init'));