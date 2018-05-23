<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<?
/**
 * Custom		 
 */
CModule::IncludeModule('iblock');			 
include(GetLangFileName(dirname(__FILE__)."/", "/payment.php"));
$inv_id = IntVal($_REQUEST["InvId"]);

if($inv_id > 0)
{
	$bCorrectPayment = True;

	$out_summ = trim($_REQUEST["OutSum"]);
	$crc = trim($_REQUEST["SignatureValue"]);
		
	$dbInvoice = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_INVOICES, 'ID' => $inv_id), false, false, array('ID', 'IBLOCK_ID', 'PROPERTY_ORDER_ID'));
	if ($arInvoice = $dbInvoice->Fetch()){
		$orderId = $arInvoice['PROPERTY_ORDER_ID_VALUE'];
		$arOrder = CSaleOrder::GetByID($orderId);
		if(!$arOrder){
			$bCorrectPayment = False;
		}
	}else{
		$bCorrectPayment = False;
	}

	if ($bCorrectPayment)
		CSalePaySystemAction::InitParamArrays($arOrder);
		
	$mrh_pass2 =  CSalePaySystemAction::GetParamValue("ShopPassword2");
	$strCheck = md5($out_summ.":".$inv_id.":".$mrh_pass2);

	if ($bCorrectPayment && ToUpper($crc) != ToUpper($strCheck))
		$bCorrectPayment = False;
		
	
	if($bCorrectPayment){			
		$num = str_pad($arInvoice['ID'], 7, " ", STR_PAD_LEFT);
		$el = new CIBlockElement;
		$paymentId = $el->Add(
			array(
				'IBLOCK_ID' => IBLOCK_ID_PAYMENTS,
				'NAME' 		=> 'Платежное поручение №'.$num,
				'PROPERTY_VALUES' 	=> array(
					'INVOICE_ID' 	=> $inv_id,
					'DATE' 			=> date('d.m.Y'),
					'AMOUNT' 		=> $out_summ,
					'COMMENT' 		=> array("VALUE" => array("TEXT" => "ROBOKASSA")),
				)
			)
		);
		
		$APPLICATION->RestartBuffer();
		if($paymentId)
			echo "OK".$arInvoice["ID"];
		
		die();
	}
}
?>