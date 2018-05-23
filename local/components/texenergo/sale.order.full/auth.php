<?php

$arResult["USER_LOGIN"] = ((strlen($arResult["POST"]["USER_LOGIN"]) > 0) ? $arResult["POST"]["USER_LOGIN"] : htmlspecialcharsbx(${COption::GetOptionString("main", "cookie_name", "BITRIX_SM")."_LOGIN"}));
$arResult["AUTH"]["captcha_registration"] = ((COption::GetOptionString("main", "captcha_registration", "N") == "Y") ? "Y" : "N");
if($arResult["AUTH"]["captcha_registration"] == "Y")
	$arResult["AUTH"]["capCode"] = htmlspecialcharsbx($APPLICATION->CaptchaGetCode());
$arResult["AUTH"]["new_user_registration"] = ((COption::GetOptionString("main", "new_user_registration", "Y") == "Y") ? "Y" : "N");

if($_SERVER["REQUEST_METHOD"] == "POST" && ($arParams["DELIVERY_NO_SESSION"] == "N" || check_bitrix_sessid()))
{
	if ($arResult["POST"]["do_authorize"] == "Y")
	{
		if (strlen($arResult["POST"]["USER_LOGIN"]) <= 0)
			$arResult["ERROR_MESSAGE"] .= GetMessage("STOF_ERROR_AUTH_LOGIN").".<br />";

		if (strlen($arResult["ERROR_MESSAGE"]) <= 0)
		{
			$arAuthResult = $USER->Login($arResult["POST"]["~USER_LOGIN"], $arResult["POST"]["~USER_PASSWORD"], "N");
			if ($arAuthResult != False && $arAuthResult["TYPE"] == "ERROR")
				$arResult["ERROR_MESSAGE"] .= GetMessage("STOF_ERROR_AUTH").((strlen($arAuthResult["MESSAGE"]) > 0) ? ": ".$arAuthResult["MESSAGE"] : ".<br />" );
			else
				LocalRedirect($arParams["PATH_TO_ORDER"]);

		}
	}
	elseif ($arResult["POST"]["do_register"] == "Y" && $arResult["AUTH"]["new_user_registration"] == "Y")
	{
		if (strlen($arResult["POST"]["NEW_NAME"]) <= 0)
			$arResult["ERROR_MESSAGE"] .= GetMessage("STOF_ERROR_REG_NAME").".<br />";

		if (strlen($arResult["POST"]["NEW_LAST_NAME"]) <= 0)
			$arResult["ERROR_MESSAGE"] .= GetMessage("STOF_ERROR_REG_LASTNAME").".<br />";

		if (strlen($arResult["POST"]["NEW_EMAIL"]) <= 0)
			$arResult["ERROR_MESSAGE"] .= GetMessage("STOF_ERROR_REG_EMAIL").".<br />";
		elseif (!check_email($arResult["POST"]["NEW_EMAIL"]))
		$arResult["ERROR_MESSAGE"] .= GetMessage("STOF_ERROR_REG_BAD_EMAIL").".<br />";

		if ($arResult["POST"]["NEW_GENERATE"] == "Y")
		{
			$arResult["POST"]["~NEW_LOGIN"] = $arResult["POST"]["~NEW_EMAIL"];

			$pos = strpos($arResult["POST"]["~NEW_LOGIN"], "@");
			if ($pos !== false)
				$arResult["POST"]["~NEW_LOGIN"] = substr($arResult["POST"]["~NEW_LOGIN"], 0, $pos);

			if (strlen($arResult["POST"]["~NEW_LOGIN"]) > 47)
				$arResult["POST"]["~NEW_LOGIN"] = substr($arResult["POST"]["~NEW_LOGIN"], 0, 47);

			if (strlen($arResult["POST"]["~NEW_LOGIN"]) < 3)
				$arResult["POST"]["~NEW_LOGIN"] .= "_";

			if (strlen($arResult["POST"]["~NEW_LOGIN"]) < 3)
				$arResult["POST"]["~NEW_LOGIN"] .= "_";

			$dbUserLogin = CUser::GetByLogin($arResult["POST"]["~NEW_LOGIN"]);
			if ($arUserLogin = $dbUserLogin->Fetch())
			{
				$newLoginTmp = $arResult["POST"]["~NEW_LOGIN"];
				$uind = 0;
				do
				{
					$uind++;
					if ($uind == 10)
					{
						$arResult["POST"]["~NEW_LOGIN"] = $arResult["POST"]["~NEW_EMAIL"];
						$newLoginTmp = $arResult["POST"]["~NEW_LOGIN"];
					}
					elseif ($uind > 10)
					{
						$arResult["POST"]["~NEW_LOGIN"] = "buyer".time().GetRandomCode(2);
						$newLoginTmp = $arResult["POST"]["~NEW_LOGIN"];
						break;
					}
					else
					{
						$newLoginTmp = $arResult["POST"]["~NEW_LOGIN"].$uind;
					}
					$dbUserLogin = CUser::GetByLogin($newLoginTmp);
				}
				while ($arUserLogin = $dbUserLogin->Fetch());
				$arResult["POST"]["~NEW_LOGIN"] = $newLoginTmp;
			}

			$def_group = COption::GetOptionString("main", "new_user_registration_def_group", "");
			if($def_group!="")
			{
				$GROUP_ID = explode(",", $def_group);
				$arPolicy = $USER->GetGroupPolicy($GROUP_ID);
			}
			else
			{
				$arPolicy = $USER->GetGroupPolicy(array());
			}

			$password_min_length = intval($arPolicy["PASSWORD_LENGTH"]);
			if($password_min_length <= 0)
				$password_min_length = 6;
			$password_chars = array(
					"abcdefghijklnmopqrstuvwxyz",
					"ABCDEFGHIJKLNMOPQRSTUVWXYZ",
					"0123456789",
			);
			if($arPolicy["PASSWORD_PUNCTUATION"] === "Y")
				$password_chars[] = ",.<>/?;:'\"[]{}\|`~!@#\$%^&*()-_+=";
			$arResult["POST"]["~NEW_PASSWORD"] = $arResult["POST"]["~NEW_PASSWORD_CONFIRM"] = randString($password_min_length, $password_chars);
		}
		else
		{
			if (strlen($arResult["POST"]["NEW_LOGIN"]) <= 0)
				$arResult["ERROR_MESSAGE"] .= GetMessage("STOF_ERROR_REG_FLAG").".<br />";

			if (strlen($arResult["POST"]["NEW_PASSWORD"]) <= 0)
				$arResult["ERROR_MESSAGE"] .= GetMessage("STOF_ERROR_REG_FLAG1").".<br />";

			if (strlen($arResult["POST"]["NEW_PASSWORD"]) > 0 && strlen($arResult["POST"]["NEW_PASSWORD_CONFIRM"]) <= 0)
				$arResult["ERROR_MESSAGE"] .= GetMessage("STOF_ERROR_REG_FLAG1").".<br />";

			if (strlen($arResult["POST"]["NEW_PASSWORD"]) > 0
					&& strlen($arResult["POST"]["NEW_PASSWORD_CONFIRM"]) > 0
					&& $arResult["POST"]["NEW_PASSWORD"] != $arResult["POST"]["NEW_PASSWORD_CONFIRM"])
				$arResult["ERROR_MESSAGE"] .= GetMessage("STOF_ERROR_REG_PASS").".<br />";
		}

		if (strlen($arResult["ERROR_MESSAGE"]) <= 0)
		{
			$arAuthResult = $USER->Register($arResult["POST"]["~NEW_LOGIN"], $arResult["POST"]["~NEW_NAME"], $arResult["POST"]["~NEW_LAST_NAME"], $arResult["POST"]["~NEW_PASSWORD"], $arResult["POST"]["~NEW_PASSWORD_CONFIRM"], $arResult["POST"]["~NEW_EMAIL"], LANG, $arResult["POST"]["~captcha_word"], $arResult["POST"]["~captcha_sid"]);
			if ($arAuthResult != False && $arAuthResult["TYPE"] == "ERROR")
				$arResult["ERROR_MESSAGE"] .= GetMessage("STOF_ERROR_REG").((strlen($arAuthResult["MESSAGE"]) > 0) ? ": ".$arAuthResult["MESSAGE"] : ".<br />" );
			else
			{
				if ($USER->IsAuthorized())
				{
					if($arParams["SEND_NEW_USER_NOTIFY"] == "Y")
						CUser::SendUserInfo($USER->GetID(), SITE_ID, GetMessage("INFO_REQ"), true);
					LocalRedirect($arParams["PATH_TO_ORDER"]);
				}
				else
				{
					$arResult["ERROR_MESSAGE"] .= GetMessage("STOF_ERROR_REG_CONFIRM")."<br />";
				}
			}
		}
	}
}
?>