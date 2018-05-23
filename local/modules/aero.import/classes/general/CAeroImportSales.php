<?php

require_once dirname(__FILE__) . '/CAeroImportCommon.php';

class CAeroImportSales extends CAeroImportCommon
{

    public function Import()
    {
        $arSales = Array();
        if ($this->oXml->sale) {
            foreach ($this->oXml->sale as $oSale) {
                $arSales[] = $this->parseSale($oSale);
            }
        } else {
            $arSales[] = $this->parseSale($this->oXml);
        }

        foreach ($arSales as $arSale) {
            if (!$this->saveSale($arSale)) {
                return false;
            }
        }
        return true;
    }

    private function parseSale($oXml)
    {


        $arFields = Array(
            'IBLOCK_ID' => IBLOCK_ID_SALES,
            'NAME' => 'Реализация №' . (string)$oXml->id,
            'CODE' => (string)$oXml->guid,
            'XML_ID' => (string)$oXml->id,
            'PROPERTY_VALUES' => Array(
                'INVOICE_ID' => (string)$oXml->invoiceId,
                'INVOICE_GUID' => (string)$oXml->invoiceGuid,
                'AMOUNT' => DoubleVal($oXml->amount),
                'DATE' => (string)$oXml->datetime,
                'BASKET_ITEMS' => $this->parseBasketItems($oXml->orderData),
                'PREVIEW_TEXT' => (string)$oXml->comment,
                'STATUS' => (string)$oXml->status
            ),
        );

        return $arFields;
    }

    private function saveSale($arFields)
    {


        $arInvoice = $this->catalog->getElementByXmlID($arFields['PROPERTY_VALUES']['INVOICE_ID'], IBLOCK_ID_INVOICES);

        if (!$arInvoice) {
            $this->log('Не удалось создать реализацию товаров: счет ' . $arFields['PROPERTY_VALUES']['INVOICE_ID'] . ' не найден в системе.');
            return false;
        }
        $arFields['PROPERTY_VALUES']['INVOICE_ID'] = $arInvoice['ID'];


        $ID = $this->catalog->updateElement($arFields, true);

        if (!$ID) {
            $this->log('Не удалось создать реализацию товаров. ' . $this->catalog->LAST_ERROR);
            $this->log($arFields);
            return false;
        }

        return true;
    }


}