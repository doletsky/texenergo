<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();?>

<div class="formSocial">
    <span class="formSocial-span">Войти при помощи соц. сетей <br>(только для физических лиц):</span>

    <div class="formSocial-icons">
        <ul class="formSocial-ul clearfix">
            <? foreach ($arParams["~AUTH_SERVICES"] as $service): ?>
                <li class="formSocial-li">
                    <?= $service['FORM_HTML']; ?>
                </li>
            <? endforeach ?>
        </ul>
    </div>
</div>