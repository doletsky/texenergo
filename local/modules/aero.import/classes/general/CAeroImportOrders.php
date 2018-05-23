<?php

require_once dirname(__FILE__) . '/CAeroImportCommon.php';

/**
 * Class CAeroImportOrders
 *
 * Импорт заказов производится при помощи вызова метода Import(filePath)
 *
 * Пример:
 *   $sDataFile = $_SERVER['DOCUMENT_ROOT'] . '/upload/import/order.xml';
 *   if (file_exists($sDataFile)) {
 *       $oImport = new CAeroImportOrders($sDataFile);
 *       $oImport->import();
 *   }
 */
class CAeroImportOrders extends CAeroImportCommon
{

    public function Import()
    {
        $arOrders = Array();
        if ($this->oXml->order) {
            foreach ($this->oXml->order as $oOrder) {
                $arOrders[] = $this->parseOrder($oOrder);
            }
        } else {
            $arOrders[] = $this->parseOrder($this->oXml);
        }

        foreach ($arOrders as $arOrder) {
            //echo '<pre>' . print_r($arOrder, true) . '</pre>';
            if (!$this->saveOrder($arOrder)) {
                return false;
            }
        }
        return true;
    }

    private function parseOrder(SimpleXMLElement $oXml)
    {
        $arUser = $this->parseUser($oXml->user);
        $arCompany = $this->parseCompany($oXml->user, $arUser['ID']);

        $arFields = Array(
            'USER_ID' => $arUser['ID'],
            "LID" => SITE_ID,
            'CURRENCY' => 'RUB',
            'PERSON_TYPE_ID' => (empty($arCompany) ? SALE_PERSON_FIZ : SALE_PERSON_YUR),
            'ACCOUNT_NUMBER' => (string)$oXml->id,
            'DATE_INSERT' => (string)$oXml->date,
            'COMMENTS' => (string)$oXml->comment,
            'PRICE' => DoubleVal(preg_replace('/[\'\s]/i', '', (string)$oXml->summary))
        );

        $arBasket = $this->parseBasket($oXml->orderData, $arUser['ID']);

        return Array(
            'ORDER' => $arFields,
            'BASKET' => $arBasket,
            'USER' => $arUser,
            'COMPANY' => $arCompany,
        );
    }

    private function parseBasket(SimpleXMLElement $oXml, $iUserID)
    {
        $arBasket = Array();

        foreach ($oXml->product as $oProduct) {

            $sXmlId = (string)$oProduct->code;

            $arItem = Array(
                'FUSER_ID' => $this->getFuserID($iUserID),
                'NAME' => (string)$oProduct->name,
                'QUANTITY' => (int)$oProduct->quantity,
                'PRICE' => (double)$oProduct->price,
                'PRODUCT_XML_ID' => $sXmlId,
                'PRODUCT_ID' => preg_replace('/[^\d]/', '', $sXmlId),
                'CAN_BUY' => 'Y',
                'CURRENCY' => 'RUB',
                'LID' => SITE_ID,
            );

            $arProduct = $this->catalog->getElementByXmlID($sXmlId, $this->IBLOCK_PRODUCTS);

            if ($arProduct) {
                $arItem['NAME'] = $arProduct['NAME'];
                $arItem['PRODUCT_ID'] = $arProduct['ID'];
                $arItem['DETAIL_PAGE_URL'] = $arProduct['DETAIL_PAGE_URL'];
            }

            $arBasket[] = $arItem;
        }

        return $arBasket;
    }


    private function parseCompany(SimpleXMLElement $oXml, $iUserID)
    {
        global $USER_FIELD_MANAGER;

        if (strlen($oXml->INN) > 0) {
            $arCompany = Array(
                'NAME' => (string)$oXml->company,
                'XML_ID' => (string)$oXml->INN,
                'IBLOCK_ID' => IBLOCK_ID_AGENTS,
                'PROPERTY_VALUES' => Array(
                    // юридический адрес
                    'CITY_LEGAL' => (string)$oXml->jurCity,
                    'STREET_LEGAL' => (string)$oXml->jurStreet,
                    'ZIP_LEGAL' => (string)$oXml->jurZip,
                    'HOUSE_LEGAL' => (string)$oXml->jurBuilding,
                    'HOUSING_LEGAL' => (string)$oXml->jurHousing,
                    'OFFICE_LEGAL' => (string)$oXml->jurOffice,
                    'STAGE_LEGAL' => (string)$oXml->jurFloor,

                    // фактический адрес
                    'CITY_ACTUAL' => (string)$oXml->factCity,
                    'STREET_ACTUAL' => (string)$oXml->factStreet,
                    'ZIP_ACTUAL' => (string)$oXml->factZip,
                    'HOUSE_ACTUAL' => (string)$oXml->factBuilding,
                    'HOUSING_ACTUAL' => (string)$oXml->factHousing,
                    'OFFICE_ACTUAL' => (string)$oXml->factOffice,
                    'STAGE_ACTUAL' => (string)$oXml->factFloor,

                    // реквизиты
                    'BANK' => (string)$oXml->Bank,
                    'BIK' => (string)$oXml->BIK,
                    'INN' => (string)$oXml->INN,
                    'KPP' => (string)$oXml->KPP,
                    'ACCOUNT_COR' => (string)$oXml->bankBillNo,
                    'ACCOUNT' => (string)$oXml->billNo,

                    // контакты
                    'PHONE' => (string)$oXml->phone,
                    'FAX' => (string)$oXml->fax,
                    'EMAIL' => (string)$oXml->email,

                ),
            );

            if (strlen($arCompany['PROPERTY_VALUES']['CITY_LEGAL']) > 0) {
                $arLocation = CSaleLocation::GetList(Array(), Array('CITY_NAME' => $arCompany['PROPERTY_VALUES']['CITY_LEGAL']), false, Array('nTopCount' => 1))->Fetch();
                if ($arLocation) {
                    $arCompany['PROPERTY_VALUES']['LOCATION_LEGAL'] = $arLocation['ID'];
                }
            }

            if (strlen($arCompany['PROPERTY_VALUES']['CITY_ACTUAL']) > 0) {
                $arLocation = CSaleLocation::GetList(Array(), Array('CITY_NAME' => $arCompany['PROPERTY_VALUES']['CITY_ACTUAL']), false, Array('nTopCount' => 1))->Fetch();
                if ($arLocation) {
                    $arCompany['PROPERTY_VALUES']['LOCATION_ACTUAL'] = $arLocation['ID'];
                }
            }

            if (!$arCompany['ID'] = $this->catalog->updateElement($arCompany)) {
                if ($ex = $GLOBALS['APPLICATION']->GetException()) {
                    $strError = $ex->GetString();
                    $this->log('Не удалось обновить контрагента: ' . $strError);
                    return Array();
                }
            }

            $USER_FIELD_MANAGER->Update('USER', $iUserID, Array(
                'UF_INN_CONFIRM' => '',
                'UF_INN' => $arCompany['XML_ID'],
            ));

            return $arCompany;
        }
        return Array();
    }

