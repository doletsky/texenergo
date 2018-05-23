<?php
if ($arResult["CurrentStep"] == 2)
{
	$arResult["SKIP_FIRST_STEP"] = "N";
	$arResult["SKIP_SECOND_STEP"] = "N";

	$numPersonTypes = 0;
	$curOnePersonType = 0;

	$dbPersonTypesList = CSalePersonType::GetList(
			array("SORT" => "ASC", "NAME" => "ASC"),
			array("LID" => SITE_ID, "ACTIVE" => "Y")
	);
	while ($arPersonTypesList = $dbPersonTypesList->Fetch())
	{
		$numPersonTypes++;
		if ($numPersonTypes >= 2)
			break;

		if ($curOnePersonType <= 0)
			$curOnePersonType = IntVal($arPersonTypesList["ID"]);
	}
	if ($numPersonTypes < 2)
	{
		$arResult["SKIP_FIRST_STEP"] = "Y";
		$arResult["CurrentStep"] = 2;
		$arResult["PERSON_TYPE"] = $curOnePersonType;
	}
}

if ($arResult["CurrentStep"] < 3)
{
	if ($arResult["SKIP_THIRD_STEP"] != "Y" && IntVal($arResult["PERSON_TYPE"]) > 0)
	{
		$arResult["SKIP_THIRD_STEP"] = "N";

		$dbOrderProps = CSaleOrderProps::GetList(
				array("SORT" => "ASC"),
				array(
						"PERSON_TYPE_ID" => $arResult["PERSON_TYPE"],
						"IS_LOCATION" => "Y",
						"ACTIVE" => "Y", "UTIL" => "N"
				),
				false,
				false,
				array("ID", "SORT")
		);
		if (!($arOrderProps = $dbOrderProps->Fetch()))
			$arResult["SKIP_THIRD_STEP"] = "Y";
	}

	if($arResult["SKIP_SECOND_STEP"] != "Y" && IntVal($arResult["PERSON_TYPE"]) > 0)
	{
		$arFilter = array("PERSON_TYPE_ID" => $arResult["PERSON_TYPE"], "ACTIVE" => "Y", "UTIL" => "N");
		if(!empty($arParams["PROP_".$arResult["PERSON_TYPE"]]))
			$arFilter["!ID"] = $arParams["PROP_".$arResult["PERSON_TYPE"]];

		$dbOrderProps = CSaleOrderProps::GetList(
				array("SORT" => "ASC"),
				$arFilter,
				false,
				false,
				array("ID", "SORT")
		);
		if (!($arOrderProps = $dbOrderProps->Fetch()))
		{
			$arResult["SKIP_SECOND_STEP"] = "Y";
			if($arResult["SKIP_THIRD_STEP"] == "Y")
				$arResult["CurrentStep"] = 4;

		}
	}

	if($arResult["SKIP_SECOND_STEP"] == "Y" && $arResult["BACK"] == "Y")
	{
		//$arResult["CurrentStep"] = 1;
		$arResult["CurrentStep"] = 2;
	}
	elseif($arResult["SKIP_SECOND_STEP"] == "Y")
	{
		$arResult["CurrentStep"] = 3;
	}
}
if ($arResult["CurrentStep"] == 3)
{

	if (IntVal($arResult["DELIVERY_LOCATION"]) > 0)
	{
		// if your custom handler needs something else, ex. cart content, you may put it here or get it from your handler using API
		$arFilter = array(
				"COMPABILITY" => array(
						"WEIGHT" => $arResult["ORDER_WEIGHT"],
						"PRICE" => $arResult["ORDER_PRICE"],
						"LOCATION_FROM" => COption::GetOptionString('sale', 'location', false, SITE_ID),
						"LOCATION_TO" => $arResult["DELIVERY_LOCATION"],
						"LOCATION_ZIP" => $arResult["DELIVERY_LOCATION_ZIP"],
				)
		);

		$rsDeliveryServicesList = CSaleDeliveryHandler::GetList(array("SORT" => "ASC"), $arFilter);

		$arDeliveryServicesList = array();
		while ($arDeliveryService = $rsDeliveryServicesList->Fetch())
		{
			$arDeliveryServicesList[] = $arDeliveryService;
		}

		//$numDelivery = count($arDeliveryServicesList);

		$curOneDelivery = false;

		$numDelivery = 0;
		foreach ($arDeliveryServicesList as $key => $arDelivery)
		{
			foreach ($arDelivery['PROFILES'] as $pkey => $arProfile)
			{
				if ($arProfile['ACTIVE'] != 'Y')
				{
					unset($arDeliveryServicesList[$key]['PROFILES'][$pkey]);
				}
			}

			$cnt = count($arDeliveryServicesList[$key]["PROFILES"]);
			if ($cnt <= 0)
				unset($arDeliveryServicesList[$key]);
			else
			{
				$numDelivery += $cnt;
				if($cnt == 1 && empty($curOneDelivery))
				{
					foreach ($arDeliveryServicesList[$key]["PROFILES"] as $pkey => $arProfile)
						$curOneDelivery = array($arDeliveryServicesList[$key]['SID'], $pkey);
				}
			}
		}

		$dbDelivery = CSaleDelivery::GetList(
				array(),
				array(
						"LID" => SITE_ID,
						"+<=WEIGHT_FROM" => $arResult["ORDER_WEIGHT"],
						"+>=WEIGHT_TO" => $arResult["ORDER_WEIGHT"],
						"+<=ORDER_PRICE_FROM" => $arResult["ORDER_PRICE"],
						"+>=ORDER_PRICE_TO" => $arResult["ORDER_PRICE"],
						"ACTIVE" => "Y",
						"LOCATION" => $arResult["DELIVERY_LOCATION"],
				)
		);

		while ($arDelivery = $dbDelivery->Fetch())
		{
			$arDeliveryDescription = CSaleDelivery::GetByID($arDelivery["ID"]);
			$arDelivery["DESCRIPTION"] = $arDeliveryDescription["DESCRIPTION"];

			$numDelivery++;
			if ($numDelivery >= 2)
				break;

			if (!is_array($curOneDelivery) || count($curOneDelivery) <= 0 || $curOneDelivery <= 0)
			{
				$curOneDelivery = $arDelivery["ID"];
			}
		}


		if ($numDelivery < 2)
		{
			$arResult["SKIP_THIRD_STEP"] = "Y";
			$arResult["CurrentStep"] = 4;
			$arResult["DELIVERY_ID"] = $curOneDelivery;
		}
	}
	else
	{
		$arResult["SKIP_THIRD_STEP"] = "Y";
		$arResult["CurrentStep"] = 4;
	}
}
if ($arResult["CurrentStep"] == 4)
{
	//if($arResult["PAY_CURRENT_ACCOUNT"] == "N")
	//{
	//if (IntVal($arResult["PAY_SYSTEM_ID"]) <= 0)
		//{
		$numPaySys = 0;
		$curOnePaySys = 0;
		$arFilter = array(
				//"LID" => SITE_ID,
				//"CURRENCY" => $arResult["BASE_LANG_CURRENCY"],
				"ACTIVE" => "Y",
				"PERSON_TYPE_ID" => $arResult["PERSON_TYPE"],
				"PSA_HAVE_PAYMENT" => "Y"
		);
		$deliv = $arResult["DELIVERY_ID"];
		if(is_array($arResult["DELIVERY_ID"]))
			$deliv = $arResult["DELIVERY_ID"][0].":".$arResult["DELIVERY_ID"][1];
		if(!empty($arParams["DELIVERY2PAY_SYSTEM"]))
		{
			foreach($arParams["DELIVERY2PAY_SYSTEM"] as $val)
			{
				if(is_array($val[$deliv]))
				{
					foreach($val[$deliv] as $v)
						$arFilter["ID"][] = $v;
				}
				elseif(IntVal($val[$deliv]) > 0)
				$arFilter["ID"][] = $val[$deliv];
			}
		}

		$dbPaySystem = CSalePaySystem::GetList(
				array("SORT" => "ASC", "PSA_NAME" => "ASC"),
				$arFilter
		);
		while ($arPaySystem = $dbPaySystem->Fetch())
		{
			$numPaySys++;
			if ($numPaySys >= 2)
				break;

			if ($curOnePaySys <= 0)
				$curOnePaySys = $arPaySystem["ID"];
		}


		if ($numPaySys < 2 && $numPaySys > 0)
		{
			if($arParams["ALLOW_PAY_FROM_ACCOUNT"] == "Y")
			{
				$dbUserAccount = CSaleUserAccount::GetList(
						array(),
						array(
								"USER_ID" => $USER->GetID(),
								"CURRENCY" => $arResult["BASE_LANG_CURRENCY"],
						)
				);
				if ($arUserAccount = $dbUserAccount->GetNext())
				{
					if ($arUserAccount["CURRENT_BUDGET"] <= 0)
					{
						$arParams["ALLOW_PAY_FROM_ACCOUNT"] = "N";
					}
					else
					{
						if($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y")
						{
							if(DoubleVal($arUserAccount["CURRENT_BUDGET"]) >= DoubleVal($arResult["ORDER_PRICE"]))
							{
								$arParams["ALLOW_PAY_FROM_ACCOUNT"] = "Y";
							}
							else
								$arParams["ALLOW_PAY_FROM_ACCOUNT"] = "N";
						}
						else
						{
							$arParams["ALLOW_PAY_FROM_ACCOUNT"] = "Y";
						}
					}

				}
				else
					$arParams["ALLOW_PAY_FROM_ACCOUNT"] = "N";
			}


			if($arParams["ALLOW_PAY_FROM_ACCOUNT"] == "N")
			{
				$arResult["SKIP_FORTH_STEP"] = "Y";
				$arResult["CurrentStep"] = 5;
				$arResult["PAY_SYSTEM_ID"] = $curOnePaySys;
			}
		}
		//}
		//else
		//{
			//	$arResult["SKIP_FORTH_STEP"] = "Y";
			//	$arResult["CurrentStep"] = 5;
			//}
			//}
		}
		
		
		

//------------------ STEP 1 ----------------------------------------------
//if ($arResult["CurrentStep"] == 1)
if ($arResult["CurrentStep"] == 2)
{
	$arResult["PERSON_TYPE_INFO"] = Array();
	$dbPersonType = CSalePersonType::GetList(
			array("SORT" => "ASC", "NAME" => "ASC"),
			array("LID" => SITE_ID, "ACTIVE" => "Y")
	);
	$bFirst = True;
	while ($arPersonType = $dbPersonType->GetNext())
	{
		if (IntVal($arResult["POST"]["PERSON_TYPE"]) == IntVal($arPersonType["ID"]) || IntVal($arResult["POST"]["PERSON_TYPE"]) <= 0 && $bFirst)
			$arPersonType["CHECKED"] = "Y";
		$arResult["PERSON_TYPE_INFO"][] = $arPersonType;
		$bFirst = False;
	}

	if(CModule::IncludeModule("statistic"))
	{
		$event1 = "eStore";
		$event2 = "Step4_1";
		$event3 = "";

		if (is_array($arProductsInBasket))
		{
			foreach($arProductsInBasket as $ar_prod)
			{
				$event3 .= $ar_prod["PRODUCT_ID"].", ";
			}
		}
		$e = $event1."/".$event2."/".$event3;

		if(!is_array($_SESSION["ORDER_EVENTS"]) || (is_array($_SESSION["ORDER_EVENTS"]) && !in_array($e, $_SESSION["ORDER_EVENTS"]))) // check for event in session
		{
			CStatistic::Set_Event($event1, $event2, $event3);
			$_SESSION["ORDER_EVENTS"][] = $e;
		}
	}
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//------------------ STEP 2 ----------------------------------------------
if ($arResult["CurrentStep"] == 2)
{
	
	$arResult["USER_PROFILES"] = Array();
	$bFillProfileFields = False;
	$bFirstProfile = True;

	$dbUserProfiles = CSaleOrderUserProps::GetList(
			array("DATE_UPDATE" => "DESC"),
			array(
					"PERSON_TYPE_ID" => $arResult["PERSON_TYPE"],
					"USER_ID" => IntVal($USER->GetID())
			)
	);

	if ($arUserProfiles = $dbUserProfiles->GetNext())
	{
		$bFillProfileFields = True;
		do
		{
			if (IntVal($arResult["PROFILE_ID"])==IntVal($arUserProfiles["ID"]) || !isset($arResult["PROFILE_ID"]) && $bFirstProfile)
				$arUserProfiles["CHECKED"] = "Y";
			$bFirstProfile = False;
			$arUserProfiles["USER_PROPS_VALUES"] = Array();
			$dbUserPropsValues = CSaleOrderUserPropsValue::GetList(
					array("SORT" => "ASC"),
					array("USER_PROPS_ID" => $arUserProfiles["ID"]),
					false,
					false,
					array("VALUE", "PROP_TYPE", "VARIANT_NAME", "SORT", "ORDER_PROPS_ID")
			);
			while ($arUserPropsValues = $dbUserPropsValues->GetNext())
			{
				$valueTmp = "";
				if ($arUserPropsValues["PROP_TYPE"] == "SELECT"
						|| $arUserPropsValues["PROP_TYPE"] == "MULTISELECT"
						|| $arUserPropsValues["PROP_TYPE"] == "RADIO")
				{
					$arUserPropsValues["VALUE_FORMATED"] = $arUserPropsValues["VARIANT_NAME"];
				}
				elseif ($arUserPropsValues["PROP_TYPE"] == "LOCATION")
				{
					if ($arLocation = CSaleLocation::GetByID($arUserPropsValues["VALUE"], LANGUAGE_ID))
					{
						/*
						 $arUserPropsValues["VALUE_FORMATED"] = htmlspecialcharsEx($arLocation["COUNTRY_NAME"]);
						if (strlen($arLocation["COUNTRY_NAME"]) > 0
								&& strlen($arLocation["CITY_NAME"]) > 0)
						{
						$arUserPropsValues["VALUE_FORMATED"] .= " - ";
						}
						$arUserPropsValues["VALUE_FORMATED"] .= htmlspecialcharsEx($arLocation["CITY_NAME"]);
						*/
							
						$locationName = "";
						$locationName .= ((strlen($arLocation["COUNTRY_NAME"])<=0) ? "" : $arLocation["COUNTRY_NAME"]);
							
						if (strlen($arLocation["COUNTRY_NAME"])>0 && strlen($arLocation["REGION_NAME"])>0)
							$locationName .= " - ".$arLocation["REGION_NAME"];
						elseif (strlen($arLocation["REGION_NAME"])>0)
						$locationName .= $arLocation["REGION_NAME"];
						if (strlen($arLocation["COUNTRY_NAME"])>0 || strlen($arLocation["REGION_NAME"])>0)
							$locationName .= " - ".$arLocation["CITY_NAME"];
						elseif (strlen($arLocation["CITY_NAME"])>0)
						$locationName .= $arLocation["CITY_NAME"];
							
						$arUserPropsValues["VALUE_FORMATED"] = $locationName;
					}
				}
				else
					$arUserPropsValues["VALUE_FORMATED"] = $arUserPropsValues["VALUE"];
				$arUserProfiles["USER_PROPS_VALUES"][] = $arUserPropsValues;
			}
			$arResult["USER_PROFILES"][] = $arUserProfiles;
		}
		while ($arUserProfiles = $dbUserProfiles->GetNext());
			
		if (isset($arResult["PROFILE_ID"]) && IntVal($arResult["PROFILE_ID"]) > 0 && $bFirstProfile)
			$arResult["USER_PROFILES_0"] = "Y";
			
			
	}

	if ($bFillProfileFields)
	{
		$arResult["USER_PROFILES_TO_FILL"] = "Y";
		if(isset($arResult["PROFILE_ID"]) && IntVal($arResult["PROFILE_ID"]) > 0 && $bFirstProfile)
			$arResult["USER_PROFILES_TO_FILL_VALUE"] = "Y";
	}


	if(CModule::IncludeModule("statistic"))
	{
		$event1 = "eStore";
		$event2 = "Step4_2";
		$event3 = "";

		foreach($arProductsInBasket as $ar_prod)
		{
			$event3 .= $ar_prod["PRODUCT_ID"].", ";
		}
		$e = $event1."/".$event2."/".$event3;

		if(!is_array($_SESSION["ORDER_EVENTS"]) || (is_array($_SESSION["ORDER_EVENTS"]) && !in_array($e, $_SESSION["ORDER_EVENTS"]))) // check for event in session
		{
			CStatistic::Set_Event($event1, $event2, $event3);
			$_SESSION["ORDER_EVENTS"][] = $e;
		}
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////
	//for function PrintPropsForm
	/////////////////////////////////////////////////////////////////////////////////////////////
	$propertyGroupID = 0;
	$propertyUSER_PROPS = "";
	
	$arFilter = array("PERSON_TYPE_ID" => $arResult["PERSON_TYPE"], "ACTIVE" => "Y", "UTIL" => "N");
	if(!empty($arParams["PROP_".$arResult["PERSON_TYPE"]]))
		$arFilter["!ID"] = $arParams["PROP_".$arResult["PERSON_TYPE"]];

	$arResult ["PRINT_PROPS_FORM"] = getPropertyList($USER, $arResult, $arFilter);
	
	if(empty($arResult["PRINT_PROPS_FORM"]["USER_PROPS_Y"]))
	{
		$arResult["USER_PROFILES"] = Array();
		$arResult["USER_PROFILES_TO_FILL_VALUE"] = "N";
		$arResult["USER_PROFILES_TO_FILL"] = "N";
	
	}
	////////////////////////////////////////////////
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//------------------ STEP 3 ----------------------------------------------
elseif ($arResult["CurrentStep"] == 3)
{
	$arResult["DELIVERY"] = Array();

	$deliv = $arResult["DELIVERY_ID"];
	if(is_array($arResult["DELIVERY_ID"]))
		$deliv = $arResult["DELIVERY_ID"][0].":".$arResult["DELIVERY_ID"][1];

	$arSelect = array();
	$dbDelivery = CSaleDelivery::GetList(
			array("SORT"=>"ASC", "NAME"=>"ASC"),
			array(
					"LID" => SITE_ID,
					"+<=WEIGHT_FROM" => $arResult["ORDER_WEIGHT"],
					"+>=WEIGHT_TO" => $arResult["ORDER_WEIGHT"],
					"+<=ORDER_PRICE_FROM" => $arResult["ORDER_PRICE"],
					"+>=ORDER_PRICE_TO" => $arResult["ORDER_PRICE"],
					"ACTIVE" => "Y",
					"LOCATION" => $arResult["DELIVERY_LOCATION"]
			),
			false,
			false,
			$arSelect
	);

	$bFirst = True;
	$currentDeliveryId = isset($arResult["DELIVERY_ID"]) ? $arResult["DELIVERY_ID"] : '';
	while ($arDelivery = $dbDelivery->GetNext())
	{
		if (strlen($arDelivery['STORE']) > 0) {
			$arDelivery['STORE_IDS'] = unserialize(trim(htmlspecialchars_decode($arDelivery['STORE'])));
		}
		$arDelivery["FIELD_NAME"] = "DELIVERY_ID";
		if (IntVal($currentDeliveryId) == IntVal($arDelivery["ID"])
				|| IntVal($currentDeliveryId) <= 0 && $bFirst)
			$arDelivery["CHECKED"] = "Y";
		if (IntVal($arDelivery["PERIOD_FROM"]) > 0 || IntVal($arDelivery["PERIOD_TO"]) > 0)
		{
			$arDelivery["PERIOD_TEXT"] = GetMessage("SALE_DELIV_PERIOD");
			if (IntVal($arDelivery["PERIOD_FROM"]) > 0)
				$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SALE_FROM")." ".IntVal($arDelivery["PERIOD_FROM"]);
			if (IntVal($arDelivery["PERIOD_TO"]) > 0)
				$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SALE_TO")." ".IntVal($arDelivery["PERIOD_TO"]);
			if ($arDelivery["PERIOD_TYPE"] == "H")
				$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SALE_PER_HOUR")." ";
			elseif ($arDelivery["PERIOD_TYPE"]=="M")
			$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SALE_PER_MONTH")." ";
			else
				$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SALE_PER_DAY")." ";
		}
		$arDelivery["PRICE_FORMATED"] = SaleFormatCurrency($arDelivery["PRICE"], $arDelivery["CURRENCY"]);
		$arResult["DELIVERY"][] = $arDelivery;
		$bFirst = false;
	}

	if (is_array($arDeliveryServicesList))
	{
		$bFirst = true;
		foreach ($arDeliveryServicesList as $arDeliveryInfo)
		{
			$delivery_id = $arDeliveryInfo["SID"];

			if (!is_array($arDeliveryInfo) || !is_array($arDeliveryInfo["PROFILES"])) continue;

			foreach ($arDeliveryInfo["PROFILES"] as $profile_id => $arDeliveryProfile)
			{
				$arProfile = array(
						"SID" => $profile_id,
						"TITLE" => $arDeliveryProfile["TITLE"],
						"DESCRIPTION" => $arDeliveryProfile["DESCRIPTION"],
						//"CHECKED" => $bFirst ? "Y" : "N",
						"FIELD_NAME" => "DELIVERY_ID",
				);

				if ($arResult['DELIVERY_ID'])
					if(strpos($deliv, ":") !== false &&
							$deliv == $delivery_id.":".$profile_id
							|| empty($arResult["DELIVERY_ID"]) && $bFirst
					)
					$arProfile["CHECKED"] = "Y";

				if (!is_array($arResult["DELIVERY"][$delivery_id]))
				{
					$arResult["DELIVERY"][$delivery_id] = array(
							"SID" => $delivery_id,
							"TITLE" => $arDeliveryInfo["NAME"],
							"DESCRIPTION" => $arDeliveryInfo["DESCRIPTION"],
							"PROFILES" => array(),
					);
				}

				$arResult["DELIVERY"][$delivery_id]["PROFILES"][$profile_id] = $arProfile;

				$bFirst = false;
			}
		}
	}
	/////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////
	//for function PrintPropsForm
	/////////////////////////////////////////////////////////////////////////////////////////////
	$propertyGroupID = 0;
	$propertyUSER_PROPS = "";

	$arFilter = array("PERSON_TYPE_ID" => $arResult["PERSON_TYPE"], "ACTIVE" => "Y", "UTIL" => "N");
	if(!empty($arParams["PROP_".$arResult["PERSON_TYPE"]]))
		$arFilter["!ID"] = $arParams["PROP_".$arResult["PERSON_TYPE"]];

	$arResult ["PRINT_PROPS_FORM"] = getPropertyList($USER, $arResult, $arFilter);

	if(empty($arResult["PRINT_PROPS_FORM"]["USER_PROPS_Y"]))
	{
		$arResult["USER_PROFILES"] = Array();
		$arResult["USER_PROFILES_TO_FILL_VALUE"] = "N";
		$arResult["USER_PROFILES_TO_FILL"] = "N";

	}
	////////////////////////////////////////////////

	if(CModule::IncludeModule("statistic"))
	{
		$event1 = "eStore";
		$event2 = "Step4_3";
		$event3 = "";

		foreach($arProductsInBasket as $ar_prod)
		{
			$event3 .= $ar_prod["PRODUCT_ID"].", ";
		}
		$e = $event1."/".$event2."/".$event3;

		if(!is_array($_SESSION["ORDER_EVENTS"]) || (is_array($_SESSION["ORDER_EVENTS"]) && !in_array($e, $_SESSION["ORDER_EVENTS"]))) // check for event in session
		{
			CStatistic::Set_Event($event1, $event2, $event3);
			$_SESSION["ORDER_EVENTS"][] = $e;
		}
	}
}

//------------------ STEP 4 ----------------------------------------------
elseif ($arResult["CurrentStep"] == 4)
{
	if ($arParams["ALLOW_PAY_FROM_ACCOUNT"] == "Y")
	{
		$dbUserAccount = CSaleUserAccount::GetList(
				array(),
				array(
						"USER_ID" => $USER->GetID(),
						"CURRENCY" => $arResult["BASE_LANG_CURRENCY"],
				)
		);
		if ($arUserAccount = $dbUserAccount->GetNext())
		{
			if ($arUserAccount["CURRENT_BUDGET"] > 0)
			{

				if($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y")
				{
					if(DoubleVal($arUserAccount["CURRENT_BUDGET"]) >= DoubleVal($arResult["ORDER_PRICE"]))
					{
						$arResult["PAY_FROM_ACCOUNT"] = "Y";
						$arResult["CURRENT_BUDGET_FORMATED"] = SaleFormatCurrency($arUserAccount["CURRENT_BUDGET"], $arResult["BASE_LANG_CURRENCY"]);
						$arResult["USER_ACCOUNT"] = $arUserAccount;
					}
					else
						$arResult["PAY_FROM_ACCOUNT"] = "N";
				}
				else
				{
					$arResult["PAY_FROM_ACCOUNT"] = "Y";
					$arResult["CURRENT_BUDGET_FORMATED"] = SaleFormatCurrency($arUserAccount["CURRENT_BUDGET"], $arResult["BASE_LANG_CURRENCY"]);
					$arResult["USER_ACCOUNT"] = $arUserAccount;
				}
			}
		}
	}
	$arResult["PAY_SYSTEM"] = Array();
	$arFilter = array(
			"ACTIVE" => "Y",
			"PERSON_TYPE_ID" => $arResult["PERSON_TYPE"],
			"PSA_HAVE_PAYMENT" => "Y"
	);
	$deliv = $arResult["DELIVERY_ID"];
	if(is_array($arResult["DELIVERY_ID"]))
		$deliv = $arResult["DELIVERY_ID"][0].":".$arResult["DELIVERY_ID"][1];
	if(!empty($arParams["DELIVERY2PAY_SYSTEM"]))
	{
		foreach($arParams["DELIVERY2PAY_SYSTEM"] as $val)
		{
			if(is_array($val[$deliv]))
			{
				foreach($val[$deliv] as $v)
					$arFilter["ID"][] = $v;
			}
			elseif(IntVal($val[$deliv]) > 0)
			$arFilter["ID"][] = $val[$deliv];
		}
	}

	//select delivery to pay
	$bShowDefault = False;
	$arD2P = array();
	$dbRes = CSaleDelivery::GetDelivery2PaySystem(array("DELIVERY_ID" => $deliv));
	while ($arRes = $dbRes->Fetch())
	{
		$arD2P[] = $arRes["PAYSYSTEM_ID"];
		$bShowDefault = True;
	}


	$dbPaySystem = CSalePaySystem::GetList(
			array("SORT" => "ASC", "PSA_NAME" => "ASC"),
			$arFilter
	);
	$bFirst = True;
	
	while ($arPaySystem = $dbPaySystem->Fetch())
	{
		if (!$bShowDefault || in_array($arPaySystem["ID"], $arD2P))
		{
			if ($arPaySystem["PSA_LOGOTIP"] > 0)
				$arPaySystem["PSA_LOGOTIP"] = CFile::GetFileArray($arPaySystem["PSA_LOGOTIP"]);

			if (IntVal($arResult["PAY_SYSTEM_ID"]) == IntVal($arPaySystem["ID"]) || IntVal($arResult["PAY_SYSTEM_ID"]) <= 0 && $bFirst)
				$arPaySystem["CHECKED"] = "Y";
			$arPaySystem["PSA_NAME"] = htmlspecialcharsEx($arPaySystem["PSA_NAME"]);
			$arResult["PAY_SYSTEM"][] = $arPaySystem;
			$bFirst = false;
		}
	}

	$bHaveTaxExempts = False;
	if (is_array($arResult["TaxExempt"]) && count($arResult["TaxExempt"])>0)
	{
		$dbTaxRateList = CSaleTaxRate::GetList(
				array("APPLY_ORDER" => "ASC"),
				array(
						"LID" => SITE_ID,
						"PERSON_TYPE_ID" => $PERSON_TYPE,
						"IS_IN_PRICE" => "N",
						"ACTIVE" => "Y",
						"LOCATION" => IntVal($TAX_LOCATION)
				)
		);
		while ($arTaxRateList = $dbTaxRateList->GetNext())
		{
			if (in_array(IntVal($arTaxRateList["TAX_ID"]), $arResult["TaxExempt"]))
			{
				$arResult["HaveTaxExempts"] = "Y";
				break;
			}
		}
	}

	if(CModule::IncludeModule("statistic"))
	{
		$event1 = "eStore";
		$event2 = "Step4_4";
		$event3 = "";

		foreach($arProductsInBasket as $ar_prod)
		{
			$event3 .= $ar_prod["PRODUCT_ID"].", ";
		}
		$e = $event1."/".$event2."/".$event3;

		if(!is_array($_SESSION["ORDER_EVENTS"]) || (is_array($_SESSION["ORDER_EVENTS"]) && !in_array($e, $_SESSION["ORDER_EVENTS"]))) // check for event in session
		{
			CStatistic::Set_Event($event1, $event2, $event3);
			$_SESSION["ORDER_EVENTS"][] = $e;
		}
	}
}
//------------------ STEP 5 ----------------------------------------------
elseif ($arResult["CurrentStep"] == 5)
{
	
	/*
	 * локация
	 */
	if (isset($arResult['DELIVERY_LOCATION']) && (int)$arResult['DELIVERY_LOCATION']) {
		foreach ($arResult['TOWN_VARIANTS'] as $town) {
			if ($town['ID'] == (int)$arResult['DELIVERY_LOCATION']) {
				$arResult['DELIVERY_LOCATION_NAME'] = $town['CITY_NAME'];
			}
		}
	}
	/*
	 * пункт вывоза если таковой имеется
	 */
	if ($storeId = $arResult['STORE_ID']) {
		$arFilter = array("ACTIVE"=>"Y", 'ID' => $storeId);
		$arSelect = array(
				"ID",
				"TITLE",
				"ADDRESS",
				"DESCRIPTION",
				"GPS_N",
				"GPS_S",
				"IMAGE_ID",
				"PHONE",
				"SCHEDULE",
		);
		$dbStoreProps = CCatalogStore::GetList(array('TITLE' => 'ASC', 'ID' => 'ASC'), $arFilter,false,false,$arSelect);
		while ($arProp = $dbStoreProps->GetNext())
		{
			$arResult['STORE_VARS'] = $arProp;
		}
		
	}
	
	$arResult["ORDER_PROPS_PRINT"] = Array();
	$propertyGroupID = -1;

	$arFilter = array("PERSON_TYPE_ID" => $arResult["PERSON_TYPE"], "ACTIVE" => "Y", "UTIL" => "N");
	if(!empty($arParams["PROP_".$arResult["PERSON_TYPE"]]))
		$arFilter["!ID"] = $arParams["PROP_".$arResult["PERSON_TYPE"]];

	$dbProperties = CSaleOrderProps::GetList(
			array(
					"GROUP_SORT" => "ASC",
					"PROPS_GROUP_ID" => "ASC",
					"SORT" => "ASC",
					"NAME" => "ASC",
			),
			$arFilter,
			false,
			false,
			array("ID", "NAME", "TYPE", "PROPS_GROUP_ID", "GROUP_NAME", "GROUP_SORT", "SORT", "CODE")
	);
	
	$pay_system_id = 0;
	if ((IntVal($arResult["PAY_SYSTEM_ID"]) > 0) && ($arPaySys = CSalePaySystem::GetByID($arResult["PAY_SYSTEM_ID"], $arResult["PERSON_TYPE"])))
	{
		$pay_system_id = $arResult["PAY_SYSTEM_ID"];
		$arResult["PAY_SYSTEM"] = $arPaySys;
		$arResult["PAY_SYSTEM"]["PSA_NAME"] = htmlspecialcharsEx($arResult["PAY_SYSTEM"]["PSA_NAME"]);
		$arResult["PAY_SYSTEM"]["~PSA_NAME"] = $arResult["PAY_SYSTEM"]["PSA_NAME"];
	}
	elseif (IntVal($arResult["PAY_SYSTEM_ID"]) > 0)
	{
		$arResult["PAY_SYSTEM"] = "ERROR";
	}
	$prefix = $arResult["PERSON_TYPE"] == USER_TYPE_PHISICAL ? 'USER_' : 'J_';

	while ($arProperties = $dbProperties->GetNext())
	{
	    /*
	     * TODO
	     */
		if ($arProperties['CODE'] == $prefix . 'PAYMENT_TYPE_ID' && $pay_system_id) {
			$arResult["POST"]["ORDER_PROP_".$arProperties["ID"]] = $pay_system_id;
			$arResult["POST"]["~ORDER_PROP_".$arProperties["ID"]] = $pay_system_id;
		}elseif ($arProperties['CODE'] == $prefix. 'PAYMENT_TYPE_NAME' && $pay_system_id) {
			$arResult["POST"]["ORDER_PROP_".$arProperties["ID"]] = isset($arResult["PAY_SYSTEM"]['PSA_NAME']) ? $arResult["PAY_SYSTEM"]['PSA_NAME'] : '';
			$arResult["POST"]["~ORDER_PROP_".$arProperties["ID"]] = isset($arResult["PAY_SYSTEM"]['PSA_NAME']) ? $arResult["PAY_SYSTEM"]['PSA_NAME'] : '';
		}elseif ($arProperties['CODE'] == $prefix . 'STORE_ID' && isset($arResult['STORE_ID'])) {
			$arResult["POST"]["ORDER_PROP_".$arProperties["ID"]] = (int)$arResult['STORE_ID'] > 0 ? (int)$arResult['STORE_ID'] : '';
			$arResult["POST"]["~ORDER_PROP_".$arProperties["ID"]] = (int)$arResult['STORE_ID'] > 0 ? (int)$arResult['STORE_ID'] : '';
		}elseif ($arProperties['CODE'] == $prefix .'DELIVERY_ID' && isset($arResult['DELIVERY_ID'])) {
			$arResult["POST"]["ORDER_PROP_".$arProperties["ID"]] = (int)$arResult['DELIVERY_ID'] > 0 ? (int)$arResult['DELIVERY_ID'] : '';
			$arResult["POST"]["~ORDER_PROP_".$arProperties["ID"]] = (int)$arResult['DELIVERY_ID'] > 0 ? (int)$arResult['DELIVERY_ID'] : '';
		}elseif ($arProperties['CODE'] == $prefix .'ADDRESS' && isset($arResult['DELIVERY_LOCATION'])) {
			$arResult["POST"]["ORDER_PROP_".$arProperties["ID"]] = (int)$arResult['DELIVERY_LOCATION'] > 0 ? (int)$arResult['DELIVERY_LOCATION'] : '';
			$arResult["POST"]["~ORDER_PROP_".$arProperties["ID"]] = (int)$arResult['DELIVERY_LOCATION'] > 0 ? (int)$arResult['DELIVERY_LOCATION'] : '';
		}
		/*
		 * ***************************************************
		 */
		
		if (IntVal($arProperties["PROPS_GROUP_ID"]) != $propertyGroupID)
		{
			$arProperties["SHOW_GROUP_NAME"] = "Y";
			$propertyGroupID = $arProperties["PROPS_GROUP_ID"];
		}
		$curVal = $arResult["POST"]["ORDER_PROP_".$arProperties["ID"]];

		if ($arProperties["TYPE"] == "CHECKBOX")
		{
			if ($curVal == "Y")
				$arProperties["VALUE_FORMATED"] = GetMessage("SALE_YES");
			else
				$arProperties["VALUE_FORMATED"] = GetMessage("SALE_NO");
		}
		elseif ($arProperties["TYPE"] == "TEXT" || $arProperties["TYPE"] == "TEXTAREA")
		{
			$arProperties["VALUE_FORMATED"] = $curVal;
		}
		elseif ($arProperties["TYPE"] == "SELECT" || $arProperties["TYPE"] == "RADIO")
		{
			$arVal = CSaleOrderPropsVariant::GetByValue($arProperties["ID"], $curVal);
			$arProperties["VALUE_FORMATED"] = htmlspecialcharsEx($arVal["NAME"]);
		}
		elseif ($arProperties["TYPE"] == "MULTISELECT")
		{
			$countCurVal = count($curVal);
			for ($i = 0; $i < $countCurVal; $i++)
			{
			$arVal = CSaleOrderPropsVariant::GetByValue($arProperties["ID"], $curVal[$i]);
			if ($i > 0)
				$arProperties["VALUE_FORMATED"] .= ", ";
						$arProperties["VALUE_FORMATED"] .= htmlspecialcharsEx($arVal["NAME"]);
			}
			}
			elseif ($arProperties["TYPE"] == "LOCATION")
			{
			$arVal = CSaleLocation::GetByID($curVal, LANGUAGE_ID);
			/*
			$arProperties["VALUE_FORMATED"] = htmlspecialcharsEx($arVal["COUNTRY_NAME"]);
				if (strlen($arVal["COUNTRY_NAME"]) > 0 && strlen($arVal["CITY_NAME"]) > 0)
				$arProperties["VALUE_FORMATED"] .= " - ";
			$arProperties["VALUE_FORMATED"] .= htmlspecialcharsEx($arVal["CITY_NAME"]);
			*/

			$locationName = "";
			$locationName .= ((strlen($arVal["COUNTRY_NAME"])<=0) ? "" : $arVal["COUNTRY_NAME"]);

				if (strlen($arVal["COUNTRY_NAME"])>0 && strlen($arVal["REGION_NAME"])>0)
					$locationName .= " - ".$arVal["REGION_NAME"];
				elseif (strlen($arVal["REGION_NAME"])>0)
					$locationName .= $arVal["REGION_NAME"];

				if (strlen($arVal["COUNTRY_NAME"])>0 || strlen($arVal["REGION_NAME"])>0)
					$locationName .= " - ".$arVal["CITY_NAME"];
				elseif (strlen($arVal["CITY_NAME"])>0)
					$locationName .= $arVal["CITY_NAME"];

				$arProperties["VALUE_FORMATED"] .= htmlspecialcharsEx($locationName);
			}
			$arResult["ORDER_PROPS_PRINT"][] = $arProperties;
	}

	if (is_array($arResult["DELIVERY_ID"]))
		{
			$obDeliveryHandler = CSaleDeliveryHandler::GetBySID($arResult["DELIVERY_ID"][0]);
			$arResult["DELIVERY"] = $obDeliveryHandler->Fetch();

			$arResult["DELIVERY_PROFILE"] = $arResult["DELIVERY_ID"][1];

			$arOrderTmpDel = array(
					"PRICE" => $arResult["ORDER_PRICE"],
				"WEIGHT" => $arResult["ORDER_WEIGHT"],
				"LOCATION_FROM" => COption::GetOptionInt('sale', 'location'),
						"LOCATION_TO" => $arResult["DELIVERY_LOCATION"],
				"LOCATION_ZIP" => $arResult["DELIVERY_LOCATION_ZIP"],

			);

						$arDeliveryPrice = CSaleDeliveryHandler::CalculateFull($arResult["DELIVERY_ID"][0], $arResult["DELIVERY_ID"][1], $arOrderTmpDel, $arResult["BASE_LANG_CURRENCY"]);

			if ($arDeliveryPrice["RESULT"] == "ERROR")
			$arResult["ERROR_MESSAGE"] = $arDeliveryPrice["TEXT"];
			else
			$arResult["DELIVERY_PRICE"] = roundEx($arDeliveryPrice["VALUE"], SALE_VALUE_PRECISION);

			}
			elseif ((IntVal($arResult["DELIVERY_ID"]) > 0) && ($arDeliv = CSaleDelivery::GetByID($arResult["DELIVERY_ID"])))
		{
					$arDeliv["NAME"] = htmlspecialcharsEx($arDeliv["NAME"]);
							$arResult["DELIVERY"] = $arDeliv;
							$arResult["DELIVERY_PRICE"] = roundEx(CCurrencyRates::ConvertCurrency($arDeliv["PRICE"], $arDeliv["CURRENCY"], $arResult["BASE_LANG_CURRENCY"]), SALE_VALUE_PRECISION);
			}
			elseif (IntVal($DELIVERY_ID)>0)
			{
			$arResult["DELIVERY"] = "ERROR";
}

// if ((IntVal($arResult["PAY_SYSTEM_ID"]) > 0) && ($arPaySys = CSalePaySystem::GetByID($arResult["PAY_SYSTEM_ID"], $arResult["PERSON_TYPE"])))
// {
// 	$arResult["PAY_SYSTEM"] = $arPaySys;
// 	$arResult["PAY_SYSTEM"]["PSA_NAME"] = htmlspecialcharsEx($arResult["PAY_SYSTEM"]["PSA_NAME"]);
// 	$arResult["PAY_SYSTEM"]["~PSA_NAME"] = $arResult["PAY_SYSTEM"]["PSA_NAME"];
// }
// elseif (IntVal($arResult["PAY_SYSTEM_ID"]) > 0)
// {
// 	$arResult["PAY_SYSTEM"] = "ERROR";
// }

$arResult["BASKET_ITEMS"] = Array();
$arResult["ORDER_WEIGHT"] = 0;

CSaleBasket::UpdateBasketPrices(CSaleBasket::GetBasketUserID(), SITE_ID);
$dbBasketItems = CSaleBasket::GetList(
		array("NAME" => "ASC"),
		array(
				"FUSER_ID" => CSaleBasket::GetBasketUserID(),
				"LID" => SITE_ID,
				"ORDER_ID" => "NULL"
		)
);
while ($arBasketItems = $dbBasketItems->Fetch())
{
	if ($arBasketItems["DELAY"] == "N" && $arBasketItems["CAN_BUY"] == "Y")
	{
		$arBasketItems['NAME'] = htmlspecialcharsEx($arBasketItems['NAME']);
		$arBasketItems['NOTES'] = htmlspecialcharsEx($arBasketItems['NOTES']);
		$arResult["ORDER_WEIGHT"] += $arBasketItems["WEIGHT"] * $arBasketItems["QUANTITY"];
		$arBasketItems["WEIGHT_FORMATED"] = roundEx(DoubleVal($arBasketItems["WEIGHT"]/$arResult["WEIGHT_KOEF"]), SALE_WEIGHT_PRECISION)." ".$arResult["WEIGHT_UNIT"];

		$arBasketItems["PRICE_FORMATED"] = SaleFormatCurrency($arBasketItems["PRICE"], $arBasketItems["CURRENCY"]);
		if(DoubleVal($arBasketItems["DISCOUNT_PRICE"]) > 0)
		{
			if(DoubleVal($arBasketItems["VAT_RATE"]) > 0)
				$arBasketItems["VAT_VALUE"] = DoubleVal(($arBasketItems["PRICE"] / ($arBasketItems["VAT_RATE"] +1)) * $arBasketItems["VAT_RATE"]);

			$arBasketItems["DISCOUNT_PRICE_PERCENT"] = $arBasketItems["DISCOUNT_PRICE"]*100 / ($arBasketItems["DISCOUNT_PRICE"] + $arBasketItems["PRICE"]);
			$arBasketItems["DISCOUNT_PRICE_PERCENT_FORMATED"] = roundEx($arBasketItems["DISCOUNT_PRICE_PERCENT"], 0)."%";
		}

		$arBasketItems["PROPS"] = Array();
		$dbProp = CSaleBasket::GetPropsList(Array("SORT" => "ASC", "ID" => "ASC"), Array("BASKET_ID" => $arBasketItems["ID"], "!CODE" => array("CATALOG.XML_ID", "PRODUCT.XML_ID")));
		while($arProp = $dbProp -> GetNext())
			$arBasketItems["PROPS"][] = $arProp;


		$arResult["BASKET_ITEMS"][] = $arBasketItems;
	}
}

$arResult["ORDER_WEIGHT_FORMATED"] = roundEx(DoubleVal($arResult["ORDER_WEIGHT"]/$arResult["WEIGHT_KOEF"]), SALE_WEIGHT_PRECISION)." ".$arResult["WEIGHT_UNIT"];
$arResult["ORDER_PRICE_FORMATED"] = SaleFormatCurrency($arResult["ORDER_PRICE"], $arResult["BASE_LANG_CURRENCY"]);
$arResult["DISCOUNT_PRICE_FORMATED"] = SaleFormatCurrency($arResult["DISCOUNT_PRICE"], $arResult["BASE_LANG_CURRENCY"]);
$DISCOUNT_PRICE_ALL += $arResult["DISCOUNT_PRICE"];

if (DoubleVal($arResult["DISCOUNT_PERCENT"])>0)
	$arResult["DISCOUNT_PERCENT_FORMATED"] = DoubleVal($arResult["DISCOUNT_PERCENT"])."%";
if (is_array($arResult["arTaxList"]) && count($arResult["arTaxList"])>0)
{
	foreach ($arResult["arTaxList"] as $key => $val)
	{
		if ($val["IS_IN_PRICE"]=="Y")
		{
			$arResult["arTaxList"][$key]["VALUE_FORMATED"] = " (".(($val["IS_PERCENT"]=="Y")?"".DoubleVal($val["VALUE"])."%, ":" ").GetMessage("SALE_TAX_INPRICE").")";
		}
		elseif ($val["IS_PERCENT"]=="Y")
		{
			$arResult["arTaxList"][$key]["VALUE_FORMATED"] = " (".DoubleVal($val["VALUE"])."%)";
		}
		$arResult["arTaxList"][$key]["VALUE_MONEY_FORMATED"] = SaleFormatCurrency($val["VALUE_MONEY"], $arResult["BASE_LANG_CURRENCY"]);
	}
}

if(IntVal($arResult["DELIVERY_PRICE"])>0)
	$arResult["DELIVERY_PRICE_FORMATED"] = SaleFormatCurrency($arResult["DELIVERY_PRICE"], $arResult["BASE_LANG_CURRENCY"]);
$orderTotalSum = $arResult["ORDER_PRICE"] + $arResult["DELIVERY_PRICE"] + $arResult["TAX_PRICE"] - $arResult["DISCOUNT_PRICE"];
$arResult["ORDER_TOTAL_PRICE_FORMATED"] = SaleFormatCurrency($orderTotalSum, $arResult["BASE_LANG_CURRENCY"]);
if ($arResult["PAY_CURRENT_ACCOUNT"] == "Y")
{
	$dbUserAccount = CSaleUserAccount::GetList(
			array(),
			array(
					"USER_ID" => $USER->GetID(),
					"CURRENCY" => $arResult["BASE_LANG_CURRENCY"]
			)
	);
	if ($arUserAccount = $dbUserAccount->Fetch())
	{
		if ($arUserAccount["CURRENT_BUDGET"] > 0)
		{
			$arResult["PAYED_FROM_ACCOUNT_FORMATED"] = SaleFormatCurrency((($arUserAccount["CURRENT_BUDGET"] >= $orderTotalSum) ? $orderTotalSum : $arUserAccount["CURRENT_BUDGET"]),	$arResult["BASE_LANG_CURRENCY"]);
		}
		if($arUserAccount["CURRENT_BUDGET"] >= $orderTotalSum)
		{
			$arResult["PAYED_FROM_ACCOUNT"] = "Y";
		}
	}
}

if(CModule::IncludeModule("statistic"))
{
	$event1 = "eStore";
	$event2 = "Step4_5";
	$event3 = "";

	foreach($arProductsInBasket as $ar_prod)
	{
		$event3 .= $ar_prod["PRODUCT_ID"].", ";
	}
	$e = $event1."/".$event2."/".$event3;

	if(!is_array($_SESSION["ORDER_EVENTS"]) || (is_array($_SESSION["ORDER_EVENTS"]) && !in_array($e, $_SESSION["ORDER_EVENTS"]))) // check for event in session
	{
		CStatistic::Set_Event($event1, $event2, $event3);
		$_SESSION["ORDER_EVENTS"][] = $e;
	}
}
}
//------------------ STEP 6 ----------------------------------------------
elseif ($arResult["CurrentStep"] == 7)
{
	$arOrder = false;
	if ($bUseAccountNumber) // supporting ACCOUNT_NUMBER or ID in the URL
	{
		$dbOrder = CSaleOrder::GetList(
				array("DATE_UPDATE" => "DESC"),
				array(
						"LID" => SITE_ID,
						"USER_ID" => IntVal($USER->GetID()),
						"ACCOUNT_NUMBER" => $ID
				)
		);

		if ($arOrder = $dbOrder->GetNext())
		{
			$arResult["ORDER_ID"] = $arOrder["ID"];
		}
	}

	if (!$arOrder)
	{
		$dbOrder = CSaleOrder::GetList(
				array("DATE_UPDATE" => "DESC"),
				array(
						"LID" => SITE_ID,
						"USER_ID" => IntVal($USER->GetID()),
						"ID" => $ID
				)
		);

		$arOrder = $dbOrder->GetNext();
	}

	if ($arOrder)
	{
		if (IntVal($arOrder["PAY_SYSTEM_ID"]) > 0)
		{
			$dbPaySysAction = CSalePaySystemAction::GetList(
					array(),
					array(
							"PAY_SYSTEM_ID" => $arOrder["PAY_SYSTEM_ID"],
							"PERSON_TYPE_ID" => $arOrder["PERSON_TYPE_ID"]
					),
					false,
					false,
					array("NAME", "ACTION_FILE", "NEW_WINDOW", "PARAMS", "ENCODING")
			);
			if ($arPaySysAction = $dbPaySysAction->Fetch())
			{
				$arPaySysAction["NAME"] = htmlspecialcharsEx($arPaySysAction["NAME"]);
				if (strlen($arPaySysAction["ACTION_FILE"]) > 0)
				{
					if ($arPaySysAction["NEW_WINDOW"] != "Y")
					{
						CSalePaySystemAction::InitParamArrays($arOrder, $ID, $arPaySysAction["PARAMS"]);

						$pathToAction = $_SERVER["DOCUMENT_ROOT"].$arPaySysAction["ACTION_FILE"];

						$pathToAction = str_replace("\\", "/", $pathToAction);
						while (substr($pathToAction, strlen($pathToAction) - 1, 1) == "/")
							$pathToAction = substr($pathToAction, 0, strlen($pathToAction) - 1);

						if (file_exists($pathToAction))
						{
							if (is_dir($pathToAction) && file_exists($pathToAction."/payment.php"))
								$pathToAction .= "/payment.php";

							$arPaySysAction["PATH_TO_ACTION"] = $pathToAction;
						}

						if(strlen($arPaySysAction["ENCODING"]) > 0)
						{
							define("BX_SALE_ENCODING", $arPaySysAction["ENCODING"]);
							AddEventHandler("main", "OnEndBufferContent", "ChangeEncoding");
							function ChangeEncoding($content)
							{
								global $APPLICATION;
								header("Content-Type: text/html; charset=".BX_SALE_ENCODING);
								$content = $APPLICATION->ConvertCharset($content, SITE_CHARSET, BX_SALE_ENCODING);
								$content = str_replace("charset=".SITE_CHARSET, "charset=".BX_SALE_ENCODING, $content);
							}
						}
					}
				}
				$arResult["PAY_SYSTEM"] = $arPaySysAction;
			}
		}

		$arResult["ORDER"] = $arOrder;

		$arDateInsert = explode(" ", $arOrder["DATE_INSERT"]);
		if (is_array($arDateInsert) && count($arDateInsert) > 0)
			$arResult["ORDER"]["DATE_INSERT_FORMATED"] = $arDateInsert[0];
		else
			$arResult["ORDER"]["DATE_INSERT_FORMATED"] = $arOrder["DATE_INSERT"];

		if(CModule::IncludeModule("statistic"))
		{
			$event1 = "eStore";
			$event2 = "order_confirm";
			$event3 = $arResult["ORDER"]["ID"];

			$e = $event1."/".$event2."/".$event3;

			if(!is_array($_SESSION["ORDER_EVENTS"]) || (is_array($_SESSION["ORDER_EVENTS"]) && !in_array($e, $_SESSION["ORDER_EVENTS"]))) // check for event in session
			{
				CStatistic::Set_Event($event1, $event2, $event3);
				$_SESSION["ORDER_EVENTS"][] = $e;
			}
		}

		foreach(GetModuleEvents("sale", "OnSaleComponentOrderComplete", true) as $arEvent)
			ExecuteModuleEventEx($arEvent, Array($arOrder["ID"], $arOrder));

	}
}

//------------------------------------------------------------------------
?>