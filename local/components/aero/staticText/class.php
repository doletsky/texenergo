<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use \Bitrix\Main;
use \Bitrix\Main\SystemException as SystemException;

class CStaticBlocksComponent extends CBitrixComponent {

    protected function checkModules() {
        if(!Main\Loader::includeModule('iblock')) {
            throw new Exception('iblock module not installed');
        }

        if(!isset($this->arParams['IBLOCK_ID']) && !class_exists('CIBlockTools')) {
            throw new Exception('CIBlockTools class not exists');
        }
    }

    /**
     * Extract data from cache. No action by default.
     * @return bool
     */
    protected function extractDataFromCache() {
        if($this->arParams['CACHE_TYPE'] == 'N') {
            return false;
        }

        global $USER;

        return !($this->StartResultCache(false, $USER->GetGroups()));
    }

    protected function putDataToCache() {
        $this->endResultCache();
    }

    protected function abortDataCache() {
        $this->AbortResultCache();
    }

    protected function prepareData() {
        $this->arResult['TEXT'] = "";

        if(isset($this->arParams['ELEMENT'])) {

            $ibId = isset($this->arParams['IBLOCK_ID']) ? $this->arParams['IBLOCK_ID'] : STATIC_IB_ID;

            $filter = array('IBLOCK_ID' => $ibId,
                            'ACTIVE'    => 'Y');

            if(is_numeric($this->arParams['ELEMENT'])) {
                $filter['ID'] = $this->arParams['ELEMENT'];
            }
            else {
                $filter['CODE'] = $this->arParams['ELEMENT'];
            }

            $arElement = CIBlockElement::GetList(array(), $filter, false, false)->GetNext();

            $this->arResult['NAME'] = $arElement['NAME'];
            $this->arResult['TEXT'] = $arElement['~PREVIEW_TEXT'];
            $this->arResult['ELEMENT'] = $arElement;
        }
    }

    public function executeComponent() {
        try {
            $this->checkModules();

            if(!$this->extractDataFromCache()) {
                $this->prepareData();
                $this->includeComponentTemplate();
                $this->putDataToCache();
            }
        }
        catch(SystemException $e) {
            $this->abortDataCache();
            ShowError($e->getMessage());
        }
    }
}