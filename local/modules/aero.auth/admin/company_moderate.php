<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

$MODERATE_RIGHT = $APPLICATION->GetGroupRight("aero.auth");
if ($CURRENCY_RIGHT == "D")
	$APPLICATION->AuthForm("Доступ запрещен");

IncludeModuleLangFile(__FILE__);

CModule::IncludeModule('iblock');
CModule::IncludeModule('workflow');
CModule::IncludeModule('aero.auth');

global $APPLICATION, $DB, $USER;

$APPLICATION->SetTitle("Модерация компаний");


$sTableID = "tbl_aero_moderate";
$oSort = new CAdminSorting($sTableID, "TIMESTAMP_X", "DESC");
$lAdmin = new CAdminList($sTableID, $oSort);

$arFilterFields = Array(
    "find_name",
);
$lAdmin->InitFilter($arFilterFields);


$arHeader = array(
//Common
    array(
        "id" => "ID",
        "content" => "ID",
        "sort" => "ID",
        "default" => true,
    ),
    array(
        "id" => "TIMESTAMP_X",
        "content" => "Дата изменения",
        "sort" => "TIMESTAMP_X",
        "default" => true,
    ),
    array(
        "id" => "TYPE",
        "content" => "Действие",
        "sort" => "type",
        "default" => true,
    ),
    array(
        "id" => "USER",
        "content" => "Пользователь",
        "sort" => "user",
        "default" => true,
    ),
    array(
        "id" => "NAME",
        "content" => "Компания",
        "sort" => "name",
        "default" => true,
    ),

    array(
        "id" => "INN",
        "content" => "ИНН",
        "sort" => "property_inn",
        "default" => true,
    ),
    array(
        "id" => "KPP",
        "content" => "КПП",
        "sort" => "property_kpp",
        "default" => true,
    ),
);

$lAdmin->AddHeaders($arHeader);

// редактирование записей
/*if (($arID = $lAdmin->EditAction())) {
    foreach ($_POST['FIELDS'] as $ID => $arFields) {
        $ID = IntVal($ID);
    }
}*/

// групповые операции
/*if (($arID = $lAdmin->GroupAction())) {
    $arIDS = $_REQUEST['ID'];
    $sAction = $_REQUEST['action'];
    if (!is_array($arIDS)) $arIDS = Array($arIDS);
    foreach ($arIDS as $ID) {
        $ID = IntVal($ID);
        switch ($sAction) {
            case 'delete':

                break;
            case 'activate':

                break;
            case 'deactivate':

                break;
        }
    }
}*/

// выбираем элементы из БД
$arFilter = array(
    'IBLOCK_ID' => IBLOCK_ID_AGENTS,
    'SHOW_HISTORY' => 'Y',
    Array(
        'LOGIC' => 'OR',
        Array(
            'WF_STATUS' => 3,
        ),
        Array('!ACTIVE' => 'Y'),
    ),
);

if ($_REQUEST['set_filter'] == 'Y') {
    $find_name = trim($_GET['find_name']);

    if (!empty($find_name)) {
        $arFilter['NAME'] = $find_name;
    }
}
$rsData = CIBlockElement::GetList(
    Array($by => $order),
    $arFilter, false, false, Array('ID', 'NAME', 'CREATED_BY', 'PROPERTY_INN', 'PROPERTY_KPP', 'WF_PARENT_ELEMENT_ID')
);

$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart();

$lAdmin->NavText($rsData->GetNavPrint("Компании"));

$arItems = Array();

while ($arItem = $rsData->NavNext()) {
    $arItem['NAME'] = htmlspecialcharsBack($arItem['NAME']);
    $arItems[$arItem['ID']] = $arItem;
}

