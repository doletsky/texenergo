<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if ($USER->IsAuthorized() && count($arResult["QUESTIONS"]) > 0) {
    $obQueryResult = CUser::GetByID($USER->GetID());
    if ($arUser = $obQueryResult->Fetch()) {
        foreach ($arResult["arAnswers"] as $code => $arAnswer) {
            $value = "";

            switch ($code) {
                case "callback_fio": $value = $arUser["NAME"]; break;
                case "callback_phone": $value = $arUser["PERSONAL_PHONE"]; break;
                case "callback_email": $value = $arUser["EMAIL"]; break;
            }
            if (strlen($value) > 0) {
                $arAnswer = $arAnswer[0];
                $arResult["QUESTIONS"][$code]["HTML_CODE"] =
                    CForm::GetTextField($arAnswer["ID"], $value, $arAnswer["FIELD_WIDTH"], $arAnswer["FIELD_PARAM"]);
            }
        }
    }
}