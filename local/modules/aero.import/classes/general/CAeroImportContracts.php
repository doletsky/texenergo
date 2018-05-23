<?php
require_once dirname(__FILE__) . '/CAeroImportCommon.php';

class CAeroImportContracts extends CAeroImportCommon
{

    public function Import()
    {
        $arContracts = Array();
        if ($this->oXml->document) {
            foreach ($this->oXml->document as $oContract) {
                $arContracts[] = $this->parseContract($oContract);
            }
        } else {
            $arContracts[] = $this->parseContract($this->oXml);
        }

        foreach ($arContracts as $arContract) {
            if (!$this->saveContract($arContract)) {
                return false;
            }
        }
        return true;
    }

    private function parseContract($oXml)
    {
        $arFields = array();

        $sFile = (string)$oXml->file;
        if (strlen($sFile) > 0) {
            $sFile = $this->getAbsoluteFilePath($sFile);
        }

        $sInn = (string)$oXml->clientInn;
        $sKPP = (string)$oXml->KPP;
        $arAgent = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => IBLOCK_ID_AGENTS, 'PROPERTY_INN' => $sInn, 'PROPERTY_KPP' => $sKPP, 'ACTIVE' => 'Y'), false, Array('nTopCount' => 1), Array('ID'))->Fetch();

        if (!$arAgent) {
            $this->log('Не найден контрагент по ИНН/КПП: ' . $sInn . "/" . $sKPP);
        } else {
            $arFields = Array(
                'IBLOCK_ID' => IBLOCK_ID_CONTRACTS,
                'NAME' => (string)$oXml->jurName,
                'XML_ID' => (string)$oXml->docNo,
                'DATE_ACTIVE_FROM' => (string)$oXml->activeFrom,
                'DATE_ACTIVE_TO' => (string)$oXml->activeTo,
                'PROPERTY_VALUES' => Array(
                    'DOC_ID' => (string)$oXml->docNo,
                    'AGENT' => IntVal($arAgent['ID']),
                    //'DEBT_DAYS' => IntVal($oXml->debtDay),
                    'REST_DAYS' => IntVal($oXml->restDay),
                    'DEBT_MONEY' => DoubleVal($oXml->debtMoney),
                    'REST_MONEY' => DoubleVal($oXml->restMoney),
                    'DISCOUNT' => IntVal($oXml->discount),
                    'DATE' => (string)$oXml->Date,
                    'INN' => $sInn,
                    'COMPANY' => '',
                ),
            );

            if ($sFile) {
                $arFields['PROPERTY_VALUES']['FILE'] = CFile::MakeFileArray($sFile);
            }

            $sCompanyName = trim((string)$oXml->jurName);

            $arCompany = $this->catalog->getElementByXmlID($sCompanyName, IBLOCK_ID_COMPANIES);
            if (!$arCompany) {
                $arCompany = Array(
                    'IBLOCK_ID' => IBLOCK_ID_COMPANIES,
                    'NAME' => $sCompanyName,
                    'XML_ID' => $sCompanyName,
                );
                $arCompany['ID'] = $this->catalog->updateElement($arCompany);
            }
            $arFields['PROPERTY_VALUES']['COMPANY'] = $arCompany['ID'];
        }

        return $arFields;
    }

    private function saveContract($arFields)
    {
        if (sizeof($arFields) == 0) {
            return false;
        }

        $ID = $this->catalog->updateElement($arFields);

        if (!$ID) {
            $this->log('Не удалось создать договор. ');
            $this->log($this->catalog->LAST_ERROR);
            $this->log($arFields);
            return false;
        }

        return true;
    }

} 