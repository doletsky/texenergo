<?php


namespace Texenergo\Api\Controllers;


use Texenergo\Api\Request;
use Texenergo\Api\Response;
use Bitrix\Main\Loader;
use CIBlockElement;

class Order
{

    private $userLogin;
    private $userID;
    	

    // MAIN METHOD
    public function __construct() {
        \CModule::IncludeModule('sale');
        \CModule::IncludeModule('iblock');
        if ($_SERVER['HTTP_AUTHORIZATION_TOKEN']) {

        	$token = trim(htmlspecialchars($_SERVER['HTTP_AUTHORIZATION_TOKEN'],ENT_QUOTES));
		$arParseToken = explode(':', $token);
		$this->userLogin = trim($arParseToken[0]);
	}
	//$arParam = $this->getRequest()['parameters'];
        global $USER;

        if (!is_object($USER)) $USER = new \CUser();

        if (!$USER->isAuthorized()) {
            \CUser::Authorize(1);
        }

    }	

    public function getOrders()
    {
        $arResult = $this->getRequest();
        $arResult['RESULT_DATA'] = $this->getOrderData();

        Response::ShowResult($arResult);
    }

    /**
     * Создание (обновление) заказа
     * @param - нет
     * @return array
     */    
    public function updateOrder()
    {
        $arResult = $this->getRequest();
	//pr("Логин:".$this->userLogin);
	$arCheckParameters = $this->checkOrderParameters();
	//pr($arCheckParameters);
	//die();
	if (isset($arCheckParameters["success"])) {
		if ($arCheckParameters["success"]["method"] == "create_status_order") {
	        	//$arResult['RESULT_DATA'] = $this->updateOrderData();
			$order_id = $arCheckParameters["success"]["order_id"];
			if ($this->setOrderStatus($order_id)) {
				//pr("Обновлен статус предварительного заказа:".$order_id);
			} else {
				//pr("Статус для заказа ".$order_id." не обновлен");
			}
			//die();
		} elseif ($arCheckParameters["success"]["method"] == "update_pre_order") {
			$order_id = $arCheckParameters["success"]["order_id"];
			$order_goods = $arCheckParameters["success"]["goods"];
			$order_comment = $arCheckParameters["success"]["comment"];		
			
			if ($this->setOrderUpdate($order_id, $order_goods, $order_comment)) {
				//pr("Обновлены данные предварительного заказа:".$order_id);
			} else {
				//pr("Статус для заказа ".$order_id." не обновлен");
			}

		} elseif ($arCheckParameters["success"]["method"] == "create_pre_order") {

			$order_goods = $arCheckParameters["success"]["goods"];
			$order_comment = $arCheckParameters["success"]["comment"];
			$order_id = $this->CreateOrder($order_goods,$order_comment);
			if ($order_id) {
				//pr("Создан предварительный заказ:".$order_id);
				$arCheckParameters["success"]["message"] = "Создан предварительный заказ: ".$order_id;
			} else {
				//pr("Предварительный заказ создать не удалось");
				$arCheckParameters["success"]["message"] = "Создать предварительный заказ не удалось";
			}

		} else {
			//$arResult['RESULT_DATA'] = $arCheckParameters;
			//pr("Параметры не корректны");
			//pr($arResult['RESULT_DATA']);
			//die();
		}
	}

	$arResult['RESULT_DATA'] = $arCheckParameters;	
        Response::ShowResult($arResult);
    }


    // ADDITIONAL METHOD


    // Get current request
    private function CreateOrder($order_goods,$order_comment)
    {	
	global $loginUserID;
	$FuserID = $this->getFuserID($loginUserID);

        $arUser = \CUser::GetList(($by = "ID"), ($order = "desc"), Array('ID' => $loginUserID), Array('FIELDS' => Array('ID','NAME','LAST_NAME','SECOND_NAME','EMAIL','LOGIN'), "SELECT"=> array("UF_PAYER_TYPE")))->Fetch();
	//pr($arUser);
	$iPersonTypeID = IntVal($arUser['UF_PAYER_TYPE']);

	$summaOrder = 0;
	foreach ($order_goods as $key => $value) {
			//pr("Нужно добавить референс : ".$value["reference"]);
			$arProduct = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_CATALOG, 'CODE' => $value["reference"],'ACTIVE' => 'Y'), false, false, array('ID','CODE','NAME'))->Fetch();
			$ar_price = GetCatalogProductPrice($arProduct['ID'], 1);	
			if (isset($ar_price['PRICE'])) $arProduct['PRICE'] = $ar_price['PRICE'];
			$arProduct['QUANTITY'] = $value["quantity"];
	            $arBasket[] = Array(
			//"ORDER_ID" => $order_id,
        	        'FUSER_ID' => $FuserID,
	                'NAME' => (string)$arProduct['NAME'],
	                'QUANTITY' => (int)$arProduct['QUANTITY'],
	                'PRICE' => (double)$arProduct['PRICE'],
	                //'PRODUCT_XML_ID' => $sXmlId,
	                'PRODUCT_ID' => $arProduct['ID'],
	                'CAN_BUY' => 'Y',
	                'CURRENCY' => 'RUB',
	                'LID' => SITE_ID,
        	    );

