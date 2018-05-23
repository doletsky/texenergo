<?
if(!$USER->IsAdmin()) return;

$module_id = 'aero.auth';
$MODULE_RIGHT = $APPLICATION->GetGroupRight($module_id);

$aTabs = array(
	array('DIV' => 'edit_rights', 'TAB' => "Доступ", 'TITLE' => "Доступ"),	
);

$tabControl = new CAdminTabControl('tabControl', $aTabs);

//Save options
if($REQUEST_METHOD=='POST' && strlen($Save) > 0 && check_bitrix_sessid()){	
	$Update = $Update.$Save;
	ob_start();
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
	ob_end_clean();

	LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($module_id)."&lang=".urlencode(LANGUAGE_ID)."&".$tabControl->ActiveTabParam());
}
?>

<?$tabControl->Begin();?>

<form method="POST" action="<?echo htmlspecialcharsbx($APPLICATION->GetCurPage().'?mid='.urlencode($module_id).'&lang='.LANGUAGE_ID)?>">

<?$tabControl->BeginNextTab();?>

<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");?>

<?$tabControl->Buttons();?>

<input type='submit' name='Save' value='Сохранить'>
<input type="hidden" name="Update" value="Y">

<?=bitrix_sessid_post();?>	

</form>

<?$tabControl->End();?>