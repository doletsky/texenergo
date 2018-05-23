<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arSection = $arResult["SECTION"];

if ($arSection && is_array($arSection["PATH"])) {
    $arCurrentSection = reset($arSection["PATH"]);

    if ($arCurrentSection["PICTURE"])
        $arCurrentSection["SCALED_PICTURE"] = CFile::ResizeImageGet($arCurrentSection["PICTURE"], array("width" => 720, "height" => 720));

    $arResult["CURRENT_SECTION"] = $arCurrentSection;
}
