<?php

CModule::IncludeModule('iblock');

/**
 * Обработчики платежных систем
 */
$GUID_certificate = 'a5b0dd53-5feb-490a-9bcc-cefa1284a991';
$import_folder = '/upload/restrict';
$basketItems = getGoodsIdsInBasket();
foreach ($arResult["ORDERS"] as &$arOrder){

	$dbPaySysAction = CSalePaySystemAction::GetList(
		array(),
		array(
				"PAY_SYSTEM_ID" => $arOrder["ORDER"]["PAY_SYSTEM_ID"],
				"PERSON_TYPE_ID" => $arOrder["ORDER"]["PERSON_TYPE_ID"]
			),
		false,
		false,
		array("ACTION_FILE", "PARAMS", "ENCODING")
	);

	if ($arPaySysAction = $dbPaySysAction->Fetch()){
		if (strlen($arPaySysAction["ACTION_FILE"]) > 0){
			$arOrder["ORDER"]["PAY_SYSTEM"]["ACTION_PARAMS"] = $arPaySysAction["PARAMS"];

			$pathToAction = $_SERVER["DOCUMENT_ROOT"].$arPaySysAction["ACTION_FILE"];
			$pathToAction = rtrim(str_replace("\\", "/", $pathToAction), "/");

			if (file_exists($pathToAction)){
				if (is_dir($pathToAction)){
					if (file_exists($pathToAction."/payment.php"))
						$arOrder["ORDER"]["PAY_SYSTEM"]["PATH_TO_ACTION"] = $pathToAction."/payment.php";
				}else{
					$arOrder["ORDER"]["PAY_SYSTEM"]["PATH_TO_ACTION"] = $pathToAction;
				}
			}
		}
	}
}


/**
 * Выбираем для каждого заказа счета, реализации и платежные поручения
 */
