<?
/*
 * Компонента изменена.
 * Пункт ТЗ 1.17. Оформление заказа
 * Логика оформления заказа поменялась,
 * Например, шаг с выбором юр лица отсутствует, элементы профиля разнесены по этапам 
 * (Выбор города (элемент профиля) на одном этапе, заполнение параметров профиля на последущих, 
 * добавление документом на завершающем этапе).
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

require_once ($_SERVER["DOCUMENT_ROOT"].'/local/components/texenergo/order_functions.php');

if (!CModule::IncludeModule("sale"))
{
	ShowError(GetMessage("SALE_MODULE_NOT_INSTALL"));
	return;
}

$arParams["PATH_TO_BASKET"] = Trim($arParams["PATH_TO_BASKET"]);
if (strlen($arParams["PATH_TO_BASKET"]) <= 0)
	$arParams["PATH_TO_BASKET"] = "basket.php";

$arParams["PATH_TO_PERSONAL"] = Trim($arParams["PATH_TO_PERSONAL"]);
if (strlen($arParams["PATH_TO_PERSONAL"]) <= 0)
	$arParams["PATH_TO_PERSONAL"] = "index.php";

$arParams["PATH_TO_PAYMENT"] = Trim($arParams["PATH_TO_PAYMENT"]);
if (strlen($arParams["PATH_TO_PAYMENT"]) <= 0)
	$arParams["PATH_TO_PAYMENT"] = "payment.php";

$arParams["PATH_TO_AUTH"] = Trim($arParams["PATH_TO_AUTH"]);
if (strlen($arParams["PATH_TO_AUTH"]) <= 0)
	$arParams["PATH_TO_AUTH"] = "/auth.php";

$arParams["ALLOW_PAY_FROM_ACCOUNT"] = (($arParams["ALLOW_PAY_FROM_ACCOUNT"] == "N") ? "N" : "Y");
$arParams["COUNT_DELIVERY_TAX"] = (($arParams["COUNT_DELIVERY_TAX"] == "Y") ? "Y" : "N");
$arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"] = (($arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"] == "Y") ? "Y" : "N");
$arParams["PATH_TO_ORDER"] = $APPLICATION->GetCurPage();
$arParams["SHOW_MENU"] = ($arParams["SHOW_MENU"] == "N" ? "N" : "Y" );
$arParams["ALLOW_EMPTY_CITY"] = ($arParams["CITY_OUT_LOCATION"] == "N" ? "N" : "Y" );

$arParams["SHOW_AJAX_LOCATIONS"] = $arParams["SHOW_AJAX_LOCATIONS"] == 'N' ? 'N' : 'Y';

$arParams['PRICE_VAT_SHOW_VALUE'] = $arParams['PRICE_VAT_SHOW_VALUE'] == 'N' ? 'N' : 'Y';

$arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] = (($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y") ? "Y" : "N");
$arParams["SEND_NEW_USER_NOTIFY"] = (($arParams["SEND_NEW_USER_NOTIFY"] == "N") ? "N" : "Y");
$arResult["AUTH"]["new_user_registration_email_confirmation"] = ((COption::GetOptionString("main", "new_user_registration_email_confirmation", "N") == "Y") ? "Y" : "N");
$arResult["AUTH"]["new_user_registration"] = ((COption::GetOptionString("main", "new_user_registration", "Y") == "Y") ? "Y" : "N");

$arResult['TOWN_VARIANTS'] = getTownVariants();

$bUseAccountNumber = (COption::GetOptionString("sale", "account_number_template", "") !== "") ? true : false;

if (!$arParams["DELIVERY_NO_SESSION"])
	$arParams["DELIVERY_NO_SESSION"] = "N";

if($arParams["SET_TITLE"] == "Y")
{
	if($USER->IsAuthorized())
		$APPLICATION->SetTitle(GetMessage("STOF_MAKING_ORDER"));
	else
		$APPLICATION->SetTitle(GetMessage("STOF_AUTH"));
}

if(strlen($arResult["POST"]["ORDER_PRICE"])>0)
	$arResult["ORDER_PRICE"]  = doubleval($arResult["POST"]["ORDER_PRICE"]);
if(strlen($arResult["POST"]["ORDER_WEIGHT"])>0)
	$arResult["ORDER_WEIGHT"] = doubleval($arResult["POST"]["ORDER_WEIGHT"]);

$arResult["WEIGHT_UNIT"] = htmlspecialcharsbx(COption::GetOptionString('sale', 'weight_unit', "", SITE_ID));
$arResult["WEIGHT_KOEF"] = htmlspecialcharsbx(COption::GetOptionString('sale', 'weight_koef', 1, SITE_ID));

$GLOBALS['CATALOG_ONETIME_COUPONS_BASKET']=null;
$GLOBALS['CATALOG_ONETIME_COUPONS_ORDER']=null;

$allCurrency = CSaleLang::GetLangCurrency(SITE_ID);

if ($_SERVER["REQUEST_METHOD"] == "POST" && ($arParams["DELIVERY_NO_SESSION"] == "N" || check_bitrix_sessid()))
{
	foreach($_POST as $k => $v)
	{
		if(!is_array($v))
		{
			$arResult["POST"][$k] = htmlspecialcharsex($v);
			$arResult["POST"]['~'.$k] = $v;
		}
		else
		{
			foreach($v as $kk => $vv)
			{
				$arResult["POST"][$k][$kk] = htmlspecialcharsex($vv);
				$arResult["POST"]['~'.$k][$kk] = $vv;
			}
		}
	}
}

$arResult["SKIP_FIRST_STEP"] = (($arResult["POST"]["SKIP_FIRST_STEP"] == "Y") ? "Y" : "N");
$arResult["SKIP_SECOND_STEP"] = (($arResult["POST"]["SKIP_SECOND_STEP"] == "Y") ? "Y" : "N");
$arResult["SKIP_THIRD_STEP"] = (($arResult["POST"]["SKIP_THIRD_STEP"] == "Y") ? "Y" : "N");
$arResult["SKIP_FORTH_STEP"] = (($arResult["POST"]["SKIP_FORTH_STEP"] == "Y") ? "Y" : "N");

/*
 * BEGIN определяем тип пользователя для сохранения значения в профиле
 */
