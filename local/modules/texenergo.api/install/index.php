<?php

use Bitrix\Main\Localization\Loc;

Loc::loadLanguageFile(__FILE__);

class texenergo_api extends CModule
{
    var $MODULE_ID = 'texenergo.api';
    
    public function __construct()
    {
        $this->MODULE_ID = 'texenergo.api';
        $this->MODULE_NAME = 'REST API TEXENERGO';
        $this->MODULE_DESCRIPTION = 'Модуль помогает организовать REST API-интерфейс';
        $this->MODULE_VERSION = '1.0.0';
        $this->MODULE_VERSION_DATE = '2017-09-09 23:00:00';
        $this->PARTNER_NAME = 'TEXENERGO';
        $this->PARTNER_URI = 'http://www.texenergo.ru/';
    }

    function DoInstall()
    {
        RegisterModule($this->MODULE_ID);
        $GLOBALS['APPLICATION']->IncludeAdminFile(Loc::getMessage('API_INSTALL_TITLE'), __DIR__ . '/step.php');
    }

    function DoUninstall()
    {
        UnRegisterModule($this->MODULE_ID);
        $GLOBALS['APPLICATION']->IncludeAdminFile(Loc::getMessage('API_UNINSTALL_TITLE'), __DIR__ . '/unstep.php');
    }
}