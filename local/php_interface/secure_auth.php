<?php
/**
 * Редирект авторизованных пользователей на https
 */
global $USER;
$authSecureEnabled = COption::GetOptionString('aero.auth', 'auth_secure_enabled', 'N');
if ($authSecureEnabled == 'Y') {
    if ($USER->isAuthorized() && $_SERVER['HTTP_HTTPS'] !== 'YES') {
        LocalRedirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    }

    if (!$USER->isAuthorized() && $_SERVER['HTTP_HTTPS'] == 'YES') {
        LocalRedirect('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    }
}
