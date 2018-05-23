<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult["ITEMS"])): ?>
    <section class="brand-block brand-block-list catalog-brand">
        <div class="cat-header">
            <h2>Скачать каталоги популярных производителей</h2>
        </div>
        <ul>
            <? foreach ($arResult["ITEMS"] as $arElement): ?>
                <li>
                    <a href="<?=$arElement['DETAIL_PAGE_URL']?>">
                        <? if (is_array($arElement["DETAIL_PICTURE"])): ?>
                            <img src="<?= $arElement["DETAIL_PICTURE"]["SRC"] ?>" alt="<?=$arElement['NAME']?>">
                        <? else: ?>
                            <?= $arElement['NAME']; ?>
                        <?endif; ?>
                    </a>
                </li>
            <? endforeach; ?>
        </ul>
    </section>
<? endif; ?>