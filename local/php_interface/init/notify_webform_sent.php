<?
/**
 * Оповещение на почту о заполнении веб-форм обратного звонка, 
 * заказа в один клик в корзине, заказа в один клик в карточке товара, отклика на вакансию 
 */
AddEventHandler('form', 'onAfterResultAdd', 'notifyWebformSent');
function notifyWebformSent($formID, $rsID){
	$emailFields = array(
		'RESULT_ID' => $rsID,
		'FORM_ID' => $formID			
	);
	
	switch($formID){
		case WF_QUICK_BASKET:
			$eventName = 'WF_ONECLICK_BUY';
			CForm::GetResultAnswerArray($formID, $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $rsID));
			
			$emailFields['PRODUCTS'] = $arrAnswersVarname[$rsID]['basket_items'][0]['USER_TEXT'];
			$emailFields['PHONE'] = $arrAnswersVarname[$rsID]['basket_phone'][0]['USER_TEXT'];		
			$emailFields['EMAIL'] = $arrAnswersVarname[$rsID]['basket_email'][0]['USER_TEXT'];	
			$emailFields['NAME'] = $arrAnswersVarname[$rsID]['basket_fio'][0]['USER_TEXT'];
			$emailFields['COMMENT'] = '';
			
			$ev = new CEvent;
			$ev->Send($eventName, SITE_ID, $emailFields);
			break;
		case WF_CALLBACK:
			$eventName = 'WF_CALLBACK';
			CForm::GetResultAnswerArray($formID, $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $rsID));
			
			$emailFields['PHONE'] = $arrAnswersVarname[$rsID]['callback_phone'][0]['USER_TEXT'];
            $emailFields['EMAIL'] = $arrAnswersVarname[$rsID]['callback_email'][0]['USER_TEXT'];
			$emailFields['COMMENT'] = $arrAnswersVarname[$rsID]['callback_comment'][0]['USER_TEXT'];			
			$emailFields['NAME'] = $arrAnswersVarname[$rsID]['callback_fio'][0]['USER_TEXT'];

			$ev = new CEvent;
			$ev->Send($eventName, SITE_ID, $emailFields);
			break;
		case WF_QUICK_PRODUCT:
			$eventName = 'WF_ONECLICK_BUY';
			CForm::GetResultAnswerArray($formID, $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $rsID));
			
			$emailFields['PRODUCTS'] = $arrAnswersVarname[$rsID]['onelick_item'][0]['USER_TEXT'];
			$emailFields['PHONE'] = $arrAnswersVarname[$rsID]['onelick_phone'][0]['USER_TEXT'];		
			$emailFields['COMMENT'] = $arrAnswersVarname[$rsID]['onelick_comment'][0]['USER_TEXT'];	
			$emailFields['EMAIL'] = '';
			$emailFields['NAME'] = $arrAnswersVarname[$rsID]['onelick_fio'][0]['USER_TEXT'];
			
			$ev = new CEvent;
			$ev->Send($eventName, SITE_ID, $emailFields);
			break;
		case WF_VACANCY_CB:
			$eventName = 'WF_VACANCY_CB';
			CForm::GetResultAnswerArray($formID, $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $rsID));			
			
			$emailFields['PHONE'] = $arrAnswersVarname[$rsID]['response_phone'][0]['USER_TEXT'];		
			$emailFields['COMMENT'] = $arrAnswersVarname[$rsID]['response_text'][0]['USER_TEXT'];	
			$emailFields['EMAIL'] = $arrAnswersVarname[$rsID]['response_email'][0]['USER_TEXT'];
			$emailFields['NAME'] = $arrAnswersVarname[$rsID]['response_fio'][0]['USER_TEXT'];
			$emailFields['VACANCY'] = $arrAnswersVarname[$rsID]['response_vacancy'][0]['USER_TEXT'];
			$emailFields['CITY'] = $arrAnswersVarname[$rsID]['response_city'][0]['USER_TEXT'];
			$emailFields['FILE'] = '';
			
			$fileId = $arrAnswersVarname[$rsID]['response_file'][0]['USER_FILE_ID'];
			if(!empty($fileId)){
				$filePath = CFile::GetPath($fileId);
				$emailFields['FILE'] = "К отклику приложен <a href='http://".SITE_SERVER_NAME.$filePath."'>файл</a>";
			}
			
			$ev = new CEvent;
			$ev->SendImmediate($eventName, SITE_ID, $emailFields);
			break;
		case WF_SETTLEMENT_CB:
			$eventName = 'WF_SETTLEMENT_CB';
			CForm::GetResultAnswerArray($formID, $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $rsID));			
			
			$emailFields['PHONE'] = $arrAnswersVarname[$rsID]['response_phone'][0]['USER_TEXT'];		
			$emailFields['COMMENT'] = $arrAnswersVarname[$rsID]['response_text'][0]['USER_TEXT'];	
			$emailFields['EMAIL'] = $arrAnswersVarname[$rsID]['response_email'][0]['USER_TEXT'];
			$emailFields['NAME'] = $arrAnswersVarname[$rsID]['response_fio'][0]['USER_TEXT'];
			$emailFields['FILE'] = '';
			
			$fileId = $arrAnswersVarname[$rsID]['response_file'][0]['USER_FILE_ID'];
			if(!empty($fileId)){
				$filePath = CFile::GetPath($fileId);
				$emailFields['FILE'] = "К отклику приложен <a href='http://".SITE_SERVER_NAME.$filePath."'>файл</a>";
			}
			
			$ev = new CEvent;
			$ev->SendImmediate($eventName, SITE_ID, $emailFields);
			break;

	}	
}
?>