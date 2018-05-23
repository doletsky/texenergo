<?
namespace aero;
\CModule::IncludeModule('iblock');
\CModule::IncludeModule('sale');

class CDocGen{

	static $templateFolder;
	static $tmpDir;

	function init(){
		CDocGen::$templateFolder = $_SERVER['DOCUMENT_ROOT'] . '/include/reclamation_blanks';
		CDocGen::$tmpDir = $_SERVER['DOCUMENT_ROOT'] . '/upload/reclamations';
	}

	function fillReclamation($recId){
		CDocGen::init();
		$arReclamation = CDocGen::getReclamation($recId);
		if(!$arReclamation)
			throw new \Exception('Рекламация не найдена');

		$recType = $arReclamation['PROPERTIES']['TYPE']['VALUE_XML_ID'];
		switch($recType){
			case 'brak': $templateName = 'brak.docx'; break;
			case 'vozvrat': $templateName = 'vozvrat.docx'; break;
			case 'nedostacha': $templateName = 'peresort_nedostacha.docx'; break;
			case 'peresort': $templateName = 'peresort_nedostacha.docx'; break;
			default:
				throw new \Exception('Неизвестный тип рекламации');
			break;
		}

		$tmpDir = date('d_m_Y_H_i_s') . rand(10, 20);
		$dstDir = CDocGen::$tmpDir . '/tmp/' . $tmpDir;
		$template = CDocGen::$templateFolder . '/' . $templateName;

		CDir::checkDir($dstDir);
		if(CZip::extract($template, $dstDir)){
			$docPath = $dstDir . '/word/document.xml';
			CDocGen::replaceVars($docPath, $arReclamation, $recType);
			$fileName = 'reclamation_' . date('d_m_Y_H_i_s') . '.docx';
			if(!CZip::addToArchive($dstDir, CDocGen::$tmpDir . '/' . $fileName)){
				CDir::deleteDir($dstDir);
				throw new \Exception('Ошибка запаковки архива');
			}
		}else{
			CDir::deleteDir($dstDir);
			throw new \Exception('Ошибка распаковки архива');
		}

		CDir::deleteDir($dstDir);

		/* global $APPLICATION;
		$APPLICATION->RestartBuffer();
		header('Content-Type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary");
		header("Content-disposition: attachment; filename=\"" . $fileName . "\"");
		readfile(CDocGen::$tmpDir . '/' . $fileName);
		CDir::deleteDir(CDocGen::$tmpDir);
		die; */

		return CDocGen::$tmpDir . '/' . $fileName;
	}

	function getReclamation($recId){
		$arReclamation = false;

		$dbElems = \CIBlockElement::GetList(array(), array('ID' => $recId, 'IBLOCK_ID' => IBLOCK_ID_RECLAMATIONS), false, false, array('ID', 'IBLOCK_ID', 'NAME', 'DATE_CREATE'));
		if($elem = $dbElems->GetNextElement()){
			$arReclamation = $elem->GetFields();
			$arReclamation['PROPERTIES'] = $elem->GetProperties();

			foreach($arReclamation['PROPERTIES']['PRODUCTS']['VALUE'] as $key => $product){
				$rNum = $product['REALIZ_NUM'];
				$pId = $product['PROD_ID'];
				if(!empty($rNum) && $rNum != '-' && !empty($pId) && $pId != '-'){
					$rQuantity = CDocGen::getQuantityByRealization($rNum, $pId);
					$arReclamation['PROPERTIES']['PRODUCTS']['VALUE'][$key]['R_QUANTITY'] = intval($rQuantity);
				}
			}

			$arReclamation['USER'] = \CUser::GetByID($arReclamation['PROPERTIES']['USER_ID']['VALUE'])->Fetch();

			if(!empty($arReclamation['USER']['UF_COMPANY_ID'])){
				$arReclamation['COMPANY'] = CDocGen::getCompanyInfo($arReclamation['USER']['UF_COMPANY_ID']);
			}
		}

		return $arReclamation;
	}

