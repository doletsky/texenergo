<?
$arResult['LEFT_COL_ITEMS'] = array();
$arResult['RIGHT_COL_ITEMS'] = array();
$arResult['YEARS'] = array();

$lastYear = false;
$itemNum = 0;
$treeIndex = 0;
foreach($arResult['ITEMS'] as $arItem){	
	$dateOnly = explode(' ', $arItem['DATE_ACTIVE_FROM']);	
	$dateParts = explode('.', $dateOnly[0]);
	$arItem['DATE_DAYMONTH'] = $dateParts[0].'.'.$dateParts[1];
	$arItem['DATE_YEAR'] = $dateParts[2];
	
	$arResult['YEARS'][] = array(
		'TREE_INDEX' => $treeIndex++,
		'YEAR' => ($lastYear != $dateParts[2]) ? $dateParts[2] : false
	);
	$lastYear = $dateParts[2];
	
	$arItem['TREE_INDEX'] = $treeIndex++;		
	
	if($arItem['PREVIEW_PICTURE']){
		$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>262, 'height'=>1000), BX_RESIZE_IMAGE_PROPORTIONAL, true);
		$arItem['PICTURE'] = $img['src'];
		$arItem['PICTURE_DATA'] = $img;		
	}
	
	if($itemNum % 2 == 0){
		$arResult['LEFT_COL_ITEMS'][] = $arItem;
	}else{
		$arResult['RIGHT_COL_ITEMS'][] = $arItem;
	}
	
	$itemNum++;
}

$treeIndex--;
$arResult['MAX_INDEX'] = $treeIndex;
?>