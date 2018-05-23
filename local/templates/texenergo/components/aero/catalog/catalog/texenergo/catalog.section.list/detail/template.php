<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="catalog">

    <div class="product-category">
        <div class="subsections" lang="ru">
            <? foreach ($arResult['SECTIONS'] as $arSection): ?>


                <? /*<h1 class="cat-subheading"><?= $arSection['NAME']; ?></h1>*/ ?>

                <? //foreach ($arSection['SECTIONS'] as $arSubSection): ?>
                <div class="cat-unit">
                    <figure>
                        <? if ($arSection["PICTURE"]): ?>
                            <img src="<?= $arSection["PICTURE"]; ?>"
                                 alt="<?= $arSection['NAME']; ?>">
                        <? endif; ?>
                        <figcaption>
                            <a href="<?= $arSection['SECTION_PAGE_URL']; ?>">
                                <?= preg_replace('/([^\s-]{9})([^\s-]{9})/u', '$1&shy;$2', $arSection['NAME']); ?>
                            </a>
                            <span class="total-items">(<?= $arSection['ELEMENT_CNT']; ?>)</span>
                        </figcaption>
                    </figure>
                </div>
                <? // endforeach; ?>

            <? endforeach; ?>
        </div>
    </div>

</div>