	function getCompanyInfo($id){
		$company = array();
		$dbElems = \CIBlockElement::GetList(array(), array('ID' => $id), false, false);
		if($elem = $dbElems->GetNextElement()){
			$company = $elem->GetFields();
			$props = $elem->GetProperties();
			$company['PROPERTIES'] = $props;

			$strAddress = '';
			if($company['PROPERTIES']['ACTUAL_EQUALS_LEGAL']['VALUE_ENUM'] == 'Y')
				$prefix = 'LEGAL';
			else
				$prefix = 'ACTUAL';

			$strAddress .= $props['ZIP_'.$prefix]['VALUE'];

			$arLocation = \CSaleLocation::GetList(array(), array('ID' => $props['LOCATION_'.$prefix]['VALUE'], 'LID' => 'ru'))->Fetch();
			if($arLocation){
				$strAddress .= ' '.$arLocation['COUNTRY_NAME'].', '.$arLocation['CITY_NAME'];
			}

			$strAddress .= ', ул. '.$props['STREET_'.$prefix]['VALUE'];
			$strAddress .= ' '.$props['HOUSE_'.$prefix]['VALUE'];
			if(!empty($props['HOUSING_'.$prefix]['VALUE']))
				$strAddress .= ' корп.'.$props['HOUSING_'.$prefix]['VALUE'];

			if(!empty($props['OFFICE_'.$prefix]['VALUE']))
				$strAddress .= ', оф.'.$props['OFFICE_'.$prefix]['VALUE'];

			$company['ADDRESS'] = $strAddress;
		}
		return $company;
	}

	function getQuantityByRealization($rId, $productId){
		$dbElems = \CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_SALES, 'XML_ID' => $rId));
		if($elem = $dbElems->GetNextElement()){
			$props = $elem->GetProperties();

			foreach($props['BASKET_ITEMS']['VALUE'] as $item){
				if($item['ID'] == $productId){
					return $item['QUANTITY'];
				}
			}

		}
		return 0;
	}

	function replaceVars($docPath, $arReclamation, $type){
		switch($type){
			case 'brak':
			case 'vozvrat':
				CDocGen::replaceBrakVozvrat($docPath, $arReclamation);
			break;

			case 'nedostacha':
			case 'peresort':
				CDocGen::replaceNedostachaPeresort($docPath, $arReclamation, $type);
			break;

			default:
				throw new \Exception('Неизвестный тип рекламации');
			break;
		}

	}

	function replaceNedostachaPeresort($docPath, $arReclamation, $type){
		$date = explode(' ', $arReclamation['DATE_CREATE']);
		$dateParts = explode('.', $date[0]);

		$arReplace = array(
			'#R_NUM#' => $arReclamation['ID'],
			'#R_DATE#' => $date[0],
			'#dd#' => $dateParts[0],
			'#mm#' => $dateParts[1],
			'#yy#' => $dateParts[2],
			'#REALIZ_INFO#' => '',
			'#ORGANIZATION#' => empty($arReclamation['COMPANY']) ? '' : $arReclamation['COMPANY']['NAME'],
			'#CONTACT_INFO#' => empty($arReclamation['COMPANY']) ? '' : $arReclamation['COMPANY']['ADDRESS'],
			'#CONTACT_PERSON#' => $arReclamation['USER']['LAST_NAME'].' '.$arReclamation['USER']['NAME'].', '.$arReclamation['USER']['PERSONAL_PHONE'].', '.$arReclamation['USER']['EMAIL'],
			'#DESCRIPTION#' => $arReclamation['PROPERTIES']['DESCRIPTION']['VALUE']['TEXT'],
			'#CASE#' => $arReclamation['PROPERTIES']['CASE']['VALUE']['TEXT'],
		);

		$i = 1;
		$rNum = '';
		foreach($arReclamation['PROPERTIES']['PRODUCTS']['VALUE'] as $product){
			if(!empty($product['REALIZ_NUM']))
				$rNum = $product['REALIZ_NUM'];

			$arReplace['#NAME'.$i.'#'] = $product['NAME'];
			$arReplace['#IZM'.$i.'#'] = $product['шт.'];

			if($type == 'nedostacha'){
				$arReplace['#KOL_FAKT'.$i.'#'] = $product['R_QUANTITY'] - $product['QUANTITY'];
				$arReplace['#KOL_NED'.$i.'#'] = $product['QUANTITY'];
				$arReplace['#KOL_IZL'.$i.'#'] = '';
			}else if($type == 'peresort'){
				$arReplace['#KOL_FAKT'.$i.'#'] = $product['R_QUANTITY'] + $product['QUANTITY'];
				$arReplace['#KOL_NED'.$i.'#'] = '';
				$arReplace['#KOL_IZL'.$i.'#'] = $product['QUANTITY'];
			}

			$arReplace['#KOL_DOC'.$i.'#'] = $product['R_QUANTITY'];

			$i++;
		}

		if(!empty($rNum))
			$arReplace['#REALIZ_INFO#'] = $rNum.', '.$date[0];
		else
			$arReplace['#REALIZ_INFO#'] = $date[0];

		for($j = $i; $j <= 3; $j++){
			$arReplace['#NAME'.$j.'#'] = '';
			$arReplace['#IZM'.$j.'#'] = '';
			$arReplace['#KOL_FAKT'.$j.'#'] = '';
			$arReplace['#KOL_NED'.$j.'#'] = '';
			$arReplace['#KOL_IZL'.$j.'#'] = '';
			$arReplace['#KOL_DOC'.$j.'#'] = '';
		}

		//echo "<pre>";print_r(array_keys($arReplace));echo "</pre>";
		//echo "<pre>";print_r(array_values($arReplace));echo "</pre>";die;

		$content = file_get_contents($docPath);
		$content = str_replace(array_keys($arReplace), array_values($arReplace), $content);

		file_put_contents($docPath, $content);
	}

	function replaceBrakVozvrat($docPath, $arReclamation){
		$date = explode(' ', $arReclamation['DATE_CREATE']);
		$dateParts = explode('.', $date[0]);

		$pNameStr = '';
		$pQuantStr = '';
		$prQuantStr = '';
		$pSerialStr = '';

		$rNum = '';
		foreach($arReclamation['PROPERTIES']['PRODUCTS']['VALUE'] as $product){
			if(!empty($product['REALIZ_NUM']))
				$rNum = $product['REALIZ_NUM'];

			$pNameStr .= $product['NAME'].'; ';
			$prQuantStr .= $product['R_QUANTITY'].'; ';
			$pQuantStr .= $product['QUANTITY'].'; ';

			if(!empty($product['S_NUM'])){
				$pSerialStr .= $product['S_NUM'].'; ';
			}
		}

		$arReplace = array(
			'#R_NUM#' => $arReclamation['ID'],
			'#R_DATE#' => $date[0],
			'#dd#' => $dateParts[0],
			'#mm#' => $dateParts[1],
			'#yy#' => $dateParts[2],
			'#REALIZ_INFO#' => '',
			'#ORGANIZATION#' => empty($arReclamation['COMPANY']) ? '' : $arReclamation['COMPANY']['NAME'],
			'#CONTACT_INFO#' => empty($arReclamation['COMPANY']) ? '' : $arReclamation['COMPANY']['ADDRESS'],
			'#CONTACT_PERSON#' => $arReclamation['USER']['LAST_NAME'].' '.$arReclamation['USER']['NAME'].', '.$arReclamation['USER']['PERSONAL_PHONE'].', '.$arReclamation['USER']['EMAIL'],
			'#PRODUCTS#' => $pNameStr,
			'#SERIALS#' => $pSerialStr,
			'#QUANTITIES_TOTAL#' => $prQuantStr,
			'#QUANTITIES_BRAK#' => $pQuantStr,
			'#DESCRIPTION#' => $arReclamation['PROPERTIES']['DESCRIPTION']['VALUE']['TEXT'],
			'#CASE#' => $arReclamation['PROPERTIES']['CASE']['VALUE']['TEXT'],
			/*'' => '',
			'' => '',
			'' => '',
			'' => '',	 */
		);

		if(!empty($rNum))
			$arReplace['#REALIZ_INFO#'] = $rNum.', '.$date[0];
		else
			$arReplace['#REALIZ_INFO#'] = $date[0];

		//echo "<pre>";print_r($arReplace);echo "</pre>";die;
		$content = file_get_contents($docPath);
		$content = str_replace(array_keys($arReplace), array_values($arReplace), $content);

		file_put_contents($docPath, $content);
	}
}

