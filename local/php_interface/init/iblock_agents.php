<?
AddEventHandler("iblock", "OnAfterIBlockElementAdd", "OnBeforeAgentsUpdate");
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "OnBeforeAgentsUpdate");

function OnBeforeAgentsUpdate(&$arFields) {

    if ($arFields['IBLOCK_ID'] == IBLOCK_ID_AGENTS) {
        CIBlockElement::SetPropertyValuesEx($arFields['ID'], $arFields['IBLOCK_ID'], ['USERS' => "/bitrix/admin/user_admin.php?lang=ru&set_filter=Y&adm_filter_applied=0&find_type=login&find_UF_COMPANY_ID={$arFields['ID']}"]);
    }
}

//AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("MyClass", "OnBeforeIBlockElementUpdateHandler"));
//
//class MyClass
//{
//// создаем обработчик события "OnBeforeIBlockElementUpdate"
//    function OnBeforeIBlockElementUpdateHandler(&$arFields)
//    {
//        if(strlen($arFields["CODE"])<=0)
//        {
//            global $APPLICATION;
//            $APPLICATION->throwException("Введите символьный код. (ID:".$arFields["ID"].")");
//            return false;
//        }
//    }
//}
?>
