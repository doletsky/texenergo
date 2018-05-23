<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');?>

<?
if(!empty($_REQUEST['r_num'])){
	CModule::IncludeModule('iblock');
	
	$products = array();
	$dbElems = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_SALES, 'XML_ID' => $_REQUEST['r_num']), false, false, array('ID', 'IBLOCK_ID', 'PROPERTY_BASKET_ITEMS', 'PROPERTY_DATE'));
	if($elem = $dbElems->Fetch()){
		$elem['REALIZ_NUM'] = $_REQUEST['r_num']; 
		$elem['PROPERTY_DATE_VALUE'] = FormatDateFromDB($elem['PROPERTY_DATE_VALUE'], 'DD MMMM YYYY');
		
		foreach($elem['PROPERTY_BASKET_ITEMS_VALUE'] as &$basketItem){
			$dbElems = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_CATALOG, 'ID' => $basketItem['ID']), false, false, array('ID', 'IBLOCK_ID', 'NAME', 'CODE', 'DETAIL_PAGE_URL', 'DETAIL_PICTURE', 'PROPERTY_SKU'));
			if($product = $dbElems->Fetch()){
				if (!empty($product['DETAIL_PICTURE'])) {
					if ($arPic = CFile::ResizeImageGet($product['DETAIL_PICTURE'], array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
						$product['PICTURE'] = $arPic['src'];
					}
				}else{
					$product['PICTURE'] = '/local/templates/texenergo/img/catalog/no-image.png';
				}
				
				$product['DETAIL_PAGE_URL'] = str_replace('#SITE_DIR#', '', $product['DETAIL_PAGE_URL']);
				$product['DETAIL_PAGE_URL'] = str_replace('#CODE#', $product['CODE'], $product['DETAIL_PAGE_URL']);
				
				$basketItem['PRODUCT_INFO'] = $product;
				
			}
		}		
		
		$products[] = $elem;
	}
	
	echo CUtil::PhpToJsObject($products);
}
?>