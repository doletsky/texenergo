<?php
AddEventHandler("search", "BeforeIndex", Array("eventHanders",
                                               "BeforeIndexHandler"));

class eventHanders {

    function BeforeIndexHandler($arFields) {

        if($arFields["MODULE_ID"] == "iblock" && $arFields["PARAM2"] == 18
           && substr($arFields["ITEM_ID"], 0, 1) != "S"
        ) {
            $arFields["PARAMS"]["iblock_section"] = array();
            //Получаем разделы привязки элемента (их может быть несколько)
            $rsSections = CIBlockElement::GetElementGroups($arFields["ITEM_ID"], true);
            while($arSection = $rsSections->Fetch()) {
                $nav = CIBlockSection::GetNavChain(18, $arSection["ID"]);
                while($ar = $nav->Fetch()) {
                    //Сохраняем в поисковый индекс
                    $arFields["PARAMS"]["iblock_section"][] = $ar['ID'];
                }
            }
        }

        //Всегда возвращаем arFields
        return $arFields;
    }
}