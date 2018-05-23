<?php

//AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserRegisterHandler");

AddEventHandler("main", "OnBeforeUserUpdate", "OnBeforeUserUpdateHandler");

function OnBeforeUserUpdateHandler(&$arParams)
{
	$arParams['LOGIN'] = $arParams['EMAIL'];
}