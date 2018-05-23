<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<? if (!empty($arParams["~AUTH_SERVICES"])): ?>
    <div class="subSocials">
        <span class="subSocials-span">Вход при помощи соц. сетей:</span>
        <? foreach ($arParams["~AUTH_SERVICES"] as $service): ?>
            <div class="subSocial">
                <div
                    class="subSocial-image subSocial-<?= $service['ICON']; ?><? if ($service['ACTIVE'] == 'Y'): ?> active<? endif; ?>"></div>
                <? if ($service['ACTIVE'] == 'Y'): ?>
                    <a href="?action=delete&user_id=<?= $service["DB_USER_ID"] . "&" . bitrix_sessid_get() ?>"
                       class="subSwitch subSwitch-on"></a>
                <? else: ?>
                    <div class="subSwitch" <?= $service["FORM_HTML"]["ON_CLICK"]; ?>></div>
                <?endif; ?>
            </div>
        <? endforeach ?>
    </div>
<? endif; ?>
