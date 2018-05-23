<?use Bitrix\Highloadblock\HighloadBlockTable;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!CModule::IncludeModule("sale")) {
    ShowError(GetMessage("SALE_MODULE_NOT_INSTALL"));
    return;
}
if (!$USER->IsAuthorized()) {
    $APPLICATION->AuthForm(GetMessage("SALE_ACCESS_DENIED"));
}

$arParams["PATH_TO_DETAIL"] = Trim($arParams["PATH_TO_DETAIL"]);
if (strlen($arParams["PATH_TO_DETAIL"]) <= 0)
    $arParams["PATH_TO_DETAIL"] = htmlspecialcharsbx($APPLICATION->GetCurPage() . "?" . "ID=#ID#");

$arParams["PATH_TO_COPY"] = Trim($arParams["PATH_TO_COPY"]);
if (strlen($arParams["PATH_TO_COPY"]) <= 0)
    $arParams["PATH_TO_COPY"] = htmlspecialcharsbx($APPLICATION->GetCurPage() . "?" . "ID=#ID#");

$arParams["PATH_TO_CANCEL"] = Trim($arParams["PATH_TO_CANCEL"]);
if (strlen($arParams["PATH_TO_CANCEL"]) <= 0)
    $arParams["PATH_TO_CANCEL"] = htmlspecialcharsbx($APPLICATION->GetCurPage() . "?" . "ID=#ID#");

$arParams["PATH_TO_BASKET"] = Trim($arParams["PATH_TO_BASKET"]);
if (strlen($arParams["PATH_TO_BASKET"]) <= 0)
    $arParams["PATH_TO_BASKET"] = "basket.php";

$arParams["PATH_TO_COPY"] .= (strpos($arParams["PATH_TO_COPY"], "?") === false ? "?" : "&amp;");
$arParams["PATH_TO_CANCEL"] .= (strpos($arParams["PATH_TO_CANCEL"], "?") === false ? "?" : "&amp;");

if ($arParams["SET_TITLE"] == 'Y')
    $APPLICATION->SetTitle(GetMessage("SPOL_DEFAULT_TITLE"));
if ($arParams["SAVE_IN_SESSION"] != "N")
    $arParams["SAVE_IN_SESSION"] = "Y";

$arParams["NAV_TEMPLATE"] = (strlen($arParams["NAV_TEMPLATE"]) > 0 ? $arParams["NAV_TEMPLATE"] : "");

$arParams["ORDERS_PER_PAGE"] = (intval($arParams["ORDERS_PER_PAGE"]) <= 0 ? 20 : intval($arParams["ORDERS_PER_PAGE"]));

$bUseAccountNumber = (COption::GetOptionString("sale", "account_number_template", "") !== "") ? true : false;

//Copy order
$ID = urldecode(urldecode($arParams["ID"]));

