<?AddEventHandler("iblock", "OnAfterIBlockElementAdd", "refreshDeliveryProfiles");
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "refreshDeliveryProfiles");
AddEventHandler("iblock", "OnAfterIBlockElementDelete", "refreshDeliveryProfiles");

function refreshDeliveryProfiles($arFields) {

    if ($arFields["IBLOCK_ID"] == IBLOCK_ID_DELIVERY_COMPANIES && CModule::IncludeModule("sale")) {
        $obQueryResult = CSaleDeliveryHandler::GetList(array(), array("SID" => "other"));
        if ($arHandler = $obQueryResult->Fetch()) {
            include_once "{$_SERVER["DOCUMENT_ROOT"]}/local/php_interface/include/sale_delivery/delivery_other.php";

            $obDelivery = new CDeliveryOther();
            $arProfiles = $obDelivery->__GetProfiles();

            CSaleDeliveryHandler::Set($arHandler["SID"], array("ACTIVE" => "Y", "PROFILES" => $arProfiles));
        }
    }
}
