<?
$arResult['SUCCESS'] = false;

if(!empty($arParams['MAIL_ID']) && !empty($arParams['MAIL_MD5'])){
	
	CModule::IncludeModule('subscribe');
	
	$rsSub = CSubscription::GetByID($arParams["MAIL_ID"]);
	if ($arSub = $rsSub->Fetch()){
		if (getUnsubscribeMailHash($arSub["EMAIL"]) == $arParams["MAIL_MD5"]){			
			$arResult["EMAIL"] = $arSub["EMAIL"];
			$subscr = new CSubscription();
			$subscr->Delete($arParams["MAIL_ID"]);
			$arResult['SUCCESS'] = true;
		}
	}
}

$this->IncludeComponentTemplate();
?>