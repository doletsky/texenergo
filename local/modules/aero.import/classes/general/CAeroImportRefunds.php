<?php

require_once dirname(__FILE__) . '/CAeroImportCommon.php';

class CAeroImportRefunds extends CAeroImportCommon
{

    public function Import()
    {
        $arSales = Array();
        if ($this->oXml->unsale) {
            foreach ($this->oXml->unsale as $oSale) {
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
            'IBLOCK_ID' => IBLOCK_ID_REFUNDS,
            'NAME' => 'Возврат по реализации №' . (string)$oXml->id,
            'XML_ID' => (string)$oXml->id,
            'PROPERTY_VALUES' => Array(
                'SALE_ID' => (string)$oXml->saleId,
                'ORDER_ID' => (string)$oXml->orderId,
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


        $arSale = $this->catalog->getElementByXmlID($arFields['PROPERTY_VALUES']['SALE_ID'], IBLOCK_ID_SALES);

        if (!$arSale) {
            $this->log('Не удалось создать возврат по реализации: реализация ' . $arFields['PROPERTY_VALUES']['SALE_ID'] . ' не найдена в системе.');
            return false;
        }
        $arFields['PROPERTY_VALUES']['SALE_ID'] = $arSale['ID'];


        $ID = $this->catalog->updateElement($arFields, true);

        if (!$ID) {
            $this->log('Не удалось создать возврат по реализации. ' . $this->catalog->LAST_ERROR);
            $this->log($arFields);
            return false;
        }

        return true;
    }


}