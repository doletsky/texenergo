<?php

class CAeroSaleOrder extends CBitrixComponent
{

    /**
     * @var array текущий пользователь
     */
    protected $arUser = Array();

    /**
     * @var int тип плательщика
     */
    protected $iPersonTypeID = 0;

    /**
     * @var int персональная скидка
     */
    protected $iPersonalDiscount = 0;

    /**
     * @var Array контрагент
     */
    protected $arCompany = Array();

    /**
     * @var array позиции корзины
     */
    protected $arBasket = Array();

    /**
     * @var array группы местоположений
     */
    protected $arLocationGroups = Array();

    /**
     * @var array профили покупателя
     */
    protected $arProfiles = Array();
    protected $arCurrentProfile = Array();

    /**
     * @var array типы доставки
     */
    protected $arDelivery = Array();

    /**
     * @var array способы оплаты
     */
    protected $arPayment = Array();

    /**
     * @var array созданный заказ
     */
    protected $arOrder = Array();

    /**
     * @var array сообщения об ошибках
     */
    protected $arErrors = Array();

    /**
     * @var int сумма заказа без учета доставки
     */
    protected $iOrderPrice = 0;

    /**
     * @var int сумма заказа с учетом скидки
     */
    protected $iOrderDiscountPrice = 0;

    /**
     * @var int вес заказа
     */
    protected $iOrderWeight = 0;

    public function onPrepareComponentParams($arParams)
    {
        //prepare params

        if (empty($arParams['DEFAULT_PERSON_TYPE'])) {
            $arParams['DEFAULT_PERSON_TYPE'] = SALE_PERSON_FIZ;
        }

        return $arParams;
    }

    public function executeComponent()
    {

        if (!CModule::IncludeModule("sale")) {
            ShowError("Не установлен модуль sale");
            return;
        }

        global $USER;


        $this->arUser = CUser::GetByID($USER->GetID())->Fetch();
        $this->iPersonTypeID = IntVal($this->arUser['UF_PAYER_TYPE']);
        if ($this->iPersonTypeID <= 0) {
            $this->iPersonTypeID = $this->arParams['DEFAULT_PERSON_TYPE'];
        }

        if (strlen($this->arUser['UF_COMPANY_ID']) > 0) {
            $this->arCompany = $this->getCompany($this->arUser['UF_COMPANY_ID']);
        }

        $this->arLocationGroups = $this->getLocationGroups();
        $this->arBasket = $this->getBasketItems();

        if (empty($this->arBasket) && $this->arParams['ONLY_CALCULATOR'] != 'Y') {
            LocalRedirect('/basket/');
        }

        $this->iOrderPrice = 0;
        $this->iOrderDiscountPrice = 0;
        $this->iOrderWeight = 0;

        foreach ($this->arBasket as $arItem) {
            $this->iOrderPrice += $arItem['QUANTITY'] * $arItem['PRICE'];
            $this->iOrderDiscountPrice += $arItem['QUANTITY'] * $arItem['DISCOUNT_PRICE'];
            $this->iOrderWeight += DoubleVal($arItem['WEIGHT']);
        }

        $this->arDelivery = $this->getDeliveryTypes();
        $this->arPayment = $this->getPaymentMethods();

        $this->arProfiles = $this->getSaleProfiles();


        // переменные шаблона
        $this->arResult['PERSON_TYPE_ID'] = $this->iPersonTypeID;
        $this->arResult['BASKET'] = $this->arBasket;
        $this->arResult['COMPANY'] = $this->arCompany;
        $this->arResult['DELIVERY'] = $this->arDelivery;
        $this->arResult['PAYMENT'] = $this->arPayment;
        $this->arResult['ORDER_PRICE'] = $this->iOrderPrice;
        $this->arResult['PROFILES'] = $this->arProfiles;
        $this->arResult['PROFILE'] = $this->arCurrentProfile;
        $this->arResult['USER'] = $this->arUser;
        $this->arResult['LOCATION_GROUPS'] = $this->arLocationGroups;
        $this->arResult['ERRORS'] = $this->arErrors;
        $this->arResult['STEP'] = 'delivery';
		$this->arResult['EXPRESS_DELIVERY_PRICE'] = $this->calculateDelivery('msc:express');
				
        //обработка запроса на удаление профиля
        if ($_REQUEST['delete_profile'] == 'yes') {
            $GLOBALS['APPLICATION']->RestartBuffer();
            $id = IntVal($_REQUEST['id']);

            foreach ($this->arProfiles as $arProfile) {
                if ($arProfile['ID'] == $id) {
                    CSaleOrderUserProps::Delete($id);
                    header("Content-type: text/javascript");
                    echo json_encode(Array('success' => true));
                    die();

                }
            }

            header("Content-type: text/javascript");
            echo json_encode(Array('success' => false));
            die();
        }

        // обработка запроса на расчет доставки
        if ($_REQUEST['calc_delivery'] == 'yes') {
            $GLOBALS['APPLICATION']->RestartBuffer();
            $sDeliveryID = trim($_REQUEST['DELIVERY_ID']);
            $iLocationID = IntVal($_REQUEST['LOCATION_ID']);
            $sZip = trim($_REQUEST['ZIP']);
            $sStreet = trim($_REQUEST['STREET']);
            $sHouse = trim($_REQUEST['HOUSE']);
            $arDelivery = $this->calculateDelivery($sDeliveryID, $iLocationID, $sZip, $sStreet, $sHouse);
            header("Content-type: text/javascript");
            echo json_encode($arDelivery);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && check_bitrix_sessid()) {
            $this->prepareOrder();
            $this->arResult['ORDER'] = $this->arOrder;
        }

        // обработка запроса на создание заказа
        if ($_REQUEST['create_order'] == 'yes' && check_bitrix_sessid()) {
            if (empty($this->arErrors)) {
                if (count($this->arPayment) > 1) {
                    $this->arResult['STEP'] = 'payment';
                    return $this->includeComponentTemplate('payment');
                } else {
                    $this->arResult['STEP'] = 'confirm';
                    return $this->includeComponentTemplate('confirm');
                }
            }
        }

        // обработка запроса на подтверждение заказа
        if ($_REQUEST['confirm_order'] == 'yes' && check_bitrix_sessid()) {
            $this->prepareOrder();
            $this->createOrder();
            $this->arResult['ORDER'] = $this->arOrder;

            if ($this->arOrder['ID'] > 0 && empty($this->arErrors)) {
                //LocalRedirect('/personal/orders/?ID=' . $this->arOrder['ID']);
				$this->arResult['STEP'] = 'ready';
				return $this->includeComponentTemplate('ready');
            }
        }


        $this->includeComponentTemplate();

    }

