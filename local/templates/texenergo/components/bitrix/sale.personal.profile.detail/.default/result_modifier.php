<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();

$arResult["PROFILE"] = array();

foreach($arResult["ORDER_PROPS"] as  $key => $orderProps) {

    foreach($orderProps["PROPS"] as  $key => $Props) {

        $arResult["PROFILE"][$Props['CODE']] = $arResult['ORDER_PROPS_VALUES']['ORDER_PROP_'.$Props[ID]];
        $arResult["CODE"][$Props['CODE']] = 'ORDER_PROP_'.$Props[ID];

    }

}
?>