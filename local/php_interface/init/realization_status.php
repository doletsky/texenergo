<?/**
 * Обновляем статус реализации заказа после изменения элемента.
 */
AddEventHandler("iblock", "OnAfterIBlockElementAdd", array("AeroRealizationEvents", "afterSave"));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", array("AeroRealizationEvents", "afterSave"));
AddEventHandler("iblock", "OnBeforeIBlockElementDelete", array("AeroRealizationEvents", "afterDelete"));

class AeroRealizationEvents {
    public static function __callStatic($name, $arArguments) {
        if (CModule::IncludeModule("iblock") && CModule::IncludeModule("sale"))
            call_user_func_array(array(self, "_{$name}"), $arArguments);
    }

    public static function _afterSave($arFields) {
        if ($arFields["IBLOCK_ID"] == IBLOCK_ID_SALES) {
            $obElPropQueryResult = CIBlockElement::GetProperty($arFields["IBLOCK_ID"], $arFields["ID"], array(),
                array("CODE" => "STATUS"));

            if ($arElProperty = $obElPropQueryResult->Fetch())
                $status = $arElProperty["VALUE"];
            else
                $status = "";

            self::_updateStatus($arFields["ID"], $status);
        }
    }

    public static function _afterDelete($ID) {
        self::_updateStatus($ID, "");
    }

    private static function _updateStatus($realizationID, $status) {
        $arElFilter = array(
            "IBLOCK_ID" => IBLOCK_ID_INVOICES,
            "ID" => CIBlockElement::SubQuery("PROPERTY_INVOICE_ID", array(
                "ID" => $realizationID
            ))
        );

        $obElQueryResult = CIBlockElement::GetList(array(), $arElFilter, false, false, array("PROPERTY_ORDER_ID"));
        if ($arElement = $obElQueryResult->Fetch()) {
            $orderID = $arElement["PROPERTY_ORDER_ID_VALUE"];
            $arOrder = CSaleOrder::GetByID($orderID);

            if (count($arOrder) > 0) {
                $arOrderPropFilter = array(
                    "PERSON_TYPE_ID" => $arOrder["PERSON_TYPE_ID"],
                    "CODE" => "REALIZATION_STATUS"
                );

                $obOrderPropQueryResult = CSaleOrderProps::GetList(array(), $arOrderPropFilter);
                if ($arOrderProp = $obOrderPropQueryResult->Fetch()) {
                    $orderPropID = $arOrderProp["ID"];

                    $arOrderPropValFilter = array("ORDER_ID" => $orderID, "ORDER_PROPS_ID" => $orderPropID);
                    $obOrderPropValQueryResult = CSaleOrderPropsValue::GetList(array(), $arOrderPropValFilter);
                    if ($arOrderPropVal = $obOrderPropValQueryResult->Fetch()) {
                        $orderPropValID = $arOrderPropVal["ID"];

                        if (strlen($status) > 0)
                            CSaleOrderPropsValue::Update($orderPropValID, array("VALUE" => $status));
                        else
                            CSaleOrderPropsValue::Delete($orderPropValID);
                    } elseif (strlen($status) > 0) {
                        $arOrderPropValFields = array(
                            "ORDER_ID" => $orderID,
                            "ORDER_PROPS_ID" => $orderPropID,
                            "NAME" => $arOrderProp["NAME"],
                            "VALUE" => $status,
                            "CODE" => $arOrderProp["CODE"]
                        );

                        CSaleOrderPropsValue::Add($arOrderPropValFields);
                    }
                }
            }
        }
    }
}