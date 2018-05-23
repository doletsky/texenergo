<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<br />
<table border="0" cellspacing="0" cellpadding="5">
	<tr>
		<td valign="top" width="60%" align="right"><input type="submit" name="contButton" value="<?= GetMessage("SALE_CONTINUE")?> &gt;&gt;"></td>
		<td valign="top" width="5%" rowspan="3">&nbsp;</td>
		<td valign="top" width="35%" rowspan="3">
			<?echo GetMessage("STOF_CORRECT_NOTE")?><br /><br />
			<?echo GetMessage("STOF_PRIVATE_NOTES")?>
		</td>
	</tr>
	<tr>
		<td valign="top" width="60%">
			<?
			//$bPropsPrinted = PrintPropsForm($arResult["PRINT_PROPS_FORM"]["USER_PROPS_N"], GetMessage("SALE_INFO2ORDER"), $arParams);

			if(!empty($arResult["USER_PROFILES"]))
			{
				if ($bPropsPrinted)
					echo "<br /><br />";
				?>
				<b><?echo GetMessage("STOF_PROFILES")?></b><br /><br />
				<table class="sale_order_full_table">
					<tr>
						<td colspan="2">
							<?= GetMessage("SALE_PROFILES_PROMT")?>:
							<script language="JavaScript">
							function SetContact(enabled)
							{
								if(enabled)
									document.getElementById('sof-prof-div').style.display="block";
								else
									document.getElementById('sof-prof-div').style.display="none";
							}
							</script>
						</td>
					</tr>
					<?

					foreach($arResult["USER_PROFILES"] as $arUserProfiles)
					{
						?>
						
						<tr id="j-tr-profile-<?= $arUserProfiles["ID"] ?>">
							<td valign="top" width="0%">
							<?php //onClick="SetContact(false)"
									// if ($arUserProfiles["CHECKED"]=="Y") echo " checked"; 
									$checked = isset($arResult['PROFILE_ID']) && $arResult['PROFILE_ID'] == $arUserProfiles["ID"] ? 'checked' : '';
							?>
								<input type="radio" name="PROFILE_ID" class="j-chose-profile" id="ID_PROFILE_ID_<?= $arUserProfiles["ID"] ?>"  value="<?= $arUserProfiles["ID"];?>" <?= $checked;?>>
								<a href="#" class="j-delete-profile" rel="<?= $arUserProfiles["ID"];?>">Удалить профиль</a>
							</td>
							<td valign="top" width="100%">
								<label for="ID_PROFILE_ID_<?= $arUserProfiles["ID"] ?>">
								<b><?=$arUserProfiles["NAME"]?></b><br />
								<table>
								<?
								foreach($arUserProfiles["USER_PROPS_VALUES"] as $arUserPropsValues)
								{
									
									if (strlen($arUserPropsValues["VALUE_FORMATED"]) > 0)
									{
										?>
										<tr>
											<td><?=$arResult["PRINT_PROPS_FORM"]["USER_PROPS_Y"][$arUserPropsValues["ORDER_PROPS_ID"]]["NAME"]?>:</td>
											<td><?=$arUserPropsValues["VALUE_FORMATED"]?></td>
										</tr>
										<?
									}
									if ($arUserPropsValues['PROP_TYPE'] == 'LOCATION') {
										echo '<input class="j-location-profile-' . $arUserProfiles["ID"] . '" type="hidden" value="' . $arUserPropsValues['VALUE'] . '">';
									}
								}
								?>
								</table>
								</label>
							</td>
						</tr>
						<?
					}
					?>
					 <tr>
						<td width="0%">
							 <input type="radio"  name="PROFILE_ID" id="ID_PROFILE_ID_0" style="display:none;"value="0"<? if ($arResult["PROFILE_ID"]=="0") echo " checked";?> ><onClick="SetContact(true)" >
						</td>
						<td width="100%"><b><label for="ID_PROFILE_ID_0">
							<?php //echo GetMessage("SALE_NEW_PROFILE"); ?>
							</label></b><br />
							
						</td>
					</tr>
				</table>
				<?
			}
			else
			{
				?><input type="hidden" name="PROFILE_ID" value="0"><?
			}
			?>
			<br /><br />
			<div id="sof-prof-div">
			<?
			 //PrintPropsForm($arResult["PRINT_PROPS_FORM"]["USER_PROPS_Y"], GetMessage("SALE_NEW_PROFILE_TITLE"), $arParams);
			?>
			</div>
			<?
			if ($arResult["USER_PROFILES_TO_FILL"]=="Y")
			{
				?>
				<script language="JavaScript">
					SetContact(<?echo ($arResult["USER_PROFILES_TO_FILL_VALUE"]=="Y" || $arResult["PROFILE_ID"] == "0")?"true":"false";?>);
				</script>
				<?
			}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<?php 
// 			echo '<pre>';
// 			print_r($arResult);
// 			die;
				if (is_array($arResult['TOWN_VARIANTS']) && count($arResult['TOWN_VARIANTS'])) {
					foreach ($arResult['TOWN_VARIANTS'] as $town) {
						if (! empty($town['CITY_NAME'])) {
							$checked = isset($arResult['DELIVERY_LOCATION']) && $arResult['DELIVERY_LOCATION'] == $town['ID'] ? 'checked' : '';
							echo '<input type="radio" class="j-location-' . $town['ID'] . '" name="DELIVERY_LOCATION" value="' . trim($town['ID']) . '" ' . $checked. '>' . $town['CITY_NAME'] . '<br/>';
						}
					}
				}
			?>
		</td>
	</tr>
	<tr>
		<td valign="top" width="60%" align="right">
		<? //if(!($arResult["SKIP_FIRST_STEP"] == "Y"))
		//{
			?>
			<!-- input type="submit" name="backButton" value="&lt;&lt; <? //echo GetMessage("SALE_BACK_BUTTON") ?>" -->
			<?
		//}
		?>
			<input type="submit" name="contButton" value="<?= GetMessage("SALE_CONTINUE")?> &gt;&gt;">
		</td>
	</tr>
</table>

<?php 


?>