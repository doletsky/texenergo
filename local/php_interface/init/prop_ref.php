<?class IBlockPropertyRef {
    public function GetUserTypeDescription() {
        return array(
            "PROPERTY_TYPE" => "S",
            "USER_TYPE" => "REF",
            "DESCRIPTION" => "Ссылка",
            "GetPropertyFieldHtml" => array(__CLASS__, "GetPropertyFieldHtml"),
            "GetAdminListViewHTML" => array(__CLASS__, "GetPropertyFieldHtml"),
        );
    }

    public function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName) {
        $ref = $value["VALUE"];
        return "<a href=\"{$ref}\">{$ref}</a>";
    }
}

AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("IBlockPropertyRef", "GetUserTypeDescription"));