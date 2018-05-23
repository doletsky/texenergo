<?php


namespace Texenergo\Api\Controllers;


use Texenergo\Api\Request;
use Texenergo\Api\Response;
use Bitrix\Main\Loader;
use CIBlockElement;

class Docum
{
    // MAIN METHOD

    public function getInvoices()
    {
        $arResult = $this->getRequest();
        $arResult['RESULT_DATA'] = $this->getIblockData(22);

        Response::ShowResult($arResult);
    }
    public function getSales()
    {
        $arResult = $this->getRequest();
        $arResult['RESULT_DATA'] = $this->getIblockData(23);

        Response::ShowResult($arResult);
    }
    public function getPayments()
    {
        $arResult = $this->getRequest();
        $arResult['RESULT_DATA'] = $this->getIblockData(24);

        Response::ShowResult($arResult);
    }
    public function getDelivery()
    {
        $arResult = $this->getRequest();
        $arResult['RESULT_DATA'] = $this->getIblockData(50);

        Response::ShowResult($arResult);
    }
    public function getRefunds()
    {
        $arResult = $this->getRequest();
        $arResult['RESULT_DATA'] = $this->getIblockData(49);

        Response::ShowResult($arResult);
    }
    public function getCargo()
    {
        $arResult = $this->getRequest();
		//print_r($arResult['PARAMETERS']);
		//die();
        $arResult['RESULT_DATA'] = $this->getIblockData(25);

        Response::ShowResult($arResult);
    }


    // ADDITIONAL METHOD


    // Get current request
    private function getRequest()
    {
        return Request::get();
    }

    // Get data iblock
    private function getIblockData($id)
    {
        $arResult = [];
	// 'ID', 'NAME','EXTERNAL_ID','DATE_CREATE','TIMESTAMP_X'

        if (Loader::includeModule('iblock')) {
            $arParam = $this->getRequest()['PARAMETERS'];
	    //echo "Параметры:";print_r($arParam); 	
	    if (isset($arParam['SELECT']) && !empty($arParam['SELECT'])) {
	        	$arSelect = explode(',',$arParam['SELECT']);
			$arSelect = array_map('mb_strtoupper', $arSelect);
		}
		else    {			
			$arSelect = ['ID', 'NAME','EXTERNAL_ID','DATE_CREATE','TIMESTAMP_X'];
		}
	    if (isset($arParam['DATE']) && !empty($arParam['DATE'])) {
	        	$arDate = explode(',',$arParam['DATE']);
			$arDate = array_map('mb_strtoupper', $arDate);
		}
		else    {			
			$arDate = ['29.12.2015 00:00:00', '23.11.2016 16:11:01'];
		}
		//print_r($arSelect);
	    //die();
			
            if ($result = CIBlockElement::getList(['DATE_CREATE' => 'DESC'], ['IBLOCK_ID' => $id,'ACTIVE' => 'Y','><DATE_CREATE' => $arDate ], false, false, $arSelect)) {

		$count = 0;
                while ($ar = $result->fetch()) {

                    $arResult[] = $ar;
		    $count++;	
                }
		$arResult['COUNT'] = $count;
            }
        }

        return $arResult;
    }
}