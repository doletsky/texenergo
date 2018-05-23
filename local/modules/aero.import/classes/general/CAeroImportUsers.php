<?php
class CAeroImportUsers {

    const DBHOST = "test.texenergo.ru";
    const DBLOGIN = "aero";
    const DBPASS = "dQVhjVuZZU";
    const DBNAME = "texenergo";

    public $allCompany = array();

    public function __construct() {

        $this->allCompany = $this->getCompany();

    }

    public function Import($date) {

        CModule::IncludeModule("iblock");

        $user = new CUser;
        $el = new CIBlockElement;

        $mysqli = new mysqli(self::DBHOST, self::DBLOGIN, self::DBPASS, self::DBNAME);

        if ($mysqli->connect_error) {
            die('Connect Error (' . mysqli_connect_errno() . ') '
                . mysqli_connect_error());
        }

        $sql = "SELECT name, email, data, date FROM eOffice_user_data WHERE  date>='".$date."'";

        if ($result = $mysqli->query($sql)) {

            while($obj = $result->fetch_object()){

                //echo $obj->data;

                if ($obj->data != "") {
                    $obj->data = mb_convert_encoding($obj->data, 'UTF-8');
                    $obj->data = str_replace("<?xml version=\"1.0\" encoding=\"WINDOWS-1251\"?>", "", $obj->data);
                    $data = simplexml_load_string($obj->data);

                    if (isset($data->companyName) && $data->companyName != "") {

                        $INN = trim((string)$data->INN);
                        $KPP = trim((string)$data->KPP);

                        if ($KPP == "нет" || $KPP == "" || $KPP == "-") {
                            $KPP = 0;
                        }

                        $arEmail = explode(",", $obj->email);
                        $arValidEmail = array();

                        foreach ($arEmail as $email) {
                            $email = trim($email);
                            if (filter_var($email, FILTER_VALIDATE_EMAIL))
                                $arValidEmail[] = $email;
                        }

                        if (count($arValidEmail) > 0) {

                            if (isset($this->allCompany[$INN."-".$KPP])) {
                                $COMPANY_ID = $this->allCompany[$INN."-".$KPP];
                            } else {
                                $PROPERTY = array(
                                    "COMMENT_LEGAL" =>(string)$data->jurAddress,
                                    "COMMENT_ACTUAL" =>(string)$data->factAddress,
                                    "BANK" =>(string)$data->Bank,
                                    "BIK" =>(string)$data->BIK,
                                    "INN" =>(string)$data->INN,
                                    "KPP" =>(string)$data->KPP,
                                    "ACCOUNT_COR" =>(string)$data->bankBillNo,
                                    "ACCOUNT" =>(string)$data->billNo,
                                    "PHONE" =>(string)$data->phone,
                                    "FAX" =>(string)$data->fax,
                                    "EMAIL" => $arValidEmail,
                                    //"ID_1C" =>(string)$data->id-1c
                                );

                                $arFields = Array(
                                    "MODIFIED_BY"    => 1,
                                    "IBLOCK_SECTION_ID" => false,
                                    "IBLOCK_ID"      => IBLOCK_ID_AGENTS,
                                    "PROPERTY_VALUES"=> $PROPERTY,
                                    "NAME"           => (string)str_replace("&quot;", "", $data->companyName),
                                    "ACTIVE"         => "Y",
                                );

                                if ($COMPANY_ID = $el->Add($arFields)) {

                                    $this->setCompany($INN, $KPP, $COMPANY_ID);

                                }

                            }

                            foreach ($arValidEmail as $email) {
                                $rsUser = $user->GetByLogin($email);

                                if (!$arUser = $rsUser->Fetch()) {
                                    if($COMPANY_ID > 0) {
                                        $arUserFields = Array(
                                            "NAME" => (string)$data->firstName,
                                            "LAST_NAME" => (string)$data->family,
                                            "SECOND_NAME" => (string)$data->secondName,
                                            "LOGIN" => $email,
                                            "EMAIL" => $email,
                                            "PASSWORD" => md5($email),
                                            "ACTIVE" => "Y",
                                            "GROUP_ID" => array(3, 4),
                                            "UF_INN" => (string)$data->INN,
                                            "UF_COMPANY_ID" => $COMPANY_ID,
                                            "UF_PAYER_TYPE" => "2",
                                        );

                                        $user->Add($arUserFields);
                                    }
                                } else {
                                    $arUserFields = Array(
                                        "NAME" => (string)$data->firstName,
                                        "LAST_NAME" => (string)$data->family,
                                        "SECOND_NAME" => (string)$data->secondName,
                                        "ACTIVE" => "Y",
                                        "GROUP_ID" => array(3, 4),
                                        "UF_INN" => (string)$data->INN,
                                        "UF_COMPANY_ID" => $COMPANY_ID,
                                        "UF_PAYER_TYPE" => "2",
                                    );

                                    $user->Update($arUser['ID'] , $arUserFields);
                                }
                            }
                        }

                    }

                }

            }

        }

        $mysqli->close();

        //self::$start = self::$limit;
        //self::$limit += self::$step;

        //header("Location:/users.php?start=".self::$start."&limit=".self::$limit);

    }

    public function getCompany() {

        CModule::IncludeModule("iblock");

        $allCompany = array();

        $arFilter = array(
            'IBLOCK_ID' => IBLOCK_ID_AGENTS,
        );
        $arSelect = array(
            'ID',
            'PROPERTY_INN',
            'PROPERTY_KPP',
        );

        $rsCompany = CIBlockElement::GetList(array(),$arFilter, false, false, $arSelect);

        while($arCompany = $rsCompany->Fetch() ) {

            if ($arCompany['PROPERTY_KPP_VALUE'] == "нет" || $arCompany['PROPERTY_KPP_VALUE'] == "" || $arCompany['PROPERTY_KPP_VALUE'] == "-") {

                $arCompany['PROPERTY_KPP_VALUE'] = 0;

            }

            $allCompany[$arCompany['PROPERTY_INN_VALUE']."-".$arCompany['PROPERTY_KPP_VALUE']] = $arCompany['ID'];

        }

        return $allCompany;

    }

    public function setCompany($INN, $KPP, $ID) {

        $this->allCompany[$INN."-".$KPP] = $ID;

    }

}