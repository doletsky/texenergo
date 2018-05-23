<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');?>

<?
if(!empty($_REQUEST['name'])){
	CModule::IncludeModule('iblock');
	
	$SKU = trim($_REQUEST['name']);
	$SKU = htmlspecialchars($name);
	
	$product = array();
	$dbElems = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_CATALOG, 'PROPERTY_SKU' => $SKU), false, array('nTopCount' => 10), array('ID', 'IBLOCK_ID', 'NAME', 'CODE', 'DETAIL_PAGE_URL', 'DETAIL_PICTURE', 'PROPERTY_SKU'));
	if($elem = $dbElems->Fetch()){	
		if (!empty($elem['DETAIL_PICTURE'])) {
			if ($arPic = CFile::ResizeImageGet($elem['DETAIL_PICTURE'], array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
				$elem['PICTURE'] = $arPic['src'];
			}
		}else{
			$elem['PICTURE'] = '/local/templates/texenergo/img/catalog/no-image.png';
		}
		
		$elem['DETAIL_PAGE_URL'] = str_replace('#SITE_DIR#', '', $elem['DETAIL_PAGE_URL']);
		$elem['DETAIL_PAGE_URL'] = str_replace('#CODE#', $elem['CODE'], $elem['DETAIL_PAGE_URL']);
		
		$product['REALIZ_NUM'] = '-'; 		
		$product['PROPERTY_DATE_VALUE'] = '';
		$product['PROPERTY_BASKET_ITEMS_VALUE'][] = array('QUANTITY' => 0, 'ID' => $elem['ID'], 'PRODUCT_INFO' => $elem);		
		$products = array($product);
	}
	
	echo CUtil::PhpToJsObject($products);
}
?>