    /**
     * Возвращает массив позиций в корзине текущего пользователя
     * @return array
     */
    private function getBasketItems()
    {
        $fUserID = CSaleBasket::GetBasketUserID(True);
        $fUserID = IntVal($fUserID);

        $arResult = Array();

        $rsBasket = CSaleBasket::GetList(
            Array('name' => 'asc'),
            Array(
                'ORDER_ID' => 'NULL',
                'FUSER_ID' => $fUserID,
                'LID' => SITE_ID,
                'CAN_BUY' => 'Y',
                'DELAY' => 'N',
            )
        );

        while ($arItem = $rsBasket->GetNext()) {
            $arResult[] = $arItem;
        }
        return $arResult;
    }

    /**
     * Возвращает список профилей текущего пользователя
     * Для каждого профиля выбираются значения свойств и тип плательщика
     * @return array
     */
    private function getSaleProfiles()
    {
        $arResult = Array();
        $rsProfiles = CSaleOrderUserProps::GetList(Array('DATE_UPDATE' => 'desc'), Array(
            'USER_ID' => $this->arUser['ID'],
            'PERSON_TYPE_ID' => $this->iPersonTypeID,
        ));

        while ($arProfile = $rsProfiles->Fetch()) {
            $arProfile['PROPS'] = Array();
            $rsProps = CSaleOrderUserPropsValue::GetList(Array('SORT' => 'asc'), Array('USER_PROPS_ID' => $arProfile['ID']));
            while ($arProp = $rsProps->Fetch()) {
                if ($arProp['PROP_TYPE'] == 'LOCATION' && strlen($arProp['VALUE']) > 0) {
                    $arLocation = CSaleLocation::GetByID($arProp['VALUE']);
                    $arProp['LOCATION'] = $arLocation;
                }


                $arProfile['PROPS'][$arProp['PROP_CODE']] = $arProp;
            }

            $arProfile['PERSON'] = CSalePersonType::GetByID($arProfile['PERSON_TYPE_ID']);

            if ($arProfile['ID'] == $_REQUEST['PROFILE_ID']) {
                $arProfile['SELECTED'] = 'Y';


                foreach ($arProfile['PROPS'] as &$arProp) {
                    if (isset($_REQUEST['PROFILE'][$arProp['PROP_CODE']])) {
                        $arProp['VALUE'] = trim($_REQUEST['PROFILE'][$arProp['PROP_CODE']]);
                    }
                }

                $this->arCurrentProfile = $arProfile;
            }

            if (!empty($arProfile['PROPS']['DELIVERY_ID']['VALUE'])) {
                $arTmp = explode(':', $arProfile['PROPS']['DELIVERY_ID']['VALUE']);
                $arDeliveryHandler = CSaleDeliveryHandler::GetBySID($arTmp[0])->Fetch();
                $arProfile['DELIVERY'] = $arDeliveryHandler['PROFILES'][$arTmp[1]]['TITLE'] . ' (' . $arDeliveryHandler['NAME'] . ')';
                $arProfile['DELIVERY_ID'] = $arProfile['PROPS']['DELIVERY_ID']['VALUE'];
            }

            if (!empty($arProfile['PROPS']['PAYMENT_ID']['VALUE'])) {
                $arPayment = $this->arPayment[$arProfile['PROPS']['PAYMENT_ID']['VALUE']];
                $arProfile['PAYMENT'] = $arPayment['NAME'];
            }

            // пропускаем профили без адреса доставки и без службы доставки
            if (empty($arProfile['PROPS']['LOCATION_DELIVERY']['VALUE'])) continue;
            //if (!$arProfile['DELIVERY']) continue;


            $arResult[] = $arProfile;
        }

        return $arResult;
    }