    private function parseUser(SimpleXMLElement $oXml)
    {
        $arUser = Array(
            'ACTIVE' => 'Y',
            'NAME' => (string)$oXml->firstname,
            'LAST_NAME' => (string)$oXml->lastname,
            'SECOND_NAME' => (string)$oXml->secondname,
            'EMAIL' => (string)$oXml->email,
            'LOGIN' => (string)$oXml->email,
            'WORK_PHONE' => (string)$oXml->phone,
            'WORK_FAX' => (string)$oXml->fax,
        );

        $arExists = CUser::GetList(($by = "ID"), ($order = "desc"), Array('EMAIL' => $arUser['EMAIL']), Array('FIELDS' => Array('ID')))->Fetch();

        if ($arExists) {
            $arUser['ID'] = $arExists['ID'];
        } else {
            $obj = new CUser();

            $sPassword = substr(str_shuffle(strtolower(sha1(rand() . time() . "texenergo"))), 0, 8);

            $arUser['PASSWORD'] = $sPassword;
            $arUser['CONFIRM_PASSWORD'] = $sPassword;

            $arUser['ID'] = $obj->Add($arUser);

            unset($arUser['PASSWORD']);
            unset($arUser['CONFIRM_PASSWORD']);
        }


        return $arUser;
    }

    private function saveOrder(Array $arFields)
    {
        $arFilter = Array(
            "ACCOUNT_NUMBER" => $arFields['ORDER']['ACCOUNT_NUMBER'],
        );

        $arExisting = CSaleOrder::GetList(array("DATE_INSERT" => "ASC"), $arFilter)->Fetch();

        if (!$arExisting) {
            if (!$orderID = CSaleOrder::Add($arFields['ORDER'])) {
                $strError = "Не удалось создать заказ";
                if ($ex = $GLOBALS['APPLICATION']->GetException()) {
                    $strError .= ' ' . $ex->GetString();
                    $this->log($strError);
                    $this->log($arFields['ORDER']);
                }
                return false;
            }

            CSaleOrder::Update($orderID, array('UPDATED_1C' => 'Y'));
        } else {
            $orderID = $arExisting['ID'];
            $arFields['ORDER']['UPDATED_1C'] = 'Y';
            if (!CSaleOrder::Update($orderID, $arFields['ORDER'])) {
                $strError = "Не удалось обновить заказ";
                if ($ex = $GLOBALS['APPLICATION']->GetException()) {
                    $strError .= ' ' . $ex->GetString();
                    $this->log($strError);
                    $this->log($arFields['ORDER']);
                }
                return false;
            }
        }

        if ($orderID > 0) {
            if (!$this->saveOrderBasket($orderID, $arFields['BASKET'])) {
                $strError = "Не удалось сохранить корзину заказа";
                if ($ex = $GLOBALS['APPLICATION']->GetException()) {
                    $strError .= ' ' . $ex->GetString();
                    $this->log($strError);
                }
                $this->log($arFields['ORDER']);
                return false;
            }
        }

        return true;
    }

    private function saveOrderBasket($orderID, $arBasket)
    {
        foreach ($arBasket as $arItem) {
            $arItem['ORDER_ID'] = $orderID;
            $arExisting = CSaleBasket::GetList(Array(), Array('ORDER_ID' => $orderID, 'PRODUCT_XML_ID' => $arItem['PRODUCT_XML_ID']), false, Array('nTopCount' => 1))->Fetch();
            if (!$arExisting) {
                $bResult = CSaleBasket::Add($arItem);
            } else {
                $bResult = CSaleBasket::Update($arExisting['ID'], $arItem);
            }

            if (!$bResult) {
                $strError = "Не удалось записать позицию заказа";
                if ($ex = $GLOBALS['APPLICATION']->GetException()) {
                    $strError .= ' ' . $ex->GetString();
                    $this->log($strError);
                    $this->log($arItem);
                }
                return false;
            }
        }
        return true;
    }

    private function getFuserID($USER_ID)
    {
        $FUSER_ID = CSaleUser::GetList(array('USER_ID' => $USER_ID));
        if (!$FUSER_ID['ID'])
            $FUSER_ID['ID'] = CSaleUser::_Add(array("USER_ID" => $USER_ID));
        return $FUSER_ID['ID'];
    }

}