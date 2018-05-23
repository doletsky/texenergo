<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

	<?
		$shopArticlesCount = count($arResult[ 'action_search' ][ 'list' ]) ;
		$catalogCount =  count($arResult[ 'catalog_search' ][ 'list' ]) ;
	?>

	<?$totalCount = $catalogCount+$shopArticlesCount;?>

<? if ($totalCount != 0) {?>

<div class="container">
	<div class="twelve">
		<h1 class="headline">все результаты поиска <span class="searchQuery">(<?=$arResult["REQUEST"]["QUERY"]?>)<em class="searchQuantity" ><?=$totalCount?></em></span> </h1>
		<aside class="share">
			<ul>
				<li><img src="<?=SITE_TEMPLATE_PATH?>/img/catalog/rss.png" alt="Подписаться на рассылку"></li>
				<li><img src="<?=SITE_TEMPLATE_PATH?>/img/catalog/share.png" alt="Поделиться с друзьями"></li>
			</ul>
		</aside>
	</div>
</div>

<div class="container">

	<div class="three three-nomargin">
		<nav class="sidebar sidebar-search">
			<ul class="sidebarSearch-main">

				<? if ($catalogCount > 0 ) {?>

					<li><a class="j-open-subcategory" href="/search/index.php?q=<?=$arResult["REQUEST"]["QUERY"]?>">все товары</a><span class="sidebarQnt"><?=$catalogCount?></span></li>

						<?if ($arResult["firstLevel"]) {?>

							<ul class="sidebarSearch-sub">

								<?foreach ($arResult['firstLevel'] as $category) {?>

									<li><a href="<?=$category["SEARCH_URL"]?>"><?=$category["NAME"]?></a><span class="sidebarQnt"><?=$category["COUNT"]?></span></li>

								<?}?>
							</ul>

						<?}?>

				<?}?>

				<? if ( $shopArticlesCount > 0) {?>

					<li><a href="/search/index.php?q=<?=$arResult["REQUEST"]["QUERY"]?>&section=action">спецпредложения</a><span class="sidebarQnt"><?=$shopArticlesCount?></span></li>

				<?}?>

			</ul>
		</nav>
	</div>

	<section class="main main-floated main-search">

		<nav class="sort">

	        <?php

	            $goodsFullViewOn =  isset($_COOKIE['goods_full_view']) ? (int)$_COOKIE['goods_full_view'] : 0;

	            $views = array('0' => 'Кратко', '1' => 'Списком', '2' => 'Подробно');

	            $types = getSortFieldArray();

	            $sortId = isset($_COOKIE['catalog_sort']) ? (int) $_COOKIE['catalog_sort'] : 4;

	        ?>
	        <div class="clear"></div>

	        <?php
	            echo '<span class="text">сортировка</span><select class="j-catalog-sort-id j-sort-select product-sort-top">';
	            foreach ($types as $type) {
	                $selected = '';
	                if ($type['CODE'] == $sortId) {
	                    $selected = 'selected';
	                }
	                echo '<option value="'.$type['CODE'].'" '.$selected.'>'.$type['NAME'].'</option>';
	            }
	            echo '</select>';

	            $arParams["ELEMENT_SORT_FIELD"] = getSortFieldById($sortId);
	            $arParams["ELEMENT_SORT_ORDER"] = getSortTypeById($sortId);


	            echo '<span class="text">ОТОБРАЖЕНИЕ ТОВАРОВ</span><select class="j-goods-full-view j-sort-select product-sort-top">';
	            foreach ($views as $key => $value) {
	                $selected = ((int)$key == (int)$goodsFullViewOn) ? 'selected' : '';
	                echo '<option value="' . $key .'" ' . $selected . '>' . $value. '</option>';
	            }
	            echo '</select>';
	        ?>


		</nav>

				<!-- Вид кратко  -->
		<?php if ( isset($_GET['section']) && $_GET['section'] == 'action')  {?>

			<header class="cat-header seach-header">
				<h1>Спецпредложения</h1>
			</header>

		<?} else if (isset($_GET['catId'])) {?>

			<?$currentCategoryID = $_GET['catId'];?>

			<header class="cat-header seach-header">

				<? foreach ($arResult["firstLevel"] as $category) {

					if ($category["ID"] == $currentCategoryID) {?>

						<h1><?=$category["NAME"]?></h1>

					<?}

				}?>
			</header>

		<?} else {?>

		<?}?>

		<!-- Вид кратко  -->
		<? if ($goodsFullViewOn == 0) {?>

			<?php if (!(isset($_GET['section']) && $_GET['section'] == 'action') && !(isset($_GET['catId'])))  {?>
				<!-- Все товары -->
				<header class="cat-header seach-header all-producnts-header">
					<h1>Все товары</h1>
				</header>
			<?}?>

			<?foreach ($arResult["SEARCH"] as $results) {

				$itemId = $results["ITEM_ID"];

				$isFolder = strripos($itemId, 'S');

				if ($isFolder === false && $results["PARAM1"] == "catalog") { ?>

					<article class="searchLinear clearfix">
						<figure class="searchImage-linear">
							<? if ($results["PREVIEW_PICTURE"]["SRC"] != "") {?>
								<a href="<?=$results["URL"]?>"><img src="<?=$results["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$results["TITLE"]?>"></a>
							<? } else { ?>
								<a href="<?=$results["URL"]?>"><img src="/local/templates/texenergo/img/catalog/no-image.png" alt="<?=$results["TITLE"]?>"></a>
							<?}?>
						</figure>
						<section class="searchDetails-linear">
							<a href="<?=$results["URL"]?>" class="product-name productName-linear"><?=$results["TITLE"]?></a>

							<span class="product-number productNumber-linear"><?=$results["PROPERTY_GOODS_ART_VALUE"]?></span>

							<? if ($results["PROPERTY_GOODS_RATE_VALUE"]) {?>
								<div class="cat-rating catRating-linear cat-value-raiting-<?=$results["PROPERTY_GOODS_RATE_VALUE"]?>"></div>
							<?}

							if ($results["PROPERTY_BLOG_COMMENTS_CNT_VALUE"]) {?>
								<span class="searchReviews"><?=$results["PROPERTY_BLOG_COMMENTS_CNT_VALUE"]?> отзыва</span>
							<?}

							if ($results["PREVIEW_TEXT"]) {?>
								<p><?=$results["PREVIEW_TEXT"]?></p>
							<?}?>
						</section>
						<aside class="searchCart-linear">
							<span class="cat-price"><?=$results["CATALOG_PRICE_1"]?> <i class="rouble">a</i></span>
							<? if ($results["PROPERTY_OLD_PRICE_VALUE"]) {?>
								<span class="pLineOld"><?=$results["PROPERTY_OLD_PRICE_VALUE"]?>Р</span>
							<?}?>
							<div class="pCart-line clearfix">
								<a href="<?=$results["~BUY_URL"]?>" class="pInCart pInCart-line clearfix">
									<img class="pCartIcon-line" src="<?=SITE_TEMPLATE_PATH?>/img/product/cart-mini.png" width="20" alt="добавить в корзину">
									<span class="pPutInBasket">в корзину</span>
								</a>
								<div class="pCartButtons pCartButtons-line clearfix">
									<a href="#">Лайкнуть</a>
									<a href="<?=$results["~COMPARE_URL"]?>">Сравнить</a>
								</div>
								<div class="pCartQuantity">
                                    <?/*
									<em>Остаток: </em>
									<div class="cat-indicator cat-white-indicator cat-value-indicator-<?if ($results["PROPERTY_REST_INDICATE_VALUE"] != "") {?><?=$results["PROPERTY_REST_INDICATE_VALUE"]?><?} else {?>0<?}?>"></div>
									<? if ($results["PROPERTY_GOODS_ANALOG_LINKS_VALUE_IDS"]) {?>
										<b class="pAnalog-linear"><a href="<?=$results["URL"]?>#tAnalogs">Аналоги</a></b>
									<?}?>
*/?>
								</div>
							</div>
						</aside>
					</article>
				<?}?>

			<?}?>

			<? echo $arResult["NAV_STRING"]; ?>



			<?php if (!(isset($_GET['section']) && $_GET['section'] == 'action') && !(isset($_GET['catId'])))  {?>
				<!-- Спецпредложения -->
				<header class="cat-header seach-header">
					<h1>Спецпредложения</h1>
				</header>

				<?foreach ($arResult["action_search"]["list"] as $results) {?>
					<article class="searchLinear clearfix">
						<figure class="searchImage-linear">
							<? if ($results["PREVIEW_PICTURE"]["SRC"] != "") {?>
								<a href="<?=$results["URL"]?>"><img src="<?=$results["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$results["TITLE"]?>"></a>
							<? } else { ?>
								<a href="<?=$results["URL"]?>"><img src="/local/templates/texenergo/img/catalog/no-image.png" alt="<?=$results["TITLE"]?>"></a>
							<?}?>
						</figure>
						<section class="searchDetails-linear">
							<a href="<?=$results["URL"]?>" class="product-name productName-linear"><?=$results["TITLE"]?></a>

							<span class="product-number productNumber-linear"><?=$results["PROPERTY_GOODS_ART_VALUE"]?></span>

							<? if ($results["PROPERTY_GOODS_RATE_VALUE"]) {?>
								<div class="cat-rating catRating-linear cat-value-raiting-<?=$results["PROPERTY_GOODS_RATE_VALUE"]?>"></div>
							<?}

							if ($results["PROPERTY_BLOG_COMMENTS_CNT_VALUE"]) {?>
								<span class="searchReviews"><?=$results["PROPERTY_BLOG_COMMENTS_CNT_VALUE"]?> отзыва</span>
							<?}

							if ($results["PREVIEW_TEXT"]) {?>
								<p><?=$results["PREVIEW_TEXT"]?></p>
							<?}?>
						</section>
						<aside class="searchCart-linear">
							<span class="cat-price"><?=$results["CATALOG_PRICE_1"]?> <i class="rouble">a</i></span>
							<? if ($results["PROPERTY_OLD_PRICE_VALUE"]) {?>
								<span class="pLineOld"><?=$results["PROPERTY_OLD_PRICE_VALUE"]?>Р</span>
							<?}?>
							<div class="pCart-line clearfix">
								<a href="<?=$results["~BUY_URL"]?>" class="pInCart pInCart-line clearfix">
									<img class="pCartIcon-line" src="<?=SITE_TEMPLATE_PATH?>/img/product/cart-mini.png" width="20" alt="добавить в корзину">
									<span class="pPutInBasket">в корзину</span>
								</a>
								<div class="pCartButtons pCartButtons-line clearfix">
									<a href="#">Лайкнуть</a>
									<a href="<?=$results["~COMPARE_URL"]?>">Сравнить</a>
								</div>
								<div class="pCartQuantity">
                                    <?/*
									<em>Остаток: </em>
									<div class="cat-indicator cat-white-indicator cat-value-indicator-<?if ($results["PROPERTY_REST_INDICATE_VALUE"] != "") {?><?=$results["PROPERTY_REST_INDICATE_VALUE"]?><?} else {?>0<?}?>"></div>
									<? if ($results["PROPERTY_GOODS_ANALOG_LINKS_VALUE_IDS"]) {?>
										<b class="pAnalog-linear"><a href="<?=$results["URL"]?>#tAnalogs">Аналоги</a></b>
									<?}?>
*/?>
								</div>
							</div>
						</aside>
					</article>
				<?}?>
				<? echo $arResult["NAV_STRING"]; ?>

			<?}?>

			<!-- <a href="#"><div class="searchBanner"></div></a> -->



		<!-- Вид списком  -->
		<?} else if ($goodsFullViewOn == 1) {?>


			<?php if (!(isset($_GET['section']) && $_GET['section'] == 'action') && !(isset($_GET['catId'])))  {?>
				<!-- Все товары -->
				<header class="cat-header seach-header all-producnts-header">
					<h1>Все товары</h1>
				</header>
			<?}?>


			<div class="colHeadings-search">
				<ul>
					<li>Количество</li>
					<li>Цена</li>
<!--					<li>Остаток</li>-->
					<li>Купить</li>
				</ul>
			</div>


			<?foreach ($arResult["SEARCH"] as $results) {

				$itemId = $results["ITEM_ID"];

				$isFolder = strripos($itemId, 'S');


				if ($isFolder === false && $results["PARAM1"] == "catalog") { ?>

					<article class="cat-product cat-product-line catProduct-search first clearfix search-list">
						<div class="col line-thumbnail lineThumbnail-search">
							<? if ($results["PREVIEW_PICTURE"]["SRC"] != "") {?>
								<a href="<?=$results["URL"]?>"><img src="<?=$results["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$results["TITLE"]?>"></a>
							<?} else {?>
								<a href="<?=$results["URL"]?>"><img src="/local/templates/texenergo/img/catalog/no-image.png" alt="<?=$results["TITLE"]?>"></a>
							<?}?>
						</div>
						<div class="col line-product-info">
							<a href="<?=$results["URL"]?>" class="product-name product-name-line"><?=$results["TITLE"]?></a>
							<span class="product-number"><?=$results["PROPERTY_GOODS_ART_VALUE"]?></span>
						</div>
						<div class="col">
							<span class="cat-price cat-price-line catPrice-search"><?=$results["CATALOG_PRICE_1"]?> <i class="rouble">a</i></span>
							<? if ($results["PROPERTY_OLD_PRICE_VALUE"]) {?>
								<span class="pLineOld"><?=$results["PROPERTY_OLD_PRICE_VALUE"]?>Р</span>
							<?}?>
						</div>
<!--						<div class="col">-->
<!--							<div class="cat-indicator cat-white-indicator cat-value-indicator---><?//if ($results["PROPERTY_REST_INDICATE_VALUE"] != "") {?><!----><?//=$results["PROPERTY_REST_INDICATE_VALUE"]?><!----><?//} else {?><!--0--><?//}?><!--"></div>-->
<!--						</div>-->

						<div class="col cat-line-icons">
							<a href="<?=$results["BUY_URL"]?>" class="cart-line"><img src="<?=SITE_TEMPLATE_PATH?>/img/catalog/cart-line.png" alt="Добавить в корзину"></a>
							<a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/img/catalog/like-gray.png" alt="Поделиться"></a>
							<a href="<?=$results["COMPARE_URL"]?>"><img src="<?=SITE_TEMPLATE_PATH?>/img/catalog/bars-gray.png" alt="Добавить в избранное"></a>
						</div>
					</article>



				<?}

			}?>

			<? echo $arResult["NAV_STRING"]; ?>


			<?php if (!(isset($_GET['section']) && $_GET['section'] == 'action') && !(isset($_GET['catId'])))  {?>

				<!-- Спецпредложения -->
				<header class="cat-header seach-header">
					<h1>Спецпредложения</h1>
				</header>

				<?foreach ($arResult["action_search"]["list"] as $results) {?>

					<article class="cat-product cat-product-line catProduct-search first clearfix search-list">
						<div class="col line-thumbnail lineThumbnail-search">
							<? if ($results["PREVIEW_PICTURE"]["SRC"] != "") {?>
								<a href="<?=$results["URL"]?>"><img src="<?=$results["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$results["TITLE"]?>"></a>
							<?} else {?>
								<a href="<?=$results["URL"]?>"><img src="/local/templates/texenergo/img/catalog/no-image.png" alt="<?=$results["TITLE"]?>"></a>
							<?}?>
						</div>
						<div class="col line-product-info">
							<a href="<?=$results["URL"]?>" class="product-name product-name-line"><?=$results["TITLE"]?></a>
							<span class="product-number"><?=$results["PROPERTY_GOODS_ART_VALUE"]?></span>
						</div>
						<div class="col">
							<span class="cat-price cat-price-line catPrice-search"><?=$results["CATALOG_PRICE_1"]?> <i class="rouble">a</i></span>
							<? if ($results["PROPERTY_OLD_PRICE_VALUE"]) {?>
								<span class="pLineOld"><?=$results["PROPERTY_OLD_PRICE_VALUE"]?>Р</span>
							<?}?>
						</div>
						<div class="col">
							<div class="cat-indicator cat-white-indicator cat-value-indicator-<?if ($results["PROPERTY_REST_INDICATE_VALUE"] != "") {?><?=$results["PROPERTY_REST_INDICATE_VALUE"]?><?} else {?>0<?}?>"></div>
						</div>

						<div class="col cat-line-icons">
							<a href="<?=$results["BUY_URL"]?>" class="cart-line"><img src="<?=SITE_TEMPLATE_PATH?>/img/catalog/cart-line.png" alt="Добавить в корзину"></a>
							<a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/img/catalog/like-gray.png" alt="Поделиться"></a>
							<a href="<?=$results["COMPARE_URL"]?>"><img src="<?=SITE_TEMPLATE_PATH?>/img/catalog/bars-gray.png" alt="Добавить в избранное"></a>
						</div>
					</article>

				<?}?>
				<? echo $arResult["NAV_STRING"]; ?>
			<?}?>


		<!-- Вид подробно  -->
		<?} else if ($goodsFullViewOn == 2) {

			$count = 0;
			$countAction = 0;

		?>

			<?php if (!(isset($_GET['section']) && $_GET['section'] == 'action') && !(isset($_GET['catId'])))  {?>
				<!-- Все товары -->
				<header class="cat-header seach-header all-producnts-header">
					<h1>Все товары</h1>
				</header>
			<?}?>

			<section class="cat-products catProducts-search clearfix j_cat_product_list">

				<?foreach ($arResult["SEARCH"] as $results) {

					$itemId = $results["ITEM_ID"];

					$isFolder = strripos($itemId, 'S');

					if ($isFolder === false && $results["PARAM1"] == "catalog") { ?>

						<?
							if ($count > 0 && $count % 4 == 0) {
								echo "</section><section class='cat-products catProducts-search clearfix j_cat_product_list'>";
							}
						?>

						<div class="wrapper">
							<article class="cat-product j_cat_product_card <? if ($count == 3) {?>forth<?}?>">
								<figure class="product-image">
									<? if ($results["PREVIEW_PICTURE"]["SRC"] != "") {?>
										<a href="<?=$results["URL"]?>"><img src="<?=$results["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$results["TITLE"]?>"></a>
									<?} else {?>
										<a href="<?=$results["URL"]?>"><img src="/local/templates/texenergo/img/catalog/no-image.png" alt="<?=$results["TITLE"]?>" width="60px"></a>
									<?}?>
									<figcaption class="product-info">
										<a href="<?=$results["URL"]?>" class="product-name"><?=$results["TITLE"]?></a>
										<span class="product-number"><?=$results["PROPERTY_GOODS_ART_VALUE"]?></span>
									</figcaption>
									<? if ($results["PROPERTY_GOODS_RATE_VALUE"]) {?>
										<div class="cat-rating catRating-linear cat-value-raiting-<?=$results["PROPERTY_GOODS_RATE_VALUE"]?>"></div>
									<?}?>
									<? if ($results["PROPERTY_OLD_PRICE_VALUE"]) {?>
										<span class="pLineOld"><?=$results["PROPERTY_OLD_PRICE_VALUE"]?>Р</span>
									<?}?>
									<span class="cat-price"><?=$results["CATALOG_PRICE_1"]?> <i class="rouble">a</i></span>
								</figure>
							</article> <!-- cat-product -->
							<div class="cat-hoverbox hidden">
								<div class="row clearfix">
									<a href="<?=$results["BUY_URL"]?>" class="cat-incart">в корзину</a>
									<div class="like-block">
										<a href="#" class="like"></a>
										<a href="<?=$results["COMPARE_URL"]?>" class="compare"></a>
									</div>
								</div>
								<div class="row rest-row">
									<?/* if ($results["PROPERTY_GOODS_ANALOG_LINKS_VALUE_IDS"]) {?>
										<a href="<?=$results["URL"]?>#tAnalogs" class="cat-analogs">Аналоги »</a>
									<?}?>
									<span class="cat-instock">Остаток:</span>
									<div class="cat-indicator cat-value-indicator-<?if ($results["PROPERTY_REST_INDICATE_VALUE"] != "") {?><?=$results["PROPERTY_REST_INDICATE_VALUE"]?><?} else {?>0<?}?>"></div>
                                    */?>
								</div>
								<div class="row">
									<section class="cat-technicals">
										<ul>
											<?foreach($results["FILTER"] as $filterValue) {?>
												<li><?=$filterValue["FILTER_VARS"]["NAME"]?>: <b><?=$filterValue["NAME"]?></b></li>
											<?}?>

											<? if ($results["CATALOG_WEIGHT"]) {
												echo "<li>вес: <b>".$results["CATALOG_WEIGHT"]."</b></li>";
					                        }
					                        if ($results["CATALOG_WIDTH"]) {
					                        	echo "<li>ширина: <b>".$results["CATALOG_WIDTH"]."</b></li>";
					                        }
					                        if ($results["CATALOG_LENGTH"]) {
												echo "<li>длина: <b>".$results["CATALOG_LENGTH"]."</b></li>";
					                        }
					                        if ($results["CATALOG_HEIGHT"]) {
												echo "<li>высота: <b>".$results["CATALOG_HEIGHT"]."</b></li>";
					                        } ?>

										</ul>
									</section>
								</div>
							</div> <!-- hoverbox -->
						</div>

						<?
							$count++;
							if ($count > 4) {
								$count = 1;
							}
						?>

					<?}

				}?>

			</section>

			<? echo $arResult["NAV_STRING"]; ?>



			<?php if (!(isset($_GET['section']) && $_GET['section'] == 'action') && !(isset($_GET['catId'])))  {?>

				<!-- Спецпредложения -->
				<header class="cat-header seach-header">
					<h1>Спецпредложения</h1>
				</header>

				<section class="cat-products catProducts-search clearfix j_cat_product_list">

					<?foreach ($arResult["action_search"]["list"] as $results) {?>


						<?
							if ($countAction > 0 && $countAction % 4 == 0) {
								echo "</section><section class='cat-products catProducts-search clearfix j_cat_product_list'>";
							}
						?>

						<div class="wrapper">
							<article class="cat-product j_cat_product_card <?=$countAction?> <? if ($countAction == 3) {?>forth<?}?>">
								<figure class="product-image">
									<? if ($results["PREVIEW_PICTURE"]["SRC"] != "") {?>
										<a href="<?=$results["URL"]?>"><img src="<?=$results["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$results["TITLE"]?>"></a>
									<?} else {?>
										<a href="<?=$results["URL"]?>"><img src="/local/templates/texenergo/img/catalog/no-image.png" alt="<?=$results["TITLE"]?>" width="60px"></a>
									<?}?>
									<figcaption class="product-info">
										<a href="<?=$results["URL"]?>" class="product-name"><?=$results["TITLE"]?></a>
										<span class="product-number"><?=$results["PROPERTY_GOODS_ART_VALUE"]?></span>
									</figcaption>
									<? if ($results["PROPERTY_GOODS_RATE_VALUE"]) {?>
										<div class="cat-rating catRating-linear cat-value-raiting-<?=$results["PROPERTY_GOODS_RATE_VALUE"]?>"></div>
									<?}?>
									<? if ($results["PROPERTY_OLD_PRICE_VALUE"]) {?>
										<span class="pLineOld"><?=$results["PROPERTY_OLD_PRICE_VALUE"]?>Р</span>
									<?}?>
									<span class="cat-price"><?=$results["CATALOG_PRICE_1"]?> <i class="rouble">a</i></span>
								</figure>
							</article> <!-- cat-product -->
							<div class="cat-hoverbox hidden">
								<div class="row clearfix">
									<a href="<?=$results["BUY_URL"]?>" class="cat-incart">в корзину</a>
									<div class="like-block">
										<a href="#" class="like"></a>
										<a href="<?=$results["COMPARE_URL"]?>" class="compare"></a>
									</div>
								</div>
								<div class="row rest-row">
									<?/* if ($results["PROPERTY_GOODS_ANALOG_LINKS_VALUE_IDS"]) {?>
										<a href="<?=$results["URL"]?>#tAnalogs" class="cat-analogs">Аналоги »</a>
									<?}?>
									<span class="cat-instock">Остаток:</span>
									<div class="cat-indicator cat-value-indicator-<?if ($results["PROPERTY_REST_INDICATE_VALUE"] != "") {?><?=$results["PROPERTY_REST_INDICATE_VALUE"]?><?} else {?>0<?}?>"></div>
*/?>
								</div>
								<div class="row">
									<section class="cat-technicals">
										<ul>
											<?foreach($results["FILTER"] as $filterValue) {?>
												<li><?=$filterValue["FILTER_VARS"]["NAME"]?>: <b><?=$filterValue["NAME"]?></b></li>
											<?}?>

											<? if ($results["CATALOG_WEIGHT"]) {
												echo "<li>вес: <b>".$results["CATALOG_WEIGHT"]."</b></li>";
					                        }
					                        if ($results["CATALOG_WIDTH"]) {
					                        	echo "<li>ширина: <b>".$results["CATALOG_WIDTH"]."</b></li>";
					                        }
					                        if ($results["CATALOG_LENGTH"]) {
												echo "<li>длина: <b>".$results["CATALOG_LENGTH"]."</b></li>";
					                        }
					                        if ($results["CATALOG_HEIGHT"]) {
												echo "<li>высота: <b>".$results["CATALOG_HEIGHT"]."</b></li>";
					                        } ?>

										</ul>
									</section>
								</div>
							</div> <!-- hoverbox -->
						</div>

						<?
							$countAction++;
							if ($countAction > 4) {
								$countAction = 1;
							}
						?>


					<?}?>

				</section>

				<? echo $arResult["NAV_STRING"]; ?>
			<?}?>


		<?}?>
	</section>



</div>

<?} else {?>

	<div class="container">
		<div class="twelve">
			<h1 class="headline">По вашему запросу ничего не найдено</h1>
		</div>
	</div>


<?}?>

<?php
// 	echo 'По спец.предложениям';
// 	if (isset ($_REQUEST[ 'section' ]) && (trim($_REQUEST[ 'section' ]) == 'action')) {
// 		//вывела тут спец-предложения
// 		foreach ($arResult[ 'action_search' ] as $item) {
// 			echo '<pre>';
// 			print_r($item);
// 		}
// 	}

?>