    /**
     * Выбирает способы оплаты, актуальные для $this->iPersonTypeID
     * @return array
     */
    private function getPaymentMethods()
    {
        $arResult = Array();
        $rsMethods = CSalePaySystem::GetList(
            $arOrder = Array("SORT" => "ASC", "PSA_NAME" => "ASC"),
            Array("ACTIVE" => "Y", "PERSON_TYPE_ID" => $this->iPersonTypeID));

        while ($arMethod = $rsMethods->Fetch()) {
            $arResult[$arMethod['ID']] = $arMethod;
        }

        return $arResult;
    }


    /**
     * Наполняет $this->arOrder данными из запроса
     * Выполняется валидация знаечений, ошибки передаются в $this->arErrors
     *
     * @return bool
     */
    private function prepareOrder()
    {

        $arOrderProps = $this->getOrderProps();

        $deliveryID = trim($_POST['DELIVERY_ID']);

        if (empty($deliveryID)) $deliveryID = $this->arResult['PROPS_VALUES']['DELIVERY_ID'];

        $arFields = Array(
            "LID" => SITE_ID,
            "PAYED" => "N",
            "CANCELED" => "N",
            "STATUS_ID" => "N",
            "PERSON_TYPE_ID" => $this->iPersonTypeID,
            "USER_ID" => $this->arUser['ID'],
            "DELIVERY_ID" => $deliveryID,
            "CURRENCY" => "RUB",
        );

		$iLocationID = IntVal($_REQUEST['PROFILE']['LOCATION_DELIVERY']);
		$sZip = trim($_REQUEST['PROFILE']['ZIP_DELIVERY']);
		$sStreet = trim($_REQUEST['PROFILE']['STREET_DELIVERY']);
		$sHouse = trim($_REQUEST['PROFILE']['HOUSE_DELIVERY']);
		$arDeliveryPrice = $this->calculateDelivery($deliveryID, $iLocationID, $sZip, $sStreet, $sHouse);
		
		if ($arDeliveryPrice['RESULT'] == 'OK') {
            $arFields['PRICE_DELIVERY'] = $arDeliveryPrice['VALUE'];
            $arFields['ALLOW_DELIVERY'] = 'Y';
        } else {
            $arFields['ALLOW_DELIVERY'] = 'N';
        }

        if (count($this->arPayment) > 1) {
            $arFields['PAY_SYSTEM_ID'] = IntVal($_REQUEST['PAYMENT_ID']);
        } else {
            $arPayment = reset($this->arPayment);
            $arFields['PAY_SYSTEM_ID'] = $arPayment['ID'];
        }

        $arFields['PRICE'] = $this->iOrderPrice + DoubleVal($arFields['PRICE_DELIVERY']);
        $arFields['DISCOUNT_VALUE'] = $this->iOrderDiscountPrice;
        $arFields['PROPS'] = $arOrderProps;

        $arFields['USER_DESCRIPTION'] = trim(strip_tags($_REQUEST['USER_DESCRIPTION']));

        $this->arOrder = $arFields;
        return true;
    }

