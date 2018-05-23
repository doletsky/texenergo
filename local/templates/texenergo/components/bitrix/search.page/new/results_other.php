<? if (!empty($arResult['OTHER'])): ?>

    <h2>Прочие результаты</h2>
    <div class="search-list">
        <? foreach ($arResult['OTHER'] as $arItem): ?>
            <div class="item">
                <a href="<?= $arItem['URL']; ?>" class="title"><?= $arItem['TITLE_FORMATED']; ?></a>

                <div class="text"><?= $arItem['BODY_FORMATED']; ?></div>
            </div>
        <? endforeach; ?>
        <div class="clear"></div>
    </div>


<? endif; ?>