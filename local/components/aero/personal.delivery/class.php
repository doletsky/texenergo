<?

class CAeroDelivery extends CBitrixComponent {

    public function onPrepareComponentParams($arParams) {
        /**
         * создаем доставку только по существующему заказу
         */
        if(!isset($arParams['IBLOCK_ID'])
           || !isset($arParams['BILLS_IBLOCK_ID'])
        ) {
            throw new \Bitrix\Main\ArgumentNullException("Iblock id not set");
        }

        return $arParams;
    }

    protected function checkModules() {
        if(!CModule::IncludeModule('iblock')) {
            ShowError('iblock module not installed');

            return false;
        }
        if(!CModule::IncludeModule('sale')) {
            ShowError('sale module not installed');

            return false;
        }

        return true;
    }

    public function executeComponent() {

        if(!$this->checkModules()) {
            return;
        }

        $billsList = $this->getBills();
        if(empty($billsList)) {
            return;
        }
        $this->arResult['BILL_LIST'] = $billsList;

        $this->arResult['USER'] = $this->getCurrentUserData();
        $this->arResult['ENUM_LISTS'] = $this->getPropertiesEnumList();

        if(isset($_POST['submit'])) {
            $this->handleSubmit();
        }
        else {
            $this->arResult['APP_ID'] = $this->getNextApplicationNumber();
        }

        if($this->getTemplateName() == "third_party_delivery") {
            $this->arResult['TK_LIST'] = $this->getDeliveryTypes();
        }

        $this->includeComponentTemplate();
    }

    protected function getCurrentUserData() {
        $user = CUser::GetById(CUser::GetId())->GetNext();

        /**
         * достаем контрагента
         */
        $rsAgent = CIBlockElement::GetList(array('ID' => 'DESC'),
                                           array(
                                               'IBLOCK_ID' => IBLOCK_ID_AGENTS,
                                               'ID' => $user['UF_COMPANY_ID']
                                           ),
                                           false,
                                           array('nTopCount' => 1));
        if($obAgent = $rsAgent->GetNextElement()) {
            $arAgent = $obAgent->GetFields();
            $arAgent['PROPERTIES'] = $obAgent->GetProperties();
            $this->arResult['AGENT'] = $arAgent;
        }

        return $user;
    }

    protected function getNextApplicationNumber() {
        $lastElement = CIBlockElement::GetList(array('ID' => 'DESC'),
                                               array('IBLOCK_ID' => $this->arParams['IBLOCK_ID']), false,
                                               array('nTopCount' => 1), array('ID',
                                                                              'IBLOCK_ID'))->Fetch();
        if(!$lastElement) {
            $lastElement['ID'] = 0;
        }

        return $lastElement['ID'] + 1;
    }

    protected function getPropertiesEnumList() {
        $enumLists = [];
        $rsPropList = CIBlockProperty::GetList(array('SORT' => 'ASC'),
                                               array('IBLOCK_ID'     => $this->arParams['IBLOCK_ID'],
                                                     'PROPERTY_TYPE' => 'L'));
        while($prop = $rsPropList->Fetch()) {
            $rsEnumList = CIBlockProperty::GetPropertyEnum($prop['ID'], array('SORT' => 'ASC'),
                                                           Array("IBLOCK_ID" => $this->arParams['IBLOCK_ID']));
            while($arEnumList = $rsEnumList->GetNext()) {
                $enumLists[$prop['CODE']][] = $arEnumList;
            }
        }

        return $enumLists;
    }

    private function getDeliveryTypes() {
        $rsTypes = CSaleDeliveryHandler::GetList(array('SORT' => 'ASC',), array('ACTIVE' => 'Y',
                                                                                'SID'    => 'other'));

        $arResult = Array();
        if($arType = $rsTypes->Fetch()) {
            // логотипы транспортных компаний из инфоблока
            $rsCompanies = CIBlockElement::GetList(Array('sort' => 'asc',
                                                         'name' => 'asc'),
                                                   Array('IBLOCK_ID' => IBLOCK_ID_DELIVERY_COMPANIES,
                                                         'ACTIVE'    => 'Y'), false, false, Array('ID',
                                                                                                  'CODE',
                                                                                                  'PREVIEW_PICTURE'));
            while($arCompany = $rsCompanies->Fetch()) {
                if(array_key_exists($arCompany['CODE'], $arType['PROFILES'])) {
                    $arType['PROFILES'][$arCompany['CODE']]['LOGO'] = CFile::GetPath($arCompany['PREVIEW_PICTURE']);
                }
            }
            $arResult = $arType['PROFILES'];
        }

        return $arResult;
    }