if (strlen($ID) > 0 && $_REQUEST["COPY_ORDER"] == "Y") {
    $arOrder = false;
    if ($bUseAccountNumber) {
        $dbOrder = CSaleOrder::GetList(Array("ID" => "DESC"), Array("ACCOUNT_NUMBER" => $ID, "USER_ID" => IntVal($USER->GetID()), "LID" => SITE_ID));
        $arOrder = $dbOrder->Fetch();
    }

    if (!$arOrder) {
        $dbOrder = CSaleOrder::GetList(Array("ID" => "DESC"), Array("ID" => $ID, "USER_ID" => IntVal($USER->GetID()), "LID" => SITE_ID));
        $arOrder = $dbOrder->Fetch();
    }

    if ($arOrder) {
        $dbBasket = CSaleBasket::GetList(Array("ID" => "ASC"), Array("ORDER_ID" => $arOrder["ID"]));
        while ($arBasket = $dbBasket->Fetch()) {
            if (CSaleBasketHelper::isSetItem($arBasket))
                continue;

            $arFields = Array();
            $arProps = array();
            $dbBasketProps = CSaleBasket::GetPropsList(
                array("SORT" => "ASC"),
                array("BASKET_ID" => $arBasket["ID"]),
                false,
                false,
                array("ID", "BASKET_ID", "NAME", "VALUE", "CODE", "SORT")
            );

            if ($arBasketProps = $dbBasketProps->Fetch()) {
                do {
                    $arProps[] = array(
                        "NAME" => $arBasketProps["NAME"],
                        "CODE" => $arBasketProps["CODE"],
                        "VALUE" => $arBasketProps["VALUE"]
                    );
                } while ($arBasketProps = $dbBasketProps->Fetch());
            }

            $arFields = array(
                "PRODUCT_ID" => $arBasket["PRODUCT_ID"],
                "PRODUCT_PRICE_ID" => $arBasket["PRODUCT_PRICE_ID"],
                "PRICE" => $arBasket["PRICE"],
                "CURRENCY" => $arBasket["CURRENCY"],
                "WEIGHT" => $arBasket["WEIGHT"],
                "QUANTITY" => $arBasket["QUANTITY"],
                "LID" => $arBasket["LID"],
                "DELAY" => "N",
                "CAN_BUY" => "Y",
                "NAME" => $arBasket["NAME"],
                "CALLBACK_FUNC" => $arBasket["CALLBACK_FUNC"],
                "MODULE" => $arBasket["MODULE"],
                "NOTES" => $arBasket["NOTES"],
                "PRODUCT_PROVIDER_CLASS" => $arBasket["PRODUCT_PROVIDER_CLASS"],
                "CANCEL_CALLBACK_FUNC" => $arBasket["CANCEL_CALLBACK_FUNC"],
                "ORDER_CALLBACK_FUNC" => $arBasket["ORDER_CALLBACK_FUNC"],
                "PAY_CALLBACK_FUNC" => $arBasket["PAY_CALLBACK_FUNC"],
                "DETAIL_PAGE_URL" => $arBasket["DETAIL_PAGE_URL"],
                "CATALOG_XML_ID" => $arBasket["CATALOG_XML_ID"],
                "PRODUCT_XML_ID" => $arBasket["PRODUCT_XML_ID"],
                "VAT_RATE" => $arBasket["VAT_RATE"],
                "PROPS" => $arProps,
                "TYPE" => $arBasket["TYPE"]
            );

            if (strlen($arBasket["PRODUCT_PROVIDER_CLASS"]) > 0)
                $arFields["PRODUCT_PROVIDER_CLASS"] = $arBasket["PRODUCT_PROVIDER_CLASS"];
            elseif ($arFields["MODULE"] == "catalog")
                $arFields["PRODUCT_PROVIDER_CLASS"] = "CCatalogProductProvider";

            CSaleBasket::Add($arFields);
        }
        LocalRedirect($arParams["PATH_TO_BASKET"]);
    }
}

//Save statuses for Filter form
$dbStatus = CSaleStatus::GetList(Array("SORT" => "ASC"), array("LID" => LANGUAGE_ID));
while ($arStatus = $dbStatus->GetNext())
    $arResult["INFO"]["STATUS"][$arStatus["ID"]] = $arStatus;

$dbPaySystem = CSalePaySystem::GetList(Array("SORT" => "ASC"));
while ($arPaySystem = $dbPaySystem->GetNext())
    $arResult["INFO"]["PAY_SYSTEM"][$arPaySystem["ID"]] = $arPaySystem;

$dbDelivery = CSaleDelivery::GetList(Array("SORT" => "ASC"));
while ($arDelivery = $dbDelivery->GetNext())
    $arResult["INFO"]["DELIVERY"][$arDelivery["ID"]] = $arDelivery;

$arResult["INFO"]["DELIVERY_HANDLERS"] = array();
$dbDelivery = CSaleDeliveryHandler::GetList(array(), array(array("SITE_ID" => SITE_ID)));
while ($arDeliveryHandler = $dbDelivery->GetNext()) {
    $arResult["INFO"]["DELIVERY_HANDLERS"][$arDeliveryHandler["SID"]] = $arDeliveryHandler;
}

