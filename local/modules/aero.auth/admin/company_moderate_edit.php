<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

$MODERATE_RIGHT = $APPLICATION->GetGroupRight("aero.auth");
if ($CURRENCY_RIGHT == "D")
	$APPLICATION->AuthForm("Доступ запрещен");

IncludeModuleLangFile(__FILE__);

CModule::IncludeModule('iblock');
CModule::IncludeModule('sale');
CModule::IncludeModule('aero.auth');
CModule::IncludeModule('workflow');

global $APPLICATION, $DB, $USER, $USER_FIELD_MANAGER;

$APPLICATION->SetTitle("Модерация компаний");

$ID = IntVal($_REQUEST['ID']);

$arUser = Array();
$arFields = Array(); // текущая компания
$arCompanies = Array(); // похожие компании
$arChanges = Array(); // версии текущей компании из документооборота

$arSelect = Array(
    'ID',
    'ACTIVE',
    'NAME',
    'CREATED_BY',
    // юридический адрес
    'PROPERTY_CITY_LEGAL',
    'PROPERTY_LOCATION_LEGAL',
    'PROPERTY_STREET_LEGAL',
    'PROPERTY_ZIP_LEGAL',
    'PROPERTY_HOUSE_LEGAL',
    'PROPERTY_HOUSING_LEGAL',
    'PROPERTY_OFFICE_LEGAL',
    'PROPERTY_STAGE_LEGAL',
    'PROPERTY_COMMENT_LEGAL',

    // фактический адрес
    'PROPERTY_ACTUAL_EQUALS_LEGAL',
    'PROPERTY_CITY_ACTUAL',
    'PROPERTY_LOCATION_ACTUAL',
    'PROPERTY_STREET_ACTUAL',
    'PROPERTY_ZIP_ACTUAL',
    'PROPERTY_HOUSE_ACTUAL',
    'PROPERTY_HOUSING_ACTUAL',
    'PROPERTY_OFFICE_ACTUAL',
    'PROPERTY_STAGE_ACTUAL',
    'PROPERTY_COMMENT_ACTUAL',

    // реквизиты
    'PROPERTY_BANK',
    'PROPERTY_BIK',
    'PROPERTY_INN',
    'PROPERTY_KPP',
    'PROPERTY_ACCOUNT_COR',
    'PROPERTY_ACCOUNT',
    'PROPERTY_OGRN',

    // контакты
    'PROPERTY_PHONE',
    'PROPERTY_FAX',
    'PROPERTY_EMAIL',

    'WF_PARENT_ELEMENT_ID',
    'WF_STATUS',
    'WF_COMMENTS',
);


$bNew = !($ID > 0);

if ($ID > 0) {

    $arFields = CIBlockElement::GetList(
        Array(),
        Array('=ID' => $ID, 'IBLOCK_ID' => IBLOCK_ID_AGENTS),
        false, Array('nTopCount'),
        $arSelect
    )->GetNext();
    //echo '<pre>' . print_r($arFields, true) . '</pre>';
    if ($arFields) {
        $APPLICATION->SetTitle($arFields['~NAME']);
        $arLocation = CSaleLocation::GetByID($arFields['PROPERTY_LOCATION_LEGAL_VALUE']);
        $arFields['PROPERTY_CITY_LEGAL_VALUE'] = $arLocation['CITY_NAME'];

        $arLocation = CSaleLocation::GetByID($arFields['PROPERTY_LOCATION_ACTUAL_VALUE']);
        $arFields['PROPERTY_CITY_ACTUAL_VALUE'] = $arLocation['CITY_NAME'];

        $arUser = CUser::GetByID($arFields['CREATED_BY'])->Fetch();


        $rsCompanies = CIBlockElement::GetList(Array('name' => 'asc'), Array(
            'IBLOCK_ID' => IBLOCK_ID_AGENTS,
            'ACTIVE' => 'Y',
            '!ID' => $arFields['ID'],
            'PROPERTY_INN' => $arFields['PROPERTY_INN_VALUE'],
            'PROPERTY_KPP' => $arFields['PROPERTY_KPP_VALUE'],
        ), false, false, $arSelect);
        while ($arCompany = $rsCompanies->GetNext()) {
            $arLocation = CSaleLocation::GetByID($arCompany['PROPERTY_LOCATION_LEGAL_VALUE']);
            $arCompany['PROPERTY_CITY_LEGAL_VALUE'] = $arLocation['CITY_NAME'];

            $arLocation = CSaleLocation::GetByID($arCompany['PROPERTY_LOCATION_ACTUAL_VALUE']);
            $arCompany['PROPERTY_CITY_ACTUAL_VALUE'] = $arLocation['CITY_NAME'];
            $arCompanies[] = $arCompany;
        }
        if ($arFields['ACTIVE'] == 'Y') {

            $rsCompanies = CIBlockElement::GetList(Array('id' => 'desc'), Array(
                'IBLOCK_ID' => IBLOCK_ID_AGENTS,
                'WF_PARENT_ELEMENT_ID' => $arFields['ID'],
                'WF_STATUS' => 3,
                'SHOW_HISTORY' => 'Y',
            ), false, false, $arSelect);
            while ($arCompany = $rsCompanies->GetNext()) {
                $arLocation = CSaleLocation::GetByID($arCompany['PROPERTY_LOCATION_LEGAL_VALUE']);
                $arCompany['PROPERTY_CITY_LEGAL_VALUE'] = $arLocation['CITY_NAME'];

                $arLocation = CSaleLocation::GetByID($arCompany['PROPERTY_LOCATION_ACTUAL_VALUE']);
                $arCompany['PROPERTY_CITY_ACTUAL_VALUE'] = $arLocation['CITY_NAME'];
                $arChanges[] = $arCompany;
            }
        }

    }
}


