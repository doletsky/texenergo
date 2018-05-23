<?php

require_once dirname(__FILE__) . '/CAeroImportCommon.php';

class CAeroExportOrders
{

    protected $orderID;

    protected $logPath;

    protected $filePath;

    public function __construct($orderID, $filePath = 'upload/orders')
    {
        CModule::IncludeModule('sale');
        CModule::IncludeModule('iblock');
        $this->orderID = IntVal($orderID);

        $this->filePath = $filePath;

        $logDir = $filePath . '/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        $this->logPath = $logDir . '/export-' . $orderID . '.log';
    }

    public function Export()
    {
        $this->log("Выгрузка заказа " . $this->orderID);

        $arOrder = $this->getOrderArray($this->orderID);

        if ($arOrder) {
            $sXml = $this->getOrderXML($arOrder);
            file_put_contents($this->filePath . '/' . date('Y-m-d-H-i-s') . '-OR-' . $this->orderID . '.xml', $sXml);
            return true;
        }
        return false;
    }

    private function getOrderArray($orderID)
    {
        $arResult = Array();

        $arOrder = CSaleOrder::GetByID($orderID);

        if (!$arOrder) {
            $this->log("Ошибка. Заказ не найден.");
            return false;
        }

        $arResult = Array(
            'id' => $arOrder['ID'],
            'date' => $this->prepareDate($arOrder['DATE_INSERT']),
            'user' => $this->getUserArray($arOrder['USER_ID']),
            'summary' => $arOrder['PRICE'],
            'comment' => htmlspecialchars($arOrder['USER_DESCRIPTION']),
            'iscanceled' => ($arOrder['CANCELED'] == 'Y') ? 1 : 0,
            'orderData' => Array('&product' => $this->getOrderBasket($orderID)),
        );

        $arProps = $this->getOrderProperties($orderID);

        // берем телефон из указанного при заказе
        if(strlen($arProps['PHONE']['VALUE']) > 0){
            $arResult['user']['phone'] = $arProps['PHONE']['VALUE'];
        }
        /**
         * Адрес доставки
         */
        $arLocation = CSaleLocation::GetByID($arProps['LOCATION_DELIVERY']['VALUE']);
        $arResult['deliveryCity'] = $arLocation['CITY_NAME'];
        $arResult['deliveryStreet'] = $arProps['STREET_DELIVERY']['VALUE'];
        $arResult['deliveryZip'] = $arProps['ZIP_DELIVERY']['VALUE'];
        $arResult['deliveryBuilding'] = $arProps['HOUSE_DELIVERY']['VALUE'];
        $arResult['deliveryHousing'] = $arProps['HOUSING_DELIVERY']['VALUE'];
        $arResult['deliveryOffice'] = $arProps['OFFICE_DELIVERY']['VALUE'];
        $arResult['deliveryFloor'] = $arProps['STAGE_DELIVERY']['VALUE'];

        $arResult['deliverySumma'] = $arOrder['PRICE_DELIVERY'];
	
	if ($arOrder['ALLOW_DELIVERY']  == 'Y') {
		$deliveryId = explode(":", $arOrder['DELIVERY_ID']);
        $arResult['deliveryService'] = $deliveryId[0];
         if ($arResult['deliveryService'] == 'other') {
            $deliveryCompanyCode = $deliveryId[1];
            $rsCompanies = CIBlockElement::GetList(Array(), Array('=IBLOCK_ID' => IBLOCK_ID_DELIVERY_COMPANIES, 'ACTIVE' => 'Y', '=CODE' => $deliveryCompanyCode), false, false, Array('ID','CODE','NAME'));
            while ($arCompany = $rsCompanies->Fetch()) {
                $arProp = CIBlockElement::GetProperty(IBLOCK_ID_DELIVERY_COMPANIES, $arCompany['ID'], "sort", "asc", array('CODE'=>'CODE1C'));
                $arResult['deliveryСompanyCode'] = $deliveryCompanyCode;
                $arResult['deliveryСompanyName'] = htmlspecialchars($arCompany['NAME']);
                $arResult['deliveryСompanyCode1c'] = $arProp->arResult[0]['VALUE'];
            }
         } else {
            $arResult['deliveryServiceType'] = $deliveryId[1];
         }
	}


        return $arResult;
    }

