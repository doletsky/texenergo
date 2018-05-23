<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(count($arResult["ITEMS"]) > 0) {
    foreach($arResult["ITEMS"] as $arItem){
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        echo "<p>";
            echo "<a href='/catalog/?SECTION_ID=".$arItem["IBLOCK_SECTION_ID"]."&ELEMENT_ID=".$arItem["ID"]."'>".$arItem["NAME"]."</a>";
        echo "</p>";
    }
}
?>