$aTabs = array(
    array(
        "DIV" => "edit1",
        "TAB" => "Компания",
        "ICON" => "site_edit",
        "TITLE" => "Компания"
    ),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);


if (strlen($_GET['action']) > 0) {
    switch ($_GET['action']) {

        case 'create':
            if ($arFields['ACTIVE'] != 'Y') {
                $obj = new CIBlockElement();
                $obj->Update($arFields['ID'], Array('ACTIVE' => 'Y'));
                $USER_FIELD_MANAGER->Update('USER', $arUser['ID'], Array(
                    'UF_COMPANY_ID' => $arFields['ID'],
                ));
            }
            break;
        case 'delete':
            if ($arFields['ACTIVE'] != 'Y') {
                $USER_FIELD_MANAGER->Update('USER', $iUserID, Array(
                    'UF_COMPANY_ID' => '',
                ));
                CIBlockElement::Delete($arFields['ID']);
            }
            break;
        case 'select':
            //if ($arFields['ACTIVE'] != 'Y') {
                $selected_id = IntVal($_GET['new_id']);
                if ($selected_id > 0) {
                    $USER_FIELD_MANAGER->Update('USER', $arUser['ID'], Array(
                        'UF_COMPANY_ID' => $selected_id,
                    ));
                    CIBlockElement::Delete($arFields['ID']);
                }
            //}
            break;
        case 'reject':
            if ($arFields['ACTIVE'] == 'Y') {
                $selected_id = IntVal($_GET['new_id']);
                $obj = new CIBlockElement();
                $obj->Update($selected_id, Array(
                    'WF_STATUS_ID' => 2,
                    'WF_COMMENTS' => 'Отклонено модератором',
                ));
                LocalRedirect($APPLICATION->GetCurPageParam('', Array('new_id', 'action')));
            }
            break;
        case 'approve':
            if ($arFields['ACTIVE'] == 'Y') {
                $selected_id = IntVal($_GET['new_id']);
                $obj = new CIBlockElement();

                $obj->Update($arFields['ID'], Array(
                    'WF_STATUS_ID' => 1,
                ), true);

                foreach ($arChanges as $arCompany) {
                    $obj->Update($arCompany['ID'], Array(
                        'WF_STATUS_ID' => 2,
                        'WF_COMMENTS' => 'Отклонено модератором',
                    ), true);
                }

            }
            break;
    }
    LocalRedirect('/bitrix/admin/aero.auth_company_moderate.php?lang=' . LANGUAGE_ID);
}


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$aMenu = array(
    array(
        "TEXT" => "Список заявок",
        "LINK" => "/bitrix/admin/aero.auth_company_moderate.php?lang=" . LANGUAGE_ID,
        "TITLE" => "Список заявок",
        "ICON" => "btn_list"
    )
);

$context = new CAdminContextMenu($aMenu);
$context->Show();


if ($e = $APPLICATION->GetException())
    $message = new CAdminMessage("Произошла ошибка", $e);

if ($message)
    echo $message->Show();

