<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();

global $USER;


$arResult['PAYMENT_METHODS'] = array();
$rsMethods = CSalePaySystem::GetList(
	$arOrder = Array("SORT" => "ASC", "PSA_NAME" => "ASC"),
	Array("ACTIVE" => "Y"));

while ($arMethod = $rsMethods->Fetch()) {
	$arResult['PAYMENT_METHODS'][$arMethod['ID']] = $arMethod;
}	

foreach($arResult["PROFILES"] as  $key => $Profile) {

    $rsProfiles = CSaleOrderUserProps::GetList(Array('DATE_UPDATE' => 'desc'), Array(
        'ID' => $Profile['ID'],
    ));

    while ($arProfile = $rsProfiles->Fetch()) {

        $arProfile['PROPS'] = Array();

        $rsProps = CSaleOrderUserPropsValue::GetList(Array('SORT' => 'asc'), Array('USER_PROPS_ID' => $arProfile['ID']));

        while ($arProp = $rsProps->Fetch()) {

            if ($arProp['PROP_TYPE'] == 'LOCATION' && strlen($arProp['VALUE']) > 0) {
                $arLocation = CSaleLocation::GetByID($arProp['VALUE']);
                $arProp['LOCATION'] = $arLocation;
            }


            $arProfile['PROPS'][$arProp['PROP_CODE']] = $arProp;

        }

        //$arProfile['PERSON'] = CSalePersonType::GetByID($arProfile['PERSON_TYPE_ID']);

        if (!empty($arProfile['PROPS']['DELIVERY_ID']['VALUE'])) {

            $arTmp = explode(':', $arProfile['PROPS']['DELIVERY_ID']['VALUE']);
            $arDeliveryHandler = CSaleDeliveryHandler::GetBySID($arTmp[0])->Fetch();
            $arProfile['DELIVERY'] = $arDeliveryHandler['PROFILES'][$arTmp[1]]['TITLE'] . ' (' . $arDeliveryHandler['NAME'] . ')';
            $arProfile['DELIVERY_ID'] = $arProfile['PROPS']['DELIVERY_ID']['VALUE'];

        }

        if (!empty($arProfile['PROPS']['PAYMENT_ID']['VALUE'])) {

            $arPayment = $arResult['PAYMENT_METHODS'][$arProfile['PROPS']['PAYMENT_ID']['VALUE']];
            $arProfile['PAYMENT'] = $arPayment['NAME'];

        }

        // пропускаем профили без адреса доставки и без службы доставки
		
        if (empty($arProfile['PROPS']['LOCATION_DELIVERY']['VALUE'])){
			unset($arResult['PROFILES'][$key]);
			continue;
		}
		
        //if (!$arProfile['DELIVERY']) continue;

        $Profile['USER'] = CUser::GetByID($arProfile["USER_ID"])->Fetch();
        $Profile['USER']["FULL_NAME"] = $USER->GetFullName();
        
		$payerType = IntVal($Profile['USER']['UF_PAYER_TYPE']);
		if($payerType <= 0){
			$payerType = SALE_PERSON_FIZ;
		}
		
		if($arProfile['PERSON_TYPE_ID'] != $payerType)
			unset($arResult['PROFILES'][$key]);
			
		$arResult['PROFILES'][$key] = array_merge($Profile, $arProfile);
    }

}
?>