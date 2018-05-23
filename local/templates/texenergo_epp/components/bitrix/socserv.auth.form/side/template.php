<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<? if (!empty($arParams["~AUTH_SERVICES"])): ?>
    <section class="sidebarSocials">
        <span>Вход при помощи соц. сетей:</span>
        <? foreach ($arParams["~AUTH_SERVICES"] as $service): ?>
            <div class="b-sidebarRow">
                <img src="<?= SITE_TEMPLATE_PATH; ?>/img/account/<?= $service['ICON']; ?>.png"
                     alt="<?= $service['NAME']; ?>">
                <span><?= $service['NAME']; ?></span>
                <? if ($service['ACTIVE'] == 'Y'): ?>
                    <a href="?action=delete&user_id=<?= $service["DB_USER_ID"] . "&" . bitrix_sessid_get() ?>"
                       class="switch-socials is-switchSocials-on"></a>
                <? else: ?>
                    <div class="switch-socials" <?= $service["FORM_HTML"]["ON_CLICK"]; ?>></div>
                <? endif; ?>
            </div>
        <? endforeach ?>
    </section>
<? endif; ?>
