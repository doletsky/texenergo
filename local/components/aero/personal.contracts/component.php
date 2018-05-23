<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!CModule::IncludeModule('iblock')) {
    ShowError('Не установлен модуль iblock');
    return;
}

if (!$USER->isAuthorized()) {
    $APPLICATION->AuthForm('Необходимо авторизоваться');
    return;
}

$arUser = CUser::GetByID($USER->GetID())->Fetch();

if (strlen($arUser['UF_COMPANY_ID']) <= 0) {
    //ShowError('Необходимо заполнить реквизиты');
    //return;
    $arUser['UF_COMPANY_ID'] = "-1";
}

$sFitlerStatus = trim($_REQUEST['status']);

if (strlen($sFitlerStatus) <= 0) $sFitlerStatus = 'open';

$arResult['FILTER_STATUS'] = $sFitlerStatus;

$arFilter = Array(
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
    '=PROPERTY_AGENT' => $arUser['UF_COMPANY_ID'],
);

if ($sFitlerStatus == 'open') {
    $arFilter['ACTIVE_DATE'] = 'Y';
}

if ($sFitlerStatus == 'closed') {
    $arFilter['!ACTIVE_DATE'] = 'Y';
}

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
//$arFilter['DATE_FROM'] = $arResult['FILTER_DATE_FROM'] . ' 00:00:00';
//$arFilter['DATE_TO'] = $arResult['FILTER_DATE_TO'] . ' 23:59:59';

$arResult['DEBT_MONEY'] = 0;
$arResult['ITEMS'] = Array();

if ($arUser['UF_COMPANY_ID'] != "-1") {

    $rsItems = CIBlockElement::GetList(Array('property_date' => 'desc'), $arFilter, false, false, Array(
        'ID', 'NAME', 'ACTIVE_FROM', 'ACTIVE_TO',
        'PROPERTY_DOC_ID',
        'PROPERTY_INN',
        'PROPERTY_DATE',
        'PROPERTY_FILE',
        'PROPERTY_DEBT_DAYS',
        'PROPERTY_REST_DAYS',
        'PROPERTY_DEBT_MONEY',
        'PROPERTY_REST_MONEY',
    ));


    while ($arItemData = $rsItems->Fetch()) {

        $arItem = Array(
            'ID' => $arItemData['ID'],
            'COMPANY' => $arItemData['NAME'],
            'ACTIVE_FROM' => date("d.m.y", MakeTimeStamp($arItemData['ACTIVE_FROM'])),
            'ACTIVE_TO' => date("d.m.y", MakeTimeStamp($arItemData['ACTIVE_TO'])),
            'DOC_ID' => $arItemData['PROPERTY_DOC_ID_VALUE'],
            'INN' => $arItemData['PROPERTY_INN_VALUE'],
            'DATE' => strtolower(FormatDate("j F Y", MakeTimeStamp($arItemData['PROPERTY_DATE_VALUE']))),
            //'FILE' => $arItemData['PROPERTY_FILE_VALUE'],
            'FILE' => "",
            'DEBT_DAYS' => $arItemData['PROPERTY_DEBT_DAYS_VALUE'],
            'DEBT_MONEY' => $arItemData['PROPERTY_DEBT_MONEY_VALUE'],
            'REST_DAYS' => $arItemData['PROPERTY_REST_DAYS_VALUE'],
            'REST_MONEY' => $arItemData['PROPERTY_REST_MONEY_VALUE'],
        );

        if ($arItemData['PROPERTY_FILE_VALUE'] != "") {
            $arItem['FILE'] = CFile::GetPath($arItemData['PROPERTY_FILE_VALUE']);
        }

        $arResult['ITEMS'][] = $arItem;
        $arResult['DEBT_MONEY'] += $arItem['DEBT_MONEY'];
    }

} else {
    $arResult['ITEMS'] = array();
    $arResult['DEBT_MONEY'] = 0;
}

$this->IncludeComponentTemplate();
