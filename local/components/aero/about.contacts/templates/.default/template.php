<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>



<style>
.contact-mail,
.contact-route {
    color: #ed5c2f;
    border-bottom: 1px dotted #ed5c2f;
}
</style>
<section class="main main-contact main-floated">
    <? if (count($arResult['CITIES']) > 1): ?>
        <div class="contactCity">
            <div class="b-contactCity">
                <? foreach ($arResult['CITIES'] as $arCity): ?>
                    <a href="#<?= $arCity['ID']; ?>" data-target="#city_<?= $arCity['ID']; ?>"
                       class="button small transparent"><?= $arCity['NAME']; ?></a>
                <? endforeach; ?>
            </div>
        </div>
    <? endif; ?>
    
	<?$i = 0;?>
	
	<? foreach ($arResult['CITIES'] as $arCity): ?>
        <section class="b-contactsInCity" id="city_<?= $arCity['ID']; ?>">
            <? foreach ($arCity['OFFICES'] as $arOffice): ?>

                <section class="contacts">
                    <header class="contactHeader">
                        <div class="b-contactHeader">
                            <h2><?= $arOffice['NAME']; ?></h2>
                        </div>
                    </header>
                    <section class="contactBody">
                        <div class="b-contactBody">
                            <div class="b-contactRow clearfix">
                                <div class="b-contactAdress">
                                    <p>
                                        <b>Адрес:</b> <?= $arOffice['ADDRESS']; ?>
                                    </p>
                                    <p>
                                        <a href="https://yandex.ru/maps/?mode=search&ol=biz&oid=1127284035&ll=37.311247%2C55.971219&z=17" class="contact-route" target="_blank">Построить маршрут на Яндекс Картах</a>
                                    </p>
                                    
									<?if($arOffice['SCHEME_AUTO']):?>
										<a href="#scheme_auto_<?=$i?>" class="showMapLink hide">Как проехать на автомобиле?</a>										
									<?endif;?>
									
									<?if($arOffice['SCHEME_FOOT']):?>
										<a href="#scheme_foot_<?=$i?>" class="showMapLink no-margin hide">Как добраться пешком?</a>
									<?endif;?>										
									
									<?if($arOffice['SCHEME_AUTO']):?>										
										
										<div id="scheme_auto_<?=$i?>" class="scheme" style="display:none;">
										
										<?if(preg_match_all('!src="([^\@\"]+)"!', $arOffice['SCHEME_AUTO']['TEXT'], $matches)):?>
											
											<?foreach($matches[1] as $match_num => $match):?>												
											
											<div class="scheme_inside" id="scheme_auto_<?=$i?>_<?=$match_num?>">
											
												<script>
												var oHead = document.getElementById('scheme_auto_<?=$i?>_<?=$match_num?>');
												var oScript= document.createElement("script");
												oScript.type = "text/javascript";
												oScript.src="<?=$match?>";
												oHead.appendChild( oScript);
												</script>
												
											</div>
												
											<?endforeach;?>
											
										<?else:?>
											<?=$arOffice['SCHEME_AUTO']['TEXT']?>
										<?endif;?>
										
										</div>
										
									<?endif;?>
									
									<?if($arOffice['SCHEME_FOOT']):?>										
										
										<div id="scheme_foot_<?=$i?>" class="scheme" style="display:none;">
										
										<?if(preg_match_all('!src="([^\@\"]+)"!', $arOffice['SCHEME_FOOT']['TEXT'], $matches)):?>
											
											<?foreach($matches[1] as $match_num => $match):?>												
											
											<div class="scheme_inside" id="scheme_foot_<?=$i?>_<?=$match_num?>">
											
											<script>										
											var oHead = document.getElementById('scheme_foot_<?=$i?>_<?=$match_num?>');
											var oScript= document.createElement("script");
											oScript.type = "text/javascript";
											oScript.src="<?=$match?>";
											oHead.appendChild( oScript);
											</script>
											
											</div>
											
											<?endforeach;?>
											
										<?else:?>
											<?=$arOffice['SCHEME_FOOT']['TEXT']?>
										<?endif;?>
										
										</div>
										
									<?endif;?>									
									
									<?$i++;?>
									
                                </div>
                                <div class="b-contactSchedule">
                                    <div class="contactDays">
                                        <ul class="clearfix">
                                            <li>пн</li>
                                            <li>вт</li>
                                            <li>ср</li>
                                            <li>чт</li>
                                            <li>пт</li>
                                            <li class="sunday">сб</li>
                                            <li class="sunday">вс</li>
                                        </ul>
                                    </div>
                                    <div class="contactTime">
                                        <span><?= $arOffice['WORKTIME']; ?></span>
                                        <em class="right">выходной</em>
                                    </div>
                                </div>
                            </div>
                            <div class="b-contactRow clearfix">
                                <? if ($arOffice['PHONE']): ?>
                                    <div class="b-contactPhone">
                                        <b>Телефон:</b>
                                        <span><?= $arOffice['PHONE']; ?></span>
                                        <span>многоканальный</span>
                                    </div>
                                <? endif; ?>
                                <? if ($arOffice['FAX']): ?>
                                    <div class="b-contactMail">
                                        <b>Факс:</b>
                                        <span><?= $arOffice['FAX']; ?></span>
                                        <span>многоканальный</span>
                                    </div>
                                <? endif; ?>
                                <? if ($arOffice['EMAIL']): ?>
                                    <div class="b-contactMail">
                                        <b>Почта:</b>
                                        <? if(false):?><a href="mailto:<?= $arOffice['EMAIL']; ?>"
                                           class="contactLink contactLink-mail"><?= $arOffice['EMAIL']; ?></a>
					<? endif; ?>
                                        <a href="mailto:<?= $arOffice['EMAIL']; ?>"
                                           class="contact-mail"><?= $arOffice['EMAIL']; ?></a>
                                    </div>
                                <? endif; ?>
                            </div>
                        </div>
                    </section>

                    <? if (!empty($arOffice['DEPARTMENTS'])): ?>
                        
						<?$depChunks = array_chunk($arOffice['DEPARTMENTS'], 3);?>
						
						<?foreach($depChunks as $depChunk):?>
						
						<section class="contactBody">
                            <div class="b-contactBody b-contactBody-borderless">
                                <div class="b-contactDept clearfix">
                                    <ul class="clearfix">
                                        <? foreach ($depChunk as $arDep): ?>
                                            <li>
                                                <b><?= $arDep['NAME']; ?></b>
                                                <span><?= $arDep['PHONE']; ?></span>
                                                <? if (!empty($arDep['EMPLOYEES'])): ?>
                                                    <a href="contactLink-<?= $arDep['ID']; ?>" id=""
                                                       class="contactLink">Сотрудники</a>
                                                <? endif; ?>
                                            </li>
                                        <? endforeach; ?>
                                    </ul>

                                </div>
                            </div>
							
							 
							 <?$depNum = 1;?>
							 
							 <?foreach($depChunk as $arDep):?>
								
								<?
								if($depNum == 1)
									$triangleClass = 'contactTriangle-left';
								else if($depNum == 2)
									$triangleClass = 'contactTriangle-center';
								else if($depNum == 3)
									$triangleClass = 'contactTriangle-right';
								
								$depNum++;
								?>
							
								<?if(!empty($arDep['EMPLOYEES'])):?>
																
									<aside class="contactBox epmloyee-list-box clearfix hidden" id="contactLink-<?= $arDep['ID']; ?>">
										<div class="contactTriangle <?=$triangleClass?>"></div>
										
										<?$empNum = 0;?>
										
										<?foreach($arDep['EMPLOYEES'] as $arEmp):?>
										
										<?
										if($empNum % 2 == 0)
											$boxClass = 'b-boxLeft';
										else
											$boxClass = 'b-boxRight';
											
										$empNum++;
										?>
										
										<div class="<?=$boxClass?> clearfix">
											<div class="b-hoverWrapper clearfix employee-picture">
												<?if($arEmp['PICTURE_SMALL']):?>
												<img alt="<?=$arEmp['PROPERTY_POSITION_VALUE'];?>" src="<?=$arEmp['PICTURE_SMALL']?>">
												<div class="contactHover employee-img" href="<?=$arEmp['PICTURE_BIG']?>"></div>
												<?endif;?>
												&nbsp;
											</div>
											
											<div class="emp-contacts-wrap">
											
												<h2><?=$arEmp['PROPERTY_POSITION_VALUE'];?></h2>
												<em><?=$arEmp['NAME'];?></em>
												
												<?if(!empty($arEmp['PROPERTY_PHONE_VALUE'])):?>
													<span><b>Тел.:</b> <?=$arEmp['PROPERTY_PHONE_VALUE'];?></span>
												<?endif;?>
												
												<?if(!empty($arEmp['PROPERTY_MOB_PHONE_VALUE'])):?>
													<span><b>Моб.:</b> <?=$arEmp['PROPERTY_MOB_PHONE_VALUE'];?></span>
												<?endif;?>
												
												<?if(!empty($arEmp['PROPERTY_EMAIL_VALUE'])):?>
													<?if (false):?><span><b>Почта:</b><a class="contactLink" href="mailto:<?=$arEmp['PROPERTY_EMAIL_VALUE'];?>"><?=$arEmp['PROPERTY_EMAIL_VALUE'];?></a></span><?endif;?>
													<span><b>Почта:</b><a class="contact-mail" href="mailto:<?=$arEmp['PROPERTY_EMAIL_VALUE'];?>"><?=$arEmp['PROPERTY_EMAIL_VALUE'];?></a></span>
												<?endif;?>
												
												<?if(!empty($arEmp['PROPERTY_ICQ_VALUE'])):?>
													<span><b>ICQ.:</b> <?=$arEmp['PROPERTY_ICQ_VALUE'];?></span>
												<?endif;?>
												
												<?if(!empty($arEmp['PROPERTY_SKYPE_VALUE'])):?>
													<span><b>Skype.:</b> <?=$arEmp['PROPERTY_SKYPE_VALUE'];?></span>
												<?endif;?>
											
											</div>
										</div>
										
										<?endforeach;?>										
										
									</aside>
									
								<?endif;?>
							
							<?endforeach;?>
							
                        </section>
						
						<?endforeach;?>
						
                    <? endif; ?>

                </section>
            <? endforeach; ?>
        </section>
    <? endforeach; ?>
</section>