<?php

class CAeroIBlockProps
{


    function OnIBlockPropertyBuildList_Order()
    {
        return Array(
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'ORDER',
            'DESCRIPTION' => 'Привязка к заказу',
            'GetPropertyFieldHtml' => Array('CAeroIblockProps', 'GetPropertyFieldHtmlOrder'),
        );
    }

    function OnIBlockPropertyBuildList_Basket()
    {
        return Array(
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'BASKET',
            'DESCRIPTION' => 'Привязка к товарам с кол-вом',
            'GetPropertyFieldHtml' => Array('CAeroIblockProps', 'GetPropertyFieldHtmlBasket'),
            'GetPropertyFieldHtmlMulty' => Array('CAeroIblockProps', 'GetPropertyFieldHtmlBasketMulty'),
            'ConvertToDB' => Array('CAeroIblockProps', 'ConvertToDBBasket'),
            'ConvertFromDB' => Array('CAeroIblockProps', 'ConvertFromDBBasket'),
            'GetLength' => Array('CAeroIblockProps', 'GetLengthBasket'),
        );
    }


    function GetPropertyFieldHtmlOrder($arProperty, $value, $strHTMLControlName)
    {
        return '<input type="text" size="20" name="' . $strHTMLControlName["VALUE"] . '" value="' . htmlspecialchars($value["VALUE"]) . '">';
    }

    function GetPropertyFieldHtmlBasket($arProperty, $value, $strHTMLControlName)
    {
        return '<input type="text" size="20" name="' . $strHTMLControlName["VALUE"] . '" value="' . htmlspecialchars($value["VALUE"]) . '">';
    }

    function GetPropertyFieldHtmlBasketMulty($arProperty, $value, $strHTMLControlName)
    {
        //return '<pre>' . print_r($value, true) . '</pre>';
        CModule::IncludeModule('iblock');
        $sReturn = '<table>';
        $sReturn .= '<tr>
        <th align="left">ID</th>
        <th align="left">Название</th>
        <th align="left">Код</th>
        <th align="left">Цена</th>
        <th align="left">Кол-во</th>
        <th align="left">Сумма</th>
        </tr>';
        $count = 20;

        foreach ($value as $i => $arValue) {
            $count--;
            $sReturn .= '<tr>
            <td><input type="text" style="width:50px" name="' . $strHTMLControlName["VALUE"] . '[' . ($i) . '][VALUE][ID]" value="' . htmlspecialchars($arValue['VALUE']["ID"]) . '"></td>
            <td><input type="text" style="width:350px" name="' . $strHTMLControlName["VALUE"] . '[' . ($i) . '][VALUE][NAME]" value="' . htmlspecialchars($arValue['VALUE']["NAME"]) . '"></td>
            <td><input type="text" style="width:50px" name="' . $strHTMLControlName["VALUE"] . '[' . ($i) . '][VALUE][CODE]" value="' . htmlspecialchars($arValue['VALUE']["CODE"]) . '"></td>
            <td><input type="text" style="width:50px" name="' . $strHTMLControlName["VALUE"] . '[' . ($i) . '][VALUE][PRICE]" value="' . htmlspecialchars($arValue['VALUE']["PRICE"]) . '"></td>
            <td><input type="number" style="width:50px" min="1" class="adm-input" size="3" name="' . $strHTMLControlName["VALUE"] . '[' . ($i) . '][VALUE][QUANTITY]" value="' . htmlspecialchars($arValue['VALUE']["QUANTITY"]) . '"></td>
            <td><input type="text" style="width:50px" name="' . $strHTMLControlName["VALUE"] . '[' . ($i) . '][VALUE][SUMM]" value="' . htmlspecialchars($arValue['VALUE']["PRICE"] * $arValue['VALUE']["QUANTITY"]) . '"></td>
            </tr>';
        }
        for ($i = 0; $i < $count; $i++) {
            $sReturn .= '<tr>
            <td><input type="text" style="width:50px" name="' . $strHTMLControlName["VALUE"] . '[n' . ($i) . '][VALUE][ID]" value=""></td>
            <td><input type="text" style="width:350px" name="' . $strHTMLControlName["VALUE"] . '[n' . ($i) . '][VALUE][NAME]" value=""></td>
            <td><input type="text" style="width:50px" name="' . $strHTMLControlName["VALUE"] . '[n' . ($i) . '][VALUE][CODE]" value=""></td>
            <td><input type="text" style="width:50px" name="' . $strHTMLControlName["VALUE"] . '[n' . ($i) . '][VALUE][PRICE]" value=""></td>
            <td><input type="number" style="width:50px" min="1" class="adm-input" size="3" name="' . $strHTMLControlName["VALUE"] . '[n' . ($i) . '][VALUE][QUANTITY]" value=""></td>
            <td><input type="text" style="width:50px" name="' . $strHTMLControlName["VALUE"] . '[n' . ($i) . '][VALUE][SUMM]" value=""></td>
            </tr>';
        }
        $sReturn .= '</table>';
        return $sReturn;
    }

    function ConvertToDBBasket($arProperty, $value)
    {
        if (!empty($value['VALUE']) && strlen($value['VALUE']['NAME']) > 0) {
            $value["VALUE"] = serialize($value['VALUE']);
        } else {
            $value["VALUE"] = null;
        }
        return $value;
    }

    function ConvertFromDBBasket($arProperty, $value)
    {
        //echo '<pre>'.print_r($value, true).'</pre>';die();
        if (strlen($value["VALUE"]) > 0) {
            $value["VALUE"] = unserialize($value['VALUE']);
        }
        return $value;
    }

    function GetLengthBasket($arProperty, $value)
    {
        return strlen(trim($value["VALUE"]['NAME'], "\n\r\t "));
    }

} 