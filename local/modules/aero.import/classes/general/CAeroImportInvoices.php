<?php

require_once dirname(__FILE__) . '/CAeroImportCommon.php';

class CAeroImportInvoices extends CAeroImportCommon
{

    public function Import()
    {
        $arInvoices = Array();
        if ($this->oXml->invoice) {
            foreach ($this->oXml->invoice as $oInvoice) {
                $arInvoices[] = $this->parseInvoice($oInvoice);
            }
        } else {
            $arInvoices[] = $this->parseInvoice($this->oXml);
        }

        foreach ($arInvoices as $arInvoice) {
            if (!$this->saveInvoice($arInvoice)) {
                return false;
            }
        }
        return true;
    }

    private function parseInvoice($oXml)
    {

        $arStatusEnum = Array(
            'N' => 34820,
        );

        $arFields = Array(
            'IBLOCK_ID' => IBLOCK_ID_INVOICES,
            'NAME' => 'Счет №' . (string)$oXml->id,
            'CODE' => (string)$oXml->guid,
            'XML_ID' => (string)$oXml->id,
            'PROPERTY_VALUES' => Array(
                'ORDER_ID' => IntVal($oXml->orderId),
                'AMOUNT' => DoubleVal($oXml->amount),
                //'STATUS' => $arStatusEnum[(string)$oXml->status],
                'ID_1C' => (string)$oXml->id_1c,
                'INN' => (string)$oXml->inn,
                'DATE' => (string)$oXml->datetime,
                'BASKET_ITEMS' => $this->parseBasketItems($oXml->orderData),
            ),
        );

        return $arFields;
    }

    private function saveInvoice($arFields)
    {
        /**
         * Проверяем для кого этот счет,
         * ИНН в счете должен совпадать с ИНН контрагента, который создал заказ (заявку).
         * Работает только если у контрагента указан ИНН.
         */
        $arCompany = false;
        if ($arFields['PROPERTY_VALUES']['ORDER_ID'] > 0) {
            if ($arOrder = CSaleOrder::GetByID($arFields['PROPERTY_VALUES']['ORDER_ID'])) {
                $arUser = CUser::GetByID($arOrder['USER_ID'])->Fetch();
                if ($arUser['UF_COMPANY_ID'] > 0) {
                    $arCompany = CIBlockElement::GetList(Array(),
                                                         Array(
                                                             'IBLOCK_ID' => IBLOCK_ID_AGENTS,
                                                             '=ID' => $arUser['UF_COMPANY_ID']),
                                                         false,
                                                         false,
                                                         Array('ID', 'PROPERTY_ID_1C', 'PROPERTY_INN'))->Fetch();

                    if($arCompany && strlen($arCompany['PROPERTY_INN_VALUE']) > 0){
                        $inn = trim($arFields['PROPERTY_VALUES']['INN']);
                        if(trim($arCompany['PROPERTY_INN_VALUE']) !== $inn){
                            $this->log('Счет не загружен: не совпадает ИНН в счете и ИНН контрагента, создавшего заказ.');
                            return false;
                        }
                    }
                }
            }
        }

        if(isset($arFields['PROPERTY_VALUES']['INN'])){
            unset($arFields['PROPERTY_VALUES']['INN']);
        }
        
        $ID = $this->catalog->updateElement($arFields, true);

        if (!$ID) {
            $this->log('Не удалось создать счет.');
            $this->log($this->catalog->LAST_ERROR);
            $this->log($arFields);
            return false;
        }

        /**
         * @костыль: внешний код агента мы задаем при получении первого счета по заказу
         * Ищем контрагента-владельца заказа и если у него не задан код 1С,
         * задаем его из счета
         */
        if($arCompany && strlen($arCompany['PROPERTY_ID_1C_VALUE']) <= 0) {
            CIBlockElement::SetPropertyValuesEx($arCompany['ID'],
                                                IBLOCK_ID_AGENTS,
                                                array('ID_1C' => $arFields['PROPERTY_VALUES']['ID_1C']));
        }

        return true;
    }
}