    protected function getBills() {
        $billsIds = $_REQUEST['bills'];
        $billsList = [];
        $rsBills = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $this->arParams['BILLS_IBLOCK_ID'],
                                                          'ID'        => $billsIds), false, false);
        while($obBill = $rsBills->GetNextElement()) {
            $arBill = $obBill->GetFields();
            $arBill['PROPERTIES'] = $obBill->GetProperties();

            $arBill['ORDER'] = CSaleOrder::GetByID($arBill['PROPERTIES']['ORDER_ID']['VALUE']);
            $billsList[] = $arBill;
        }

        return $billsList;
    }

    function handleSubmit() {

        $this->arResult['success'] = false;
        $sendAll = false;
        if(check_bitrix_sessid()) {
            foreach($_POST['fields'] as $key => $field) {
                if(!is_array($field)) {
                    if(strlen(trim($field)) > 0) {
                        $fields[strtoupper($key)] = htmlspecialchars(trim($field));
                    }
                }
                else {
                    foreach($field as $value) {
                        if(strlen(trim($value)) > 0) {
                            $fields[strtoupper($key)][] = htmlspecialchars(trim($value));
                        }
                    }
                }
            }

            if(is_uploaded_file($_FILES['route_file']['tmp_name'])) {
                $fields['ROUTE_FILE'] = $_FILES;
            }

            $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"  => "DESC",
                                                                 "SORT" => "ASC"),
                                                           Array("IBLOCK_ID" => $this->arParams['IBLOCK_ID'],
                                                                 "CODE"      => "DELIVERY_TYPE",
                                                                 'XML_ID'    => $_POST['template']));
            if($enum_fields = $property_enums->GetNext()) {
                $fields['DELIVERY_TYPE'] = $enum_fields["ID"];
            }

            // поле в обычной доставке
            if(isset($fields['DELIVERY'])) {
                $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"  => "DESC",
                                                                     "SORT" => "ASC"),
                                                               Array("IBLOCK_ID" => $this->arParams['IBLOCK_ID'],
                                                                     'ID'        => $fields['DELIVERY']));
                if($enum_fields = $property_enums->GetNext()) {
                    $sendAll = !!($enum_fields['XML_ID'] == "send_all");
                }
            }

            $fields['FIO'] = CUser::GetFullName();
            $fields['CUSTOMER'] = $this->arResult['USER']['ID'];
            $fields['ORG_NAME'] = $this->arResult['AGENT']['NAME'];
            $fields['EMAIL'] = $this->arResult['AGENT']['PROPERTIES']['EMAIL']['VALUE'];
            $fields['PHONE'] = $this->arResult['AGENT']['PROPERTIES']['PHONE']['VALUE'];
            $fields['KPP'] = $this->arResult['AGENT']['PROPERTIES']['KPP']['VALUE'];
            $fields['INN'] = $this->arResult['AGENT']['PROPERTIES']['INN']['VALUE'];

            if(is_array($fields['EMAIL'])) {
                $fields['EMAIL'] = implode(", ", $fields['EMAIL']);
            }
            if(is_array($fields['PHONE'])) {
                $fields['PHONE'] = implode(", ", $fields['PHONE']);
            }


            $fields = array('NAME'            => 'Доставка '.$this->arResult['USER']['LOGIN']." ".date('d-m-Y H:i'),
                            'ACTIVE'          => 'Y',
                            'IBLOCK_ID'       => $this->arParams['IBLOCK_ID'],
                            'PROPERTY_VALUES' => $fields);

            $emailFields = $fields['PROPERTY_VALUES'];

            if(isset($emailFields['SEND_DOCUMENTS'])) {
                foreach($this->arResult['ENUM_LISTS']['SEND_DOCUMENTS'] as $val) {
                    if($emailFields['SEND_DOCUMENTS'] == $val['ID']) {
                        $emailFields['SEND_DOCUMENTS_ADDRESS'] = $val['VALUE'];
                        break;
                    }
                }
            }

            if(strlen($fields['PROPERTY_VALUES']['SEND_DOCUMENTS_ADDRESS']) > 0){
                $emailFields['SEND_DOCUMENTS_ADDRESS'] = $fields['PROPERTY_VALUES']['SEND_DOCUMENTS_ADDRESS'];
            }

            $emailFields['CONTACT_ADDRESS'] = implode(", ",
                                                      array($this->arResult['AGENT']['PROPERTIES']['CITY_LEGAL']['VALUE'],
                                                            $this->arResult['AGENT']['PROPERTIES']['STREET_LEGAL']['VALUE'],
                                                            $this->arResult['AGENT']['PROPERTIES']['HOUSE_LEGAL']['VALUE'],
                                                            $this->arResult['AGENT']['PROPERTIES']['HOUSING_LEGAL']['VALUE'],
                                                            $this->arResult['AGENT']['PROPERTIES']['OFFICE_LEGAL']['VALUE'],
                                                            $this->arResult['AGENT']['PROPERTIES']['STAGE_LEGAL']['VALUE'],));

            $newEl = new CIBlockElement;
            if($id = $newEl->Add($fields)) {
                try {
                    $billNames = [];
                    foreach($this->arResult['BILL_LIST'] as $bill) {
                        $billNames[] = $bill['NAME'];
                    }
                    $emailFields['BILLS'] = $billNames;
                    $emailFields['SEND_ALL'] = $sendAll;
                    $filePath = aero\CDeliveryDocGenerator::generateDoc($emailFields, $_POST['template']);
                    $fileName = basename($filePath);
                    $emailFields['ATTACH_FILES'] = $filePath.'=>'.$fileName.';';

                    $ev = new CEvent;
                    require_once($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/include/helpers/custom_mail.php');
                    $ev->SendImmediate('NEW_PERSONAL_DELIVERY', 's1', $emailFields);
                    unset($this->arResult['BILL_LIST'], $this->arResult['ENUM_LISTS'], $this->arResult['AGENT'], $this->arResult['USER']);
                    $this->arResult['redirect'] = '/personal/orders/delivery/';
                    $this->arResult['success'] = true;
                }
                catch(Exception $ex) {
                    $this->arResult['message'] = $ex->getMessage();
                }
            }
            else {
                $this->arResult['message'] = $newEl->LAST_ERROR;
            }
        }
        else {
            $this->arResult['message'] = 'Сессия не верна';
        }

        $this->setTemplateName('ajax');
    }
}