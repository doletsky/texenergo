<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php
$queryWord = isset ($arResult['REQUEST']['QUERY']) ? $arResult['REQUEST']['QUERY'] : '';
$count = 0;
$actionCount = 0;
?>
<? //='<pre>'.print_r($arResult["PRODUCTS"], true).'</pre>';?>

<? if (!empty($arResult["PRODUCTS"])) : ?>
    <article class="search-category clearfix">
        <aside>
            <a href="/catalog/">Интернет-магазин</a>
        </aside>

        <section>
            <ul>
                <? foreach ($arResult["PRODUCTS"] as $arItem) : ?>
                    <li class="search-result">
                        <? if ($arItem["PICTURE"]) : ?>
                            <a href="<?= $arItem["URL"] ?>" class="picture">
                                <img src="<?= $arItem["PICTURE"] ?>" alt="<?= $arItem["TITLE"] ?>">
                            </a>
                        <? endif; ?>
                        <a href="<?= $arItem["URL"] ?>" class="title">
                            <? if ($arItem['IS_SPECIAL']): ?>
                                <span class="promo">Акция!</span>
                            <? endif; ?>
                            <?= $arItem["TITLE_FORMATED"] ?>
                        </a>
                        <? if ($arItem['HAVE_ANALOGS']): ?>
                            <a href="<?= $arItem["URL"] ?>" class="analogs-link">Аналоги</a>
                        <? endif; ?>
                    </li>
                <? endforeach; ?>
            </ul>
            <? if ($arResult["PRODUCTS_TOTAL"] > 3): ?>
                <a href="/search/?q=<?= $queryWord; ?>" class="more">
                    еще найдено товаров <?= (int)$arResult["PRODUCTS_TOTAL"] - 3 ?> »</a>
            <? endif; ?>
        </section>
    </article>
<? endif; ?>
