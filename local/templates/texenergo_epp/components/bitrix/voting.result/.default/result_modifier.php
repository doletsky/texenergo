<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arQuestions = &$arResult["QUESTIONS"];

if (is_array($arQuestions) && count($arQuestions) > 0) {
    $uploadDir = COption::GetOptionString("main", "upload_dir", "upload");

    array_walk($arQuestions, function(&$arQuestion) use ($uploadDir) {
        array_walk($arQuestion["ANSWERS"], function(&$arAnswer) use ($uploadDir) {
            if (preg_match('#http(s)?://|/'.$uploadDir.'/#i', $arAnswer["MESSAGE"])) {
                $arAnswer["MESSAGE"] = "<img src='{$arAnswer["MESSAGE"]}'>";
            }
        });
    });
}

