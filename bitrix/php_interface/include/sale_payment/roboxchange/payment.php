<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<?
include(GetLangFileName(dirname(__FILE__)."/", "/payment.php"));

/**
 * Custom
 * Параметры беруться не из глобального массива $GLOBALS["SALE_INPUT_PARAMS"]
 */

$GLOBALS["SALE_CORRESPONDENCE"] = CSalePaySystemAction::UnSerializeParams($arOrder["PAY_SYSTEM"]["ACTION_PARAMS"]);
foreach($GLOBALS["SALE_CORRESPONDENCE"] as $key => $val){
	$GLOBALS["SALE_CORRESPONDENCE"][$key]["~VALUE"] = $val["VALUE"];
	$GLOBALS["SALE_CORRESPONDENCE"][$key]["VALUE"] = htmlspecialcharsEx($val["VALUE"]);
}
		
$mrh_login = $GLOBALS["SALE_CORRESPONDENCE"]["ShopLogin"]["VALUE"];
$mrh_pass1 =  $GLOBALS["SALE_CORRESPONDENCE"]["ShopPassword"]["VALUE"];
$inv_id = $arInvoice["ID"];
$inv_desc =  CSalePaySystemAction::GetParamValue("OrderDescr");
$user_mail = CSalePaySystemAction::GetParamValue("EMAIL_USER");
$out_summ = number_format(floatval($PAY_SUM), 2, ".", "");
$isTest = trim(CSalePaySystemAction::GetParamValue("IS_TEST"));
$crc = md5($mrh_login.":".$out_summ.":".$inv_id.":".$mrh_pass1);
$paymentType = trim(CSalePaySystemAction::GetParamValue("PAYMENT_VALUE"));
?>

<?if(strlen($isTest) > 0):?>

<form class="pay_form" action="http://test.robokassa.ru/Index.aspx" method="post" target="_blank">
	
<?else:?>

<form class="pay_form" action="https://merchant.roboxchange.com/Index.aspx" method="post" target="_blank">
	
<?endif;?>


	<input type="hidden" name="FinalStep" value="1">
	<input type="hidden" name="MrchLogin" value="<?=$mrh_login?>">
	<input type="hidden" name="OutSum" value="<?=$out_summ?>">
	<input type="hidden" name="InvId" value="<?=$inv_id?>">
	<input type="hidden" name="Desc" value="<?=$inv_desc?>">
	<input type="hidden" name="SignatureValue" value="<?=$crc?>">
	<input type="hidden" name="Email" value="<?=$user_mail?>">

	<?if (strlen($paymentType) > 0 && $paymentType != "0"):?>
		
		<input type="hidden" name="IncCurrLabel" value="<?=$paymentType?>">
		
	<?endif;?>

	<input type="submit" class="button" name="Submit" value="<?=GetMessage("PYM_BUTTON")?>">

</form>