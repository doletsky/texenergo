<?
/**
 * Обработчики изменения и удаления счетов и платежных поручений
 * После каждого изменения обновляем статус договора
 */
AddEventHandler("iblock", "OnAfterIBlockElementAdd", "OnAfterDocsAddUpdate");
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "OnAfterDocsAddUpdate");
AddEventHandler("iblock", "OnBeforeIBlockElementDelete", "OnBeforeDocsDelete");

function OnAfterDocsAddUpdate($arFields)
{
    CModule::IncludeModule('iblock');

    if ($arFields['IBLOCK_ID'] == IBLOCK_ID_INVOICES) {
        $arValue = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $arFields['ID'], Array(), Array('CODE' => 'ORDER_ID'))->Fetch();
        if ($arValue) {
            UpdateOrderStatus($arValue['VALUE']);
        }
    }

    if ($arFields['IBLOCK_ID'] == IBLOCK_ID_PAYMENTS) {
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/reg.log', 'payment update:' . print_r($arFields, true) . "\n", FILE_APPEND);
        $arValue = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $arFields['ID'], Array(), Array('CODE' => 'INVOICE_ID'))->Fetch();
        if ($arValue) {
            $arValueOrder = CIBlockElement::GetProperty(IBLOCK_ID_INVOICES, $arValue['VALUE'], Array(), Array('CODE' => 'ORDER_ID'))->Fetch();
            if ($arValueOrder) {
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/reg.log', 'order update:' . $arValueOrder['VALUE'] . "\n", FILE_APPEND);
                UpdateOrderStatus($arValueOrder['VALUE']);
            }
        }

    }
}


function UpdateOrderStatus($orderID)
{
    CModule::IncludeModule('sale');
    $arOrder = CSaleOrder::GetByID($orderID);
    if (!$arOrder) return;

    $sStatus = 'N'; //принят


    $rsInvoices = CIBlockElement::GetList(
        Array('property_date' => 'desc'),
        Array('IBLOCK_ID' => IBLOCK_ID_INVOICES, 'PROPERTY_ORDER_ID' => $arOrder['ID']),
        false, false,
        Array('ID', 'PROPERTY_AMOUNT')
    );

    if ($rsInvoices->SelectedRowsCount() > 0) {
        $sStatus = 'I'; //выставлен счет
    }

    $iPrice = 0;
    $iPayed = 0;

    while ($arInvoice = $rsInvoices->GetNext()) {

        $iPrice += $arInvoice['PROPERTY_AMOUNT_VALUE'];

        // платежные поручения по счету
        $rsPayments = CIBlockElement::GetList(
            Array('property_date' => 'desc'),
            Array('IBLOCK_ID' => IBLOCK_ID_PAYMENTS, 'PROPERTY_INVOICE_ID' => $arInvoice['ID']),
            false, false,
            Array('ID', 'PROPERTY_AMOUNT')
        );

        if ($rsPayments->SelectedRowsCount() > 0) {
            $sStatus = 'P'; //частично оплачен
        }

        while ($arPayment = $rsPayments->GetNext()) {
            $iPayed += $arPayment['PROPERTY_AMOUNT_VALUE'];
        }
    }

    if (($iPayed >= $iPrice) and ($iPrice > 0)) {
    //if ($iPayed >= $iPrice) {
        $sStatus = 'C'; //полностью оплачен
    }

    if ($arOrder['STATUS_ID'] != $sStatus) {
        CSaleOrder::StatusOrder($arOrder['ID'], $sStatus);

        // ставим заказу флаг обновления из 1С, чтобы заказ не выгрузился в xml повторно
        CSaleOrder::Update($orderID, array('UPDATED_1C' => 'Y'));

        if ($ex = $GLOBALS['APPLICATION']->GetException()) {
            $strError = $ex->GetString();
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/reg.log', 'order error:' . $strError . "\n", FILE_APPEND);
        }
    }

}