<?if (count($arResult['CITIES']) > 0):?>

<div class="contactCity">
	<div class="b-contactCity">
		<?foreach ($arResult['CITIES'] as $arCity):?>
			<a href="#<?= $arCity['ID'];?>" data-target="#city_<?= $arCity['ID'];?>"
			   class="button small transparent"><?= $arCity['NAME'];?></a>
		<?endforeach;?>
	</div>
</div>

<?foreach ($arResult['CITIES'] as $arCity):?>

	<section class="b-contactsInCity" id="city_<?= $arCity['ID'];?>">
		<section class="contacts vacancyies_wrap">

			<header class="contactHeader">
				<div class="b-contactHeader">
					<h2><?= $arCity['NAME']; ?></h2>
			</header>

			<section class="contactBody">
				<div class="b-contactBody">

					<?if(count($arCity['VACANCIES']) == 0):?>

						<div class="no-vacancy">
							На данный момент в вашем городе нет открытых вакансий
						</div>

					<?else:?>

						<?foreach($arCity['VACANCIES'] as $vacancy):?>

							<section class="helpLinks toggleThis">
								<div class="questionHeadline">
									<h2>
										<a class="toggle_trigger" href="#"></a>
										<a class="help_href t_trigger" href="#"><?=$vacancy['NAME']?></a>
									</h2>
									<a class="send_response popup" href="/ajax/vacancy_response.php?vid=<?=$vacancy['ID']?>&city=<?=$arCity['NAME'];?>" data-width="400">Отправить резюме</a>
								</div>
								<div class="questionDetails newsContent toggleThis" style="display:none;">
									<div class="newsPr"><?=$vacancy['~DETAIL_TEXT'];?></div>
								</div>
							</section>

						<?endforeach;?>

					<?endif;?>

				</div>
			</section>

		</section>
	</section>

<?endforeach;?>

<?endif;?>