$rsUser = $USER->GetByID($USER->GetID());
$arUser = $rsUser->Fetch();
$juridicalPerson = isset($arUser['UF_JURIDICAL_PERSON']) ? (int)$arUser['UF_JURIDICAL_PERSON'] : 0;
$juridicalPerson = 1;
$arResult["POST"]["PERSON_TYPE"] = (int)$juridicalPerson ?  USER_TYPE_JURIDICAL : USER_TYPE_PHISICAL;
$prefix = (int)$juridicalPerson ? 'J_' : 'USER_';
$arResult['PROFILE_VARS'] = array();
/*
 * END определяем тип пользователя
*/

if(strlen($arResult["POST"]["PERSON_TYPE"])>0)
	$arResult["PERSON_TYPE"] = IntVal($arResult["POST"]["PERSON_TYPE"]);
if(strlen($arResult["POST"]["PROFILE_ID"])>0)
{
	$arResult["PROFILE_ID"] = IntVal($arResult["POST"]["PROFILE_ID"]);
	$dbUserProfiles = CSaleOrderUserProps::GetList(
			array("DATE_UPDATE" => "DESC"),
			array(
					"PERSON_TYPE_ID" => $arResult["PERSON_TYPE"],
					"USER_ID" => IntVal($USER->GetID()),
					"ID" => $arResult["PROFILE_ID"],
				)
		);

	if(!$dbUserProfiles->GetNext()) {
		$arResult["PROFILE_ID"] = 0;
	}else {
	    $db_propVals = CSaleOrderUserPropsValue::GetList(array("ID" => "ASC"), Array("USER_PROPS_ID"=>$arResult["PROFILE_ID"]));
	    while ($arPropVals = $db_propVals->Fetch())
	    {
	        $arResult['PROFILE_VARS'][$arPropVals['PROP_CODE']] = $arPropVals;
	    } 
	}

}
$arResult["DELIVERY_LOCATION"] = (int)$arResult["POST"]["DELIVERY_LOCATION"] ?  (int) $arResult["POST"]["DELIVERY_LOCATION"] :  '';
if ((int)$_REQUEST["CurrentStep"] == 3) 
{
   $arResult["DELIVERY_ID"] = isset($arResult['PROFILE_VARS'][$prefix . 'DELIVERY_ID']['VALUE']) ? (int)$arResult['PROFILE_VARS'][$prefix . 'DELIVERY_ID']['VALUE'] : '';
}elseif(strlen($arResult["POST"]["DELIVERY_ID"])>0)
{
	if (strpos($arResult["POST"]["DELIVERY_ID"], ":") === false)
		$arResult["DELIVERY_ID"] = IntVal($arResult["POST"]["DELIVERY_ID"]);
	else
		$arResult["DELIVERY_ID"] = explode(":", $arResult["POST"]["DELIVERY_ID"]);
}

if(isset($arResult["POST"]["STORE_ID"]))
{
	$arResult["STORE_ID"] = (int)$arResult["POST"]["STORE_ID"];
}

if ((int)$_REQUEST["CurrentStep"] == 4) {
	$arResult["PAY_SYSTEM_ID"] = isset($arResult['PROFILE_VARS'][$prefix . 'PAYMENT_TYPE_ID']['VALUE']) ? (int)$arResult['PROFILE_VARS'][ $prefix . 'PAYMENT_TYPE_ID']['VALUE'] : '';

}else {
    if(strlen($arResult["POST"]["PAY_SYSTEM_ID"])>0)
    	$arResult["PAY_SYSTEM_ID"] = IntVal($arResult["POST"]["PAY_SYSTEM_ID"]);
}

if(strlen($arResult["POST"]["PAY_CURRENT_ACCOUNT"])>0)
	$arResult["PAY_CURRENT_ACCOUNT"] = $arResult["POST"]["PAY_CURRENT_ACCOUNT"];
else
	$arResult["PAY_CURRENT_ACCOUNT"] = "N";
