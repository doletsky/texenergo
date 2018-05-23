<?
IncludeModuleLangFile(__FILE__);

if ($APPLICATION->GetGroupRight("aero.import") != "D") {
    $aMenu = array(
        "parent_menu" => "global_menu_settings",
        "section" => "aero",
        "sort" => 0,
        "text" => "Обмен с 1С",
        "title" => "Обмен с 1С",
        "icon" => "aero_menu_icon",
        "page_icon" => "aero_page_icon",
        "items_id" => "menu_aero_import",
        "url" => "settings.php?lang=ru&mid=aero.import&mid_menu=1",
        "items" => Array()
    );

    return $aMenu;
}
return false;
?>