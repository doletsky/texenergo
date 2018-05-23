<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeTemplateLangFile(__FILE__);

$arComponentDescription = array(
    "NAME" => GetMessage("CODEROID_BARCODE_NAME"),
    "DESCRIPTION" => GetMessage("CODEROID_BARCODE_DESCRIPTION"),
    "ICON" => "icon.png",
    "CACHE_PATH" => "Y",
    "SORT" => 70,
    "PATH" => array(
        "ID" => "coderoid_barcode",
        "NAME" => GetMessage("CODEROID_BARCODE_COMPONENTS")
    )
);
?>