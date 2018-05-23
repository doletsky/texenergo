<?php


namespace Clientste\Api\Controllers\v1;


use Clientste\Api\Request;
use Clientste\Api\Response;
use Bitrix\Main\Loader;
use CIBlockElement;

class Catalog
{
    // MAIN METHOD

    public function getGoods()
    {
        $arResult = $this->getRequest();
        $arResult['RESULT_DATA'] = $this->getIblockData(18);

        Response::ShowResult($arResult);
    }

    public function check()
    {
        $arResult = $this->getRequest();
	//echo 'IBLOCK_ID_CATALOG:'.IBLOCK_ID_CATALOG;
	//print_r($arResult);
	//if (isset($arResult['PARAMETERS']['INFOBLOCK_ID'])) echo "Есть параметр для инфоблока\n"; 
        $arResult['IBLOCK_DATA'] = $this->getIblockData(18);
        //$arResult['OPERATING_METHOD'] = 'OBJECT_ORIENTED';

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
	$nPageSize = 50;
	$iNumPage  = 1;
	

        if (Loader::includeModule('iblock')) {
            $arParam = $this->getRequest()['parameters'];
	    $arAllowedFields = ['NAME','CODE','PROPERTY_SKU_VALUE','ID','CATALOG_QUANTITY'];	
	    //echo "Параметры:";print_r($arParam); 	
	    if (isset($arParam['SELECT']) && !empty($arParam['SELECT'])) {
	        	$arSelect = explode(',',$arParam['SELECT']);
			$arSelect = array_map('mb_strtoupper', $arSelect);
		}
		else    {			
			$arSelect = ['CODE','PROPERTY_SKU','NAME', 'ID','CATALOG_QUANTITY'];
		}

		if (isset($arParam['NUM_PAGE']) && !empty($arParam['NUM_PAGE'])) {
			$iNumPageParam = intval($arParam['NUM_PAGE']);
			if ($iNumPageParam > 0) {
				$iNumPage = $iNumPageParam;
			}
		}
			$arNavStartParams = ["nPageSize"=>$nPageSize,"iNumPage"=> $iNumPage];
		$arFilter = ['IBLOCK_ID' => $id,'ACTIVE' => 'Y'];
		if (isset($arParam['REFERENCE']) && !empty($arParam['REFERENCE'])) {
		    if (strlen($arParam['REFERENCE']) == 10)	
		    	$arFilter['CODE'] = $arParam['REFERENCE'];
		}

            if ($result = CIBlockElement::getList(['ID' => 'DESC'], $arFilter , false, $arNavStartParams, $arSelect)) {
		$count = 0;
		$count_cur = 0;
		if ($iNumPage > 1) $count_cur = $nPageSize * ($iNumPage-1);
                while ($ar = $result->fetch()) {
		    $arPrint = array();  	
		    foreach ($ar as $key => $value) {
			if (!in_array($key , $arAllowedFields)) continue;
			if ($key == "CATALOG_QUANTITY") $key = "QUANTITY";
			if ($key == "CODE") $key = "REFERENCE";		
			if (stristr($key ,'PROPERTY_')) {
				$pos = strlen($key)-15;
				//echo "Позиция:".$pos;
				$key = substr($key,9,$pos);				
			}
			if ($key == "NAME") $value = preg_replace ('/\s+/', ' ',  $value) ;

		    	if ($key == "ID") {
				$ar_price = GetCatalogProductPrice($value, 1);	
				if (isset($ar_price['PRICE'])) $arPrint['PRICE'] = $ar_price['PRICE'];
				continue;
			}
			$arPrint[$key] = $value; 
		    }
		    $count++;
		    $count_cur++;	
                    $arResult[$count_cur] = $arPrint;
                }
		$arResult['COUNT'] = $count;
            }
        }

        return $arResult;
    }
}