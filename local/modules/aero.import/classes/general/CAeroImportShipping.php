<?php

require_once dirname(__FILE__) . '/CAeroImportCommon.php';

class CAeroImportShipping extends CAeroImportCommon
{

    public function Import()
    {
        $arShippings = Array();
        if ($this->oXml->shipping) {
            foreach ($this->oXml->shipping as $oShipping) {
                $arShippings[] = $this->parseShipping($oShipping);
            }
        } else {
            $arShippings[] = $this->parseShipping($this->oXml);
        }

        foreach ($arShippings as $arShipping) {
            if (!$this->saveShipping($arShipping)) {
                return false;
            }
        }
        return true;
    }

    private function parseShipping($oXml)
    {

        $arFields = Array(
            'IBLOCK_ID' => IBLOCK_ID_CARGO,
            'NAME' => 'Отправка груза №' . (string)$oXml->id,
            'XML_ID' => (string)$oXml->id,
            'PROPERTY_VALUES' => Array(
                'DATE' => (string)$oXml->datetime,
                'COMPANY' => (string)$oXml->company,
                'RECEIPT' => (string)$oXml->receipt,
                'COMMENT' => (string)$oXml->comment,
                'SALE' => false,
            ),
        );

        foreach ($oXml->shippingData->shippingItem as $oShippingItem) {
            $sXmlId = (string)$oShippingItem->saleId;
            $arSale = $this->catalog->getElementByXmlID($sXmlId, IBLOCK_ID_SALES);
            if ($arSale) {
                $arFields['PROPERTY_VALUES']['SALE'][] = $arSale['ID'];
            } else {
                $this->log("Реализация $sXmlId не найдена");
            }
        }

        return $arFields;
    }

    private function saveShipping($arFields)
    {
        $ID = $this->catalog->updateElement($arFields);

        if (!$ID) {
            $this->log('Не удалось создать отправку груза. ' . $this->catalog->LAST_ERROR);
            $this->log($arFields);
            return false;
        }

        return true;
    }


} 