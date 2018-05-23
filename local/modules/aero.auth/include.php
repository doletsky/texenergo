<?php

/**
 * User: timokhin
 * Date: 04.07.14
 * Time: 14:42
 */
Class CAeroAuth
{

    /**
     * в качестве логина может быть использован email
     * email принимается в виде параметра запроса USER_EMAIL
     * и если все в порядке, то из бд выбирается нужный пользователь и его логин подменяется в $arParams
     *
     */
    function OnBeforeUserLogin(&$arParams)
    {
        $rsUsers = null;

        $login = trim($_POST['USER_LOGIN']);

        if (strlen($login) > 0 && check_email($login)) {
            $rsUsers = CUser::GetList(($by = "id"), ($order = "desc"), Array('EMAIL' => $login));
        }


        if ($rsUsers && $arUser = $rsUsers->Fetch()) {
            $arParams['LOGIN'] = $arUser['LOGIN'];
        }

        //не даём авторизоваться пользователям с "битыми" конртагентами (11017)
        CModule::IncludeModule('iblock');
        if(!$arUser) $arUser = CUser::GetList(($by = "id"), ($order = "desc"), Array('LOGIN' => $login))->Fetch();
        if($arUser) {
            $arUser = CUser::GetByID($arUser['ID'])->Fetch();
            if(!empty($arUser['UF_COMPANY_ID'])){
                $rs = CIBlockElement::GetById($arUser['UF_COMPANY_ID']);
                $company = $rs->GetNext();
                if(empty($company)){
                    $arParams['LOGIN'] = false;
                }
            }
        }
    }


    /**
     * в качестве логина может быть использован email
     * email принимается в виде параметра запроса USER_EMAIL
     */
    function OnBeforeUserRegister(&$arParams)
    {

		$originalName = $arParams['NAME'];
		$originalLastName = $arParams['LAST_NAME'];

		$arParams['NAME'] = mb_eregi_replace('[^а-я\-]', '', $arParams['NAME']);
        $arParams['LAST_NAME'] = mb_eregi_replace('[^а-я\-]', '', $arParams['LAST_NAME']);

		if (strlen($originalName) > 0 && strlen($arParams['NAME']) <= 0) {
           $GLOBALS['APPLICATION']->ThrowException('Имя может содержать только кириллицу');
		   return false;
        }

		if (strlen($originalLastName) > 0 && strlen($arParams['LAST_NAME']) <= 0) {
           $GLOBALS['APPLICATION']->ThrowException('Фамилия может содержать только кириллицу');
		   return false;
        }

        if (strlen($arParams['NAME']) <= 0) {
            $GLOBALS['APPLICATION']->ThrowException('Введите свое имя');
            return false;
        }

        if (strlen($arParams['LAST_NAME']) <= 0) {
            $GLOBALS['APPLICATION']->ThrowException('Введите свою фамилию');
            return false;
        }

        if (strlen($_POST["USER_EMAIL"]) <= 0) {
            $GLOBALS['APPLICATION']->ThrowException('Введите E-mail');
            return false;
        }

        if (strlen($_POST["USER_EMAIL"]) > 0 && !check_email($_POST["USER_EMAIL"])) {
            $GLOBALS['APPLICATION']->ThrowException('Проверьте правильность ввода E-mail адреса');
            return false;
        }

        if (strlen($_POST['PROFILE']['PHONE']) > 0) {
            $arParams['PERSONAL_PHONE'] = $_POST['PROFILE']['PHONE'];
        }

        $arParams['LOGIN'] = $_POST["USER_EMAIL"];

        return CAeroAuth::ValidateUserProfile();

    }

    /**
     * После регистрации создаем профиль покупателя на основе введенных данных
     * ID типа плательщика определяется в $_REQUEST['PERSON_TYPE']
     * Значения для свойств профиля определяются в $_REQUEST['PROFILE'] (CODE=>VALUE)
     * @param $arParams
     * @return bool
     */
    function OnAfterUserRegister(&$arParams)
    {
        if (CAeroAuth::ValidateUserProfile()) {
            return CAeroAuth::UpdateUserProfile($arParams);
        }
        else {
            $arParams["RESULT_MESSAGE"]["MESSAGE"] = "Ошибка регистрации, попробуйте, пожалуйста, еще раз.";
            return false;
        }
    }

    /**
     * После изменения данных пользователя обновляем профиль покупателя на основе введенных данных
     * ID типа плательщика определяется в $_REQUEST['PERSON_TYPE']
     * Значения для свойств профиля определяются в $_REQUEST['PROFILE'] (CODE=>VALUE)
     * @param $arParams
     * @return bool
     */
    function OnAfterUserUpdate($arParams)
    {
        if (CAeroAuth::ValidateUserProfile()) {
            return CAeroAuth::UpdateUserProfile($arParams);
        }
    }

    /**
     * Функционал подтверждения EMAIL при попытке изменить его пользователем
     * Если предлагаемый обработчику адрес отличается от текущего,
     * то новый адрес пишется в поле UF_NEW_EMAIL, а текущий мейл остается неизменным до подтверждения адреса пользвоателем
     *
     * Генерируется почтовое событие USER_EMAIL_CHANGE_CONFIRM с параметрами:
     * NEW_EMAIL
     * OLD_EMAIL
     * CONFIRM_CODE
     *
     * @param $arParams
     * @return bool
     */
    function OnBeforeUserUpdate(&$arParams)
    {

        if ($_GET['change_password'] == 'yes') return;

		$arUser = CUser::GetByID($arParams['ID'])->Fetch();
        if (!$arUser) return;

		if($arUser['ACTIVE'] == 'Y'){
			if(!CAeroAuth::ValidateUserFields($arParams))
				return false;
		}


        if ($_GET['confirm_new_email'] == 'yes') return;

       /*  if ($arUser['EMAIL'] !== $arParams['EMAIL']) {

            $rsUsers = CUser::GetList(($by = "id"), ($order = "desc"), Array('EMAIL' => $arParams['EMAIL']));
            if ($rsUsers->SelectedRowsCount() > 0) {
                //$GLOBALS['APPLICATION']->ThrowException('Указанный вами Email уже используется другим пользователем.');
                return;
            }

            global $USER_FIELD_MANAGER;
            $sCode = md5(time() . '' . $arUser['ID']) . rand(0, 999);
            $USER_FIELD_MANAGER->Update('USER', $arUser['ID'], Array(
                'UF_NEW_EMAIL' => $arParams['EMAIL'],
                'UF_NEW_EMAIL_CODE' => $sCode,
            ));

            CEvent::Send("USER_EMAIL_CHANGE_CONFIRM", SITE_ID, Array(
                'NEW_EMAIL' => $arParams['EMAIL'],
                'OLD_EMAIL' => $arUser['EMAIL'],
                'CONFIRM_CODE' => $sCode,
            ));

            $arParams['EMAIL'] = $arUser['EMAIL'];
        } */

        return CAeroAuth::ValidateUserProfile();
    }

	/**
     * Валидация полей пользователя при изменении из личного кабинета
     */
	function ValidateUserFields(&$arParams){

		if($arParams['FORCE_UPD'])
			return true;

		$originalName = $arParams['NAME'];
		$originalLastName = $arParams['LAST_NAME'];

		$arParams['NAME'] = mb_eregi_replace('[^а-я\-]', '', $arParams['NAME']);
        $arParams['LAST_NAME'] = mb_eregi_replace('[^а-я\-]', '', $arParams['LAST_NAME']);

		if (strlen($originalName) > 0 && strlen($arParams['NAME']) <= 0) {
           $GLOBALS['APPLICATION']->ThrowException('Имя может содержать только кириллицу');
		   return false;
        }

		if (strlen($originalLastName) > 0 && strlen($arParams['LAST_NAME']) <= 0) {
           $GLOBALS['APPLICATION']->ThrowException('Фамилия может содержать только кириллицу');
		   return false;
        }

        if (strlen($arParams['NAME']) <= 0) {
            $GLOBALS['APPLICATION']->ThrowException('Введите свое имя');
            return false;
        }

        if (strlen($arParams['LAST_NAME']) <= 0) {
            $GLOBALS['APPLICATION']->ThrowException('Введите свою фамилию');
            return false;
        }

        if (strlen($arParams["EMAIL"]) <= 0) {
            $GLOBALS['APPLICATION']->ThrowException('Введите E-mail');
            return false;
        }

        if (strlen($arParams["EMAIL"]) > 0 && !check_email($arParams["EMAIL"])) {
            $GLOBALS['APPLICATION']->ThrowException('Проверьте правильность ввода E-mail адреса');
            return false;
        }

		return true;
	}

    /**
     * Валидация полей профиля, переданных в _REQUEST[PROFILE]
     * Тип плательщика передается в _REQUEST[PERSON_TYPE]
     * @return bool
     */
    function ValidateUserProfile()
    {
        global $APPLICATION;
        if (!CModule::IncludeModule('sale')) {
            $APPLICATION->ThrowException('Sale module not installed');
            return false;
        }
        if (!CModule::IncludeModule('iblock')) {
            $APPLICATION->ThrowException('Iblock module not installed');
            return false;
        }

        $arProfileIn = $_REQUEST['PROFILE'];
        $iPersonTypeID = IntVal($_REQUEST['PERSON_TYPE']);

        if (!is_array($arProfileIn) || empty($arProfileIn) || $iPersonTypeID <= 0) return;

        $rsProps = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"),
            Array(
                "ACTIVE" => "Y",
                "IBLOCK_ID" => IBLOCK_ID_AGENTS,
            )
        );

        $arProps = Array();
        while ($arProp = $rsProps->Fetch()) {
            $arProps[$arProp['CODE']] = $arProp;
        }

        foreach ($arProfileIn as $sCode => $sValue) {
            $sValue = trim($sValue);
            if ($arProps[$sCode]['IS_REQUIRED'] == 'Y' && strlen($sValue) <= 0) {
                $APPLICATION->ThrowException('Не заполнено поле "' . $arProps[$sCode]['NAME'] . '"');
                return false;
            }
        }
        return true;
    }

    /**
     * После имзенения информации о пользователе создаем или обновляем профиль покупателя на основе введенных данных
     * ID типа плательщика определяется в $_REQUEST['PERSON_TYPE']
     * Значения для свойств профиля определяются в $_REQUEST['PROFILE'] (CODE=>VALUE)
     * @param $arParams
     * @return bool
     */
    function UpdateUserProfile($arParams)
    {
        global $USER, $USER_FIELD_MANAGER;
        $iUserID = isset($arParams['USER_ID']) ? IntVal($arParams['USER_ID']) : IntVal($arParams['ID']);

        if (!CModule::IncludeModule('sale')) return false;
        if (!CModule::IncludeModule('iblock')) return false;

        if ($iUserID > 0 && is_array($_REQUEST['PROFILE'])) {

            $iPersonTypeID = IntVal($_REQUEST['PERSON_TYPE']);

            $arUser = CUser::GetByID($iUserID)->Fetch();

            if (empty($arUser['UF_PAYER_TYPE'])) { // тип плательщика можно задать только при регистрации
                $USER_FIELD_MANAGER->Update('USER', $iUserID, Array(
                    'UF_PAYER_TYPE' => $iPersonTypeID,
                ));
            } else {
                $iPersonTypeID = IntVal($arUser['UF_PAYER_TYPE']);
            }

            $arProfileIn = $_REQUEST['PROFILE'];
            if (!is_array($arProfileIn) || empty($arProfileIn)) return false;

			if(empty($arProfileIn['COMPANY'])){//ИП
				$arProfileIn['COMPANY'] = "ИП ".$_REQUEST['USER_LAST_NAME'].' '.$_REQUEST['USER_NAME'];
			}

            $arFields = Array(
                'NAME' => $arProfileIn['COMPANY'],
                'ACTIVE' => 'Y',
                'XML_ID' => $arProfileIn['INN'],
                'IBLOCK_ID' => IBLOCK_ID_AGENTS,
                'PROPERTY_VALUES' => Array(
                    // юридический адрес
                    'CITY_LEGAL' => $arProfileIn['CITY_LEGAL'],
                    'LOCATION_LEGAL' => $arProfileIn['LOCATION_LEGAL'],
                    'STREET_LEGAL' => $arProfileIn['STREET_LEGAL'],
                    'ZIP_LEGAL' => $arProfileIn['ZIP_LEGAL'],
                    'HOUSE_LEGAL' => $arProfileIn['HOUSE_LEGAL'],
                    'HOUSING_LEGAL' => $arProfileIn['HOUSING_LEGAL'],
                    'OFFICE_LEGAL' => $arProfileIn['OFFICE_LEGAL'],
                    'STAGE_LEGAL' => $arProfileIn['STAGE_LEGAL'],
                    'COMMENT_LEGAL' => $arProfileIn['COMMENT_LEGAL'],

                    // фактический адрес
                    'CITY_ACTUAL' => $arProfileIn['CITY_ACTUAL'],
                    'LOCATION_ACTUAL' => $arProfileIn['LOCATION_ACTUAL'],
                    'STREET_ACTUAL' => $arProfileIn['STREET_ACTUAL'],
                    'ZIP_ACTUAL' => $arProfileIn['ZIP_ACTUAL'],
                    'HOUSE_ACTUAL' => $arProfileIn['HOUSE_ACTUAL'],
                    'HOUSING_ACTUAL' => $arProfileIn['HOUSING_ACTUAL'],
                    'OFFICE_ACTUAL' => $arProfileIn['OFFICE_ACTUAL'],
                    'STAGE_ACTUAL' => $arProfileIn['STAGE_ACTUAL'],
                    'COMMENT_ACTUAL' => $arProfileIn['COMMENT_ACTUAL'],

                    // реквизиты
                    'BANK' => $arProfileIn['BANK'],
                    'BIK' => $arProfileIn['BIK'],
                    'INN' => $arProfileIn['INN'],
                    'KPP' => $arProfileIn['KPP'],
                    'ACCOUNT_COR' => $arProfileIn['ACCOUNT_COR'],
                    'ACCOUNT' => $arProfileIn['ACCOUNT'],
                    'OGRN' => $arProfileIn['OGRN'],

                    // контакты
                    'PHONE' => $arProfileIn['PHONE'],
                    'FAX' => $arProfileIn['FAX'],
                    'EMAIL' => $arParams['EMAIL'],

                ),
            );

            if ($arProfileIn['ACTUAL_EQUALS_LEGAL'] == 'Y') {
                $arFields['PROPERTY_VALUES']['ACTUAL_EQUALS_LEGAL'] = 34824;
                $arFields['PROPERTY_VALUES']['CITY_ACTUAL'] = $arProfileIn['CITY_LEGAL'];
                $arFields['PROPERTY_VALUES']['LOCATION_ACTUAL'] = $arProfileIn['LOCATION_LEGAL'];
                $arFields['PROPERTY_VALUES']['STREET_ACTUAL'] = $arProfileIn['STREET_LEGAL'];
                $arFields['PROPERTY_VALUES']['ZIP_ACTUAL'] = $arProfileIn['ZIP_LEGAL'];
                $arFields['PROPERTY_VALUES']['HOUSE_ACTUAL'] = $arProfileIn['HOUSE_LEGAL'];
                $arFields['PROPERTY_VALUES']['HOUSING_ACTUAL'] = $arProfileIn['HOUSING_LEGAL'];
                $arFields['PROPERTY_VALUES']['OFFICE_ACTUAL'] = $arProfileIn['OFFICE_LEGAL'];
                $arFields['PROPERTY_VALUES']['STAGE_ACTUAL'] = $arProfileIn['STAGE_LEGAL'];
                $arFields['PROPERTY_VALUES']['COMMENT_ACTUAL'] = $arProfileIn['COMMENT_LEGAL'];
            }

            $arFilter = Array(
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $arFields['IBLOCK_ID'],
                'PROPERTY_INN' => $arFields['PROPERTY_VALUES']['INN'],
                'PROPERTY_KPP' => $arFields['PROPERTY_VALUES']['KPP'],
            );

            if ($arUser['UF_COMPANY_ID'] > 0) {
                $arFilter['=ID'] = $arUser['UF_COMPANY_ID'];
            }

            $arSelect = Array('ID', 'XML_ID', 'NAME');
            foreach ($arFields['PROPERTY_VALUES'] as $code => $value) {
                $arSelect[] = 'PROPERTY_' . $code;
            }

            $arExisting = CIBlockElement::GetList(
                Array(),
                $arFilter,
                false, Array('nTopCount' => 1),
                $arSelect
            )->GetNext(false, false);

            /**
             * Если контрагент уже существует, отправляем запрос на подтверждение админам
             * Если агент существует и уже привязан к пользователю, запускаем бизнес-процесс изменения
             * Если агент не существует, создаем его
             */
            $obj = new CIBlockElement();
            if ($arExisting) {
                $arUser = CUser::GetByID($iUserID)->Fetch();

                if ($arUser['UF_COMPANY_ID'] != $arExisting['ID']) {
                    $arFields['ACTIVE'] = 'N';
                    $arFields['ID'] = $obj->Add($arFields);

                    $USER_FIELD_MANAGER->Update('USER', $iUserID, Array(
                        'UF_COMPANY_ID' => $arFields['ID'],
                    ));
                    CEvent::Send("AERO_INN_CONFIRM", SITE_ID, Array(
                        'COMPANY_ID' => $arExisting['ID'],
                        'COMPANY_NAME' => $arExisting['NAME'],
                        'COMPANY_INN' => $arExisting['XML_ID'],
                        'USER_ID' => $arUser['ID'],
                        'USER_NAME' => $arUser['NAME'] . ' ' . $arUser['LAST_NAME'],
                        'USER_EMAIL' => $arUser['EMAIL'],
                    ));
                } else {

                    /**
                     * Првоеряем, были ли внесены изменения в св-ва
                     * Если да, то обновляем элемент в режиме документооборота
                     */
                    foreach ($arFields['PROPERTY_VALUES'] as $code => $value) {
                        if ($code == 'ACTUAL_EQUALS_LEGAL') $value = 'Y';
                        $key = 'PROPERTY_' . $code . '_VALUE';
                        if ($value == $arExisting[$key]) {
                            unset($arFields['PROPERTY_VALUES'][$code]);
                        }
                    }

                    if (!empty($arFields['PROPERTY_VALUES']) || htmlspecialchars_decode($arExisting['NAME']) != $arFields['NAME']) {
                        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/reg.log', print_r(htmlspecialchars_decode($arExisting['NAME']) . '===' . $arFields['NAME'], true) . "\n\n", FILE_APPEND);
                        $arFields['WF_STATUS_ID'] = 3;
                        $arFields['WF_COMMENTS'] = "Изменено пользователем:\n";

                        if (htmlspecialchars_decode($arExisting['NAME']) != $arFields['NAME']) {
                            $arFields['WF_COMMENTS'] .= 'Название: ' . $arExisting['NAME'] . ': ' . $arFields['NAME'] . "\n";
                        }

                        foreach ($arFields['PROPERTY_VALUES'] as $code => $value) {
                            $arFields['WF_COMMENTS'] .= $code . ': ' . $value . "\n";
                        }
                        $obj->Update($arExisting['ID'], $arFields, true);
                    }

                }
            }
            else {
                $arFields['ID'] = $obj->Add($arFields);
                if (!$arFields['ID']) {
                    $GLOBALS['APPLICATION']->ThrowException($obj->LAST_ERROR);
                    $arParams['RESULT_MESSAGE'] = $obj->LAST_ERROR;
                    return false;
                }
                $USER_FIELD_MANAGER->Update('USER', $iUserID, Array(
                    'UF_COMPANY_ID' => $arFields['ID'],
                ));
            }
        }
        else {
            $GLOBALS['APPLICATION']->ThrowException('Данные профиля не заполнены или не указан ID пользователя');
            return false;
        }
        return true;
    }

}