$arResult["REALIZATION_STATUS"] = array();
if (defined("REALIZATION_STATUS_DICTIONARY") && CModule::IncludeModule("highloadblock")) {
    $obQueryResult = HighloadBlockTable::getById(REALIZATION_STATUS_DICTIONARY);
    if ($arHL = $obQueryResult->fetch()) {
        $obHLEntity = HighloadBlockTable::compileEntity($arHL);

        $hlTable = "{$arHL["NAME"]}Table";
        $obQueryResult = $hlTable::GetList(array("order" => array("UF_SORT" => "ASC")));
        while ($arStatus = $obQueryResult->Fetch()){
            $arResult["REALIZATION_STATUS"][$arStatus['UF_GROUP']][] = $arStatus;
        }
        foreach($arResult["REALIZATION_STATUS"] as &$group){
            \Bitrix\Main\Type\Collection::sortByColumn($group, ['UF_SORT' => SORT_ASC]);
        }
        ksort($arResult["REALIZATION_STATUS"], SORT_NATURAL);
    }
}

$arResult["CURRENT_PAGE"] = $APPLICATION->GetCurPage();

//Preparing filter
$arFilter = Array();
$arFilter["USER_ID"] = IntVal($USER->GetID());

if (strlen($_REQUEST["del_filter"]) > 0) {
    unset($_REQUEST["filter_id"]);
    unset($_REQUEST["filter_date_from"]);
    unset($_REQUEST["filter_date_to"]);
    unset($_REQUEST["filter_status"]);
    unset($_REQUEST["filter_payed"]);
    unset($_REQUEST["filter_canceled"]);
    $_REQUEST["filter_history"] = "Y";
    if ($arParams["SAVE_IN_SESSION"] == "Y") {
        unset($_SESSION["spo_filter_id"]);
        unset($_SESSION["spo_filter_date_from"]);
        unset($_SESSION["spo_filter_date_to"]);
        unset($_SESSION["spo_filter_status"]);
        unset($_SESSION["spo_filter_payed"]);
        unset($_SESSION["spo_filter_canceled"]);
        $_SESSION["spo_filter_history"] = "Y";
    }
}

if ($arParams["SAVE_IN_SESSION"] == "Y" && strlen($_REQUEST["filter"]) <= 0) {
    if (IntVal($_SESSION["spo_filter_id"]) > 0)
        $_REQUEST["filter_id"] = $_SESSION["spo_filter_id"];
    if (strlen($_SESSION["spo_filter_date_from"]) > 0)
        $_REQUEST["filter_date_from"] = $_SESSION["spo_filter_date_from"];
    if (strlen($_SESSION["spo_filter_date_to"]) > 0)
        $_REQUEST["filter_date_to"] = $_SESSION["spo_filter_date_to"];
    if (strlen($_SESSION["spo_filter_status"]) > 0)
        $_REQUEST["filter_status"] = $_SESSION["spo_filter_status"];
    if (strlen($_SESSION["spo_filter_payed"]) > 0)
        $_REQUEST["filter_payed"] = $_SESSION["spo_filter_payed"];
    if (strlen($_SESSION["spo_filter_canceled"]) > 0)
        $_REQUEST["filter_canceled"] = $_SESSION["spo_filter_canceled"];
    if ($_SESSION["spo_filter_history"] == "Y")
        $_REQUEST["filter_history"] == "Y";
}

if (strlen($_REQUEST["filter_canceled"]) > 0)
    $arFilter["CANCELED"] = Trim($_REQUEST["filter_canceled"]);

$arFilter["LID"] = SITE_ID;
if ($arParams["SAVE_IN_SESSION"] == "Y" && strlen($_REQUEST["filter"]) > 0) {
    $_SESSION["spo_filter_id"] = $_REQUEST["filter_id"];
    $_SESSION["spo_filter_date_from"] = $_REQUEST["filter_date_from"];
    $_SESSION["spo_filter_date_to"] = $_REQUEST["filter_date_to"];
    $_SESSION["spo_filter_status"] = $_REQUEST["filter_status"];
    $_SESSION["spo_filter_payed"] = $_REQUEST["filter_payed"];
    $_SESSION["spo_filter_history"] = $_REQUEST["filter_history"];
}

$by = (strlen($_REQUEST["by"]) > 0 ? $_REQUEST["by"] : "ID");
$order = (strlen($_REQUEST["order"]) > 0 ? $_REQUEST["order"] : "DESC");

