<?
/*
 * Компонента изменена.
 * Пункт ТЗ 1.15. Страница сравнения товаров.
 * Изменена логика добавления в список сравнения,
 * сравнивать элементы можно только одной категории 
 */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
global $DB;
/** @global CUser $USER */
global $USER;
/** @global CMain $APPLICATION */
global $APPLICATION;

if(!CModule::IncludeModule("iblock"))
{
	ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
	return;
}
/*************************************************************************
	Processing of received parameters
*************************************************************************/
unset($arParams["IBLOCK_TYPE"]);
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);

$arParams["DETAIL_URL"]=trim($arParams["DETAIL_URL"]);
$arParams["COMPARE_URL"]=trim($arParams["COMPARE_URL"]);
if(strlen($arParams["COMPARE_URL"])<=0)
	$arParams["COMPARE_URL"] = "compare.php";

$arParams["NAME"]=trim($arParams["NAME"]);
if(strlen($arParams["NAME"])<=0)
	$arParams["NAME"] = "CATALOG_COMPARE_LIST";

if(!isset($_SESSION[$arParams["NAME"]]) || !is_array($_SESSION[$arParams["NAME"]]))
	$_SESSION[$arParams["NAME"]] = array();

if(!isset($_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]) || !is_array($_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]))
	$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]] = array();

if(!isset($_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"]) || !is_array($_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"]))
	$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"] = array();

if(isset($_REQUEST["id"]))
	$id = intval($_REQUEST["id"]);
else
	$id = 0;
/*************************************************************************
			Handling the Compare button
*************************************************************************/
if($_REQUEST["action"] == "ADD_TO_COMPARE_LIST" && $id > 0)
{
	if(!array_key_exists($id, $_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"]))
	{
		//SELECT
		$arSelect = array(
			"ID",
			"IBLOCK_ID",
			"IBLOCK_SECTION_ID",
			"NAME",
			"DETAIL_PAGE_URL",
			"IBLOCK_SECTION_ID"
		);
		//WHERE
		$arFilter = array(
			"ID" => $id,
			"IBLOCK_LID" => SITE_ID,
			"IBLOCK_ACTIVE" => "Y",
			"ACTIVE_DATE" => "Y",
			"ACTIVE" => "Y",
			"CHECK_PERMISSIONS" => "Y",
		);

		$arOffers = CIBlockPriceTools::GetOffersIBlock($arParams["IBLOCK_ID"]);
		$OFFERS_IBLOCK_ID = $arOffers? $arOffers["OFFERS_IBLOCK_ID"]: 0;

		if($arOffers)
			$arFilter["IBLOCK_ID"] = array($arParams["IBLOCK_ID"], $arOffers["OFFERS_IBLOCK_ID"]);
		else
			$arFilter["IBLOCK_ID"] = $arParams["IBLOCK_ID"];

		$rsElement = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
		$rsElement->SetUrlTemplates($arParams["DETAIL_URL"]);
		$arElement = $rsElement->GetNext();

		/*
		 * BEGIN сравнивать элементы можно только одной категории
		*/
		$catId = (int)$arElement['IBLOCK_SECTION_ID'];
		$nav = CIBlockSection::GetNavChain($arParams["IBLOCK_ID"], $arElement['IBLOCK_SECTION_ID']);
		while($params = $nav->ExtractFields("nav_")) {
			if ($params['DEPTH_LEVEL'] == 1) {
				$catId = $params['ID'];
			}
		}
	
		if (! isset($_SESSION['categoryId']) || ! (int)$_SESSION['categoryId']) {
			$_SESSION['categoryId'] = $catId;
			$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"] = array();
		}else if ($catId != (int) $_SESSION['categoryId']) {
			$_SESSION['categoryId'] = $catId;
			$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"] = array();
		}
		/*
		 * END сравнивать элементы можно только одной категории
		*/
		
		$arMaster = false;
		if($arElement && $arElement["IBLOCK_ID"] == $OFFERS_IBLOCK_ID)
		{
			$rsMasterProperty = CIBlockElement::GetProperty($arElement["IBLOCK_ID"], $arElement["ID"], array(), array("ID" => $arOffers["OFFERS_PROPERTY_ID"], "EMPTY" => "N"));
			if($arMasterProperty = $rsMasterProperty->Fetch())
			{
				$rsMaster = CIBlockElement::GetList(
					array()
					,array(
						"ID" => $arMasterProperty["VALUE"],
						"IBLOCK_ID" => $arMasterProperty["LINK_IBLOCK_ID"],
						"ACTIVE" => "Y",
					)
				,false, false, $arSelect);
				$rsMaster->SetUrlTemplates($arParams["DETAIL_URL"]);
				$arMaster = $rsMaster->GetNext();
			}
		}

		if($arMaster)
		{
			$arMaster["NAME"] = $arElement["NAME"];
			$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"][$id] = $arMaster;
		}
		elseif($arElement)
		{
			$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"][$id] = $arElement;
		}
	
	}
}
if (isset($_REQUEST['serial']) && (int)$_REQUEST['serial']) {
	//SELECT
	$arSelect = array(
		"ID",
		"IBLOCK_ID",
		"IBLOCK_SECTION_ID",
		"NAME",
		"DETAIL_PAGE_URL",
		"IBLOCK_SECTION_ID",
		"PROPERTY_GOODS_FILTER_VALUE"
	);
	//WHERE

	$arFilter = array(
		"PROPERTY_GOODS_FILTER_VALUE" => (int)$_REQUEST['serial'],
		"IBLOCK_LID" => SITE_ID,
		"IBLOCK_ACTIVE" => "Y",
		"ACTIVE_DATE" => "Y",
		"ACTIVE" => "Y",
		"CHECK_PERMISSIONS" => "Y",
	);
	$rsElement = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
	while ($arElement = $rsElement->GetNext()) {
		$catId = (int)$arElement['IBLOCK_SECTION_ID'];
		if (! isset($_SESSION['categoryId']) || ! (int)$_SESSION['categoryId']) {
			$_SESSION['categoryId'] = $catId;
			$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"] = array();
		}else if ($catId != (int) $_SESSION['categoryId']) {
			$_SESSION['categoryId'] = $catId;
			$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"] = array();
		}
		$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"][$arElement['ID']] = $arElement;
	}
	
}

/*************************************************************************
			Handling the Remove link
*************************************************************************/

if($_REQUEST["action"]=="DELETE_FROM_COMPARE_LIST" && $id > 0)
{
	unset($_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"][$id]);
}

$arResult = $_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"];
foreach($arResult as $id=>$arItem)
{
	$arResult[$id]["DELETE_URL"] = htmlspecialcharsbx($APPLICATION->GetCurPageParam("action=DELETE_FROM_COMPARE_LIST&id=".$arItem["ID"], array("action", "id")));
}

$this->IncludeComponentTemplate();
?>