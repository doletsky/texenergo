<? if (!empty($arResult['NEWS'])): ?>
    <h2>Новости</h2>
    <div class="search-grid">
        <? foreach ($arResult['NEWS'] as $k => $arItem): ?>
            <div class="item">
                <a href="<?= $arItem['URL']; ?>" class="photo">
                    <img src="<?= $arItem['PICTURE']; ?>" alt="<?= $arItem['TITLE']; ?>">
                </a>
                <a href="<?= $arItem['URL']; ?>" class="title"><?= $arItem['TITLE_FORMATED']; ?></a>
                <time class="date">
                    <?= FormatDate('d F Y', MakeTimeStamp($arItem['DATE_FROM'], 'YYYY-MM-DD HH:MI:SS')); ?>
                </time>
                <div class="text"><?= $arItem['BODY_FORMATED']; ?></div>
            </div>
            <? if (($k + 1) % 4 == 0): ?>
                <div class="clear"></div>
            <? endif; ?>
        <? endforeach; ?>
        <div class="clear"></div>
    </div>


<? endif; ?>