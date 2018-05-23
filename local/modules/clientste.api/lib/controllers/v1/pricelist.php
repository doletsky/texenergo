<?php


namespace Clientste\Api\Controllers\v1;;


use Clientste\Api\Request;
use Clientste\Api\Response;
use Bitrix\Main\Loader;
use CIBlockElement;

class Pricelist
{
    // MAIN METHOD

    public function getPriceJSON()
    {
        $arResult = $this->getRequest();
        //$arResult['RESULT_DATA'] = $this->getDataOrder();

        //Response::ShowResult($arResult);
	$file_name = 'catalog_texenergo.json';
	$file = 'catalog_texenergo_quantity_update.json';  
	$pathFile = '/price_list/files/wex856yjk916wdf563yi236985jdt6jf/'.$file;
	$pathFull = $_SERVER['DOCUMENT_ROOT'].$pathFile;
	$param = array("FILE" => $file_name, "PATH_FULL" => $pathFull); 
	$l = $this->sendPriceList($param);
	die();
    }

    public function getPriceBrandXLS()
    {
        $arResult = $this->getRequest();
        //$arResult['RESULT_DATA'] = $this->getDataOrder();

        //Response::ShowResult($arResult);
	$file = 'price_brand_texenergo_update.xls';  
	$pathFile = '/price_list/files/wex856yjk916wdf563yi236985jdt6jf/'.$file;
	$pathFull = $_SERVER['DOCUMENT_ROOT'].$pathFile;
	$param = array("FILE" => $file, "PATH_FULL" => $pathFull); 
	$l = $this->sendPriceList($param);
	die();
    }
    public function getPriceNoBrandXLS()
    {
        $arResult = $this->getRequest();
        //$arResult['RESULT_DATA'] = $this->getDataOrder();

        //Response::ShowResult($arResult);
	$file = 'price_no_brand_texenergo_update.xls';  
	$pathFile = '/price_list/files/wex856yjk916wdf563yi236985jdt6jf/'.$file;
	$pathFull = $_SERVER['DOCUMENT_ROOT'].$pathFile;
	$param = array("FILE" => $file, "PATH_FULL" => $pathFull); 
	$l = $this->sendPriceList($param);
	//echo $pathFull;
	die();
	//die();
	//exit();
    }
    public function getPriceXLS()
    {
        $arResult = $this->getRequest();
        //$arResult['RESULT_DATA'] = $this->getDataOrder();

        //Response::ShowResult($arResult);
	$file = 'price_texenergo_update.xls';  
	$pathFile = '/price_list/files/wex856yjk916wdf563yi236985jdt6jf/'.$file;
	$pathFull = $_SERVER['DOCUMENT_ROOT'].$pathFile;
	$param = array("FILE" => $file, "PATH_FULL" => $pathFull); 
	$l = $this->sendPriceList($param);
	//echo $pathFull;
	die();
	//die();
	//exit();
    }

    // ADDITIONAL METHOD
    // send price-list
    private function sendPriceList($param)
    {

        if (file_exists($param["PATH_FULL"])) {
		header('HTTP/1.1 200');
		header ("Content-Type: application/octet-stream"); 
		header ("Accept-Ranges: bytes"); 
		header ("Content-Length: ".filesize($param["PATH_FULL"]));   
		header ("Content-Disposition: attachment; filename=".$param["FILE"]);   
		echo file_get_contents ($param["PATH_FULL"]);
		return "";
		//readfile($pathFull); 
	}

        return Request::get();
    }


    // Get current request
    private function getRequest()
    {
        return Request::get();
    }

    // Get data iblock
    private function getDataPriceList()
    {
        $arResult = [];


        return $arResult;
    }
}