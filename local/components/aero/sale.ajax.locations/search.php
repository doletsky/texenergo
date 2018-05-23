<?
define("STOP_STATISTICS", true);
define("PUBLIC_AJAX_MODE", true);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
$arResult = array();

if (CModule::IncludeModule("sale")) {
    if (!empty($_REQUEST["search"]) && is_string($_REQUEST["search"])) {
        $search = $APPLICATION->UnJSEscape($_REQUEST["search"]);

        $arParams = array();
        $params = explode(",", $_REQUEST["params"]);
        foreach ($params as $param) {
            list($key, $val) = explode(":", $param);
            $arParams[$key] = $val;
        }

        $arFilter = array(
            "~CITY_NAME" => $search . "%",
            "LID" => LANGUAGE_ID,
        );

        $groupID = IntVal($_REQUEST['group']);
        if ($groupID > 0) {
            $arFilter['ID'] = Array();
            $rsIDS = CSaleLocationGroup::GetLocationList(Array(
                'LOCATION_GROUP_ID' => $groupID,
            ));
            while ($arID = $rsIDS->Fetch()) {
                $arFilter['ID'][] = $arID['LOCATION_ID'];
            }
        }

        $rsLocationsList = CSaleLocation::GetList(
            array(
                "CITY_NAME_LANG" => "ASC",
                "COUNTRY_NAME_LANG" => "ASC",
                "SORT" => "ASC",
            ),
            $arFilter,
            false,
            array("nTopCount" => 10),
            array(
                "ID", "CITY_ID", "CITY_NAME", "COUNTRY_NAME_LANG", "REGION_NAME_LANG"
            )
        );
        while ($arCity = $rsLocationsList->GetNext()) {
            $arZips = Array();
            $rsZip = CSaleLocation::GetLocationZIP($arCity["ID"]);
            while ($arZip = $rsZip->Fetch()) {
                $arZips[] = $arZip['ZIP'];
            }
            sort($arZips);
            $arResult[] = array(
                "ID" => $arCity["ID"],
                "NAME" => $arCity["CITY_NAME"],
                "REGION_NAME" => $arCity["REGION_NAME_LANG"],
                "COUNTRY_NAME" => $arCity["COUNTRY_NAME_LANG"],
                "ZIP" => $arZips,
            );
        }

    }
}

//echo CUtil::PhpToJSObject($arResult);
echo json_encode($arResult, JSON_UNESCAPED_UNICODE);
require_once($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/include/epilog_after.php");
die();

?>