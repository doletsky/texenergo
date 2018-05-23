<?php

/**
 * Выбираем для каждого заказа счета, реализации и платежные поручения
 */
CModule::IncludeModule('iblock');
foreach ($arResult['ORDERS'] as &$arOrder) {
    $arOrder['ORDER']['INVOICES'] = Array();

    $arOrder['ORDER']['PAYED'] = 0;
    $arOrder['ORDER']['SOLD'] = 0;
    $arOrder['ORDER']['PROGRESS'] = 0;

    $rsInvoices = CIBlockElement::GetList(
        Array('property_date' => 'desc'),
        Array('IBLOCK_ID' => IBLOCK_ID_INVOICES, 'PROPERTY_ORDER_ID' => $arOrder['ORDER']['ID']),
        false, false,
        Array('ID', 'NAME', 'PROPERTY_DATE', 'PROPERTY_BASKET_ITEMS', 'PROPERTY_STATUS', 'PROPERTY_AMOUNT')
    );

    if ($rsInvoices->SelectedRowsCount() > 0) {
        $arOrder['ORDER']['PRICE'] = 0;
    }

    while ($arInvoiceData = $rsInvoices->GetNext()) {
        $arInvoice = Array(
            'ID' => $arInvoiceData['ID'],
            'NAME' => $arInvoiceData['NAME'],
            'DATE' => $arInvoiceData['PROPERTY_DATE_VALUE'],
            'BASKET_ITEMS' => $arInvoiceData['PROPERTY_BASKET_ITEMS_VALUE'],
            'STATUS' => $arInvoiceData['PROPERTY_STATUS_VALUE'],
            'AMOUNT' => $arInvoiceData['PROPERTY_AMOUNT_VALUE'],
            'PAYED' => 0,
            'SOLD' => 0,
            'SALES' => Array(),
            'PAYMENTS' => Array(),
        );

        $arOrder['ORDER']['PRICE'] += $arInvoice['AMOUNT'];

        // реализация товаров по счету
        $rsSales = CIBlockElement::GetList(
            Array('property_date' => 'desc'),
            Array('IBLOCK_ID' => IBLOCK_ID_SALES, 'PROPERTY_INVOICE_ID' => $arInvoice['ID']),
            false, false,
            Array('ID', 'NAME', 'PROPERTY_INVOICE_ID', 'PROPERTY_DATE', 'PROPERTY_COMMENT', 'PROPERTY_AMOUNT', 'PROPERTY_BASKET_ITEMS')
        );

        while ($arSaleData = $rsSales->GetNext()) {
            $arSale = Array(
                'ID' => $arSaleData['ID'],
                'NAME' => $arSaleData['NAME'],
                'DATE' => $arSaleData['PROPERTY_DATE_VALUE'],
                'BASKET_ITEMS' => $arSaleData['PROPERTY_BASKET_ITEMS_VALUE'],
                'COMMENT' => $arSaleData['PROPERTY_COMMENT_VALUE']['TEXT'],
                'AMOUNT' => $arSaleData['PROPERTY_AMOUNT_VALUE'],
                'CARGO' => Array(),
            );

            // отправка груза
            $rsCargo = CIBlockElement::GetList(
                Array('property_date' => 'desc'),
                Array('IBLOCK_ID' => IBLOCK_ID_CARGO, 'PROPERTY_SALE_ID' => $arSale['ID']),
                false, false,
                Array('ID', 'NAME', 'PROPERTY_SALE_ID', 'PROPERTY_DATE', 'PROPERTY_COMMENT', 'PROPERTY_AMOUNT', 'PROPERTY_COMPANY')
            );

            while ($arCargoData = $rsCargo->GetNext()) {
                $arCargo = Array(
                    'ID' => $arCargoData['ID'],
                    'NAME' => $arCargoData['NAME'],
                    'DATE' => $arCargoData['PROPERTY_DATE_VALUE'],
                    'COMPANY' => $arCargoData['PROPERTY_COMPANY_VALUE'],
                    'COMMENT' => $arCargoData['PROPERTY_COMMENT_VALUE']['TEXT'],
                    'AMOUNT' => $arCargoData['PROPERTY_AMOUNT_VALUE'],
                );
                $arSale['CARGO'][] = $arCargo;
            }

            $arInvoice['SOLD'] += $arSale['AMOUNT'];
            $arInvoice['SALES'][] = $arSale;
        }

        // платежные поручения по счету
        $rsPayments = CIBlockElement::GetList(
            Array('property_date' => 'desc'),
            Array('IBLOCK_ID' => IBLOCK_ID_PAYMENTS, 'PROPERTY_INVOICE_ID' => $arInvoice['ID']),
            false, false,
            Array('ID', 'NAME', 'PROPERTY_DATE', 'PROPERTY_COMMENT', 'PROPERTY_AMOUNT')
        );

        while ($arPaymentData = $rsPayments->GetNext()) {
            $arPayment = Array(
                'ID' => $arPaymentData['ID'],
                'NAME' => $arPaymentData['NAME'],
                'DATE' => $arPaymentData['PROPERTY_DATE_VALUE'],
                'COMMENT' => $arPaymentData['PROPERTY_COMMENT_VALUE'],
                'AMOUNT' => $arPaymentData['PROPERTY_AMOUNT_VALUE'],
            );
            $arInvoice['PAYED'] += $arPayment['AMOUNT'];
            $arInvoice['PAYMENTS'][] = $arPayment;
        }

        $arOrder['ORDER']['PAYED'] += $arInvoice['PAYED'];
        $arOrder['ORDER']['SOLD'] += $arInvoice['SOLD'];

        $arOrder['ORDER']['INVOICES'][] = $arInvoice;

        $arOrder['ORDER']['PROGRESS'] = IntVal(min(round($arOrder['ORDER']['SOLD'] / $arOrder['ORDER']['PRICE'] * 100), 100));
    }
}