    private function getUserArray($userID)
    {

        $arUser = CUser::GetByID($userID)->Fetch();

        $arResult = Array(
            'firstname' => $arUser['NAME'],
            'lastname' => $arUser['LAST_NAME'],
            'secondname' => $arUser['SECOND_NAME'],
            'phone' => $arUser['PERSONAL_PHONE'],
            'email' => $arUser['EMAIL'],
        );

        if (strlen($arUser['UF_COMPANY_ID']) > 0) {

            $arCompany = CIBlockElement::GetList(Array(),
                Array('IBLOCK_ID' => IBLOCK_ID_AGENTS, 'ACTIVE' => 'Y', '=ID' => $arUser['UF_COMPANY_ID']),
                false, Array('nTopCount' => 1),
                Array(
                    'ID',
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

                    // контакты
                    'PROPERTY_PHONE',
                    'PROPERTY_FAX',
                    'PROPERTY_EMAIL',

                    'PROPERTY_ID_1C',

                ))->Fetch();

            $arResult['company'] = $arCompany['NAME'];
            $arResult['id_1c'] = $arCompany['PROPERTY_ID_1C_VALUE'];

            /**
             * Юридический адрес
             */
            $arLocation = CSaleLocation::GetByID($arCompany['PROPERTY_LOCATION_LEGAL_VALUE']);
            $arResult['jurCity'] = $arLocation['CITY_NAME'];
            $arResult['jurStreet'] = $arCompany['PROPERTY_STREET_LEGAL_VALUE'];
            $arResult['jurZip'] = $arCompany['PROPERTY_ZIP_LEGAL_VALUE'];
            $arResult['jurBuilding'] = $arCompany['PROPERTY_HOUSE_LEGAL_VALUE'];
            $arResult['jurHousing'] = $arCompany['PROPERTY_HOUSING_LEGAL_VALUE'];
            $arResult['jurOffice'] = $arCompany['PROPERTY_OFFICE_LEGAL_VALUE'];
            $arResult['jurFloor'] = $arCompany['PROPERTY_STAGE_LEGAL_VALUE'];
            $arResult['jurAdditional'] = $arCompany['PROPERTY_COMMENT_LEGAL_VALUE'];


            /**
             * Фактический адрес
             */
            $arLocation = CSaleLocation::GetByID($arCompany['PROPERTY_LOCATION_ACTUAL_VALUE']);
            $arResult['factCity'] = $arLocation['CITY_NAME'];
            $arResult['factStreet'] = $arCompany['PROPERTY_STREET_ACTUAL_VALUE'];
            $arResult['factZip'] = $arCompany['PROPERTY_ZIP_ACTUAL_VALUE'];
            $arResult['factBuilding'] = $arCompany['PROPERTY_HOUSE_ACTUAL_VALUE'];
            $arResult['factHousing'] = $arCompany['PROPERTY_HOUSING_ACTUAL_VALUE'];
            $arResult['factOffice'] = $arCompany['PROPERTY_OFFICE_ACTUAL_VALUE'];
            $arResult['factFloor'] = $arCompany['PROPERTY_STAGE_ACTUAL_VALUE'];
            $arResult['factAdditional'] = $arCompany['PROPERTY_COMMENT_ACTUAL_VALUE'];

            $arResult['INN'] = $arCompany['PROPERTY_INN_VALUE'];
            $arResult['KPP'] = $arCompany['PROPERTY_KPP_VALUE'];
            $arResult['BIK'] = $arCompany['PROPERTY_BIK_VALUE'];
            $arResult['billNo'] = $arCompany['PROPERTY_ACCOUNT_VALUE'];
            $arResult['Bank'] = $arCompany['PROPERTY_BANK_VALUE'];
            $arResult['bankBillNo'] = $arCompany['PROPERTY_ACCOUNT_COR_VALUE'];
            $arResult['phone'] = $arCompany['PROPERTY_PHONE_VALUE'];
            $arResult['fax'] = $arCompany['PROPERTY_FAX_VALUE'];

        }

        foreach($arResult as $key=>$val){
            $arResult[$key] = htmlspecialchars($val);
        }

        return $arResult;
    }