    /**
     * Создает новый заказ из запроса
     */
    private function createOrder()
    {
        if (empty($this->arOrder)) {
            $this->arErrors[] = 'Не удалось создать заказ. Не указаны обязательные поля.';
            return false;
        }

        $ORDER_ID = CSaleOrder::Add($this->arOrder);

        if (!$ORDER_ID) {
            $strError = 'Не удалось создать заказ.';
            if ($ex = $GLOBALS['APPLICATION']->GetException()) {
                $strError .= ' ' . $ex->GetString();
                $this->arErrors[] = $strError;
            }
            return;
        }

        /**
         * Записываем значения свойств заказа
         */
        foreach ($this->arOrder['PROPS'] as $arProp) {
            $arProp['ORDER_ID'] = $ORDER_ID;
            CSaleOrderPropsValue::Add($arProp);
        }

        $this->arResult['PROPS_VALUES']['DELIVERY_ID'] = $this->arOrder['DELIVERY_ID'];
        $this->arResult['PROPS_VALUES']['PAYMENT_ID'] = $this->arOrder['PAY_SYSTEM_ID'];

        $this->addSaleProfile($this->arResult['PROPS_VALUES']);

        $this->arOrder = CSaleOrder::GetByID($ORDER_ID);

        CSaleBasket::OrderBasket($ORDER_ID, CSaleBasket::GetBasketUserID(), SITE_ID);

    }

    /**
     * Создает новый профиль
     * @param $arPropsValues
     * @return bool
     */
    private function addSaleProfile($arPropsValues)
    {
        foreach ($this->arProfiles as $arProfile) {
            $bEquals = true;
            foreach ($arPropsValues as $sCode => $sValue) {
                if ($arProfile['PROPS'][$sCode]['VALUE'] != $sValue) {
                    $bEquals = false;
                    break;
                }
            }
            if ($bEquals) return false;
        }

        $rsProps = CSaleOrderProps::GetList(Array('sort' => 'asc'), Array(
            'ACTIVE' => 'Y',
            'PERSON_TYPE_ID' => $this->iPersonTypeID,
            //'USER_PROPS' => 'Y',
            //'UTIL' => 'N',
        ));


        while ($arProp = $rsProps->Fetch()) {
            $arProp['VALUE'] = $arPropsValues[$arProp['CODE']];
            $arProps[$arProp['CODE']] = $arProp;
        }

        $arFields = Array(
            'PERSON_TYPE_ID' => $this->iPersonTypeID,
            'USER_ID' => $this->arUser['ID'],
            'NAME' => (empty($this->arCompany['COMPANY']) ? $arPropsValues['NAME'] . ' ' . $arPropsValues['LAST_NAME'] : $this->arCompany['COMPANY']),
        );

        $iProfileId = CSaleOrderUserProps::Add($arFields);
        foreach ($arProps as $arProp) {
            CSaleOrderUserPropsValue::Add(Array(
                "USER_PROPS_ID" => $iProfileId,
                "ORDER_PROPS_ID" => $arProp['ID'],
                "NAME" => $arProp['NAME'],
                "VALUE" => $arProp['VALUE'],
            ));
        }


    }

    /**
     * Получает и проверяет свойства заказа из запроса
     * Если передан PROFILE_ID, то свойства бреутся из профиля
     * Если какие-то поля не заполнены, ошибки передаются в массив $this->arErrors
     *
     * @return bool|array false или Array(CODE=>VALUE) - массив значений свойств заказа
     */
    private function getOrderProps()
    {
        $arResult = Array();
        $arErrors = Array();

        $arPropsValues = $_REQUEST['PROFILE'];
        if (!is_array($arPropsValues)) $arPropsValues = Array();

        $rsProps = CSaleOrderProps::GetList(Array('sort' => 'asc'), Array(
            'ACTIVE' => 'Y',
            'PERSON_TYPE_ID' => $this->iPersonTypeID,
            'USER_PROPS' => 'Y',
            'UTIL' => 'N',
        ));


        /**
         * Если пользователь выбрал предыдущий профиль, выбираем из него адрес доставки
         */
        $iProfileID = IntVal($_REQUEST['PROFILE_ID']);
        if ($iProfileID > 0) {
            $arProfile = CSaleOrderUserProps::GetList(Array(), Array('USER_ID' => $this->arUser['ID'], 'ID' => $iProfileID), false, false, Array('ID'))->Fetch();
            if ($arProfile) {
                $arProfile['PROPS'] = Array();
                $rsValues = CSaleOrderUserPropsValue::GetList(array("ID" => "ASC"), Array("USER_PROPS_ID" => $arProfile['ID']));
                while ($arValue = $rsValues->Fetch()) {
                    $arPropsValues[$arValue['PROP_CODE']] = $arValue['VALUE'];
                }

            }
        }

        while ($arProp = $rsProps->Fetch()) {
            $arFields = Array(
                'ORDER_PROPS_ID' => $arProp['ID'],
                'NAME' => $arProp['NAME'],
                'CODE' => $arProp['CODE'],
                'VALUE' => $arPropsValues[$arProp['CODE']],
            );

            if ($arProp['REQUIRED'] == 'Y' && strlen($arFields['VALUE']) <= 0) {
                $arErrors[] = 'Поле "' . $arProp['NAME'] . '" обязательно к заполнению';
            }

            $arResult[] = $arFields;
        }
        $this->arResult['PROPS_VALUES'] = $arPropsValues;

        $this->arErrors = $arErrors;
        return $arResult;
    }