foreach ($arResult['ORDERS'] as &$arOrder) {
    $arOrder['ORDER']['INVOICES'] = Array();

	$arOrder['ORDER']['SOLD'] = 0;
	$arOrder['ORDER']['PAYED'] = 0;


    $rsInvoices = CIBlockElement::GetList(
        Array('property_date' => 'desc'),
        Array('IBLOCK_ID' => IBLOCK_ID_INVOICES, 'PROPERTY_ORDER_ID' => $arOrder['ORDER']['ID']),
        false, false,
        Array('ID', 'NAME', 'PROPERTY_DATE', 'PROPERTY_BASKET_ITEMS', 'PROPERTY_AMOUNT')
    );

    if ($rsInvoices->SelectedRowsCount() > 0) {
        $arOrder['ORDER']['PRICE'] = 0;
    }

    while ($arInvoiceData = $rsInvoices->GetNext()) {
        $sDate = reset(explode(' ', $arInvoiceData['PROPERTY_DATE_VALUE']));
        $arInvoice = Array(
            'ID' => $arInvoiceData['ID'],
            'NAME' => $arInvoiceData['NAME'],
            'DATE' => $sDate,
            'BASKET_ITEMS' => $arInvoiceData['PROPERTY_BASKET_ITEMS_VALUE'],
            'AMOUNT' => $arInvoiceData['PROPERTY_AMOUNT_VALUE'],
            'PAYED' => 0,
            'SOLD' => 0,
            'SALES' => Array(),
            'PAYMENTS' => Array(),
            'REFUNDS' => Array(),
        );

        foreach ($arInvoice['BASKET_ITEMS'] as &$arBasketItem) {
            /*$arBasketItem['PICTURE'] = SITE_TEMPLATE_PATH . '/img/catalog/no-image.png';
            if ($arBasketItem['ID'] > 0) {
                $arProduct = CIBlockElement::GetByID($arBasketItem['ID'])->GetNext();
                if ($arProduct['DETAIL_PICTURE'] > 0) {
                    $arPic = CFile::ResizeImageGet($arProduct['DETAIL_PICTURE'], array('width' => 60, 'height' => 60), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                    $arBasketItem['PICTURE'] = $arPic['src'];
                }
                $arBasketItem['URL'] = $arProduct['DETAIL_PAGE_URL'];
            }*/
		$arProduct = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_CATALOG, 'EXTERNAL_ID' => $arBasketItem['CODE'],'ACTIVE' => 'Y'), false, false, array('ID','NAME','CODE','EXTERNAL_ID','PROPERTY_SKU'))->Fetch();
		$arBasketItem['ID'] = $arProduct['ID'];
		if(in_array($arBasketItem['ID'], $basketItems))
			$arBasketItem['IN_BASKET'] = true;

		$arParamPhoto['GUID'] 	= $arProduct['EXTERNAL_ID']; 
		$arParamPhoto['WIDTH']  = 50;
		$arParamPhoto['HEIGHT'] = 50;
		$arBasketItem['PICTURE'] = getURLThumbnailPhoto($arParamPhoto);

		if ($arProduct['CODE']) {
			$arBasketItem['URL'] = '/catalog/item.html/'. $arProduct['CODE'];
			$arBasketItem['REFERENCE'] = "Референс: " . $arProduct['CODE'];
			if ($arProduct['PROPERTY_SKU_VALUE']) 
		 		$arBasketItem['REFERENCE'] = $arBasketItem['REFERENCE'] .", Артикул: ". $arProduct['PROPERTY_SKU_VALUE'];
		}
        }


        $arOrder['ORDER']['PRICE'] += $arInvoice['AMOUNT'];

        // реализация товаров по счету
		$arRealizFilter = Array('IBLOCK_ID' => IBLOCK_ID_SALES, 'PROPERTY_INVOICE_ID' => $arInvoice['ID']);
		if(!empty($arResult['AVAIL_REALIZATION_STATUS']))
			$arRealizFilter['PROPERTY_STATUS'] = $arResult['AVAIL_REALIZATION_STATUS'];

        $rsSales = CIBlockElement::GetList(
            Array('property_date' => 'desc'),
            $arRealizFilter,
            false, false,
            Array('ID', 'NAME', 'CODE', 'PROPERTY_INVOICE_ID', 'PROPERTY_DATE', 'PROPERTY_COMMENT', 'PROPERTY_AMOUNT', 'PROPERTY_BASKET_ITEMS')
        );

		$bHaveRealiz = false;
        $salesIds = [];

        while ($arSaleData = $rsSales->GetNext()) {
			$bHaveRealiz = true;
            $sDate = reset(explode(' ', $arSaleData['PROPERTY_DATE_VALUE']));

            $salesIds[] = $arSaleData['ID'];

            $arSale = Array(
                'ID' => $arSaleData['ID'],
                'NAME' => $arSaleData['NAME'],
                'GUID' => $arSaleData['CODE'],
                'DATE' => $sDate,
                'BASKET_ITEMS' => $arSaleData['PROPERTY_BASKET_ITEMS_VALUE'],
                'COMMENT' => $arSaleData['PROPERTY_COMMENT_VALUE']['TEXT'],
                'AMOUNT' => $arSaleData['PROPERTY_AMOUNT_VALUE'],
                'CARGO' => Array(),
            );

            foreach ($arSale['BASKET_ITEMS'] as &$arBasketItem) {
                /*$arBasketItem['PICTURE'] = SITE_TEMPLATE_PATH . '/img/catalog/no-image.png';

                if ($arBasketItem['ID'] > 0) {
                    $arProduct = CIBlockElement::GetByID($arBasketItem['ID'])->GetNext();
                    if ($arProduct['DETAIL_PICTURE'] > 0) {
                        $arPic = CFile::ResizeImageGet($arProduct['DETAIL_PICTURE'], array('width' => 60, 'height' => 60), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                        $arBasketItem['PICTURE'] = $arPic['src'];

                    }
                    $arBasketItem['URL'] = $arProduct['DETAIL_PAGE_URL'];
                } */
		$arProduct = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_CATALOG, 'EXTERNAL_ID' => $arBasketItem['CODE'],'ACTIVE' => 'Y'), false, false, array('ID','NAME','CODE','EXTERNAL_ID','PROPERTY_SKU','PROPERTY_FILES_PRODUCT'))->Fetch();

		$arParamPhoto['GUID'] 	= $arProduct['EXTERNAL_ID']; 
		$arParamPhoto['WIDTH']  = 50;
		$arParamPhoto['HEIGHT'] = 50;
		$arBasketItem['PICTURE'] = getURLThumbnailPhoto($arParamPhoto);
		if ($arProduct['CODE']) {
		$prop_files_product = $arProduct['PROPERTY_FILES_PRODUCT_VALUE'];
		$arBasketItem['ID'] = $arProduct['ID'];
		if(in_array($arBasketItem['ID'], $basketItems))
			$arBasketItem['IN_BASKET'] = true;
		$arBasketItem['REFERENCE'] = "Референс: " . $arProduct['CODE'];
		if ($arProduct['PROPERTY_SKU_VALUE']) 
		 	$arBasketItem['REFERENCE'] = $arBasketItem['REFERENCE'] .", Артикул: ". $arProduct['PROPERTY_SKU_VALUE'];
                $arBasketItem['URL'] = '/catalog/item.html/'. $arProduct['CODE'];		
                if($prop_files_product){
                    $arFilesProduct = array();
                    foreach($prop_files_product as $sFileXmlId) {
                        $arSelect = Array("ID", "NAME", "PROPERTY_PATH", "PROPERTY_FILESGROUP");
                        $arFilter = Array("XML_ID" => $sFileXmlId, "ACTIVE" => "Y");
                        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                        while ($arFile = $res->GetNext()) {
                            $sFilesGroupXmlId = $arFile['PROPERTY_FILESGROUP_VALUE'];
                            $arFilesGroup = CIBlockElement::GetList(array(), array('XML_ID' => $sFilesGroupXmlId), false, false, array('ID', 'NAME', "EXTERNAL_ID"))->GetNext();
			    if ($arFilesGroup['EXTERNAL_ID'] == $GUID_certificate) {
				$arBasketItem['FILES'] .= ' <a href="'.$import_folder . $arFile['PROPERTY_PATH_VALUE']. '" class="orange" target="_blank">'. $arFile['NAME'].'</a>'; 
			    }
			    
                         }
		    }

		}
			if ($arBasketItem['FILES']) $arBasketItem['FILES'] = '<span class="files-download">Скачать сертификаты: ' . $arBasketItem['FILES'] . '</span>'; 
	     }	
          }

            // отправка груза
            $rsCargo = CIBlockElement::GetList(
                Array('property_date' => 'desc'),
                Array('IBLOCK_ID' => IBLOCK_ID_CARGO, 'PROPERTY_SALE' => $arSale['ID']),
                false, false,
                Array('ID', 'NAME', 'PROPERTY_SALE_ID', 'PROPERTY_DATE', 'PROPERTY_COMMENT', 'PROPERTY_AMOUNT', 'PROPERTY_COMPANY', 'PROPERTY_RECEIPT')
            );

            while ($arCargoData = $rsCargo->GetNext()) {
                $sDate = reset(explode(' ', $arCargoData['PROPERTY_DATE_VALUE']));

                $arCargo = Array(
                    'ID' => $arCargoData['ID'],
                    'NAME' => $arCargoData['NAME'],
                    'DATE' => $sDate,
                    'COMPANY' => $arCargoData['PROPERTY_COMPANY_VALUE'],
                    'COMMENT' => $arCargoData['PROPERTY_COMMENT_VALUE']['TEXT'],
                    'AMOUNT' => $arCargoData['PROPERTY_AMOUNT_VALUE'],
                    'RECEIPT' => $arCargoData['PROPERTY_RECEIPT_VALUE'],
                );
                $arSale['CARGO'][] = $arCargo;
            }

            $arInvoice['SOLD'] += $arSale['AMOUNT'];
            $arInvoice['SALES'][] = $arSale;
        }

        // достаем возвраты по реализациям
        if(!empty($salesIds)){

            $arRefundsFilter = Array('IBLOCK_ID' => IBLOCK_ID_REFUNDS, 'PROPERTY_SALE_ID' => $salesIds);

            $rsRefunds = CIBlockElement::GetList(
                Array('property_date' => 'desc'),
                $arRefundsFilter,
                false, false,
                Array('ID', 'NAME', 'PROPERTY_SALE_ID', 'PROPERTY_DATE', 'PROPERTY_COMMENT', 'PROPERTY_AMOUNT', 'PROPERTY_BASKET_ITEMS')
            );

            while ($arRefundData = $rsRefunds->GetNext()) {

                $sDate = reset(explode(' ', $arRefundData['PROPERTY_DATE_VALUE']));

                $arSale = Array(
                    'ID' => $arRefundData['ID'],
                    'NAME' => $arRefundData['NAME'],
                    'DATE' => $sDate,
                    'BASKET_ITEMS' => $arRefundData['PROPERTY_BASKET_ITEMS_VALUE'],
                    'COMMENT' => $arRefundData['PROPERTY_COMMENT_VALUE']['TEXT'],
                    'AMOUNT' => $arRefundData['PROPERTY_AMOUNT_VALUE'],
                );

                foreach ($arSale['BASKET_ITEMS'] as &$arBasketItem) {
                    $arBasketItem['PICTURE'] = SITE_TEMPLATE_PATH . '/img/catalog/no-image.png';

                    if ($arBasketItem['ID'] > 0) {
                        $arProduct = CIBlockElement::GetByID($arBasketItem['ID'])->GetNext();
                        if ($arProduct['DETAIL_PICTURE'] > 0) {
                            $arPic = CFile::ResizeImageGet($arProduct['DETAIL_PICTURE'], array('width' => 60, 'height' => 60), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                            $arBasketItem['PICTURE'] = $arPic['src'];
                        }
                        $arBasketItem['URL'] = $arProduct['DETAIL_PAGE_URL'];
                    }
                }

                $arInvoice['REFUNDS'][] = $arSale;
            }
        }

		if(!$bHaveRealiz && !empty($arResult['AVAIL_REALIZATION_STATUS']))
			continue;

        // платежные поручения по счету
        $rsPayments = CIBlockElement::GetList(
            Array('property_date' => 'desc'),
            Array('IBLOCK_ID' => IBLOCK_ID_PAYMENTS, 'PROPERTY_INVOICE_ID' => $arInvoice['ID']),
            false, false,
            Array('ID', 'NAME', 'PROPERTY_DATE', 'PROPERTY_COMMENT', 'PROPERTY_AMOUNT')
        );

        while ($arPaymentData = $rsPayments->GetNext()) {
            $sDate = reset(explode(' ', $arPaymentData['PROPERTY_DATE_VALUE']));

            $arPayment = Array(
                'ID' => $arPaymentData['ID'],
                'NAME' => $arPaymentData['NAME'],
                'DATE' => $sDate,
                'COMMENT' => $arPaymentData['PROPERTY_COMMENT_VALUE'],
                'AMOUNT' => $arPaymentData['PROPERTY_AMOUNT_VALUE'],
            );
            $arInvoice['PAYED'] += $arPayment['AMOUNT'];
            $arInvoice['PAYMENTS'][] = $arPayment;
        }

        $arOrder['ORDER']['PAYED'] += $arInvoice['PAYED'];
        $arOrder['ORDER']['SOLD'] += $arInvoice['SOLD'];

        $arOrder['ORDER']['INVOICES'][] = $arInvoice;
    }
}