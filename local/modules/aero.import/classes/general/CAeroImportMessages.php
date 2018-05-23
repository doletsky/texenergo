<?php

require_once dirname(__FILE__) . '/CAeroImportCommon.php';

class CAeroImportMessages extends CAeroImportCommon
{

    public function Import()
    {
        $arMessages = Array();
        if ($this->oXml->message) {
            foreach ($this->oXml->message as $oMessage) {
                $arMessages[] = $this->parseMessage($oMessage);
            }
        } else {
            $arMessages[] = $this->parseMessage($this->oXml);
        }

        foreach ($arMessages as $arMessage) {
            if (!$this->saveMessage($arMessage)) {
                return false;
            }
        }
        return true;
    }

    private function parseMessage($oXml)
    {

        $arFields = Array(
            'IBLOCK_ID' => IBLOCK_ID_MESSAGES,
            'NAME' => (string)$oXml->title,
            'XML_ID' => (string)$oXml->id,
            'PROPERTY_VALUES' => Array(
                'ORDER_ID' => IntVal($oXml->requestNo),
                'DATE' => (string)$oXml->datetime,
                'MESSAGE' => (string)$oXml->messageText,
                'OBJECT_TYPE' => (string)$oXml->objectType ,
                'OBJECT_ID' => (string)$oXml->objectId ,
            ),
        );

        return $arFields;
    }

    private function saveMessage($arFields)
    {

        $ID = $this->catalog->updateElement($arFields);

        if (!$ID) {
            $this->log('Не удалось создать сообщение. ');
            $this->log($this->catalog->LAST_ERROR);
            $this->log($arFields);
            return false;
        }

        return true;
    }


} 