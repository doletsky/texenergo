<?php
/**
 * Пользователь заполняет в веб-форме только номер телефона
 * Перед сохранением результата подставляем в поле Товары список товаров из текущей корзины
 * Корзину очищаем
 */
AddEventHandler("form", "onBeforeResultAdd", "BasketClickFillProducts");

function BasketClickFillProducts($WEB_FORM_ID, &$arFields, &$arrVALUES)
{
    if ($WEB_FORM_ID == 1) {
        CModule::IncludeModule('sale');
        $fUserID = CSaleBasket::GetBasketUserID(True);
        $fUserID = IntVal($fUserID);

        $sProducts = '';

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
            $sProducts .= '[' . $arItem['PRODUCT_ID'] . '] ' . $arItem['NAME'] . ' ' . $arItem['QUANTITY'] . 'x' . priceFormat($arItem['PRICE']) . 'руб.';
            $sProducts .= "\n";
            CSaleBasket::Delete($arItem['ID']);
        }

        $arrVALUES['form_textarea_2'] = $sProducts;
    }
}