    /**
     * Возвращает массив групп местоположений
     * @return array
     */
    private function getLocationGroups()
    {
        CModule::IncludeModule('sale');
        $arResult = Array();
        $rsGroups = CSaleLocationGroup::GetList(Array("SORT" => "ASC"), array(), LANGUAGE_ID);
        while ($arGroup = $rsGroups->Fetch()) {
            if ($arGroup['ID'] == $_POST['GROUP_ID']) {
                $arGroup['SELECTED'] = 'Y';
            }
            $arResult[] = $arGroup;
        }
        return $arResult;
    }

    private function getDeliveryTypes()
    {

        $arDeliveryID = explode(':', $_REQUEST['DELIVERY_ID']);

        CModule::IncludeModule('sale');
        $rsTypes = CSaleDeliveryHandler::GetList(
            array(
                'SORT' => 'ASC',
            ),
            array(
                'ACTIVE' => 'Y'
            )
        );

        $arResult = Array();
        while ($arType = $rsTypes->Fetch()) {
//echo '<pre>'.print_r($arType, true).'</pre>';

            // логотипы транспортных компаний из инфоблока
            if ($arType['SID'] == 'other') {
                $rsCompanies = CIBlockElement::GetList(Array('sort' => 'asc', 'name' => 'asc'), Array('IBLOCK_ID' => IBLOCK_ID_DELIVERY_COMPANIES, 'ACTIVE' => 'Y'), false, false, Array('ID', 'CODE', 'PREVIEW_PICTURE'));
                while ($arCompany = $rsCompanies->Fetch()) {
                    if (array_key_exists($arCompany['CODE'], $arType['PROFILES'])) {
                        $arType['PROFILES'][$arCompany['CODE']]['LOGO'] = CFile::GetPath($arCompany['PREVIEW_PICTURE']);
                    }
                }
            }

            if ($arDeliveryID[0] == $arType['SID']) {
                $arType['SELECTED'] = 'Y';
                foreach ($arType['PROFILES'] as $sid => &$arProfile) {
                    if ($arDeliveryID[1] == $sid) {
                        $arProfile['SELECTED'] = 'Y';
                    }
                }
            }

            $arResult[$arType['SID']] = $arType;
        }
        return $arResult;
    }

    private function calculateDelivery($sDeliveryID, $iLocationID = 0, $sZip = '', $sStreet = '', $sHouse = '')
    {

        $arTmp = explode(':', $sDeliveryID);

        $sDeliverySID = $arTmp[0];
        $sDeliveryProfileSID = $arTmp[1];

        $arOrderTmp = array(
            "PRICE" => $this->iOrderPrice,
            "WEIGHT" => $this->iOrderWeight,
            "DIMENSIONS" => 0,
            "LOCATION_FROM" => COption::GetOptionInt('sale', 'location'),
            "LOCATION_TO" => $iLocationID,
            "LOCATION_ZIP" => $sZip,
            "STREET" => $sStreet,
            "HOUSE" => $sHouse,
            "ITEMS" => $this->arBasket,
        );

        return CSaleDeliveryHandler::CalculateFull($sDeliverySID, $sDeliveryProfileSID, $arOrderTmp, "RUB");
    }

    private function getCompany($ID)
    {

        $arProfile = CIBlockElement::GetList(
            Array(),
            Array('ACTIVE' => 'Y', '=ID' => $ID, 'IBLOCK_ID' => IBLOCK_ID_AGENTS),
            false, Array('nTopCount'),
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

            )
        )->GetNext(false, false);

        if ($arProfile['ID'] > 0) {
            $arResult = Array();
            $arResult['COMPANY'] = $arProfile['NAME'];
            foreach ($arProfile as $code => $value) {
                $key = $code;
                if (preg_match('/^PROPERTY_(.+)_VALUE$/', $key)) {
                    $key = preg_replace('/^PROPERTY_(.+)_VALUE$/', '$1', $key);
                    $arResult[$key] = htmlspecialcharsEx($value);
                }
            }
            return $arResult;
        }
        return Array();
    }

} 