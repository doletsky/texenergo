<?php


namespace Texenergo\Api\Controllers;


use Texenergo\Api\Request;
use Texenergo\Api\Response;
use Bitrix\Main\Loader;
use CIBlockElement;

class Example
{
    // MAIN METHOD

    public function check()
    {
        $arResult = $this->getRequest();
	//echo 'IBLOCK_ID_CATALOG:'.IBLOCK_ID_CATALOG;
	//print_r($arResult);
	//if (isset($arResult['PARAMETERS']['INFOBLOCK_ID'])) echo "Есть параметр для инфоблока\n"; 
        $arResult['IBLOCK_DATA'] = $this->getIblockData(62);
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

        if (Loader::includeModule('iblock')) {

            if ($result = CIBlockElement::getList(['ID' => 'DESC'], ['IBLOCK_ID' => $id,'ACTIVE' => 'Y'], false, false, ['ID', 'NAME','EXTERNAL_ID','DATE_CREATE','TIMESTAMP_X'])) {

                while ($ar = $result->fetch()) {

                    $arResult[] = $ar;
                }
            }
        }

        return $arResult;
    }
}