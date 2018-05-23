<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if(!isAjax()) {
    LocalRedirect($_SERVER["HTTP_REFERER"]);
}

$arResult = Array(
    'success' => false,
    'data' => "",
    'id' => "",
);


if (!isset($_SESSION['LIKE']["ElementID".$_REQUEST['id']])) {

    CModule::IncludeModule("iblock");

    $arSelect = Array(
        'ID',
        'NAME',
        'PROPERTY_HELPFUL',
    );

    $rsComments = CIBlockElement::GetList(
        Array(),
        Array(
            'IBLOCK_ID' => IBLOCK_ID_COMMENTS,
            'ID' => intval($_REQUEST['id'])
        ),
        false,
        array("nTopCount"=>1),
        $arSelect
    );

    if ($arComments = $rsComments->Fetch()) {

        $arResult = Array(
            'success' => true,
            'data' => ++$arComments['PROPERTY_HELPFUL_VALUE'],
            'id' => intval($_REQUEST['id']),
        );

        CIBlockElement::SetPropertyValues(intval($_REQUEST['id']), IBLOCK_ID_COMMENTS, $arResult['data'], "HELPFUL");

        $_SESSION['LIKE']["ElementID" . $_REQUEST['id']] = true;

    }

}

$this->IncludeComponentTemplate();