$arCurUser = CUser::GetByID($USER->GetID())->Fetch();
if ($arCurUser['UF_PAYER_TYPE'] == SALE_PERSON_YUR) {
    if (strlen($arCurUser['UF_COMPANY_ID']) > 0) {
        $arUserIDS = Array();
        $rsUsers = CUser::GetList(($by2 = "ID"), ($order2 = "asc"), Array('UF_COMPANY_ID' => $arCurUser['UF_COMPANY_ID'], 'ACTIVE' => 'Y'));
        while ($arUser = $rsUsers->Fetch()) {
            $arUserIDS[] = $arUser['ID'];
        }

        if (!empty($arUserIDS)) {
            $arFilter['USER_ID'] = $arUserIDS;
        } else {
            $arFilter['USER_ID'] = "-1";
        }
    } else {
        $arFilter['USER_ID'] = "-1";
    }
}

if ($arParams['USE_FILTER'] == 'Y') {
    $sStatusFilter = trim($_REQUEST['status']);
    $arResult['FILTER_STATUS'] = $sStatusFilter;
    if (strlen($sStatusFilter) > 0) {
        if ($_REQUEST["real"] == "Y") {
            //фильтруем не по самим заявкам, а по реализациям
			$arResult['AVAIL_REALIZATION_STATUS'] = $sStatusFilter;
			$availableOrders = array();

			$arRealizationFilter = array(
				'IBLOCK_ID' => IBLOCK_ID_SALES,
				'PROPERTY_STATUS' => $sStatusFilter
			);
			$arRealizationSelect = array(
				'ID',
				'IBLOCK_ID',
				'PROPERTY_INVOICE_ID.PROPERTY_ORDER_ID'
			);

			$dbElems = CIBlockElement::GetList(array(), $arRealizationFilter, false, false, $arRealizationSelect);
			while($elem = $dbElems->Fetch()){
				$orderId = $elem['PROPERTY_INVOICE_ID_PROPERTY_ORDER_ID_VALUE'];
				$availableOrders[$orderId] = $orderId;
			}

			if(count($availableOrders) > 0)
				$arFilter['ID'] = array_keys($availableOrders);
			else
				$arFilter['ID'] = false;

        } else
            $arFilter['STATUS_ID'] = $sStatusFilter;
    }

    $arResult['FILTER_DATE_TO'] = date('d.m.Y');
    $arResult['FILTER_DATE_FROM'] = date('d.m.Y', time() - 86400 * 31);

    $sDateFrom = trim($_REQUEST['date_from']);
    if (strlen($sDateFrom) > 0 && $arDateFrom = ParseDateTime($sDateFrom, 'DD.MM.YYYY')) {
        $arResult['FILTER_DATE_FROM'] = $arDateFrom['DD'] . '.' . $arDateFrom['MM'] . '.' . $arDateFrom['YYYY'];
    }

    $sDateTo = trim($_REQUEST['date_to']);
    if (strlen($sDateTo) > 0 && $arDateTo = ParseDateTime($sDateTo, 'DD.MM.YYYY')) {
        $arResult['FILTER_DATE_TO'] = $arDateTo['DD'] . '.' . $arDateTo['MM'] . '.' . $arDateTo['YYYY'];
    }
    $arFilter['DATE_FROM'] = $arResult['FILTER_DATE_FROM'] . ' 00:00:00';
    $arFilter['DATE_TO'] = $arResult['FILTER_DATE_TO'] . ' 23:59:59';

    $arResult['FILTER_QUERY'] = trim($_REQUEST['q']);

    if (strlen($arResult['FILTER_QUERY']) > 0) {
        if(is_array($arFilter['ID'])){
			$arFilter['ID'][] = $arResult['FILTER_QUERY'];
			$arFilter['ID'] = array_unique($arFilter['ID']);
		}else{
			$arFilter['ID'] = $arResult['FILTER_QUERY'];
		}

        unset($arFilter['DATE_FROM']);
        unset($arFilter['DATE_TO']);
    }

    $arResult['FILTER_QUERY'] = htmlspecialcharsEx($arResult['FILTER_QUERY']);
}

if (!is_array($arParams['FILTER'])) $arParams['FILTER'] = Array();
$arFilter = array_merge($arFilter, $arParams['FILTER']);

