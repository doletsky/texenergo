<?
AddEventHandler('iblock', 'OnIBlockPropertyBuildList', array('CReclamationProperty', 'OnIBlockPropertyBuildList'));

class CReclamationProperty{    

    function OnIBlockPropertyBuildList(){
        return Array(
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'RECLAMATION',
            'DESCRIPTION' => 'Привязка к товарам в рекламациях',
            'GetPropertyFieldHtml' => Array('CReclamationProperty', 'GetPropertyFieldHtmlBasket'),
            'GetPropertyFieldHtmlMulty' => Array('CReclamationProperty', 'GetPropertyFieldHtmlBasketMulty'),
            'ConvertToDB' => Array('CReclamationProperty', 'ConvertToDBBasket'),
            'ConvertFromDB' => Array('CReclamationProperty', 'ConvertFromDBBasket'),
            'GetLength' => Array('CReclamationProperty', 'GetLengthBasket'),
        );
    }


    function GetPropertyFieldHtmlBasket($arProperty, $value, $strHTMLControlName){
        return '<input type="text" size="20" name="' . $strHTMLControlName["VALUE"] . '" value="' . htmlspecialchars($value["VALUE"]) . '">';
    }

    function GetPropertyFieldHtmlBasketMulty($arProperty, $value, $strHTMLControlName){        
        CModule::IncludeModule('iblock');
        $sReturn = '<table>';
        $sReturn .= '<tr>
        <th align="left">№ Реализации</th>		
		<th align="left">ID</th>
        <th align="left">Название</th>        
        <th align="left">Кол-во</th>
		<th align="left">Серийные номера</th>
        </tr>';
        $count = 30;
        foreach ($value as $i => $arValue) {
            $count--;
            $sReturn .= '<tr>
            <td><input type="text" style="width:100px" name="' . $strHTMLControlName["VALUE"] . '[' . ($i) . '][VALUE][REALIZ_NUM]" value="' . htmlspecialchars($arValue['VALUE']["REALIZ_NUM"]) . '"></td>           
			<td><input type="text" style="width:100px" name="' . $strHTMLControlName["VALUE"] . '[' . ($i) . '][VALUE][PROD_ID]" value="' . htmlspecialchars($arValue['VALUE']["PROD_ID"]) . '"></td>
            <td><input type="text" style="width:350px" name="' . $strHTMLControlName["VALUE"] . '[' . ($i) . '][VALUE][NAME]" value="' . htmlspecialchars($arValue['VALUE']["NAME"]) . '"></td>
            <td><input type="text" style="width:50px" name="' . $strHTMLControlName["VALUE"] . '[' . ($i) . '][VALUE][QUANTITY]" value="' . htmlspecialchars($arValue['VALUE']["QUANTITY"]) . '"></td>
            <td><input type="text" style="width:350px" name="' . $strHTMLControlName["VALUE"] . '[' . ($i) . '][VALUE][S_NUM]" value="' . htmlspecialchars($arValue['VALUE']["S_NUM"]) . '"></td>
            </tr>';
        }
        for ($i = 0; $i < $count; $i++) {
            $sReturn .= '<tr>
            <td><input type="text" style="width:100px" name="' . $strHTMLControlName["VALUE"] . '[n' . ($i) . '][VALUE][REALIZ_NUM]" value=""></td>            
			<td><input type="text" style="width:100px" name="' . $strHTMLControlName["VALUE"] . '[n' . ($i) . '][VALUE][PROD_ID]" value=""></td>
            <td><input type="text" style="width:350px" name="' . $strHTMLControlName["VALUE"] . '[n' . ($i) . '][VALUE][NAME]" value=""></td>
            <td><input type="text" style="width:50px" name="' . $strHTMLControlName["VALUE"] . '[n' . ($i) . '][VALUE][QUANTITY]" value=""></td>
            <td><input type="text" style="width:350px" name="' . $strHTMLControlName["VALUE"] . '[n' . ($i) . '][VALUE][S_NUM]" value=""></td>
            </tr>';
        }
        $sReturn .= '</table>';
        return $sReturn;
    }

    function ConvertToDBBasket($arProperty, $value){
        
		if (!empty($value['VALUE']) && strlen($value['VALUE']['NAME']) > 0) {		
            $value["VALUE"] = serialize($value['VALUE']);
        } else {
            $value["VALUE"] = null;
        }
        return $value;
    }

    function ConvertFromDBBasket($arProperty, $value){        
        if (strlen($value["VALUE"]) > 0) {
            $value["VALUE"] = unserialize($value['VALUE']);
        }
        return $value;
    }

    function GetLengthBasket($arProperty, $value){
        return strlen(trim($value["VALUE"]['NAME'], "\n\r\t "));
    }

} 