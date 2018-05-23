<?php


namespace Texenergo\Api\Controllers;


use Texenergo\Api\Request;
use Texenergo\Api\Response;
use Bitrix\Main\Loader;
use CIBlockElement;

class Pricelist
{
    // MAIN METHOD

    public function getPriceBrandTE()
    {
        $arResult = $this->getRequest();
        //$arResult['RESULT_DATA'] = $this->getDataOrder();

        //Response::ShowResult($arResult);
	$file = 'price_brand_texenergo.xls';  
	$pathFile = '/price_list/files/wex856yjk916wdf563yi236985jdt6jf/'.$file;
	$pathFull = $_SERVER['DOCUMENT_ROOT'].$pathFile;
	//echo $pathFull;
	//die();
        if (file_exists($pathFull)) {
		/*
		$str="Content-Disposition: attachment; filename=".$file;  
		header($str);  
		header("Content-type: application/octet-stream");  
		echo file_get_contents ($file);  	
		*/
	//$file = "files/archive.rar"; 
	header('HTTP/1.1 200');
	header ("Content-Type: application/octet-stream"); 
	header ("Accept-Ranges: bytes"); 
	header ("Content-Length: ".filesize($pathFull));   
	header ("Content-Disposition: attachment; filename=".$file);   
	echo file_get_contents ($pathFull);
	//readfile($pathFull); 
	}
	//die();
	//exit();
    }

    // ADDITIONAL METHOD


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