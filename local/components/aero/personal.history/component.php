<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

CModule::IncludeModule('sale');
$arResult['IDS'] = Array();

$arParams["VIEWED_COUNT"] = IntVal($arParams["VIEWED_COUNT"]);
if ($arParams["VIEWED_COUNT"] <= 0) $arParams["VIEWED_COUNT"] = 20;


$arFilter = Array(
    'LID' => SITE_ID,
    'FUSER_ID' => CSaleBasket::GetBasketUserID(),
);

$rsItems = CSaleViewedProduct::GetList(
    array(
        "DATE_VISIT" => "DESC"
    ),
    $arFilter,
    false,
    array(
        "nTopCount" => $arParams["VIEWED_COUNT"]
    ),
    array('ID', 'PRODUCT_ID')
);

while ($arItem = $rsItems->Fetch()) {
    $arResult['IDS'][] = $arItem["PRODUCT_ID"];
}

$this->IncludeComponentTemplate();