?>
<? if ($arFields['ID'] > 0): ?>
    <?
    $tabControl->Begin();
    $tabControl->BeginNextTab();
    ?>

    <tr class="heading">
        <? if ($arFields['ACTIVE'] != 'Y'): ?>
            <td colspan="2">Информация, указанная пользователем</td>
        <? else: ?>
            <td colspan="2">Текущая информация о компании</td>
        <?endif; ?>
    </tr>
    <tr class="adm-detail-required-field">
        <td width="50%">Пользователь</td>
        <td width="50%"><?= $arUser['NAME']; ?> <?= $arUser['LAST_NAME']; ?> (<a
                href="/bitrix/admin/user_edit.php?lang=ru&ID=<?= $arUser['ID']; ?>"
                target="_blank">редактировать</a>)
        </td>
    </tr>
    <tr class="adm-detail-required-field">
        <td width="50%">Компания</td>
        <td width="50%">
            <?= $arFields['NAME']; ?>
            (<a
                href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?= IBLOCK_ID_AGENTS; ?>&type=orders&ID=<?= $ID; ?>&lang=ru"
                target="_blank">редактировать</a>)
        </td>
    </tr>
    <tr class="adm-detail-required-field">
        <td>Тел</td>
        <td>
            <?= $arFields['PROPERTY_PHONE_VALUE']; ?>
        </td>
    </tr>
    <tr class="adm-detail-required-field">
        <td>Факс</td>
        <td>
            <?= $arFields['PROPERTY_FAX_VALUE']; ?>
        </td>
    </tr>
    <tr class="adm-detail-required-field">
        <td>ИНН</td>
        <td>
            <?= $arFields['PROPERTY_INN_VALUE']; ?>
        </td>
    </tr>
    <tr class="adm-detail-required-field">
        <td>КПП</td>
        <td>
            <?= $arFields['PROPERTY_KPP_VALUE']; ?>
        </td>
    </tr>
    <tr class="adm-detail-required-field">
        <td>Наименование банка</td>
        <td>
            <?= $arFields['PROPERTY_BANK_VALUE']; ?>
        </td>
    </tr>
    <tr class="adm-detail-required-field">
        <td>БИК</td>
        <td>
            <?= $arFields['PROPERTY_BIK_VALUE']; ?>
        </td>
    </tr>
    <tr class="adm-detail-required-field">
        <td>Корреспондентский счет</td>
        <td>
            <?= $arFields['PROPERTY_ACCOUNT_COR_VALUE']; ?>
        </td>
    </tr>
    <tr class="adm-detail-required-field">
        <td>Расчетный счет</td>
        <td>
            <?= $arFields['PROPERTY_ACCOUNT_VALUE']; ?>
        </td>
    </tr>
    <tr class="adm-detail-required-field">
        <td>ОГРН</td>
        <td>
            <?= $arFields['PROPERTY_OGRN_VALUE']; ?>
        </td>
    </tr>
    <tr class="adm-detail-required-field">
        <td>Юридический адрес</td>
        <td>
            <?= $arFields['PROPERTY_CITY_LEGAL_VALUE']; ?>,
            ул.<?= $arFields['PROPERTY_STREET_LEGAL_VALUE']; ?>,
            дом <?= $arFields['PROPERTY_HOUSE_LEGAL_VALUE']; ?>,
            корпус <?= $arFields['PROPERTY_HOUSING_LEGAL_VALUE'] ? : '- '; ?>,
            офис <?= $arFields['PROPERTY_OFFICE_LEGAL_VALUE'] ? : '- '; ?>,
            этаж <?= $arFields['PROPERTY_STAGE_LEGAL_VALUE'] ? : '- '; ?>
        </td>
    </tr>
    <tr class="adm-detail-required-field">
        <td>Фактический адрес</td>
        <td>
            <?= $arFields['PROPERTY_CITY_ACTUAL_VALUE']; ?>,
            ул.<?= $arFields['PROPERTY_STREET_ACTUAL_VALUE']; ?>,
            дом <?= $arFields['PROPERTY_HOUSE_ACTUAL_VALUE']; ?>,
            корпус <?= $arFields['PROPERTY_HOUSING_ACTUAL_VALUE'] ? : '- '; ?>,
            офис <?= $arFields['PROPERTY_OFFICE_ACTUAL_VALUE'] ? : '- '; ?>,
            этаж <?= $arFields['PROPERTY_STAGE_ACTUAL_VALUE'] ? : '- '; ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <? if ($arFields['ACTIVE'] != 'Y'): ?>
                <a href="<?= $APPLICATION->GetCurPageParam('action=create', Array('action')); ?>"
                   class="adm-btn-green adm-btn">
                    Сохранить как новую
                </a>
                <a href="<?= $APPLICATION->GetCurPageParam('action=delete', Array('action')); ?>"
                   class="adm-btn">
                    Удалить
                </a>
            <? endif; ?>

        </td>
    </tr>



    <? if (!empty($arChanges)): ?>
        <tr class="heading">
            <td colspan="2">Внесение изменений пользователем</td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="adm-list-table">
                    <thead>
                    <tr class="adm-list-table-header">
                        <td class="adm-list-table-cell">&nbsp;</td>
                        <td class="adm-list-table-cell">
                            <div class="adm-list-table-cell-inner">
                                Компания
                            </div>
                        </td>
                        <td class="adm-list-table-cell">
                            <div class="adm-list-table-cell-inner">
                                Реквизиты
                            </div>
                        </td>
                        <td class="adm-list-table-cell">
                            <div class="adm-list-table-cell-inner">
                                Юридический адрес
                            </div>
                        </td>
                        <td class="adm-list-table-cell">
                            <div class="adm-list-table-cell-inner">
                                Фактический адрес
                            </div>
                        </td>
                        <td class="adm-list-table-cell">
                            <div class="adm-list-table-cell-inner">
                                &nbsp;
                            </div>
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($arChanges as $arCompany): ?>
                        <tr>
                            <td class="adm-list-table-cell">
                                <a href="<?= $APPLICATION->GetCurPageParam('action=approve&new_id=' . $arCompany['ID'], Array('action', 'new_id')); ?>"
                                   class="adm-btn-green adm-btn">
                                    <nobr>Утвердить</nobr>
                                </a>
                                <br><br>
                                <a href="<?= $APPLICATION->GetCurPageParam('action=reject&new_id=' . $arCompany['ID'], Array('action', 'new_id')); ?>"
                                   class="adm-btn-red adm-btn">
                                    <nobr>Отклонить</nobr>
                                </a>
                            </td>
                            <td class="adm-list-table-cell">
                                <?= $arCompany['NAME']; ?><br><br>
                                Тел:
                                <nobr><?= $arCompany['PROPERTY_PHONE_VALUE']; ?></nobr>
                                <br>
                                Факс:
                                <nobr><?= $arCompany['PROPERTY_FAX_VALUE']; ?></nobr>
                                <br>
                            </td>
                            <td class="adm-list-table-cell">
                                ИНН <?= $arCompany['PROPERTY_INN_VALUE']; ?><br>
                                КПП <?= $arCompany['PROPERTY_KPP_VALUE']; ?><br>
                                Наименование банка <?= $arCompany['PROPERTY_BANK_VALUE']; ?><br>
                                БИК <?= $arCompany['PROPERTY_BIK_VALUE']; ?><br>
                                Корреспондентский счет <?= $arCompany['PROPERTY_ACCOUNT_COR_VALUE']; ?><br>
                                Расчетный счет <?= $arCompany['PROPERTY_ACCOUNT_VALUE']; ?>
                                ОГРН <?= $arCompany['PROPERTY_OGRN_VALUE']; ?>
                            </td>
                            <td class="adm-list-table-cell">
                                <?= $arCompany['PROPERTY_CITY_LEGAL_VALUE']; ?>,
                                ул.<?= $arCompany['PROPERTY_STREET_LEGAL_VALUE']; ?>,
                                дом <?= $arCompany['PROPERTY_HOUSE_LEGAL_VALUE']; ?>,
                                корпус <?= $arCompany['PROPERTY_HOUSING_LEGAL_VALUE'] ? : '- '; ?>,
                                офис <?= $arCompany['PROPERTY_OFFICE_LEGAL_VALUE'] ? : '- '; ?>,
                                этаж <?= $arCompany['PROPERTY_STAGE_LEGAL_VALUE'] ? : '- '; ?>
                            </td>
                            <td class="adm-list-table-cell">
                                <?= $arCompany['PROPERTY_CITY_LEGAL_VALUE']; ?>,
                                ул.<?= $arCompany['PROPERTY_STREET_LEGAL_VALUE']; ?>,
                                дом <?= $arCompany['PROPERTY_HOUSE_LEGAL_VALUE']; ?>,
                                корпус <?= $arCompany['PROPERTY_HOUSING_LEGAL_VALUE'] ? : '- '; ?>,
                                офис <?= $arCompany['PROPERTY_OFFICE_LEGAL_VALUE'] ? : '- '; ?>,
                                этаж <?= $arCompany['PROPERTY_STAGE_LEGAL_VALUE'] ? : '- '; ?>
                            </td>
                            <td class="adm-list-table-cell">
                                <?= $arCompany['WF_COMMENTS']; ?>
                            </td>
                        </tr>
                    <? endforeach; ?>
                    </tbody>
                </table>
            </td>
        </tr>
    <? endif; ?>


    <? if (!empty($arCompanies)): ?>
        <tr class="heading">
            <td colspan="2">Компании с похожими реквизитами</td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="adm-list-table">
                    <thead>
                    <tr class="adm-list-table-header">
                        <td class="adm-list-table-cell">&nbsp;</td>
                        <td class="adm-list-table-cell">
                            <div class="adm-list-table-cell-inner">
                                Компания
                            </div>
                        </td>
                        <td class="adm-list-table-cell">
                            <div class="adm-list-table-cell-inner">
                                Реквизиты
                            </div>
                        </td>
                        <td class="adm-list-table-cell">
                            <div class="adm-list-table-cell-inner">
                                Юридический адрес
                            </div>
                        </td>
                        <td class="adm-list-table-cell">
                            <div class="adm-list-table-cell-inner">
                                Фактический адрес
                            </div>
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($arCompanies as $arCompany): ?>
                        <tr>
                            <td class="adm-list-table-cell">
                                <a href="<?= $APPLICATION->GetCurPageParam('action=select&new_id=' . $arCompany['ID'], Array('action', 'new_id')); ?>"
                                   class="adm-btn-green adm-btn">
                                    <nobr>Заменить на</nobr>
                                </a>
                            </td>
                            <td class="adm-list-table-cell"><?= $arCompany['NAME']; ?></td>
                            <td class="adm-list-table-cell">
                                ИНН <?= $arCompany['PROPERTY_INN_VALUE']; ?><br>
                                КПП <?= $arCompany['PROPERTY_KPP_VALUE']; ?><br>
                                Наименование банка <?= $arCompany['PROPERTY_BANK_VALUE']; ?><br>
                                БИК <?= $arCompany['PROPERTY_BIK_VALUE']; ?><br>
                                Корреспондентский счет <?= $arCompany['PROPERTY_ACCOUNT_COR_VALUE']; ?><br>
                                Расчетный счет <?= $arCompany['PROPERTY_ACCOUNT_VALUE']; ?>
                            </td>
                            <td class="adm-list-table-cell">
                                <?= $arCompany['PROPERTY_CITY_LEGAL_VALUE']; ?>,
                                ул.<?= $arCompany['PROPERTY_STREET_LEGAL_VALUE']; ?>,
                                дом <?= $arCompany['PROPERTY_HOUSE_LEGAL_VALUE']; ?>,
                                корпус <?= $arCompany['PROPERTY_HOUSING_LEGAL_VALUE'] ? : '- '; ?>,
                                офис <?= $arCompany['PROPERTY_OFFICE_LEGAL_VALUE'] ? : '- '; ?>,
                                этаж <?= $arCompany['PROPERTY_STAGE_LEGAL_VALUE'] ? : '- '; ?>
                            </td>
                            <td class="adm-list-table-cell">
                                <?= $arCompany['PROPERTY_CITY_LEGAL_VALUE']; ?>,
                                ул.<?= $arCompany['PROPERTY_STREET_LEGAL_VALUE']; ?>,
                                дом <?= $arCompany['PROPERTY_HOUSE_LEGAL_VALUE']; ?>,
                                корпус <?= $arCompany['PROPERTY_HOUSING_LEGAL_VALUE'] ? : '- '; ?>,
                                офис <?= $arCompany['PROPERTY_OFFICE_LEGAL_VALUE'] ? : '- '; ?>,
                                этаж <?= $arCompany['PROPERTY_STAGE_LEGAL_VALUE'] ? : '- '; ?>
                            </td>
                        </tr>
                    <? endforeach; ?>
                    </tbody>
                </table>
            </td>
        </tr>
    <? endif; ?>

    <? //$tabControl->Buttons(array("back_url" => "aero.auth_company_moderate.php?lang=" . LANGUAGE_ID));
    $tabControl->End();
    $tabControl->ShowWarnings("bform", $message);
    ?>
<? else: ?>
    <? ShowError('Заявка не найдена. Возможно кто-то ее уже обработал.'); ?>
<? endif; ?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php"); ?>