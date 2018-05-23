<?php

require_once dirname(__FILE__) . '/CAeroImportCommon.php';

class CAeroImportPayments extends CAeroImportCommon
{

    public function Import()
    {
        $arPayments = Array();
        if ($this->oXml->payment) {
            foreach ($this->oXml->payment as $oPayment) {
                $arPayments = array_merge($arPayments, $this->parsePayment($oPayment));
            }
        } else {
            $arPayments = $this->parsePayment($this->oXml);
        }

        foreach ($arPayments as $arPayment) {
            if (!$this->savePayment($arPayment)) {
                return false;
            }
        }
        return true;
    }

    private function parsePayment($oXml)
    {
        $arResult = Array();
        foreach ($oXml->paymentData->paymentItem as $oPaymentItem) {

            $arFields = Array(
                'IBLOCK_ID' => IBLOCK_ID_PAYMENTS,
                'NAME' => 'Платежное поручение №' . (string)$oXml->id,
                'XML_ID' => (string)$oXml->id . '-' . (string)$oPaymentItem->invoiceId,
                'PROPERTY_VALUES' => Array(
                    'AMOUNT' => DoubleVal($oPaymentItem->amount),
                    'DATE' => (string)$oXml->datetime,
                    'PREVIEW_TEXT' => (string)$oXml->comment,
                    'INVOICE_ID' => (string)$oPaymentItem->invoiceId,
                ),
            );
            $arResult[] = $arFields;
        }

        return $arResult;
    }

    private function savePayment($arFields)
    {

        $arInvoice = $this->catalog->getElementByXmlID($arFields['PROPERTY_VALUES']['INVOICE_ID'], IBLOCK_ID_INVOICES);

        if (!$arInvoice) {
            $this->log('Не удалось создать платежное поручение: счет ' . $arFields['PROPERTY_VALUES']['INVOICE_ID'] . ' не найден в системе.');
            return false;
        }

        $arFields['PROPERTY_VALUES']['INVOICE_ID'] = $arInvoice['ID'];

        $ID = $this->catalog->updateElement($arFields);

        if (!$ID) {
            $this->log('Не удалось создать платежное поручение. ' . $this->catalog->LAST_ERROR);
            $this->log($arFields, true);
            return false;
        }

        return true;
    }

} 