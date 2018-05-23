<?
/* При добавлении/удалении связи с другими публикациями, у них какже должна добавляться/удаляться связь (свойство "Связана с публикациями") */

AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "publicationBackLink");
AddEventHandler("iblock", "OnAfterIBlockElementAdd", "publicationBackLink");
function publicationBackLink($arFields){	
	if($arFields['IBLOCK_ID'] == IBLOCK_ID_PUBLICATIONS){
		require_once(dirname(__FILE__).'/../include/helpers/'.'publication_backlink.php');
		PublicationBackLink::addBackLink($arFields['IBLOCK_ID'], $arFields['ID']);
	}
	
	/* if($arFields['IBLOCK_ID'] == IBLOCK_ID_PUBLICATIONS || $arFields['IBLOCK_ID'] == IBLOCK_ID_CATALOG){
		require_once(dirname(__FILE__).'/../include/helpers/'.'publication_backlink.php');
		PublicationBackLink::addProductsBackLink($arFields['IBLOCK_ID'], $arFields['ID']);
	} */
}
?>