<?
IncludeModuleLangFile(__FILE__);

if ($APPLICATION->GetGroupRight("aero.auth") != "D") {
    $aMenu = array(
        "parent_menu" => "global_menu_settings",
        "section" => "aero",
        "sort" => 0,
        "text" => "Модерация компаний",
        "title" => "Модерация компаний",
        "icon" => "aero_menu_icon",
        "page_icon" => "aero_page_icon",
        "items_id" => "menu_aero_import",
        "url" => "/bitrix/admin/aero.auth_company_moderate.php?lang=ru",
        "items" => Array(),
        "more_url" => Array(
            'aero.auth_company_moderate_edit.php',
        ),
    );

    return $aMenu;
}
return false;
?>