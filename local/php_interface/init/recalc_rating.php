<?AddEventHandler('iblock', 'OnAfterIBlockElementUpdate', 'recalcRating');

function recalcRating($arFields) {
	if ($arFields['IBLOCK_ID'] == IBLOCK_ID_COMMENTS) {
        $obProp = CIBlockElement::GetProperty(IBLOCK_ID_COMMENTS, $arFields['ID'], array(), array('CODE' => 'ELEMENT_ID'));
        $arProp = $obProp->Fetch();

        if ($arProp && $arProp['VALUE']) {
            $elementID = $arProp['VALUE'];

            $rating = 0;
            $count = 0;

            $arSort = array('ID' => 'DESC');

            $arFilter = array(
                'IBLOCK_ID' => IBLOCK_ID_COMMENTS,
                'PROPERTY_ELEMENT_ID' => $elementID,
                'ACTIVE' => 'Y'
            );

            $arSelect = array(
                'ID',
                'PROPERTY_RAITING',
            );

            $obComments = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
            while ($arComment = $obComments->Fetch()) {
                $rating += $arComment['PROPERTY_RAITING_VALUE'];
                $count++;
            }

            $rating = round(($rating + 5) / ($count + 1));

            CIBlockElement::SetPropertyValues($elementID, IBLOCK_ID_CATALOG, $rating, 'RAITING');
            CIBlockElement::SetPropertyValues($elementID, IBLOCK_ID_CATALOG, $count, 'COMMENTS_CNT');
        }
	}
}