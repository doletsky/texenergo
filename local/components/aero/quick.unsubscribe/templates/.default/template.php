<div class="subscription-form-wrap">
	<?if($arResult['SUCCESS']):?>
		<p class="note-ok">Подписка помечена как неактивная. Рассылки на адрес <?=$arResult['EMAIL']?> производиться не будут.</p>
	<?else:?>
		<p class="note-fail">При попытке отписаться от рассылки произошла ошибка.</p>
	<?endif;?>
</div>