<?php

$fUserID = CSaleBasket::GetBasketUserID(True);
$fUserID = IntVal($fUserID);

$arResult['BASKET'] = Array();

$rsBasket = CSaleBasket::GetList(
    Array('name' => 'asc'),
    Array(
        'ORDER_ID' => 'NULL',
        'FUSER_ID' => $fUserID,
        'LID' => SITE_ID,
        'CAN_BUY' => 'Y',
        'DELAY' => 'N',
    )
);

while ($arItem = $rsBasket->GetNext()) {
    $arResult['BASKET'][] = $arItem;
}