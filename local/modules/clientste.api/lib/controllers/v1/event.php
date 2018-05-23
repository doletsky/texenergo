<?php


namespace Clientste\Api\Controllers\v1;


use Clientste\Api\Request;
use Clientste\Api\Response;
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Type\DateTime;
use CIBlockElement;

class Event
{

    private $userLogin;
    	

    // MAIN METHOD
    public function __construct() {
        if ($_SERVER['HTTP_AUTHORIZATION_TOKEN']) {

        	$token = trim($_SERVER['HTTP_AUTHORIZATION_TOKEN']);
		$arParseToken = explode(':', $token);
		$this->userLogin = trim($arParseToken[0]);
	}
	//$arParam = $this->getRequest()['parameters'];

    }	


    public function getEvents()
    {
        $arResult = $this->getRequest();
        $arResult['RESULT_DATA'] = $this->getEventsData();


        Response::ShowResult($arResult);
    }


    // ADDITIONAL METHOD


    // Get current request
    private function getRequest()
    {
        return Request::get();
    }

    // Get data iblock
    private function getEventsData()
    {
	$arParam = $this->getRequest()['parameters'];
	// Подключаем хайлоад инфоблоки                  
        if (!\CModule::IncludeModule("highloadblock"))
        {
               	$arResult["error"] = array("message" => "Нет доступа к журналу событий. Error:1");
		return $arResult;
        }

	    // ID HighLoadBlock ChangesToDocuments
            $hlblock_id = 2;
            
            $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();
            
            if (empty($hlblock))
            {
               	$arResult["error"] = array("message" => "Нет доступа к журналу событий. Error:2");
        	return $arResult;
            }
	$entity = HL\HighloadBlockTable::compileEntity($hlblock);
	$entity_data_class = $entity->getDataClass();

	$limit = 50;      
	$rsData = $entity_data_class::getList(array(
		"select" => array("*"),
		"order" => array("ID" => "DESC"),
		"filter" => array('UF_LOGIN' => $this->userLogin),
		"limit" => $limit,
	));

	$countEvent = 1;
	while($arData = $rsData->Fetch())
	{
		$dateTmp = new DateTime();
		$dateTmp = $arData["UF_DATE"];
		$arDataTmp["date"] = $dateTmp->toString();		
		$arDataTmp["login"] = $arData["UF_LOGIN"];
		$arDataTmp["event"] = $arData["UF_EVENT"];
		$arDataTmp["object_type"] = $arData["UF_OBJECT_TYPE"];
		$arDataTmp["object_id"] = $arData["UF_OBJECT_NUMBER"];
		$dateTmp = $arData["UF_OBJECT_DATE"];
		$arDataTmp["object_date"] = $dateTmp->toString();		
		$arDataTmp["order_id"] = $arData["UF_ORDER_NUMBER"];
		$arDataTmp["comment"] = $arData["UF_COMMENT"];

		$arResult[$countEvent] = $arDataTmp;   
		$countEvent++;
	} 	
	//pr($countEvent);
	//die();
	if ($countEvent == 1) {
		$arResult["error"] = array("message" => "Нет данных в журнале событий");
        	return $arResult;	
	}
	if (isset($arParam["EVENT_DEBUG"])) {
		pr($arResult);
		die();
	}
        return $arResult;
    }
}