if(strlen($arResult["POST"]["TAX_EXEMPT"])>0)
	$arResult["TAX_EXEMPT"] = $arResult["POST"]["TAX_EXEMPT"];
if(strlen($arResult["POST"]["ORDER_DESCRIPTION"])>0)
	$arResult["ORDER_DESCRIPTION"] = trim($arResult["POST"]["ORDER_DESCRIPTION"]);

if ($_REQUEST["CurrentStep"] == 7 || ($_SERVER["REQUEST_METHOD"] == "POST" && ($arParams["DELIVERY_NO_SESSION"] == "N" || check_bitrix_sessid())))
{
	if(strlen($_REQUEST["ORDER_ID"])>0)
		$ID = urldecode(urldecode($_REQUEST["ORDER_ID"]));
	if(IntVal($_REQUEST["CurrentStep"])>0)
		$arResult["CurrentStep"] = IntVal($_REQUEST["CurrentStep"]);
	if(IntVal($_REQUEST["CurrentStep"])>0)
		$CurrentStepTmp = IntVal($_REQUEST["CurrentStep"]);
	elseif(IntVal($arResult["POST"]["CurrentStep"])>0)
		$CurrentStepTmp = IntVal($arResult["POST"]["CurrentStep"]);
}


$arResult["BACK"] = (($arResult["POST"]["BACK"] == "Y") ? "Y" : "");

if ($_SERVER["REQUEST_METHOD"] == "POST" && strlen($_REQUEST["backButton"]) > 0 && ($arParams["DELIVERY_NO_SESSION"] == "N" || check_bitrix_sessid()))
{
	if($arResult["POST"]["CurrentStep"] == 6 && $arResult["SKIP_FORTH_STEP"] == "Y")
		$arResult["CurrentStepTmp"] = 3;

	if($arResult["POST"]["CurrentStepTmp"] <= 5 && $arResult["SKIP_THIRD_STEP"] == "Y")
		$arResult["CurrentStepTmp"] = 2;

	if($arResult["POST"]["CurrentStepTmp"] <= 3 && $arResult["SKIP_SECOND_STEP"] == "Y") {
		//$arResult["CurrentStepTmp"] = 1;
		$arResult["CurrentStepTmp"] = 2;
	}

	if(IntVal($arResult["CurrentStepTmp"])>0)
		$arResult["CurrentStep"] = $arResult["CurrentStepTmp"];
	else
		$arResult["CurrentStep"] = $arResult["CurrentStep"] - 2;
	$arResult["BACK"] = "Y";
}
if ($arResult["CurrentStep"] <= 1) {
	//$arResult["CurrentStep"] = 1;
	$arResult["CurrentStep"] = 2;
}

$arResult["ERROR_MESSAGE"] = "";

/*******************************************************************************/
/*****************  ACTION  ****************************************************/
/*******************************************************************************/
if (!$USER->IsAuthorized())
{
	require_once ($_SERVER["DOCUMENT_ROOT"].'/local/components/texenergo/sale.order.full/auth.php');
	/*******************Начало социальных кнопок*********************************/
	if(!$USER->IsAuthorized() && CModule::IncludeModule("socialservices"))
	{
		$oAuthManager = new CSocServAuthManager();
		$arServices = $oAuthManager->GetActiveAuthServices($arResult);
	
		if(!empty($arServices))
		{
			$arResult["AUTH_SERVICES"] = $arServices;
			if(isset($_REQUEST["auth_service_id"]) && $_REQUEST["auth_service_id"] <> '' && isset($arResult["AUTH_SERVICES"][$_REQUEST["auth_service_id"]]))
			{
				$arResult["CURRENT_SERVICE"] = $_REQUEST["auth_service_id"];
				if(isset($_REQUEST["auth_service_error"]) && $_REQUEST["auth_service_error"] <> '')
				{
					$arResult['ERROR_MESSAGE'] = $oAuthManager->GetError($arResult["CURRENT_SERVICE"], $_REQUEST["auth_service_error"]);
				}
				elseif(!$oAuthManager->Authorize($_REQUEST["auth_service_id"]))
				{
					$ex = $APPLICATION->GetException();
					if ($ex)
						$arResult['ERROR_MESSAGE'] = $ex->GetString();
				}
			}
		}
	}
	/*******************Конец социальных кнопок*********************************/
}
else
{
	/*
	 * тут различные проверки и переход на следущий шаг
	 */
	require_once ($_SERVER["DOCUMENT_ROOT"].'/local/components/texenergo/sale.order.full/check.php');
}

/*******************************************************************************/
/*****************  BODY  ******************************************************/
/*******************************************************************************/
if ($USER->IsAuthorized())
{
	//получаем данные для отобрсжения steps
	require_once ($_SERVER["DOCUMENT_ROOT"].'/local/components/texenergo/sale.order.full/steps.php');
}

$arResult["DISCOUNT_PRICE_ALL"] = $DISCOUNT_PRICE_ALL;
$arResult["DISCOUNT_PRICE_ALL_FORMATED"] = SaleFormatCurrency($DISCOUNT_PRICE_ALL, $allCurrency);
$this->IncludeComponentTemplate();
?>