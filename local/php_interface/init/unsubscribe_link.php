<?
AddEventHandler("subscribe", "BeforePostingSendMail", "addUnsubscribeLink");

function AddUnsubscribeLink($arFields){
	$rsSub = CSubscription::GetByEmail($arFields["EMAIL"]);
	$arSub = $rsSub->Fetch();
	
		
	$arFields["BODY"] = str_replace("#MAIL_ID#", $arSub["ID"], $arFields["BODY"]);
	
	$hash = getUnsubscribeMailHash($arFields["EMAIL"]);
	$arFields["BODY"] = str_replace("#MAIL_MD5#", $hash, $arFields["BODY"]);

	return $arFields;
}

function getUnsubscribeMailHash($email){
	return md5(md5($email).'tech');
}
?>