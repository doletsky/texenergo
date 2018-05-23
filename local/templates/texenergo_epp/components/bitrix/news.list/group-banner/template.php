<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(count($arResult["ITEMS"]) > 0) {
    echo "<ul>";
    $count = 0;
    foreach($arResult["ITEMS"] as $arItem){
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        if($count < 3) {
            echo "<li>";
                if($arItem["PREVIEW_PICTURE"] != "") {
                    echo "<img src='".$arItem["PREVIEW_PICTURE"]["SRC"]."' alt='".$arItem["NAME"]."'>";
                } else {
                    echo $arItem["NAME"];
                }
            echo "</li>";
        }
        $count++;
    }
    echo "</ul>";
    $modCount = count($arResult["ITEMS"]) - 3;
    if($modCount > 0) { echo "<a class='block-bar-other' href='#'>Еще ".$modCount." ".GetMessage("GOODS_WORD_".$modCount)."</a>"; }
}
?>
