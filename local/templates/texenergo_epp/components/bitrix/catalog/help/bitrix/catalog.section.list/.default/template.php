<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
$totalSections = count($arResult['SECTIONS']);
$halfCnt = round($totalSections / 2);
$first = true;
$subsectionsOpen = false;
?>

<div class="helpColumn helpColumn-first">
	<ul>

	<?for($i = 0; $i < $totalSections; $i++):?>
		
		<?$arSection = $arResult['SECTIONS'][$i];?>
		
		<?if($first && $i >= $halfCnt):?>
				
			<?$first = false;?>
							
				</ul>
			</div>
			<div class="helpColumn">
			<ul>
			
		<?endif;?>
		
		<?if($arSection["DEPTH_LEVEL"] == 1):?>
		
			<li>
			<section class="helpItem helpItem-hover">
				
				<?if($arSection['IS_PARENT']):?>
				
					<header class="clearfix">
						<h1><i class="<?=$arSection['UF_ICON_CLASS'];?>"></i><?=$arSection['NAME'];?></h1>
					</header>
					
					<section class="helpLinks" style="display: none;">
						<ul>
							
							<?
							$i++;
							$childSection = $arResult['SECTIONS'][$i];
							?>
							
							<?while($i < $totalSections && $childSection['DEPTH_LEVEL'] > $arSection['DEPTH_LEVEL']):?>								
								
								<?if(count($childSection['ELEMENTS']) > 0):?>
								
								<li>
									<a href="#" class="with_children"><?=$childSection['NAME']?></a>
									<section class="helpSubLinks" style="display:none;">
										<ul>
											
											<?foreach($childSection['ELEMENTS'] as $element):?>
											
												<li><a href="<?=$element['DETAIL_PAGE_URL'];?>"><?=$element['NAME'];?></a></li>
											
											<?endforeach;?>
											
										</ul>
									</section>
								</li>
								
								<?endif;?>
								
								<?
								$i++;
								$childSection = $arResult['SECTIONS'][$i];
								?>
							
							<?endwhile;?>
							
							<?$i = $i - 2;?>
							
						</ul>
					</section>
					
				<?elseif(count($arSection['ELEMENTS']) > 0):?>
					
					<header class="clearfix">
						<h1><i class="<?=$arSection['UF_ICON_CLASS'];?>"></i><?=$arSection['NAME'];?></h1>
					</header>
					<section class="helpLinks" style="display:none;">
						<ul>
							
							<?foreach($arSection['ELEMENTS'] as $element):?>
							
								<li><a href="<?=$element['DETAIL_PAGE_URL'];?>"><?=$element['NAME'];?></a></li>
							
							<?endforeach;?>
							
						</ul>
					</section>											
				
				<?endif;?>				
				
			</section>
			</li>
		<?endif;?>
		
	<?endfor;?>
	
	</ul>
</div>