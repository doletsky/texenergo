<?
IncludeModuleLangFile(__FILE__);
Class aero_discount extends CModule
{
    const MODULE_ID = 'aero.discount';
    var $MODULE_ID = 'aero.discount';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $strError = '';

    function __construct()
    {
        $arModuleVersion = array();
        include(dirname(__FILE__) . '/version.php');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = GetMessage('AERO_DISCOUNT_MODULE_NAME');
        $this->MODULE_DESCRIPTION = GetMessage('AERO_DISCOUNT_MODULE_DESC');

        $this->PARTNER_NAME = GetMessage('AERO_DISCOUNT_PARTNER_NAME');
        $this->PARTNER_URI = GetMessage('AERO_DISCOUNT_PARTNER_URI');
    }

    function InstallDB($arParams = array())
    {
        return true;
    }

    function UnInstallDB($arParams = array())
    {
        return true;
    }

    function InstallEvents()
    {
        return true;
    }

    function UnInstallEvents()
    {
        return true;
    }

    function InstallFiles($arParams = array())
    {
        return true;
    }

    function UnInstallFiles()
    {
        return true;
    }

    function DoInstall()
    {
        global $APPLICATION;
        $this->InstallFiles();
        $this->InstallDB();
        $this->InstallEvents();
        RegisterModule(self::MODULE_ID);
        RegisterModuleDependences('main', 'OnAfterUserAuthorize', self::MODULE_ID, 'CAeroUserDiscount', 'OnAfterUserAuthorize');
        RegisterModuleDependences('main', 'OnBeforeUserLogout', self::MODULE_ID, 'CAeroUserDiscount', 'OnBeforeUserLogout');
    }

    function DoUninstall()
    {
        global $APPLICATION;
        UnRegisterModuleDependences('main', 'OnAfterUserAuthorize', self::MODULE_ID, 'CAeroUserDiscount', 'OnAfterUserAuthorize');
        UnRegisterModuleDependences('main', 'OnBeforeUserLogout', self::MODULE_ID, 'CAeroUserDiscount', 'OnBeforeUserLogout');
        UnRegisterModule(self::MODULE_ID);
        $this->UnInstallDB();
        $this->UnInstallFiles();
        $this->UnInstallEvents();
    }
}
