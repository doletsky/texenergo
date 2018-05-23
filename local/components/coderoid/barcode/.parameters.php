<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile(__FILE__);

$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "CODE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("CODEROID_BARCODE_CODE"),
            "TYPE" => "STRING",
        ),
        "CLASS" => array(
            "PARENT" => "SETTINGS",
            "NAME" => GetMessage("CODEROID_BARCODE_CLASS"),
            "TYPE" => "STRING",
        ),
        "ID" => array(
            "PARENT" => "SETTINGS",
            "NAME" => GetMessage("CODEROID_BARCODE_ID"),
            "TYPE" => "STRING",
        ),
        "SCALE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("CODEROID_BARCODE_SCALE"),
            "TYPE" => "LIST",
            "VALUES" => array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7'),
            "DEFAULT" => "1",
        ),
        "MODE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("CODEROID_BARCODE_MODE"),
            "TYPE" => "LIST",
            "VALUES" => array('png' => 'png', 'jpg' => 'jpg', 'jpeg' => 'jpeg', 'gif' => 'gif'),
            "DEFAULT" => "png",
        ),
    ),
);