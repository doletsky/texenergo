<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$content = "";
foreach ($arParams["DATA"] as $tabId => $arTab){
    $id = $arResult["ID"].$tabId;
    if(isset($arTab["NAME"]) && isset($arTab["CONTENT"])){
        $content .= $arTab["CONTENT"];
    }
}
echo $content;
?>


