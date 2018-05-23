<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if ($arResult["DETAIL_PICTURE"])
    $arResult["SCALED_PICTURE"] = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], array("width" => 720, "height" => 720));