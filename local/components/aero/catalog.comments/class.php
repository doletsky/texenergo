<?php
class CAeroCatalogComments extends CBitrixComponent {

    public function onPrepareComponentParams($arParams) {
        //prepare params
        return $arParams;
    }

    public function executeComponent() {

        global $USER, $APPLICATION;

        if (!CModule::IncludeModule("iblock")) {
            ShowError("Не установлен модуль iblock");
            return;
        }

        $this->arResult["USER"]["AUTH"] = $USER->IsAuthorized();

        $this->arResult["USER"]["NAME"] = "";
        $this->arResult["USER"]["EMAIL"] = "";

        if ($this->arResult["USER"]["AUTH"]) {
            $this->arResult["USER"]["NAME"] = $USER->GetFullName();
            $this->arResult["USER"]["EMAIL"] = $USER->GetEmail();
        }

		if($_GET['success'] == 'true')
			$this->arResult['COMMENT_ADDED'] = true;

        $this->arResult["ERROR"] = "";

        if (isset($_REQUEST['add']) && $_REQUEST['add'] == "true") {

            $captcha = true;

            if(!$this->arResult["USER"]["AUTH"]) {
                if (!$APPLICATION->CaptchaCheckCode($_POST["captcha_word"], $_POST["captcha_code"])) {
                    $captcha = false;
                }
            }

            if ($_REQUEST['name'] != "" && $_REQUEST['description'] != "" && $_REQUEST['email'] != "" && $captcha == true) {

                $comment = new CIBlockElement;

                $PROP = array(
                    "USER_NAME" =>$_REQUEST['name'],
                    "USER_EMAIL" =>($_REQUEST['email'] != "") ? $_REQUEST['email'] : "",
                    "RAITING" => $_REQUEST['rating'],
                    "HELPFUL" => 0,
                    "NOT_HELPFUL" => 0,
                    'ELEMENT_ID' => $this->arParams['ELEMENT_ID'],
                );

                $arCommentArray = Array(
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID"      => $this->arParams['IBLOCK_ID'],
                    "PROPERTY_VALUES"=> $PROP,
                    "NAME"           => "Комментарий к товару #".$this->arParams['ELEMENT_ID'],
                    "ACTIVE"         => "N",
                    "DETAIL_TEXT"    => $_REQUEST['description'],
                );

                ;

                if ($COMMENT_ID = $comment->Add($arCommentArray)) {
				
                    /*$arSelect = Array(
                        'ID',
                        'PROPERTY_RAITING',
                    );

                    $rsComments = CIBlockElement::GetList(
                        Array(
                            'ID' => 'desc'
                        ), Array(
                            'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
                            'PROPERTY_ELEMENT_ID' => $this->arParams['ELEMENT_ID'],
                            'ACTIVE' => 'Y'
                        ),
                        false,
                        false,
                        $arSelect
                    );

                    $raiting = 0;
                    $count = 0;

                    while ($arComments = $rsComments->Fetch()) {

                        $raiting += $arComments['PROPERTY_RAITING_VALUE'];
                        $count++;

                    }

                    if ($raiting > 0) {
                        $raiting = round(($raiting + 5) / ($count + 1));
                        CIBlockElement::SetPropertyValues($this->arParams['ELEMENT_ID'], $this->arParams['ELEMENT_IBLOCK_ID'], $raiting, "RAITING");
                    }

                    CIBlockElement::SetPropertyValues($this->arParams['ELEMENT_ID'], $this->arParams['ELEMENT_IBLOCK_ID'], $count, "COMMENTS_CNT");*/

                    LocalRedirect($this->arParams['PAGE_URL']."?success=true#id".$COMMENT_ID);
                }
				
            } else {

                if ($_REQUEST['name'] == "") {
                    $this->arResult["ERROR"] .= "<br />Вы не ввели Имя!";
                }

                if ($_REQUEST['email'] == "") {
                    $this->arResult["ERROR"] .= "<br />Вы не ввели E-mail!";
                }

                if ($_REQUEST['description'] == "") {
                    $this->arResult["ERROR"] .= "<br />Вы не написали отзыв!";
                }

                if(!$this->arResult["USER"]["AUTH"]) {
                    if (!$APPLICATION->CaptchaCheckCode($_POST["captcha_word"], $_POST["captcha_code"])) {
                        $this->arResult["ERROR"] .= "<br />Не верно заполнен код подверждения!";
                    }
                }

            }

        }

        $arSelect = Array(
            'ID',
            'NAME',
            'DATE_CREATE',
            'DETAIL_TEXT',
            'PROPERTY_USER_NAME',
            'PROPERTY_USER_EMAIL',
            'PROPERTY_RAITING',
            'PROPERTY_HELPFUL',
            'PROPERTY_NOT_HELPFUL'
        );

        $rsComments = CIBlockElement::GetList(
            Array(
                'ID' => 'desc'
            ), Array(
                'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
                'PROPERTY_ELEMENT_ID' => $this->arParams['ELEMENT_ID'],
                'ACTIVE' => 'Y'
            ),
            false,
            false,
            $arSelect
        );

        $count = 0;
        $raiting = 0;

        while ($arComments = $rsComments->Fetch()) {

            $CropStr = CropStr($arComments['DETAIL_TEXT'], 255);

            if ($CropStr != "") {
                $CropStr = str_replace("\n", "<br />", $CropStr);
            }

            $this->arResult['COMMENTS'][] = array(
                "USER_NAME" => $arComments['PROPERTY_USER_NAME_VALUE'],
                "DESCRIPTION" => str_replace("\n", "<br />", $arComments['DETAIL_TEXT']),
                "CROP_DESCRIPTION" => $CropStr,
                "RAITING" => $arComments['PROPERTY_RAITING_VALUE'],
                "HELPFUL" =>  $arComments['PROPERTY_HELPFUL_VALUE'],
                "DATE" => $arComments['DATE_CREATE'],
                "ID" => $arComments['ID'],
            );

            $raiting += $arComments['PROPERTY_RAITING_VALUE'];
            $count++;

        }

        $this->arResult["COMMENTS_CNT"] = $count;
        $dbRaiting = CIBlockElement::GetProperty($this->arParams['ELEMENT_IBLOCK_ID'], $this->arParams['ELEMENT_ID'], array(), Array("CODE"=>"RAITING"));
        if($arRaiting = $dbRaiting->Fetch()) {
            $this->arResult["RAITING"] = IntVal($arRaiting["VALUE"]);
        }else {
            $this->arResult["RAITING"] = false;
        }

        //$this->arResult["RAITING"] = round(($raiting + 5) / ($count + 1));

        $this->arResult["CAPTCHA_CODE"] = "";

        if (!$this->arResult["USER"]["AUTH"]) {

            include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");
            $cpt = new CCaptcha();
            $captchaPass = COption::GetOptionString("main", "captcha_password", "");

            if (strLen($captchaPass) <= 0) {
                $captchaPass = randString(10);
                COption::SetOptionString("main", "captcha_password", $captchaPass);
            }

            $cpt->SetCodeCrypt($captchaPass);
            $this->arResult["CAPTCHA_CODE"] = htmlspecialcharsbx($cpt->GetCodeCrypt());

        }

        $this->includeComponentTemplate();
    }

}