if($arParams['ONLY_WITH_INVOICES'] == 'Y'){
	$orderIds = array();
	$dbElems = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_INVOICES), array('PROPERTY_ORDER_ID'), false, array('ID', 'PROPERTY_ORDER_ID'));
	while($elem = $dbElems->Fetch()){
		$orderIds[] = $elem['PROPERTY_ORDER_ID_VALUE'];
	}

	if(is_array($arFilter['ID'])){
		$arFilter['ID'] = array_merge($arFilter['ID'], $orderIds);
		$arFilter['ID'] = array_unique($arFilter['ID']);
	}else{
		$arFilter['ID'] = $orderIds;
	}
}

$dbOrder = CSaleOrder::GetList(Array($by => $order), $arFilter);
$dbOrder->NavStart($arParams["ORDERS_PER_PAGE"], false);
$arResult["NAV_STRING"] = $dbOrder->GetPageNavString(GetMessage("SPOL_PAGES"), $arParams["NAV_TEMPLATE"]);
$arResult["CURRENT_PAGE"] = $APPLICATION->GetCurPage();
$arResult["ORDERS"] = Array();

while ($arOrder = $dbOrder->GetNext()) {
    $arOrder["FORMATED_PRICE"] = SaleFormatCurrency($arOrder["PRICE"], $arOrder["CURRENCY"]);
    $arOrder["CAN_CANCEL"] = (($arOrder["CANCELED"] != "Y" && $arOrder["STATUS_ID"] != "F" && $arOrder["PAYED"] != "Y") ? "Y" : "N");

    $arOrder["URL_TO_DETAIL"] = CComponentEngine::MakePathFromTemplate($arParams["PATH_TO_DETAIL"], Array("ID" => urlencode(urlencode($arOrder["ACCOUNT_NUMBER"]))));
    $arOrder["URL_TO_COPY"] = CComponentEngine::MakePathFromTemplate($arParams["PATH_TO_COPY"], Array("ID" => urlencode(urlencode($arOrder["ACCOUNT_NUMBER"])))) . "COPY_ORDER=Y";
    $arOrder["URL_TO_CANCEL"] = CComponentEngine::MakePathFromTemplate($arParams["PATH_TO_CANCEL"], Array("ID" => urlencode(urlencode($arOrder["ACCOUNT_NUMBER"])))) . "CANCEL=Y";

    $arOBasket = Array();
    $dbBasket = CSaleBasket::GetList(($b = "NAME"), ($o = "ASC"), array("ORDER_ID" => $arOrder["ID"]));
    while ($arBasket = $dbBasket->Fetch()) {
        if (CSaleBasketHelper::isSetItem($arBasket))
            continue;

        $arBasket["NAME~"] = $arBasket["NAME"];
        $arBasket["NOTES~"] = $arBasket["NOTES"];
        $arBasket["NAME"] = htmlspecialcharsEx($arBasket["NAME"]);
        $arBasket["NOTES"] = htmlspecialcharsEx($arBasket["NOTES"]);
        $arBasket["QUANTITY"] = DoubleVal($arBasket["QUANTITY"]);

        $arOBasket[] = $arBasket;
    }

    $arOBasket = getMeasures($arOBasket);

    $arResult["ORDERS"][] = Array(
        "ORDER" => $arOrder,
        "BASKET_ITEMS" => $arOBasket,
    );
}

//get current discount value

$arUser = CUser::GetList(($by = "ID"), ($order = "asc"), array('ID' => $USER->GetID()), array('SELECT' => array('UF_COMPANY_ID')))->Fetch();
if($arUser && !empty($arUser['UF_COMPANY_ID'])){
	$arContractFilter = array(
		'IBLOCK_ID' => IBLOCK_ID_CONTRACTS,
		'PROPERTY_AGENT.ID' => $arUser['UF_COMPANY_ID']
	);
	$arSelect = array(
		'ID', 'IBLOCK_ID', 'PROPERTY_DISCOUNT'
	);

	$arContract = CIBlockElement::GetList(array('created_date' => 'desc'), $arContractFilter, false, false, $arSelect)->Fetch();
	if($arContract){
		$arResult['CURRENT_DISCOUNT'] = $arContract['PROPERTY_DISCOUNT_VALUE'];
	}
}

$this->IncludeComponentTemplate();
?>