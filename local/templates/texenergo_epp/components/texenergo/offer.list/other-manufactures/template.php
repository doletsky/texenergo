<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (count($arResult["ITEMS"]) > 0):

    $count = 0;

    ?>
    <section class="other-brands">

        <div class="switch-wrap">
            <div id="local-manufactures-switch" class="switch"></div>
            Только отечественные производители
        </div>

        <div class="brands table">
            <? foreach ($arResult["ITEMS"] as $arElement): ?>

            <a href="<?=$arElement['DETAIL_PAGE_URL']?>" class="brand<? if ($arElement['PROPERTIES']['RUSSIAN_VENDOR']['VALUE']): ?> rus<? endif; ?>"
               title="<?= $arElement["NAME"] ?>">
                <? if ($arElement["PREVIEW_PICTURE"]["SRC"]): ?>
                    <img src="<?= $arElement["PREVIEW_PICTURE"]["SRC"] ?>" alt="<?= $arElement["NAME"] ?>">
                <? else: ?>
                    <?= $arElement['NAME']; ?>
                <?endif; ?>
            </a>

        <? $count++; ?>
        <? if ($count == 12): ?>
            <div class="more" style="display:none;">
                <? endif; ?>
                <? endforeach; ?>
            </div>
            <? if (count($arResult['ITEMS']) >= 12): ?>
        </div>
        <a href="" id="show-all-brands" class="show-all-brands">показать всех производителей</a>
        <? endif; ?>
    </section>

<? endif; ?>
