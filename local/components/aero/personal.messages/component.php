<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

if (!CModule::IncludeModule('iblock')) {
    ShowError('Не установлен модуль iblock');
    return;
}

if (!CModule::IncludeModule('sale')) {
    ShowError('Не установлен модуль sale');
    return;
}

if (!$USER->isAuthorized()) {
    $APPLICATION->AuthForm('Необходимо авторизоваться');
    return;
}


$arResult['ITEMS'] = Array();

$arFilter = Array(
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
);

$arResult['FILTER_DATE_TO'] = date('d.m.Y');
$arResult['FILTER_DATE_FROM'] = date('d.m.Y', time() - 86400 * 365);

$sDateFrom = trim($_REQUEST['date_from']);
if (strlen($sDateFrom) > 0 && $arDateFrom = ParseDateTime($sDateFrom, 'YYYY.MM.DD')) {
    $arResult['FILTER_DATE_FROM'] = $arDateFrom['DD'] . '.' . $arDateFrom['MM'] . '.' . $arDateFrom['YYYY'];
}

$sDateTo = trim($_REQUEST['date_to']);
if (strlen($sDateTo) > 0 && $arDateTo = ParseDateTime($sDateTo, 'YYYY.MM.DD')) {
    $arResult['FILTER_DATE_TO'] = $arDateTo['DD'] . '.' . $arDateTo['MM'] . '.' . $arDateTo['YYYY'];
}

$arSalesFilter = Array(
    "USER_ID" => $USER->GetID(),
);

$oIds = array();
$rsSales = CSaleOrder::GetList(array("DATE_INSERT" => "ASC"), $arSalesFilter);

while ($arSales = $rsSales->Fetch()) {

    $oIds[] = $arSales["ACCOUNT_NUMBER"];

}

$arFilter[] = array(
	'LOGIC' => 'OR',
	array('PROPERTY_ORDER_ID' => $oIds),
	array('PROPERTY_USER_ID' => $USER->GetID()),
);

$rsItems = CIBlockElement::GetList(
        Array(
            'DATE_INSERT' => 'desc'
        ),
        $arFilter,
        false,
        false,
        Array(
            'ID',
            'NAME',
            'PROPERTY_DATE',
			'PROPERTY_ORDER_ID',
            'PROPERTY_INVOICE_ID',
            'PROPERTY_MESSAGE',
        )
    );

    while ($arItemData = $rsItems->Fetch()) {

        $arItem = Array(
            'ID' => $arItemData['ID'],
            'NAME' => $arItemData['NAME'],
            'DATE' => $arItemData['PROPERTY_DATE_VALUE'],
            'INVOICE_ID' => $arItemData['PROPERTY_INVOICE_ID_VALUE'],
            'MESSAGE' => str_replace('руб.', '<i class="rouble">a</i>', $arItemData['PROPERTY_MESSAGE_VALUE']),
            'ORDER_ID' => $arItemData["PROPERTY_ORDER_ID_VALUE"],
        );

        $arResult['ITEMS'][] = $arItem;
    }

$this->IncludeComponentTemplate();
