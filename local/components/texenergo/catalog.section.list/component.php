<?php
/*
 * Компонента изменена.
 * Пункт ТЗ 1.13. Каталог товаров. Страница первого уровня каталога.
 * Добавлены баннера.
 * Добавлена фильтрация по товарам техэнерго.
 * Добавлены данные по рекламе-баннеру для корнего элемента категории.
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

/*************************************************************************
	Processing of received parameters
*************************************************************************/
if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
$arParams["SECTION_ID"] = intval($arParams["SECTION_ID"]);
$arParams["SECTION_CODE"] = trim($arParams["SECTION_CODE"]);

$arParams["SECTION_URL"]=trim($arParams["SECTION_URL"]);

$arParams["TOP_DEPTH"] = intval($arParams["TOP_DEPTH"]);
if($arParams["TOP_DEPTH"] <= 0)
	$arParams["TOP_DEPTH"] = 2;
$arParams["COUNT_ELEMENTS"] = $arParams["COUNT_ELEMENTS"]!="N";
$arParams["ADD_SECTIONS_CHAIN"] = $arParams["ADD_SECTIONS_CHAIN"]!="N"; //Turn on by default

$arResult["SECTIONS"]=array();

/*
 * добавленный параметр FILTER_NAME
 */

if(strlen($arParams["FILTER_NAME"])<=0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
{
	$arrFilter = array();
}
else
{
	global ${$arParams["FILTER_NAME"]};
	$arrFilter = ${$arParams["FILTER_NAME"]};
	if(!is_array($arrFilter))
		$arrFilter = array();
}

/*************************************************************************
			Work with cache
*************************************************************************/
if($this->StartResultCache(false, array($arrFilter, ($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()))))
{
// if($this->StartResultCache(false, ($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups())))
// {
	if(!CModule::IncludeModule("iblock"))
	{
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	$arFilter = array(
		"ACTIVE" => "Y",
		"GLOBAL_ACTIVE" => "Y",
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"CNT_ACTIVE" => "Y",
	);

	$arSelect = array();
	if(array_key_exists("SECTION_FIELDS", $arParams) && !empty($arParams["SECTION_FIELDS"]) && is_array($arParams["SECTION_FIELDS"]))
	{
		foreach($arParams["SECTION_FIELDS"] as &$field)
		{
			if (!empty($field) && is_string($field))
				$arSelect[] = $field;
		}
		if (isset($field))
			unset($field);
	}

	if(!empty($arSelect))
	{
		$arSelect[] = "ID";
		$arSelect[] = "NAME";
		$arSelect[] = "LEFT_MARGIN";
		$arSelect[] = "RIGHT_MARGIN";
		$arSelect[] = "DEPTH_LEVEL";
		$arSelect[] = "IBLOCK_ID";
		$arSelect[] = "IBLOCK_SECTION_ID";
		$arSelect[] = "LIST_PAGE_URL";
		$arSelect[] = "SECTION_PAGE_URL";
	}

	$arParams['SECTION_USER_FIELDS'][] = 'UF_NEW_ITEMS';
	$arParams['SECTION_USER_FIELDS'][] = 'UF_LEADER_ITEMS';
	if(array_key_exists('SECTION_USER_FIELDS', $arParams) && !empty($arParams["SECTION_USER_FIELDS"]) && is_array($arParams["SECTION_USER_FIELDS"]))
	{
		foreach($arParams["SECTION_USER_FIELDS"] as &$field)
		{
			if(is_string($field) && preg_match("/^UF_/", $field))
				$arSelect[] = $field;
		}
		if (isset($field))
			unset($field);
	}
	$arResult["SECTION"] = false;

	$intSectionDepth = 0;
	if($arParams["SECTION_ID"]>0)
	{
		$arFilter["ID"] = $arParams["SECTION_ID"];
		$rsSections = CIBlockSection::GetList(array(), $arFilter, $arParams["COUNT_ELEMENTS"], $arSelect);
		$rsSections->SetUrlTemplates("", $arParams["SECTION_URL"]);
		$arResult["SECTION"] = $rsSections->GetNext();

	}
	elseif('' != $arParams["SECTION_CODE"])
	{
		$arFilter["=CODE"] = $arParams["SECTION_CODE"];
		$rsSections = CIBlockSection::GetList(array(), $arFilter, $arParams["COUNT_ELEMENTS"], $arSelect);
		$rsSections->SetUrlTemplates("", $arParams["SECTION_URL"]);
		$arResult["SECTION"] = $rsSections->GetNext();
	}

	if(is_array($arResult["SECTION"]))
	{
 		unset($arFilter["ID"]);
 		//$arFilter['CNT_ACTIVE'] = 'N';
  		unset($arFilter["=CODE"]);
 		$arFilter["LEFT_MARGIN"]=$arResult["SECTION"]["LEFT_MARGIN"]+1;
 		$arFilter["RIGHT_MARGIN"]=$arResult["SECTION"]["RIGHT_MARGIN"];
 		$arFilter["<="."DEPTH_LEVEL"]=$arResult["SECTION"]["DEPTH_LEVEL"] + $arParams["TOP_DEPTH"];

		$arResult["SECTION"]["PATH"] = array();
		/*
		 * добавить в путь данные по рекламе категории UF_INSIDE_BANNER
		 */
		$rootSectionIds = array();
		$rsPath = CIBlockSection::GetNavChain($arResult["SECTION"]["IBLOCK_ID"], $arResult["SECTION"]["ID"]);
		$rsPath->SetUrlTemplates("", $arParams["SECTION_URL"]);
		while($arPath = $rsPath->GetNext())
		{
			$arResult["SECTION"]["PATH"][$arPath["ID"]]=$arPath;

			if (isset($arPath["DEPTH_LEVEL"]) && $arPath["DEPTH_LEVEL"] == 1) {
				$rootSectionIds[] = $arPath["ID"];
			}
		}
		if (count($rootSectionIds)) {
			$arFilter1 = array(
					"ACTIVE" => "Y",
					"GLOBAL_ACTIVE" => "Y",
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"CNT_ACTIVE" => "Y",
					"ID" => $rootSectionIds
			);
			$arSelect1 = array('ID', 'UF_LINK_BANNER', 'UF_INSIDE_BANNER');
			$rsSectionList = CIBlockSection::GetList(array(), $arFilter1, false, $arSelect1);
			while($item = $rsSectionList->GetNext() ) {
				if ((int)$item['UF_INSIDE_BANNER']) {
					$fileId = (int) $item['UF_INSIDE_BANNER'];
					$item['UF_INSIDE_BANNER'] = getFileParams($item['UF_INSIDE_BANNER']);
				}
				$arResult["SECTION"]["PATH"][$item['ID']]['UF_LINK_BANNER'] = $item['UF_LINK_BANNER'];
				$arResult["SECTION"]["PATH"][$item['ID']]['UF_INSIDE_BANNER'] = $item['UF_INSIDE_BANNER'];
			}
		}
	}
	else
	{
		$arResult["SECTION"] = array("ID"=>0, "DEPTH_LEVEL"=>0);
		$arFilter["<="."DEPTH_LEVEL"] = $arParams["TOP_DEPTH"];
	}

	$intSectionDepth = $arResult["SECTION"]['DEPTH_LEVEL'];

	//ORDER BY
	$arSort = array(
		"left_margin"=>"asc",
	);

    if(isset($arrFilter)
       && isset($arrFilter['PROPERTY_IS_OWN'])){
        $arFilter['PROPERTY']['IS_OWN'] = $arrFilter['PROPERTY_IS_OWN'];
        $arParams["COUNT_ELEMENTS"] = 'Y';
    }

	$boolPicture = empty($arSelect) || in_array('PICTURE', $arSelect);
	//EXECUTE

    $arFilter = array_merge($arFilter, $arrFilter);
	$rsSections = CIBlockSection::GetList($arSort, $arFilter, $arParams["COUNT_ELEMENTS"], $arSelect);
	$rsSections->SetUrlTemplates("", $arParams["SECTION_URL"]);
    $sectionIds = array();

	while($arSection = $rsSections->GetNext())
	{
		/*$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arSection["IBLOCK_ID"], $arSection["ID"]);
		$arSection["IPROPERTY_VALUES"] = $ipropValues->getValues();*/

		if ($boolPicture)
		{
			$mxPicture = false;
			$arSection["PICTURE"] = intval($arSection["PICTURE"]);
			if (0 < $arSection["PICTURE"])
				$mxPicture = CFile::GetFileArray($arSection["PICTURE"]);
			$arSection["PICTURE"] = $mxPicture;
			/*if ($arSection["PICTURE"])
			{
				$arSection["PICTURE"]["ALT"] = $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"];
				if ($arSection["PICTURE"]["ALT"] == "")
					$arSection["PICTURE"]["ALT"] = $arSection["NAME"];
				$arSection["PICTURE"]["TITLE"] = $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"];
				if ($arSection["PICTURE"]["TITLE"] == "")
					$arSection["PICTURE"]["TITLE"] = $arSection["NAME"];
			}*/
		}
		$arSection['RELATIVE_DEPTH_LEVEL'] = $arSection['DEPTH_LEVEL'] - $intSectionDepth;

		/*
		$arButtons = CIBlock::GetPanelButtons(
			$arSection["IBLOCK_ID"],
			0,
			$arSection["ID"],
			array("SESSID"=>false, "CATALOG"=>true)
		);
		$arSection["EDIT_LINK"] = $arButtons["edit"]["edit_section"]["ACTION_URL"];
		$arSection["DELETE_LINK"] = $arButtons["edit"]["delete_section"]["ACTION_URL"];
		*/
		$arResult["SECTIONS"][$arSection['ID']]=$arSection;

        $sectionIds[] = $arSection['ID'];
	}

	$arResult["SECTIONS_COUNT"] = count($arResult["SECTIONS"]);

	$this->SetResultCacheKeys(array(
		"SECTIONS_COUNT",
		"SECTION",
		"SECTIONS",
	));

	$this->IncludeComponentTemplate();
}

if($arResult["SECTIONS_COUNT"] > 0 || isset($arResult["SECTION"]))
{
	if(
		$USER->IsAuthorized()
		&& $APPLICATION->GetShowIncludeAreas()
		&& CModule::IncludeModule("iblock")
	)
	{
		$UrlDeleteSectionButton = "";
		if(isset($arResult["SECTION"]) && $arResult["SECTION"]['IBLOCK_SECTION_ID'] > 0)
		{
			$rsSection = CIBlockSection::GetList(
				array(),
				array("=ID" => $arResult["SECTION"]['IBLOCK_SECTION_ID']),
				false,
				array("SECTION_PAGE_URL")
			);
			$rsSection->SetUrlTemplates("", $arParams["SECTION_URL"]);
			$arSection = $rsSection->GetNext();
			$UrlDeleteSectionButton = $arSection["SECTION_PAGE_URL"];
		}

		if(empty($UrlDeleteSectionButton))
		{
			$url_template = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "LIST_PAGE_URL");
			$arIBlock = CIBlock::GetArrayByID($arParams["IBLOCK_ID"]);
			$arIBlock["IBLOCK_CODE"] = $arIBlock["CODE"];
			$UrlDeleteSectionButton = CIBlock::ReplaceDetailURL($url_template, $arIBlock, true, false);
		}

		$arReturnUrl = array(
			"add_section" => (
				'' != $arParams["SECTION_URL"]?
				$arParams["SECTION_URL"]:
				CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_PAGE_URL")
			),
			"add_element" => (
				'' != $arParams["SECTION_URL"]?
				$arParams["SECTION_URL"]:
				CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_PAGE_URL")
			),
			"delete_section" => $UrlDeleteSectionButton,
		);
		$arButtons = CIBlock::GetPanelButtons(
			$arParams["IBLOCK_ID"],
			0,
			$arResult["SECTION"]["ID"],
			array("RETURN_URL" =>  $arReturnUrl, "CATALOG"=>true)
		);

		$this->AddIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));
	}

	if($arParams["ADD_SECTIONS_CHAIN"] && isset($arResult["SECTION"]) && is_array($arResult["SECTION"]["PATH"]))
	{
		foreach($arResult["SECTION"]["PATH"] as $arPath)
		{
			$APPLICATION->AddChainItem($arPath["NAME"], $arPath["~SECTION_PAGE_URL"]);
		}
	}
}
?>