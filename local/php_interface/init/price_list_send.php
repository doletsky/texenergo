<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');?>

<?
$PRICE_PATH = $_SERVER['DOCUMENT_ROOT'] . '/price_list/files/pricete.xls';
$POSTING_ID = 1;

if(!file_exists($PRICE_PATH))
	return;
	

CModule::IncludeModule('subscribe');


$cPosting = new CPosting;
$arPosting = CPosting::GetByID($POSTING_ID)->Fetch();
if($arPosting){	
	CPosting::DeleteFile($POSTING_ID);
	$arFile = CFile::MakeFileArray($PRICE_PATH);	
	if($arFile){
		CPosting::SaveFile($POSTING_ID, $arFile);
	}
	
	$cPosting->ChangeStatus($POSTING_ID, "D");
	$cPosting->ChangeStatus($POSTING_ID, "P");
	$cPosting->SendMessage($POSTING_ID);
}
?>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');?>