$arRows = Array();
foreach ($arItems as $arItem) {
//echo '<pre>'.print_r($arItem, true).'</pre>';
    $arItem['INN'] = $arItem['PROPERTY_INN_VALUE'];
    $arItem['KPP'] = $arItem['PROPERTY_KPP_VALUE'];

    if (strlen($arItem['WF_PARENT_ELEMENT_ID']) > 0) {
        $arItem['ID'] = $arItem['WF_PARENT_ELEMENT_ID'];
        $arItem['TYPE'] = 'Изменение';
    } else {
        $arItem['TYPE'] = 'Подключение';
    }

    $arUser = CUser::GetByID($arItem['CREATED_BY'])->Fetch();
    if ($arUser) {
        $arItem['USER'] = '[' . $arUser['ID'] . '] ' . $arUser['NAME'] . ' ' . $arUser['LAST_NAME'];
    } else {
        $arItem['USER'] = 'Пользователь удален';
    }

    $arRows[$arItem['ID']] = $arItem;


}

foreach ($arRows as $arItem) {
    $row = & $lAdmin->AddRow($arItem['ID'], $arItem);

    $arActions = Array();

    $arActions[] = array("ICON" => "edit", "TEXT" => "Проверить", "ACTION" => $lAdmin->ActionRedirect("aero.auth_company_moderate_edit.php?ID=" . urlencode($arItem['ID'])), "DEFAULT" => true);

    $row->AddActions($arActions);
}

/*$lAdmin->AddGroupActionTable(Array(
    "delete" => true,
    "approve" => "Одобрить",
    "separate" => "Создать новую компанию",
));*/

$lAdmin->AddFooter(
    array(
//Языковое сообщение для количество выбранных элементов в GetList
        array("title" => "Всего", "value" => $rsData->SelectedRowsCount()),
//Языковое сообщение для количества отмеченных записей в списке
        array("counter" => true, "title" => "Выбрано", "value" => "0"),
    )
);

//$aContext = array();
//$lAdmin->AddAdminContextMenu($aContext);

$lAdmin->CheckListMode();

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
?>
<? /*
    <form method="GET" name="find_form" id="find_form" action="<? echo $APPLICATION->GetCurPage() ?>">
        <?
        $arFindFields = Array();
        $arFindFields["NAME"] = "Название";

        $filterUrl = $APPLICATION->GetCurPageParam();
        $oFilter = new CAdminFilter($sTableID . "_filter", $arFindFields, array("table_id" => $sTableID, "url" => $filterUrl));
        ?>
        <script type="text/javascript">
            var arClearHiddenFields = new Array();
            function applyFilter(el) {
                BX.adminPanel.showWait(el);
                <?=$sTableID."_filter";?>.
                OnSet('<?=CUtil::JSEscape($sTableID)?>', '<?=CUtil::JSEscape($filterUrl)?>');
                return false;
            }

            function deleteFilter(el) {
                BX.adminPanel.showWait(el);
                if (0 < arClearHiddenFields.length) {
                    for (var index = 0; index < arClearHiddenFields.length; index++) {
                        if (undefined != window[arClearHiddenFields[index]]) {
                            if ('ClearForm' in window[arClearHiddenFields[index]]) {
                                window[arClearHiddenFields[index]].ClearForm();
                            }
                        }
                    }
                }
                <?=$sTableID."_filter"?>.
                OnClear('<?=CUtil::JSEscape($sTableID)?>', '<?=CUtil::JSEscape($APPLICATION->GetCurPage().'?lang='.urlencode(LANG).'&')?>');
                return false;
            }
        </script>
        <?
        $oFilter->Begin();
        ?>
        <tr>
            <td>Компания</td>
            <td><input type="text" name="find_name" size="47" value="<? echo htmlspecialcharsbx($find_name) ?>"></td>
        </tr>
        <?
        $oFilter->Buttons();
        ?><input class="adm-btn" type="submit" name="set_filter"
                 value="Найти"
                 title="Найти" onClick="return applyFilter(this);">
        <input class="adm-btn" type="submit" name="del_filter"
               value="Отменить"
               title="Отменить"
               onClick="deleteFilter(this); return false;">
        <?
        $oFilter->End();
        ?>
    </form>*/
?>
<?

$lAdmin->DisplayList();

?>



<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php"); ?>