<?
IncludeModuleLangFile(__FILE__);

Class aero_auth extends CModule
{
    const MODULE_ID = 'aero.auth';
    var $MODULE_ID = 'aero.auth';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $strError = '';

    function __construct()
    {
        $arModuleVersion = array();
        include(dirname(__FILE__) . "/version.php");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = GetMessage("aero.auth_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("aero.auth_MODULE_DESC");

        $this->PARTNER_NAME = GetMessage("aero.auth_PARTNER_NAME");
        $this->PARTNER_URI = GetMessage("aero.auth_PARTNER_URI");
    }

    function InstallDB($arParams = array())
    {
        $obUserField = new CUserTypeEntity();
        $arEntity = CUserTypeEntity::GetList(Array(), Array('ENTITY_ID' => 'USER', 'FIELD_NAME' => 'UF_NEW_EMAIL'))->Fetch();
        if (!$arEntity) {
            $obUserField->Add(Array(
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_NEW_EMAIL',
                'MULTIPLE' => 'N',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'SHOW_FILTER' => 'N',
                'EDIT_IN_LIST' => 'N',
                'SHOW_IN_LIST' => 'N',
                'SETTINGS' => Array(
                    'DEFAULT_VALUE' => '',
                ),
                'EDIT_FORM_LABEL' => Array(
                    'ru' => 'Новый email (не подтвержден)',
                    'en' => 'New email',
                )
            ));
        }

        $obUserField = new CUserTypeEntity();
        $arEntity = CUserTypeEntity::GetList(Array(), Array('ENTITY_ID' => 'USER', 'FIELD_NAME' => 'UF_NEW_EMAIL_CODE'))->Fetch();
        if (!$arEntity) {
            $obUserField->Add(Array(
                'ENTITY_ID' => 'USER',
                'FIELD_NAME' => 'UF_NEW_EMAIL_CODE',
                'MULTIPLE' => 'N',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'SHOW_FILTER' => 'N',
                'EDIT_IN_LIST' => 'N',
                'SHOW_IN_LIST' => 'N',
                'SETTINGS' => Array(
                    'DEFAULT_VALUE' => '',
                ),
                'EDIT_FORM_LABEL' => Array(
                    'ru' => 'Код подтверждения Email',
                    'en' => 'New email code',
                )
            ));
        }

        return true;
    }

    function UnInstallDB($arParams = array())
    {
        return true;
    }

    function InstallEvents()
    {
        RegisterModuleDependences('main', 'OnAfterUserUpdate', self::MODULE_ID, 'CAeroAuth', 'OnAfterUserUpdate');
        RegisterModuleDependences('main', 'OnBeforeUserLogin', self::MODULE_ID, 'CAeroAuth', 'OnBeforeUserLogin');
        RegisterModuleDependences('main', 'OnBeforeUserRegister', self::MODULE_ID, 'CAeroAuth', 'OnBeforeUserRegister');
        RegisterModuleDependences('main', 'OnAfterUserRegister', self::MODULE_ID, 'CAeroAuth', 'OnAfterUserRegister');
        RegisterModuleDependences('main', 'OnBeforeUserUpdate', self::MODULE_ID, 'CAeroAuth', 'OnBeforeUserUpdate');
        return true;
    }

    function UnInstallEvents()
    {
        UnRegisterModuleDependences('main', 'OnAfterUserUpdate', self::MODULE_ID, 'CAeroAuth', 'OnAfterUserUpdate');
        UnRegisterModuleDependences('main', 'OnBeforeUserLogin', self::MODULE_ID, 'CAeroAuth', 'OnBeforeUserLogin');
        UnRegisterModuleDependences('main', 'OnBeforeUserRegister', self::MODULE_ID, 'CAeroAuth', 'OnBeforeUserRegister');
        UnRegisterModuleDependences('main', 'OnAfterUserRegister', self::MODULE_ID, 'CAeroAuth', 'OnAfterUserRegister');
        UnRegisterModuleDependences('main', 'OnBeforeUserUpdate', self::MODULE_ID, 'CAeroAuth', 'OnBeforeUserUpdate');
        return true;
    }

    function InstallFiles($arParams = array())
    {
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . self::MODULE_ID . '/admin')) {
            if ($dir = opendir($p)) {
                while (false !== $item = readdir($dir)) {
                    if ($item == '..' || $item == '.' || $item == 'menu.php')
                        continue;
                    file_put_contents($file = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/' . self::MODULE_ID . '_' . $item,
                        '<' . '? require($_SERVER["DOCUMENT_ROOT"]."/local/modules/' . self::MODULE_ID . '/admin/' . $item . '");?' . '>');
                }
                closedir($dir);
            }
        }
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . self::MODULE_ID . '/install/components')) {
            if ($dir = opendir($p)) {
                while (false !== $item = readdir($dir)) {
                    if ($item == '..' || $item == '.')
                        continue;
                    CopyDirFiles($p . '/' . $item, $_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/' . $item, $ReWrite = True, $Recursive = True);
                }
                closedir($dir);
            }
        }
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . self::MODULE_ID . '/install/gadgets')) {
            if ($dir = opendir($p)) {
                while (false !== $item = readdir($dir)) {
                    if ($item == '..' || $item == '.')
                        continue;
                    CopyDirFiles($p . '/' . $item, $_SERVER['DOCUMENT_ROOT'] . '/bitrix/gadgets/aero/' . $item, $ReWrite = True, $Recursive = True);
                }
                closedir($dir);
            }
        }
        return true;
    }

    function UnInstallFiles()
    {
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . self::MODULE_ID . '/admin')) {
            if ($dir = opendir($p)) {
                while (false !== $item = readdir($dir)) {
                    if ($item == '..' || $item == '.')
                        continue;
                    unlink($_SERVER['DOCUMENT_ROOT'] . '/local/admin/' . self::MODULE_ID . '_' . $item);
                }
                closedir($dir);
            }
        }
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . self::MODULE_ID . '/install/components')) {
            if ($dir = opendir($p)) {
                while (false !== $item = readdir($dir)) {
                    if ($item == '..' || $item == '.' || !is_dir($p0 = $p . '/' . $item))
                        continue;

                    $dir0 = opendir($p0);
                    while (false !== $item0 = readdir($dir0)) {
                        if ($item0 == '..' || $item0 == '.')
                            continue;
                        DeleteDirFilesEx('/bitrix/components/' . $item . '/' . $item0);
                    }
                    closedir($dir0);
                }
                closedir($dir);
            }
        }
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . self::MODULE_ID . '/install/gadgets')) {
            if ($dir = opendir($p)) {
                while (false !== $item = readdir($dir)) {
                    if ($item == '..' || $item == '.' || !is_dir($p0 = $p . '/' . $item))
                        continue;

                    $dir0 = opendir($p0);
                    while (false !== $item0 = readdir($dir0)) {
                        if ($item0 == '..' || $item0 == '.')
                            continue;
                        DeleteDirFilesEx('/bitrix/gadgets/aero/' . $item . '/' . $item0);
                    }
                    closedir($dir0);
                }
                closedir($dir);
            }
        }
        return true;
    }

    function DoInstall()
    {
        global $APPLICATION;
        $this->InstallFiles();
        $this->InstallEvents();
        $this->InstallDB();
        RegisterModule(self::MODULE_ID);
    }

    function DoUninstall()
    {
        global $APPLICATION;
        UnRegisterModule(self::MODULE_ID);
        $this->UnInstallDB();
        $this->UnInstallEvents();
        $this->UnInstallFiles();
    }
}

?>