			//pr($arProduct);
			$summaOrder = $summaOrder + ($arProduct['QUANTITY'] * $arProduct['PRICE']);
	}
	//pr($arBasket);
	//die();
        $arFields = Array(
            'USER_ID' => $loginUserID,
            "LID" => SITE_ID,
            'CURRENCY' => 'RUB',
            "PAYED" => "N",
            "CANCELED" => "N",
            "STATUS_ID" => "R",
            'PERSON_TYPE_ID' => $iPersonTypeID,
            //'ACCOUNT_NUMBER' => (string)$oXml->id,
            //'DATE_INSERT' => (string)$oXml->date,
            //'COMMENTS' => (string)$oXml->comment,
            //'PRICE' => 5,
        );
	$arFields["PRICE"] = (double)$summaOrder;
	if ($order_comment) $arFields['USER_DESCRIPTION'] = $order_comment;

	//pr($arFields);
	//die();
        $order_id = \CSaleOrder::Add($arFields);
	if ($order_id) {
		//pr("Создали заказ: ".$order_id);
	} else {
		//pr("Не смогли создать заказ");
		return false;
	}
	//die();

	//pr("Товары корзины");
	foreach ($arBasket as $key => $value) {
		//pr($value);		
		$value["ORDER_ID"] = $order_id;
		if (\CSaleBasket::Add($value)) {				
			//pr("Удалось добавить товар корзины");
		} else {
			//pr("Не удалось добавить товар корзины");			
			return false;
		}		
	}
	
	return $order_id;

    }


   private function getFuserID($USER_ID)
    {
        $FUSER_ID = \CSaleUser::GetList(array('USER_ID' => $USER_ID));
        if (!$FUSER_ID['ID'])
            $FUSER_ID['ID'] = \CSaleUser::_Add(array("USER_ID" => $USER_ID));
        return $FUSER_ID['ID'];
    }

    private function setOrderUpdate($order_id, $order_goods,$order_comment)
    {
	//pr("Номер заказа для обновления:".$order_id);
	//pr($order_goods);
	//foreach ($order_goods as $key => $value) {
		//pr("Референс:". $value["reference"] . " Количество:". $value["quantity"]);
	//}
	//die();
	//$arFields["PRICE"] = "100.10";
	global $loginUserID;
	$FuserID = $this->getFuserID($loginUserID);
	//pr("loginUserID:".$loginUserID);
	
	$arSelectFields = array("ID","ORDER_ID","PRODUCT_ID","PRICE","QUANTITY");
	//$arSelectFields = array("*");

        $rsBasket = \CSaleBasket::GetList(Array(), Array("ORDER_ID" => $order_id), false, false, $arSelectFields);
        $arBasket = Array();
	$countBasket = 1;
	$updatePriceOrder = false;
	$summaOrder = 0;
	// Отредактируем (удалим) товары в корзине (количество и цены)
        while ($arBasketItem = $rsBasket->Fetch()) {
		//$arProduct = \CIBlockElement::GetByID($arBasketItem['PRODUCT_ID'])->Fetch();
		$update_item = false;
		$delete_item = true;
		$arProduct = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_CATALOG, 'ID' => $arBasketItem['PRODUCT_ID'],'ACTIVE' => 'Y'), false, false, array('ID','CODE'))->Fetch();
		if ($arProduct) {
			$ar_price = GetCatalogProductPrice($arBasketItem['PRODUCT_ID'], 1);	
			if (isset($ar_price['PRICE'])) $arBasketItem["CATALOG_PRICE"] = $ar_price['PRICE'];
			$arBasketItem["CATALOG_ID"] = $arProduct["ID"];
			$arBasketItem["CATALOG_REFERENCE"] = $arProduct["CODE"];
			$arBasketReference[] = $arProduct["CODE"];
			$priceItem = $arBasketItem["PRICE"];
			if ($arBasketItem["CATALOG_PRICE"] != $arBasketItem["PRICE"]) {
				$update_item = true;
				$priceItem = $arBasketItem["CATALOG_PRICE"];
			}
		}
		foreach ($order_goods as $key => $value) {
			if ($arBasketItem["CATALOG_REFERENCE"] == $value["reference"]) {
				$quantityItem = $arBasketItem["QUANTITY"];
				$delete_item = false;
				if ($quantityItem != $value["quantity"]) {
					$update_item = true;
					$quantityItem = $value["quantity"];
				}
				continue;
			}
		}

            //pr($arBasketItem);
		$summaOrder = $summaOrder + ($quantityItem * $priceItem);
		if ($update_item) {
			//pr("Надо обновлять товар");
			$arFields = array(
			   "QUANTITY" => $quantityItem,
			   "PRICE" => $priceItem,
			);
			if (\CSaleBasket::Update($arBasketItem['ID'], $arFields)) {
				$updatePriceOrder = true;
			}
		}
		if ($delete_item) {
		 	//pr("Нужно удалить товар из корзины");
			if (\CSaleBasket::Delete($arBasketItem['ID'])) {
				$summaOrder = $summaOrder - ($quantityItem * $priceItem);			
				$updatePriceOrder = true;
			}
		}
	    //pr($arProduct);
        }
        //pr("Товары для корзины заказа ".$order_id);
	// Добавим новые товары
	foreach ($order_goods as $key => $value) {
		if (!in_array($value["reference"], $arBasketReference)) {
			//pr("Нужно добавить референс : ".$value["reference"]);
			$arProduct = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_CATALOG, 'CODE' => $value["reference"],'ACTIVE' => 'Y'), false, false, array('ID','CODE','NAME'))->Fetch();
			$ar_price = GetCatalogProductPrice($arProduct['ID'], 1);	
			if (isset($ar_price['PRICE'])) $arProduct['PRICE'] = $ar_price['PRICE'];
			$arProduct['QUANTITY'] = $value["quantity"];
	            $arFields = Array(
			"ORDER_ID" => $order_id,
        	        //'FUSER_ID' => $this->getFuserID($loginUserID),
        	        'FUSER_ID' => $FuserID,
	                'NAME' => (string)$arProduct['NAME'],
	                'QUANTITY' => (int)$arProduct['QUANTITY'],
	                'PRICE' => (double)$arProduct['PRICE'],
	                //'PRODUCT_XML_ID' => $sXmlId,
	                'PRODUCT_ID' => $arProduct['ID'],
	                'CAN_BUY' => 'Y',
	                'CURRENCY' => 'RUB',
	                'LID' => SITE_ID,
        	    );

			//pr($arProduct);
			//pr($arFields);
			if (\CSaleBasket::Add($arFields)) {				
				//pr("Удалось добавить");
				$summaOrder = $summaOrder + ($arProduct['QUANTITY'] * $arProduct['PRICE']);			
				$updatePriceOrder = true;
			} else {
				//pr("Не удалось добавить");			
			}

		}		
	}

	if ($updatePriceOrder) {
		//pr("Нужно обновить сумму заказа: ".$summaOrder);
		$arFields["PRICE"] = $summaOrder;
	}

	if ($order_comment) $arFields["USER_DESCRIPTION"] = $order_comment;
	if ($arFields) {
		if (\CSaleOrder::Update($order_id, $arFields)) {
			return true;
		} else {
			return false;
		}
	}
	return true;

    }


    private function setOrderStatus($order_id)
    {
    	//$sStatus = 'N'; //принят
	$arFields["STATUS_ID"] = 'N';
	$arFields["DATE_INSERT"] = Date(\CDatabase::DateFormatToPHP(\CLang::GetDateFormat("FULL", LANG)));
	//die();

	//if (!(\CSaleOrder::StatusOrder($order_id, $sStatus))) {
	if (!\CSaleOrder::Update($order_id, $arFields)) {
		return false;
	} else {
        	return true;
	}
    }


    private function getRequest()
    {
        return Request::get();
    }


    private function checkOrderParameters()
    {
	$arParam = $this->getRequest()['parameters'];
	// Если указан номер заказа
	if (isset($arParam["ORDER_ID"])) {
		$orderID = intval($arParam["ORDER_ID"]);
		if (!($orderID > 0)) {		
			$arResult["error"]["order_id"] = array("message" =>"Неверный номер заказа");
			return $arResult;
		}			
		$arOrder = Array("DATE_INSERT" => "DESC");
		//pr("Логин: ". $this->userLogin);
		//die();
		$arFilter = Array(
			"LID" => SITE_ID,
			//"USER_LOGIN" => $userLogin,
			"USER_LOGIN" => $this->userLogin,
		);
		$arFilter["ACCOUNT_NUMBER"] = $orderID;
		$arFilter["@STATUS_ID"] = array("R");
		$arGroupBy = array();
		$arNavStartParams = false;
		//$arSelectFields = array("ACCOUNT_NUMBER","DATE_INSERT","STATUS_ID","DATE_STATUS");
		$arSelectFields = array("ACCOUNT_NUMBER","USER_DESCRIPTION");
		$dbOrder = \CSaleOrder::GetList($arOrder, $arFilter,false,$arNavStartParams,$arSelectFields);
		$arOrderSearch = $dbOrder->Fetch();
		if ($arOrderSearch) {
			//pr($arOrderSearch);
			//die();
			$arOrderTmp['order_id'] = $arOrderSearch['ACCOUNT_NUMBER'];
			if ($arOrderSearch['USER_DESCRIPTION']) {
				$arOrderTmp['comment']	= $arOrderSearch['USER_DESCRIPTION'];				
			}
			//$arOrderTmp['date'] = $arOrderSearch['DATE_INSERT'];
			//$arOrderTmp['order_status'] = $arOrderSearch['STATUS_ID'];
		}
		if (!(isset($arOrderTmp))) {
			$arResult["error"]["order_id"] = array("message" =>"Предварительный заказ с номером ".$orderID." для изменений не найден");
			return $arResult;
		} else {
			$arOrderUpdate["order_id"] = $orderID;
		}
	}
	if (isset($arParam["REFERENCES"])) {
		//$arReferences = htmlspecialchars($arParam["REFERENCES"],ENT_QUOTES);
		$arReferences = trim($arParam["REFERENCES"]);
		$arReferences = explode(",", $arReferences);
		$error_reference = false;
		foreach ($arReferences as $key => $value) {
			$arReferences[$key] = explode(":", $value);
			$arReferencesSelect[] =  trim($arReferences[$key][0]);
			if (strlen(trim($arReferences[$key][0])) <> 10) {
				$arResult["error"]["reference"][$key+1]["len_reference"] = "Длина референса не равна 10 ".$arReferences[$key][0];
				$error_reference = true;
				//pr("Неверное количество для ".$arReferences[$key][0]);
				//die();			
			}
			if (substr(trim($arReferences[$key][0]),0,2) <> "te") {
				$arResult["error"]["reference"][$key+1]["format_reference"] = "Референс не начинается с te ".$arReferences[$key][0];
				$error_reference = true;
				//pr("Неверное количество для ".$arReferences[$key][0]);
				//die();			
			}
			if (!(intVal($arReferences[$key][1]) > 0)) {
				$arResult["error"]["reference"][$key+1]["quantity"] = "Неверное количество для ".$arReferences[$key][0];
				$error_reference = true;
				//pr("Неверное количество для ".$arReferences[$key][0]);
				//die();			
			}
		}
		if ($error_reference) {
			$arResult["error"]["message"] = "Ошибки в референсах или неверное количество";
			return $arResult;
		}		
		//  проверим на дубли референсов

		$arReferencesDubl =  array_count_values($arReferencesSelect);
			//pr($arReferencesDuble);
		$countDublicate = 1;
		foreach ($arReferencesDubl as $key => $value) {
		     if ($value > 1) {				
			$arReference_duplicate[$countDublicate] = implode(":",array($key,$value));
			$countDublicate++;
		    }	
		}
		if ($arReference_duplicate) {
			//pr($arReference_duplicate);
			$arResult["error"]["reference_duplicate"] = $arReference_duplicate;
			$arResult["error"]["message"] = "Ошибка! Дубли в референсах товаров.";		
			return $arResult;
		}
			//die();


		// Проверим на наличие товаров на сайте
		$reference_not_find = false;
		$arSelect = array('ID','CODE');
		$arFilter = array('CODE' => $arReferencesSelect, 'ACTIVE' => 'Y');
		if ($result = \CIBlockElement::getList(array(), $arFilter , false, false, $arSelect)) {
			while ($arProduct = $result->fetch()) {
		           $arFindReference[] = $arProduct['CODE'];
				}
			   //die();
			}
		if (!isset($arFindReference)) {
			foreach ($arReferencesSelect as $key => $value) { 
				$arResult["error"]["reference_not_find"][$key+1] = $value;
				$reference_not_find = true;
			}
		} else {
			foreach ($arReferencesSelect as $key => $value) {
				if (!in_array($value, $arFindReference)) {
					$arResult["error"]["reference_not_find"][$key+1] = $value;
					$reference_not_find = true;
				}	
			}				
		}			
		if ($reference_not_find) {
			$arResult["error"]["message"] = "Товары с указанными референсами не найдены.";
			return $arResult;
		}

	} else {	
		if (!(isset($arParam["ORDER_ID"]))) {
			$arResult["error"]["create_order_not_reference"] = array("message" => "Для создания заказа не указаны референсы, параметр references");			
			$arResult["error"]["message"] = "Для создания заказа не указаны референсы, параметр references";
			return $arResult;
		}		
	}	
	//pr($orderID);
	//die();
	if ($orderID) {
		$arResult["success"]["order_id"] = $orderID;
		$arResult["success"]["method"] = "update_pre_order";
		if (isset($arParam["CREATE_STATUS_ORDER"])) {
			//pr("Изменяем статус для заказа");
			//die();
			$arResult["success"]["method"] = "create_status_order";
		}
	} else  {
		$arOrder = Array("DATE_INSERT" => "DESC");
		$arFilter = Array(
			"LID" => SITE_ID,
			"USER_LOGIN" => $this->userLogin,
		);
		$arFilter["@STATUS_ID"] = array("R");
		$arGroupBy = array("COUNT"=> "ACCOUNT_NUMBER");
		$arNavStartParams = false;
		//$arSelectFields = array("ACCOUNT_NUMBER","DATE_INSERT","STATUS_ID","DATE_STATUS");
		$arSelectFields = array("ACCOUNT_NUMBER");
		$dbOrder = \CSaleOrder::GetList($arOrder, $arFilter,$arGroupBy,$arNavStartParams,$arSelectFields);
		$arOrderCount = $dbOrder->Fetch();
		//pr($arOrderCount);
		$limit_pre_order = 5;
		if ($arOrderCount["ACCOUNT_NUMBER"] >= $limit_pre_order) {
			$arResult["error"]["create_order_limit_exceeded"] = $limit_pre_order;			
			$arResult["error"]["message"] = "Нельзя создать более ".$limit_pre_order. " предварительных заказов";
			return $arResult;
		}

		$arResult["success"]["method"] = "create_pre_order";	
	}	
	foreach ($arReferences as $key => $value) { 
		$arResult["success"]["goods"][$key+1]["reference"] = trim($value[0]);	
		$arResult["success"]["goods"][$key+1]["quantity"] = intVal($value[1]);	
	}
	if (isset($arParam["COMMENT"])) {
		$comment = trim($arParam["COMMENT"]);	
		if ($arOrderTmp['comment'] != $comment) {
			$arResult["success"]["comment"] = $comment;
		}
	}
	return $arResult;
    }
    // Get data iblock
    private function updateOrderData()
    {
	
	

	//$arResult["error"][1] = "Не найден заказ для обновления";
	//$arResult["error"][2] = "Ошибка в референсах te00362363:42, te00256987";
	//$arResult["error"][3] = "Референсы не найдены: te00306880, te00306880, te00306880, te00306880, te00306880";
	//$arResult["success"] = array("message" =>"Успешно создан(обновлен) заказ", "order_id" => 16424);
	return $arResult;
    }
    // Get data iblock
    private function getOrderData()
    {
	

        if (\CModule::IncludeModule("sale")) {
        	//$dbOrder = CSaleOrder::GetList(Array("ID" => "DESC"), Array("ACCOUNT_NUMBER" => $ID, "USER_ID" => IntVal($USER->GetID()), "LID" => SITE_ID));

		//$orderID = 4515;
		$arParam = $this->getRequest()['parameters'];
		$order_debug = false;	
		$orderID = false;
		//$userLogin = false;
		$order_last = false;
		$order_detail = false;
		$order_last_count = 50;		

		if (isset($arParam["ORDER_DEBUG"])) {
			$order_debug = true;
		}
            	//if ($_SERVER['HTTP_AUTHORIZATION_TOKEN']) {

                //	$token = trim($_SERVER['HTTP_AUTHORIZATION_TOKEN']);
		//	$arParseToken = explode(':', $token);
		//	$userLogin = trim($arParseToken[0]);
		//}
		//if (isset($arParam["USER_LOGIN"])) {
		//	$userLogin = $arParam["USER_LOGIN"];
		//}
		if (isset($arParam["ORDER_ID"])) {
			$orderID = intval($arParam["ORDER_ID"]);
			if (isset($arParam["ORDER_DETAIL"])) {
				$order_detail = true;
			}
		} else 	{	
			$order_last = true;	
		}
		//if ($order_last) {
			$arOrder = Array("DATE_INSERT" => "DESC");
		//}			
		//else    {
		//	$arOrder = Array("DATE_INSERT" => "ASC");
		//}
		$arFilter = Array(
			"LID" => SITE_ID,
			//"USER_LOGIN" => $userLogin,
			//"USER_LOGIN" => $this->userLogin,
			//"STATUS_ID" => 'R',
		);

		if (!isset($arParam["GET_ALL_ORDER"])) {
			if (isset($arParam["PRE_ORDER"])) {
				$arFilter["@STATUS_ID"] = array("R");			
			} else {
				//$arFilter["@STATUS_ID"] = array("N", "I", "P", "C","F");
				$arFilter["@STATUS_ID"] = array("N");
			}
		}

		//pr($arFilter);
		//die();
		$arGroupBy = array();
		if ($order_last) {		
			$arNavStartParams = array("nTopCount" => $order_last_count);
		} else {
			$arNavStartParams = false;
			if ($orderID) {
				$arFilter["ACCOUNT_NUMBER"] = $orderID;
			}
		}
		//pr($arFilter); die();
		//$arSelectFields = false;
		$arSelectFields = array("ACCOUNT_NUMBER","DATE_INSERT","DATE_UPDATE","PERSON_TYPE_ID","PRICE","PRICE_DELIVERY","DELIVERY_ID","USER_ID","USER_LOGIN","USER_NAME",
					"USER_LAST_NAME","USER_EMAIL","USER_DESCRIPTION","CANCELED","ALLOW_DELIVERY","STATUS_ID","DATE_STATUS");
		$dbOrder = \CSaleOrder::GetList($arOrder, $arFilter,false,$arNavStartParams,$arSelectFields);
		$countOrder = 1;
		$arOrder = array();
        	while ($arOrderTmp = $dbOrder->Fetch()) {
            	$arOrder['order_id'] = $arOrderTmp['ACCOUNT_NUMBER'];
            	$arOrder['order_date_create'] = $this->prepareDate($arOrderTmp['DATE_INSERT']);
            	$arOrder['order_date_update'] = $this->prepareDate($arOrderTmp['DATE_UPDATE']);
            	$arOrder['order_status_id'] = $arOrderTmp['STATUS_ID'];
            	$arOrder['order_date_status_update'] = $this->prepareDate($arOrderTmp['DATE_STATUS']);
		$arOrder['user'] = $this->getUserArray($arOrderTmp["USER_ID"]);
            	$arOrder['summary'] = $arOrderTmp['PRICE'];
            	$arOrder['comment'] = htmlspecialchars($arOrderTmp['USER_DESCRIPTION']);
            	$arOrder['iscanceled'] = ($arOrderTmp['CANCELED'] == 'Y') ? 1 : 0;

		$arProps = array();
        	$arProps = $this->getOrderProperties($arOrderTmp['ACCOUNT_NUMBER']);
		//pr($arProps);
	        // берем телефон из указанного при заказе
        	if(strlen($arProps['PHONE']['VALUE']) > 0){
            		$arOrder['user']['phone'] = $arProps['PHONE']['VALUE'];
        	}
		//if($arProps['PRE_ORDER']['VALUE'] == "Y") {
            	//	$arOrder['preorder'] = $arProps['PRE_ORDER']['VALUE'];
		//}

		$arOrder['orderGoods'] = $this->getOrderGoods($arOrderTmp['ACCOUNT_NUMBER']);
		if ($order_detail) {
			$orderDocuments = $this->getOrderDocuments($arOrderTmp['ACCOUNT_NUMBER']);
			if ($orderDocuments) 
				$arOrder['invoices'] = $orderDocuments;
		}


        	/**
         	* Адрес доставки
         	*/
        	$arLocation = \CSaleLocation::GetByID($arProps['LOCATION_DELIVERY']['VALUE']);
        	$arOrder['deliveryCity'] = $arLocation['CITY_NAME'];
        	$arOrder['deliveryStreet'] = $arProps['STREET_DELIVERY']['VALUE'];
        	$arOrder['deliveryZip'] = $arProps['ZIP_DELIVERY']['VALUE'];
        	$arOrder['deliveryBuilding'] = $arProps['HOUSE_DELIVERY']['VALUE'];
        	$arOrder['deliveryHousing'] = $arProps['HOUSING_DELIVERY']['VALUE'];
        	$arOrder['deliveryOffice'] = $arProps['OFFICE_DELIVERY']['VALUE'];
        	$arOrder['deliveryFloor'] = $arProps['STAGE_DELIVERY']['VALUE'];

        	$arOrder['deliverySumma'] = $arOrderTmp['PRICE_DELIVERY'];
	
		if ($arOrderTmp['ALLOW_DELIVERY']  == 'Y') {
			$deliveryId = explode(":", $arOrderTmp['DELIVERY_ID']);
        	$arOrder['deliveryService'] = $deliveryId[0];
         	if ($arOrder['deliveryService'] == 'other') {
            		$deliveryCompanyCode = $deliveryId[1];
            		$rsCompanies = \CIBlockElement::GetList(Array(), Array('=IBLOCK_ID' => IBLOCK_ID_DELIVERY_COMPANIES, 'ACTIVE' => 'Y', '=CODE' => $deliveryCompanyCode), false, false, Array('ID','CODE','NAME'));
            	while ($arCompany = $rsCompanies->Fetch()) {
                	$arProp = \CIBlockElement::GetProperty(IBLOCK_ID_DELIVERY_COMPANIES, $arCompany['ID'], "sort", "asc", array('CODE'=>'CODE1C'));
                	$arOrder['deliveryСompanyCode'] = $deliveryCompanyCode;
                	$arOrder['deliveryСompanyName'] = htmlspecialchars($arCompany['NAME']);
                	//$arOrder['deliveryСompanyCode1c'] = $arProp->arResult[0]['VALUE'];
            		}
         	} else {
            		$arOrder['deliveryServiceType'] = $deliveryId[1];
         		}
		}

			$arResult[$countOrder] = $arOrder;		
			$countOrder++;
		}
		if ($countOrder == 1) {
			$arResult["error"]["orders"] = array("message" =>"Нет заказов для указанных параметров");			
			return $arResult;
		}
		if ($order_debug) {	
			pr($arResult);
			die();
		}
        return $arResult;
	}
    }

    /**
     * Возвращает массив с элементами подчиненных документов заказа
     * @param $orderID номер заказа
     * @return array
     */
    private function getOrderDocuments($orderID)
    {

	$arDoc = Array();
    	$rsInvoices = \CIBlockElement::GetList(
        	Array('property_date' => 'desc'),
        	Array('IBLOCK_ID' => IBLOCK_ID_INVOICES, 'PROPERTY_ORDER_ID' => $orderID),
        	false, false,
        	Array('ID', 'NAME', 'CODE','EXTERNAL_ID', 'PROPERTY_DATE', 'PROPERTY_BASKET_ITEMS', 'PROPERTY_AMOUNT')
    );

    //if ($rsInvoices->SelectedRowsCount() > 0) {
        //$arOrder['ORDER']['PRICE'] = 0;
    //}
    $countInvoice = 1;
    while ($arInvoiceData = $rsInvoices->GetNext()) {
	//pr($arInvoiceData);
	//die();
        $sDate = reset(explode(' ', $arInvoiceData['PROPERTY_DATE_VALUE']));
	$invoice_amount = $arInvoiceData['PROPERTY_AMOUNT_VALUE'];
        $arInvoice = Array(
            'invoice_id' => $arInvoiceData['EXTERNAL_ID'],
            'invoice_guid' => $arInvoiceData['CODE'],
            //'name' => $arInvoiceData['NAME'],
            'date' => $sDate,
            //'BASKET_ITEMS' => $arInvoiceData['PROPERTY_BASKET_ITEMS_VALUE'],
            'amount' => $invoice_amount,
            //'PAYED' => 0,
            //'SOLD' => 0,
            //'SALES' => Array(),
            //'PAYMENTS' => Array(),
            //'REFUNDS' => Array(),
        );
    
	$countInvoiceItem = 1;
        foreach ($arInvoiceData['PROPERTY_BASKET_ITEMS_VALUE'] as $arBasketItem) {
		$arProduct = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_CATALOG, 'EXTERNAL_ID' => $arBasketItem['CODE'],'ACTIVE' => 'Y'), false, false, array('ID','CODE'))->Fetch();
		if ($arProduct['CODE']) {
			$arItem['reference'] = $arProduct['CODE'];
		} else {
			$arItem['reference'] = "Нет на сайте";			
		}
		$arItem['guid'] = $arBasketItem['CODE'];
		$arItem['name'] = preg_replace ('/\s+/', ' ', $arBasketItem['NAME']);
		$arItem['price'] = $arBasketItem['PRICE'];
		$arItem['quantity'] = $arBasketItem['QUANTITY'];
		$arItem['summa'] = $arBasketItem['PRICE'] * $arBasketItem['QUANTITY'] ;	
		$invoice_basket_summa = $invoice_basket_summa + $arItem['summa'];
		$arInvoice['invoiceGoods'][$countInvoiceItem] = $arItem;
		$countInvoiceItem++;
        }
	$invoiceService = round($invoice_amount - $invoice_basket_summa,2);	
	if ($invoiceService > 0 ) {
		$arBasketService = array( "name" => "Транспортно-упаковочная услуга", "summa" => $invoiceService);		
		$arInvoice['invoiceGoods'][$countInvoiceItem] = $arBasketService;
		}

        // реализация товаров по счету
		$arRealizFilter = Array('IBLOCK_ID' => IBLOCK_ID_SALES, 'PROPERTY_INVOICE_ID' => $arInvoiceData['ID']);
		if(!empty($arResult['AVAIL_REALIZATION_STATUS']))
			$arRealizFilter['PROPERTY_STATUS'] = $arResult['AVAIL_REALIZATION_STATUS'];

        $rsSales = \CIBlockElement::GetList(
            Array('property_date' => 'desc'),
            $arRealizFilter,
            false, false,
            Array('ID','EXTERNAL_ID', 'CODE', 'PROPERTY_INVOICE_ID', 'PROPERTY_DATE', 'PROPERTY_COMMENT', 'PROPERTY_AMOUNT', 'PROPERTY_BASKET_ITEMS')
        );

		$bHaveRealiz = false;
        $salesIds = [];
        $countSales = 1;
        while ($arSaleData = $rsSales->GetNext()) {
			$bHaveRealiz = true;
            $sDate = reset(explode(' ', $arSaleData['PROPERTY_DATE_VALUE']));

            $salesIds[] = $arSaleData['ID'];

	    $sale_amount = $arSaleData['PROPERTY_AMOUNT_VALUE'];	
            $arSale = Array(
                'sale_id' => $arSaleData['EXTERNAL_ID'],
                'date' => $sDate,
                //'comment' => $arSaleData['PROPERTY_COMMENT_VALUE']['TEXT'],
                'amount' => $sale_amount,

            );
 	    $sale_basket_summa = 0; 
	    $countSaleItem = 1;
            foreach ($arSaleData['PROPERTY_BASKET_ITEMS_VALUE'] as $arBasketItem) {
		$arProduct = \CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_CATALOG, 'EXTERNAL_ID' => $arBasketItem['CODE'],'ACTIVE' => 'Y'), false, false, array('ID','CODE'))->Fetch();
		if ($arProduct['CODE']) {
			$arItem['reference'] = $arProduct['CODE'];
		} else {
			$arItem['reference'] = "Нет на сайте";			
		}
		$arItem['name'] = preg_replace ('/\s+/', ' ', $arBasketItem['NAME']);
		$arItem['price'] = $arBasketItem['PRICE'];
		$arItem['quantity'] = $arBasketItem['QUANTITY'];
		$arItem['summa'] = $arBasketItem['PRICE'] * $arBasketItem['QUANTITY'] ;	
		$arSale['saleGoods'][$countSaleItem] = $arItem;
		$countSaleItem++;
		$sale_basket_summa = $sale_basket_summa + $arItem['summa'];	
	     }	
		$saleService = 	round($sale_amount - $sale_basket_summa,2);	
	    if 	($saleService > 0 ) {
			$arBasketService = array( "name" => "Транспортно-упаковочная услуга", "summa" => $saleService);		
			$arSale['saleGoods'][$countSaleItem] = $arBasketService;
			}		


            // отправка груза
            $rsCargo = \CIBlockElement::GetList(
                Array('property_date' => 'desc'),
                Array('IBLOCK_ID' => IBLOCK_ID_CARGO, 'PROPERTY_SALE' => $arSaleData['ID']),
                false, false,
                Array('ID', 'EXTERNAL_ID', 'PROPERTY_DATE', 'PROPERTY_COMMENT','PROPERTY_COMPANY', 'PROPERTY_RECEIPT')
                //Array('ID', 'EXTERNAL_ID', 'PROPERTY_SALE_ID', 'PROPERTY_DATE', 'PROPERTY_COMMENT', 'PROPERTY_AMOUNT', 'PROPERTY_COMPANY', 'PROPERTY_RECEIPT')
            );

	    $countCargo = 1;
            while ($arCargoData = $rsCargo->GetNext()) {
                $sDate = reset(explode(' ', $arCargoData['PROPERTY_DATE_VALUE']));

                $arCargo = Array(
                    'cargo_id' => $arCargoData['EXTERNAL_ID'],
                    'date' => $sDate,
                    'transport_company' => $arCargoData['PROPERTY_COMPANY_VALUE'],
                    'receipt' => $arCargoData['PROPERTY_RECEIPT_VALUE'],
                    //'comment' => $arCargoData['PROPERTY_COMMENT_VALUE']['TEXT'],
                    //'amount' => $arCargoData['PROPERTY_AMOUNT_VALUE'],

                );
                $arSale['cargos'][$countCargo] = $arCargo;
		$countCargo++;
            }

        // возвраты по реализациям
        if(!empty($salesIds)){

            $arRefundsFilter = Array('IBLOCK_ID' => IBLOCK_ID_REFUNDS, 'PROPERTY_SALE_ID' => $salesIds);

            $rsRefunds = CIBlockElement::GetList(
                Array('property_date' => 'desc'),
                $arRefundsFilter,
                false, false,
                Array('ID', 'NAME', 'EXTERNAL_ID', 'PROPERTY_SALE_ID', 'PROPERTY_DATE', 'PROPERTY_COMMENT', 'PROPERTY_AMOUNT', 'PROPERTY_BASKET_ITEMS')
            );

	    $countRefund = 1;
            while ($arRefundData = $rsRefunds->GetNext()) {

                $sDate = reset(explode(' ', $arRefundData['PROPERTY_DATE_VALUE']));

		$refund_amount = $arRefundData['PROPERTY_AMOUNT_VALUE']; 
                $arRefund = Array(
                    'refund_id' => $arRefundData['EXTERNAL_ID'],
                    'name' => $arRefundData['NAME'],
                    'date' => $sDate,
                    //'BASKET_ITEMS' => $arRefundData['PROPERTY_BASKET_ITEMS_VALUE'],
                    //'comment' => $arRefundData['PROPERTY_COMMENT_VALUE']['TEXT'],
                    'amount' => $refund_amount,
                );

		$countRefundItem = 1;
                foreach ($arRefundData['PROPERTY_BASKET_ITEMS_VALUE'] as $arBasketItem) {
			$arProduct = \CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_CATALOG, 'EXTERNAL_ID' => $arBasketItem['CODE'],'ACTIVE' => 'Y'), false, false, array('ID','CODE'))->Fetch();
			if ($arProduct['CODE']) {
				$arItem['reference'] = $arProduct['CODE'];
			} else {
				$arItem['reference'] = "Нет на сайте";			
			}
			$arItem['name'] = preg_replace ('/\s+/', ' ', $arBasketItem['NAME']);
			$arItem['price'] = $arBasketItem['PRICE'];
			$arItem['quantity'] = $arBasketItem['QUANTITY'];
			$arItem['summa'] = $arBasketItem['PRICE'] * $arBasketItem['QUANTITY'] ;	
			$arRefund['refundGoods'][$countRefundItem] = $arItem;
			$countRefundItem++;
	     		}	

                $arSale['refunds'][$countRefund] = $arRefund;
		$countRefund++;

                }

        }
        

            $arInvoice['sales'][$countSales] = $arSale;
	    $countSales++;
          }

        // платежные поручения по счету
        $rsPayments = \CIBlockElement::GetList(
            Array('property_date' => 'desc'),
            Array('IBLOCK_ID' => IBLOCK_ID_PAYMENTS, 'PROPERTY_INVOICE_ID' => $arInvoiceData['ID']),
            false, false,
            Array('ID', 'NAME', 'EXTERNAL_ID', 'PROPERTY_DATE', 'PROPERTY_COMMENT', 'PROPERTY_AMOUNT')
        );

	$countPayments = 1;
        while ($arPaymentData = $rsPayments->GetNext()) {
            $sDate = reset(explode(' ', $arPaymentData['PROPERTY_DATE_VALUE']));

	    $pay_number = explode("-",$arPaymentData['EXTERNAL_ID']);
            $arPayment = Array(
                'payment_id' => $pay_number[0],
                //'name' => $arPaymentData['NAME'],
                'date' => $sDate,
                //'comment' => $arPaymentData['PROPERTY_COMMENT_VALUE'],
                'amount' => $arPaymentData['PROPERTY_AMOUNT_VALUE'],
            );
            //$arInvoice['PAYED'] += $arPayment['AMOUNT'];
            $arInvoice['payments'][$countPayments] = $arPayment;
	    $countPayments++;
        }



	$arDoc[$countInvoice] = $arInvoice;
	$countInvoice++;
       }

        return $arDoc;
    }

    private function getOrderProperties($orderID)
    {
        $rsProps = \CSaleOrderPropsValue::GetList(Array(), Array("ORDER_ID" => $orderID));
	$arAllowedFields = array("NAME","VALUE");
        $arProps = Array();
        while ($arProp = $rsProps->Fetch()) {
	    foreach ($arProp as $key => $value) {
		if (!in_array($key , $arAllowedFields)) continue;		
		   $arProps[$arProp["CODE"]][$key] = $value;	
		}	
	}
        return $arProps;
    }

    private function getUserArray($userID)
    {

        $arUser = \CUser::GetByID($userID)->Fetch();

        $arResult = Array(
            'user_id' => $arUser['ID'],
            'user_login' => $arUser['LOGIN'],
            'user_email' => $arUser['EMAIL'],
            'user_date_register' => $arUser['DATE_REGISTER'],
            'user_date_last_login' => $arUser['LAST_LOGIN'],
            'user_date_update' => $arUser['TIMESTAMP_X'],	
            'user_firstname' => $arUser['NAME'],
            'user_lastname' => $arUser['LAST_NAME'],
            'user_secondname' => $arUser['SECOND_NAME'],
            'user_phone' => $arUser['PERSONAL_PHONE'],
        );

        if (strlen($arUser['UF_COMPANY_ID']) > 0) {

            $arCompany = \CIBlockElement::GetList(Array(),
                Array('IBLOCK_ID' => IBLOCK_ID_AGENTS, 'ACTIVE' => 'Y', '=ID' => $arUser['UF_COMPANY_ID']),
                false, Array('nTopCount' => 1),
                Array(
                    'ID',
                    'NAME',
                    // юридический адрес
                    'PROPERTY_CITY_LEGAL',
                    'PROPERTY_LOCATION_LEGAL',
                    'PROPERTY_STREET_LEGAL',
                    'PROPERTY_ZIP_LEGAL',
                    'PROPERTY_HOUSE_LEGAL',
                    'PROPERTY_HOUSING_LEGAL',
                    'PROPERTY_OFFICE_LEGAL',
                    'PROPERTY_STAGE_LEGAL',
                    'PROPERTY_COMMENT_LEGAL',

                    // фактический адрес
                    'PROPERTY_ACTUAL_EQUALS_LEGAL',
                    'PROPERTY_CITY_ACTUAL',
                    'PROPERTY_LOCATION_ACTUAL',
                    'PROPERTY_STREET_ACTUAL',
                    'PROPERTY_ZIP_ACTUAL',
                    'PROPERTY_HOUSE_ACTUAL',
                    'PROPERTY_HOUSING_ACTUAL',
                    'PROPERTY_OFFICE_ACTUAL',
                    'PROPERTY_STAGE_ACTUAL',
                    'PROPERTY_COMMENT_ACTUAL',

                    // реквизиты
                    'PROPERTY_BANK',
                    'PROPERTY_BIK',
                    'PROPERTY_INN',
                    'PROPERTY_KPP',
                    'PROPERTY_ACCOUNT_COR',
                    'PROPERTY_ACCOUNT',

                    // контакты
                    'PROPERTY_PHONE',
                    'PROPERTY_FAX',
                    'PROPERTY_EMAIL',

                    'PROPERTY_ID_1C',

                ))->Fetch();

            $arResult['company_id'] = $arCompany['ID'];
            $arResult['company'] = $arCompany['NAME'];
            $arResult['id_1c'] = $arCompany['PROPERTY_ID_1C_VALUE'];

            /**
             * Юридический адрес
             */
            $arLocation = \CSaleLocation::GetByID($arCompany['PROPERTY_LOCATION_LEGAL_VALUE']);
            $arResult['jurCity'] = $arLocation['CITY_NAME'];
            $arResult['jurStreet'] = $arCompany['PROPERTY_STREET_LEGAL_VALUE'];
            $arResult['jurZip'] = $arCompany['PROPERTY_ZIP_LEGAL_VALUE'];
            $arResult['jurBuilding'] = $arCompany['PROPERTY_HOUSE_LEGAL_VALUE'];
            $arResult['jurHousing'] = $arCompany['PROPERTY_HOUSING_LEGAL_VALUE'];
            $arResult['jurOffice'] = $arCompany['PROPERTY_OFFICE_LEGAL_VALUE'];
            $arResult['jurFloor'] = $arCompany['PROPERTY_STAGE_LEGAL_VALUE'];
            $arResult['jurAdditional'] = $arCompany['PROPERTY_COMMENT_LEGAL_VALUE'];


            /**
             * Фактический адрес
             */
            $arLocation = \CSaleLocation::GetByID($arCompany['PROPERTY_LOCATION_ACTUAL_VALUE']);
            $arResult['factCity'] = $arLocation['CITY_NAME'];
            $arResult['factStreet'] = $arCompany['PROPERTY_STREET_ACTUAL_VALUE'];
            $arResult['factZip'] = $arCompany['PROPERTY_ZIP_ACTUAL_VALUE'];
            $arResult['factBuilding'] = $arCompany['PROPERTY_HOUSE_ACTUAL_VALUE'];
            $arResult['factHousing'] = $arCompany['PROPERTY_HOUSING_ACTUAL_VALUE'];
            $arResult['factOffice'] = $arCompany['PROPERTY_OFFICE_ACTUAL_VALUE'];
            $arResult['factFloor'] = $arCompany['PROPERTY_STAGE_ACTUAL_VALUE'];
            $arResult['factAdditional'] = $arCompany['PROPERTY_COMMENT_ACTUAL_VALUE'];

            $arResult['INN'] = $arCompany['PROPERTY_INN_VALUE'];
            $arResult['KPP'] = $arCompany['PROPERTY_KPP_VALUE'];
            $arResult['BIK'] = $arCompany['PROPERTY_BIK_VALUE'];
            $arResult['billNo'] = $arCompany['PROPERTY_ACCOUNT_VALUE'];
            $arResult['Bank'] = $arCompany['PROPERTY_BANK_VALUE'];
            $arResult['bankBillNo'] = $arCompany['PROPERTY_ACCOUNT_COR_VALUE'];
            $arResult['phone'] = $arCompany['PROPERTY_PHONE_VALUE'];
            $arResult['fax'] = $arCompany['PROPERTY_FAX_VALUE'];

        }

        foreach($arResult as $key=>$val){
            $arResult[$key] = htmlspecialchars($val);
        }

        return $arResult;
    }
    /**
     * Возвращает массив с элементами корзины заказа
     * Для каждого элемента определяется IBLOCK_ID и доп св-ва для веб-сервиса: VARIANT_CODE и VARIANT_NAME
     * Ключами массива являются ID позиций
     * @param $orderID номер заказа
     * @return array
     */
    private function getOrderGoods($orderID)
    {
        $rsBasket = \CSaleBasket::GetList(Array(), Array("ORDER_ID" => $orderID));
        $arBasket = Array();
	$countBasket = 1;
        while ($arBasketItem = $rsBasket->Fetch()) {

            $arProduct = \CIBlockElement::GetByID($arBasketItem['PRODUCT_ID'])->Fetch();

            $arItem = Array(
                //'id' => $arBasketItem['ID'],
                'reference' => $arProduct['CODE'],
                'guid' => $arProduct['XML_ID'],
                'name' => preg_replace ('/\s+/', ' ', $arBasketItem['NAME']),
                'quantity' => $arBasketItem['QUANTITY'],
                'price' => $arBasketItem['PRICE'],
                'summa' => $arBasketItem['PRICE'] * $arBasketItem['QUANTITY'],
            );
            $arBasket[$countBasket] = $arItem;
	    $countBasket++;	
        }
        return $arBasket;
    }
    private function prepareDate($dateDB)
    {
        $timestamp = strtotime($dateDB);
        return date('d.m.Y H:i:s', $timestamp);
    }
}