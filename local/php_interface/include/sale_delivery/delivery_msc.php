<?php
/**
 * User: timokhin
 * Date: 30.09.13
 * Time: 10:13
 */

CModule::IncludeModule('sale');

class CDeliveryMsc
{

    function Init()
    {
        return array(
            /* Основное описание */
            'SID' => 'msc',
            'NAME' => 'Доставка по Москве и области',
            'DESCRIPTION' => '',
            'DESCRIPTION_INNER' => 'Расчет обычной и срочной доставки по Москве и области',
            'BASE_CURRENCY' => COption::GetOptionString('sale', 'default_currency', 'RUB'),

            'HANDLER' => __FILE__,

            /* Методы обработчика */
            'DBGETSETTINGS' => array('CDeliveryMsc', 'GetSettings'),
            'DBSETSETTINGS' => array('CDeliveryMsc', 'SetSettings'),
            'GETCONFIG' => array('CDeliveryMsc', 'GetConfig'),

            'COMPABILITY' => array('CDeliveryMsc', 'Compability'),
            'CALCULATOR' => array('CDeliveryMsc', 'Calculate'),

            /* Список профилей доставки */
            'PROFILES' => CDeliveryMsc::__GetProfiles(),
        );
    }

    function GetConfig()
    {
        $arProfiles = CDeliveryMsc::__GetProfiles();

        $arConfig = array(
            'CONFIG_GROUPS' => array(
                'common' => 'Общие настройки',
            ),
            'CONFIG' => array(),
        );

        foreach ($arProfiles as $sid => $arProfile) {
            $arConfig['CONFIG_GROUPS'][$sid] = $arProfile['TITLE'];
            if ($sid == 'self') continue;

            $arConfig['CONFIG']['price_zone_' . $sid . '_default'] = Array(
                'TYPE' => 'STRING',
                'TITLE' => 'Стоимость по-умолчанию (если не удалось определить зону)',
                'DEFAULT' => '0',
                'GROUP' => $sid,
            );
            CModule::IncludeModule('iblock');
            $rsZones = CIBlockElement::GetList(Array('NAME' => 'asc'), Array('IBLOCK_ID' => IBLOCK_ID_DELIVERY_ZONES), false, false, Array('ID', 'NAME'));


            while ($arZone = $rsZones->Fetch()) {
                $arConfig['CONFIG']['price_zone_' . $sid . '_' . $arZone['ID']] = Array(
                    'TYPE' => 'STRING',
                    'TITLE' => 'Стоимость (' . $arZone['NAME'] . ')',
                    'DEFAULT' => '0',
                    'GROUP' => $sid,
                );
            }
        }

        $arLocationGroups = Array();

        $rsLocationGroups = CSaleLocationGroup::GetList(Array("NAME" => "ASC"), array(), LANGUAGE_ID);

        while ($arLocationGroup = $rsLocationGroups->Fetch()) {
            $arLocationGroups[$arLocationGroup['ID']] = $arLocationGroup['NAME'];
        }

        $arConfig['CONFIG']['location_group'] = Array(
            'TYPE' => 'DROPDOWN',
            'TITLE' => 'Группа местоположений',
            'DEFAULT' => '0',
            'GROUP' => 'common',
            'VALUES' => $arLocationGroups,
        );

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
        return array(
            'simple' => array(
                'TITLE' => 'Обычная',
                'DESCRIPTION' => 'Обычная доставка',

                'RESTRICTIONS_WEIGHT' => array(0), // без ограничений
                'RESTRICTIONS_SUM' => array(0), // без ограничений
            ),
            'express' => array(
                'TITLE' => 'Срочная',
                'DESCRIPTION' => 'Срочная доставка',

                'RESTRICTIONS_WEIGHT' => array(0), // без ограничений
                'RESTRICTIONS_SUM' => array(0), // без ограничений
            ),
            'self' => array(
                'TITLE' => 'Самовывоз',
                'DESCRIPTION' => 'Самовывоз',

                'RESTRICTIONS_WEIGHT' => array(0), // без ограничений
                'RESTRICTIONS_SUM' => array(0), // без ограничений
            ),
        );
    }

