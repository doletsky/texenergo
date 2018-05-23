<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<section class="cat-block cat-block-separate">
    <header class="cat-header border-bottom">
        <h2>Скачать каталоги популярных производителей</h2>
    </header>
    <div class="brands-slider">
        <ul class="owl-slider j_owl_slider_4">
            <? foreach ($arResult["ITEMS"] as $arItem): ?>
                <li>
                    <? if ($arItem["PICTURE"]): ?>
                        <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" title="<?= $arItem['NAME']; ?>">
                            <img src="<?= $arItem["PICTURE"] ?>" alt="<?= $arItem["NAME"] ?>">
                        </a>
                    <? else: ?>
                        <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="text" title="<?= $arItem['NAME']; ?>">
                            <?= TruncateText($arItem['NAME'], 20); ?>
                        </a>
                    <? endif ?>
                </li>
            <? endforeach; ?>
        </ul>
    </div>
</section>