class CZip{
	function extract($zipFile, $destPath){
		$zip = new \ZipArchive;
		if($zip->open($zipFile) === true){
			$zip->extractTo($destPath);
			$zip->close();
			return true;
		}
		return false;
	}

	function addToArchive($source, $dstFile){

		$zip = new \ZipArchive();
		if(!$zip->open($dstFile, \ZIPARCHIVE::CREATE)) {
			return false;
		}


		if(is_dir($source) === true){
			$files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), \RecursiveIteratorIterator::SELF_FIRST);

			foreach ($files as $file){

				if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
					continue;

				if(is_dir($file) === true){
					$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
				}else if(is_file($file) === true){
					$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
				}
			}
		}else if(is_file($source) === true){
			$zip->addFromString(basename($source), file_get_contents($source));
		}

		return $zip->close();
	}
}

class CDir{

	function checkDir($dir){
		if(!file_exists($dir)){
			mkdir($dir, 0775, true);
		}
	}

	function deleteDir($dirname) {
		if (is_dir($dirname))
			$dir_handle = opendir($dirname);

		if (!$dir_handle)
			return false;

		while($file = readdir($dir_handle)){
			if ($file != "." && $file != ".."){
				if (!is_dir($dirname."/".$file))
					unlink($dirname."/".$file);
			else
				CDir::deleteDir($dirname.'/'.$file);
			}
		}
		closedir($dir_handle);
		rmdir($dirname);
		return true;
	}
}
?>