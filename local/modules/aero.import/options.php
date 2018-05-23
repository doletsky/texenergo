<?
if (!$USER->IsAdmin())
    return;

if (!IsModuleInstalled('catalog') || !CModule::IncludeModule('sale') || !CModule::IncludeModule('iblock')) {
    echo BeginNote();
    echo 'Не установлены требуемые модули: iblock, catalog, sale';
    echo EndNote();
    return;
}

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

$module_id = "aero.import";

$arIblocks = Array();

$rsIblocks = CIBlock::GetList(Array('name' => 'asc'), Array('TYPE' => 'catalog'));

$arIblocksValues = Array('0' => 'Не выбран');

while ($arIblock = $rsIblocks->Fetch()) {
    $arIblocksValues[$arIblock['ID']] = $arIblock['NAME'];
}

$arBrandsValues = Array('0' => 'Не выбран');

$iblockBrands = COption::GetOptionInt($module_id, 'iblock_brands', 0);

if ($iblockBrands > 0) {
    $rsBrands = CIBlockElement::GetList(Array('name' => 'asc'), Array('IBLOCK_ID' => $iblockBrands, 'ACTIVE' => 'Y'), false, Array('nTopCount' => 100), Array('ID', 'NAME'));
    while ($arBrand = $rsBrands->Fetch()) {
        $arBrandsValues[$arBrand['ID']] = $arBrand['NAME'] . ' [' . $arBrand['ID'] . ']';
    }
}

$arErrors = Array();
$arMessages = Array();


$MOD_RIGHT = $APPLICATION->GetGroupRight($module_id);

$arAllOptions = Array();


$arAllOptions[] = 'Инфоблоки для каталога';
$arAllOptions[] = Array("iblock_catalog", "Инфоблок с товарами", '', Array('selectbox', $arIblocksValues));
$arAllOptions[] = Array("iblock_brands", "Инфоблок с производителями", '', Array('selectbox', $arIblocksValues));
$arAllOptions[] = Array("iblock_series", "Инфоблок с сериями", '', Array('selectbox', $arIblocksValues));
$arAllOptions[] = Array("iblock_actions", "Инфоблок с акциями", '', Array('selectbox', $arIblocksValues));

$arAllOptions[] = 'Агент';
$arAllOptions[] = Array("data_file", "Директория для импорта каталога(относительно корня сайта, должна содержать файл catTE.xml)", '', Array('text', 40));
$arAllOptions[] = Array("data_file_docs", "Директория для импорта дополнительных документов(относительно корня сайта)", '', Array('text', 40));
$arAllOptions[] = Array("data_file_orders", "Директория для выгрузки заказов(относительно корня сайта)", '', Array('text', 40));
$arAllOptions[] = Array("agent_is_running", "Запретить параллельный запуск", '', Array('checkbox'));
$arAllOptions[] = Array('note' => 'Если агент завис, отключите эту галочку');

$arAllOptions[] = 'Что обновлять при импорте каталога';
$arAllOptions[] = Array("import_iblock_properties", "Свойства товаров", '', Array('checkbox'));
$arAllOptions[] = Array("import_special", "Спецпредложения, новинки, лидеры продаж", '', Array('checkbox'));
$arAllOptions[] = Array("import_iblock_sections", "Разделы каталога", '', Array('checkbox'));
$arAllOptions[] = Array("import_iblock_elements", "Товары", '', Array('checkbox'));
$arAllOptions[] = Array("import_catalog", "Остатки и цены", '', Array('checkbox'));

$logFile = COption::GetOptionString($module_id, 'log_path', '');
$logContent = '';
if (file_exists($logFile) && is_file($logFile)) {
    $logContent = file_get_contents($logFile);
    $logContent = nl2br($logContent);
}

if ($MOD_RIGHT >= 'Y' || $USER->IsAdmin()):

    if ($REQUEST_METHOD == 'GET' && strlen($RestoreDefaults) > 0 && check_bitrix_sessid()) {
        COption::RemoveOption($module_id);
        $z = CGroup::GetList($v1 = 'id', $v2 = 'asc', array('ACTIVE' => 'Y', 'ADMIN' => 'N'));
        while ($zr = $z->Fetch())
            $APPLICATION->DelGroupRight($module_id, array($zr['ID']));
    }

    if ($REQUEST_METHOD == 'POST' && strlen($Update) > 0 && check_bitrix_sessid()) {
        $arOptions = $arAllOptions;

        foreach ($arOptions as $option) {
            if (!is_array($option) || isset($option['note']))
                continue;

            $name = $option[0];
            $val = ${$name};
            if ($option[3][0] == 'checkbox' && $val != 'Y')
                $val = 'N';
            if ($option[3][0] == 'multiselectbox')
                $val = @implode(',', $val);

            COption::SetOptionString($module_id, $name, $val, $option[1]);
        }

        if (empty($arErrors)) {
            LocalRedirect('/bitrix/admin/settings.php?lang=ru&mid=' . $module_id . '&mid_menu=1');
        }
    }

endif; //if($MOD_RIGHT>="W"):

foreach ($arErrors as $strError)
    CAdminMessage::ShowMessage($strError);
foreach ($arMessages as $strMessage)
    CAdminMessage::ShowMessage(array("MESSAGE" => $strMessage, "TYPE" => "OK"));

?>
<?
$aTabs = array();
$aTabs[] = array('DIV' => 'set', 'TAB' => GetMessage('MAIN_TAB_SET'), 'ICON' => 'aero_import', 'TITLE' => GetMessage('MAIN_TAB_TITLE_SET'));


$tabControl = new CAdminTabControl("tabControl", $aTabs);

$tabControl->Begin();
?>

<form method="POST"
      action="<? echo $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($mid) ?>&lang=<?= LANGUAGE_ID ?>"
      name="aero_settings">
    <? $tabControl->BeginNextTab(); ?>
    <? __AdmSettingsDrawList('aero.import', $arAllOptions); ?>

    <? if (strlen($logContent) > 0): ?>
        <tr class="heading">
            <td colspan="2">Лог последнего импорта</td>
        </tr>
        <tr>
            <td colspan="2">
                <div style="height:300px; overflow:scroll; background:#fff;">
                    <?= $logContent; ?>
                </div>
            </td>
        </tr>
    <? endif; ?>


    <? $tabControl->Buttons(); ?>
    <input type="submit" name="Update" <? if ($MOD_RIGHT < 'W') echo "disabled" ?>
           value="<? echo GetMessage('MAIN_SAVE') ?>">
    <input type="hidden" name="Update" value="Y">
    <?= bitrix_sessid_post(); ?>
    <? $tabControl->End(); ?>
</form>