    function Calculate($profileID, $arConfig, $arOrder, $STEP, $TEMP = false)
    {
        CModule::IncludeModule('sale');
        CModule::IncludeModule('iblock');

        if ($profileID == 'self') {
            return array(
                "RESULT" => "OK",
                "VALUE" => 0,
                "TRANSIT" => "",
            );
        }
		
		if ($profileID == 'express' && $arOrder['LOCATION_TO'] == 0) {
            return array(
                "RESULT" => "OK",
                "VALUE" => DoubleVal($arConfig['price_zone_' . $profileID . '_default']['VALUE']),
                "TRANSIT" => "",
            );
        }

        $arProfiles = CDeliveryMsc::__GetProfiles();
        if (!array_key_exists($profileID, $arProfiles)) {
            return array(
                "RESULT" => "ERROR",
                "TEXT" => "Не найден профиль доставки",
            );
        }

        $locationToID = IntVal($arOrder['LOCATION_TO']);
        if ($locationToID <= 0) {
            return array(
                "RESULT" => "ERROR",
                "TEXT" => "Не указан адрес доставки",
            );
        }

        $arLocation = CSaleLocation::GetByID($locationToID);
        $sAddress = $arLocation['CITY_NAME'] . ', ' . $arOrder['STREET'] . ', ' . $arOrder['HOUSE'];

        $arResult = array(
            "RESULT" => "OK",
            "VALUE" => 0,
            "TRANSIT" => "",
            "ADDRESS" => $sAddress,
            "ZONE_ID" => 'default',
            "ZONE" => '',
        );


        $sResponse = file_get_contents('http://catalog.api.2gis.ru/geo/search?q=' . urlencode($sAddress) . '&version=1.3&key=ruxlih0718');
        //$sResponse = file_get_contents('http://geocode-maps.yandex.ru/1.x/?geocode=' . urlencode($sAddress) . '&format=json');
        $oResponse = json_decode($sResponse, true);
        //echo '<pre>' . print_r($oResponse, true) . '</pre>';

        if ($oResponse['total'] > 0) {

            $sPoint = $oResponse['result'][0]['centroid'];
            $sPoint = str_replace(Array('POINT(', ')'), '', $sPoint);
            $arPoint = explode(' ', $sPoint);

            //echo '<pre>' . print_r($arPoint, true) . '</pre>';

            $sPoint = $arPoint[0] . ',' . $arPoint[1];

            $rsZones = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => IBLOCK_ID_DELIVERY_ZONES), false, false, Array('ID', 'NAME', 'PROPERTY_COORDS', 'PROPERTY_COLOR'));

            while ($arZone = $rsZones->Fetch()) {
                $arCoords = $arZone['PROPERTY_COORDS_VALUE'];

                foreach ($arCoords as $sCoords) {

                    $arRows = explode("\n", $sCoords);
                    $arPoints = Array();
                    foreach ($arRows as $sRow) {
                        $arRow = explode(",", trim($sRow));
                        if (count($arRow) == 3) {
                            $arPoints[] = $arRow[0] . ',' . $arRow[1];
                        }
                    }

                    if (self::isWithinBoundary($sPoint, $arPoints)) {
                        $arResult['ZONE_ID'] = $arZone['ID'];
                        $arResult['ZONE'] = $arZone['NAME'];
                        break;
                        break;
                    }

                }
            }
        }

        $arResult['VALUE'] = DoubleVal($arConfig['price_zone_' . $profileID . '_' . $arResult['ZONE_ID']]['VALUE']);
        //echo '<pre>' . print_r($arConfig, true) . '</pre>';
        return $arResult;
    }

    function Compability($arOrder, $arConfig)
    {
        $arProfiles = CDeliveryMsc::__GetProfiles();

        return array_keys($arProfiles);
    }


    function pointStringToCoordinates($pointString)
    {
        $coordinates = explode(",", $pointString);
        return array("x" => trim($coordinates[0]), "y" => trim($coordinates[1]));
    }

    function isWithinBoundary($point, $polygon)
    {
        $result = FALSE;
        $point = self::pointStringToCoordinates($point);
        $vertices = array();
        foreach ($polygon as $vertex) {
            $vertices[] = self::pointStringToCoordinates($vertex);
        }
        // Check if the point is inside the polygon or on the boundary
        $intersections = 0;
        $vertices_count = count($vertices);
        for ($i = 1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i - 1];
            $vertex2 = $vertices[$i];
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) {
                // This point is on an horizontal polygon boundary
                $result = TRUE;
                // set $i = $vertices_count so that loop exits as we have a boundary point
                $i = $vertices_count;
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) {
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];
                if ($xinters == $point['x']) { // This point is on the polygon boundary (other than horizontal)
                    $result = TRUE;
                    // set $i = $vertices_count so that loop exits as we have a boundary point
                    $i = $vertices_count;
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                    $intersections++;
                }
            }
        }
        // If the number of edges we passed through is even, then it's in the polygon.
        // Have to check here also to make sure that we haven't already determined that a point is on a boundary line
        if ($intersections % 2 != 0 && $result == FALSE) {
            $result = TRUE;
        }
        return $result;
    }
}

AddEventHandler('sale', 'onSaleDeliveryHandlersBuildList', array('CDeliveryMsc', 'Init'));