    protected function log($mess = '', $bResetLog = false)
    {
        if (is_array($mess) || is_object($mess)) {
            $mess = print_r($mess, true);
        }

        //echo "{$mess}\n";

        $mess = date('Y-m-d H:i:s') . ": " . $mess;

        if ($bResetLog) {
            @file_put_contents($this->logPath, $mess . "\n");
        } else {
            @file_put_contents($this->logPath, $mess . "\n", FILE_APPEND);
        }
    }


    public function getOrderXML($arOrder)
    {


        $arOrdersXML = Array(
            'order' => $arOrder,
        );


        $sXmlHead = '<?xml version="1.0" encoding="UTF-8"?>';
        return $sXmlHead . $this->arrayToXMLStr($arOrdersXML);
    }


    private function prepareDate($dateDB)
    {
        $timestamp = strtotime($dateDB);
        return date('d.m.Y H:i:s', $timestamp);
    }

    /**
     * Возвращает массива значений свойств заказа
     * Ключами массива являются мнемонические коды свойств
     * @param $orderID номер заказа
     * @return array
     */
    private function getOrderProperties($orderID)
    {
        $rsProps = CSaleOrderPropsValue::GetList(Array(), Array("ORDER_ID" => $orderID));
        $arProps = Array();
        while ($arProp = $rsProps->Fetch())
            $arProps[$arProp["CODE"]] = $arProp;

        return $arProps;
    }

    /**
     * Возвращает массив с элементами корзины заказа
     * Для каждого элемента определяется IBLOCK_ID и доп св-ва для веб-сервиса: VARIANT_CODE и VARIANT_NAME
     * Ключами массива являются ID позиций
     * @param $orderID номер заказа
     * @return array
     */
    private function getOrderBasket($orderID)
    {
        $rsBasket = CSaleBasket::GetList(Array(), Array("ORDER_ID" => $orderID));
        $arBasket = Array();
        while ($arBasketItem = $rsBasket->Fetch()) {

            $arProduct = CIBlockElement::GetByID($arBasketItem['PRODUCT_ID'])->Fetch();

            $arItem = Array(
                'id' => $arBasketItem['ID'],
                'code' => $arProduct['XML_ID'],
                'name' => htmlspecialchars($arBasketItem['NAME']),
                'quantity' => $arBasketItem['QUANTITY'],
                'price' => $arBasketItem['PRICE'],
                'summa' => $arBasketItem['PRICE'] * $arBasketItem['QUANTITY'],
            );
            $arBasket[] = $arItem;
        }
        return $arBasket;
    }

    /**
     * Преобразует массив в xml-строку
     *
     * Если название ключа начинается с &, то его элементы будут добавлены на первый уровень
     * Индексированные массивы именуются по родительскому ключу
     *
     * @param array $array
     * @param string $parentKey
     * @return string
     */
    private function arrayToXMLStr(Array $array = Array(), $parentKey = '')
    {
        $strXml = '';
        foreach ($array as $key => $item) {
            if (is_integer($key)) $key = $parentKey;

            if (is_array($item)) {
                if ($key[0] == '&') {
                    $key = substr($key, 1);
                    $strXml .= $this->arrayToXMLStr($item, $key);
                } else {
                    $strXml .= "<{$key}>" . $this->arrayToXMLStr($item, $key) . "</$key>";
                }
            } else {
                $strXml .= "<{$key}>{$item}</$key>";
            }
        }
        return $strXml;
    }
}