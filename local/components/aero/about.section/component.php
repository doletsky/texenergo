<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!CModule::IncludeModule('iblock')) {
    ShowError('Не установлен модуль iblock');
    return;
}

if (!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 3600;

$arSections = Array();
$rsSection = CIBlockElement::GetList(Array('sort' => 'asc', 'name' => 'asc'), Array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y'), false, false, Array('ID', 'NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'PROPERTY_LINK'));

while ($arSection = $rsSection->Fetch()) {

    $arSections[] = array(
            "ID" => $arSection["ID"],
            "NAME" => $arSection["NAME"],
            "LINK" => $arSection["PROPERTY_LINK_VALUE"],
            "PREVIEW_PICTURE" => $arSection["PREVIEW_PICTURE"] = (0 < $arSection["PREVIEW_PICTURE"] ? CFile::GetFileArray($arSection["PREVIEW_PICTURE"]) : false),
            "PREVIEW_TEXT" => $arSection["PREVIEW_TEXT"],
        );

	}

$arResult['ITEMS'] = $arSections;

$this->IncludeComponentTemplate();
