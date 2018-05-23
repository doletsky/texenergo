<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (count($arResult["ITEMS"]) > 0) {
    ?>

    <section class="main-brands">
        <div class="box-arrow"></div>
        <? foreach ($arResult["ITEMS"] as $arElement) { ?>
            <article>
                <figure>
                    <a href="<?=$arElement['DETAIL_PAGE_URL']?>" class="brand-logo">
                        <? if ($arElement["PREVIEW_PICTURE"]["SRC"]): ?>
                            <img src="<?= $arElement["PREVIEW_PICTURE"]["SRC"] ?>" alt="<?= $arElement["NAME"] ?>">
                        <? else: ?>
                            <?= $arElement['NAME']; ?>
                        <?endif; ?>
                    </a>
                </figure>
                <section class="brand-text"><?= $arElement["PREVIEW_TEXT"] ?></section>
            </article>

        <? } ?>
    </section>

<?php
}
?>
