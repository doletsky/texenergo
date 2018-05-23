<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>

<?
if($_POST["get_subscribed"]){
	CModule::IncludeModule("subscribe");

	$userId = false;
	if($USER->IsAuthorized())
		$userId = $USER->GetID();
	
	$subscription = CSubscription::GetByEmail($_POST["sf_EMAIL"])->Fetch();
	if($subscription){
		
		if($subscription['CONFIRMED'] == 'Y'){
			if($subscription['ACTIVE'] != 'Y'){				
				$subscr = new CSubscription;	
				$subscr->Update($subscription['ID'], array('ACTIVE' => 'Y'));
				
				$arReturn = array('status' => 'ok', 'msg' => 'Подписка возобновлена');
				echo json_encode($arReturn);
				exit();
			}else{
				$arReturn = array('status' => 'ok', 'msg' => 'Вы уже подписаны на рассылку');
				echo json_encode($arReturn);
				exit();	
			}
		}else{
			CSubscription::Delete($subscription['ID']);
		}
	}else if(!$USER->IsAuthorized()){
		$order = array('sort' => 'asc');
		$tmp = 'sort';
		$filter = array('EMAIL' => $_POST["sf_EMAIL"]);
		$arUser = CUser::GetList($order, $tmp, $filter)->Fetch();
		if($arUser)
			$userId = $arUser['ID'];
	}
	
	$arFields = Array(
		"USER_ID" => $userId,
		"FORMAT" => "html",
		"SEND_CONFIRM" => "Y",
		"EMAIL" => $_POST["sf_EMAIL"],
		"ACTIVE" => "Y",
		"RUB_ID" => $_POST["sf_RUB_ID"]
	);

	$subscr = new CSubscription;

	$ID = $subscr->Add($arFields);
	if($ID){
		CSubscription::Authorize($ID);
		$arReturn = array('status' => 'ok', 'msg' => 'Письмо с подтверждением подписки отправлено на e-mail');
	}else{
		$arReturn = array('status' => 'fail', 'msg' => $subscr->LAST_ERROR);
	}

	echo json_encode($arReturn);
	
	exit();
}
?>

<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php"); ?>