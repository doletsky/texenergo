<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Производство компании TEXENERGO");
$APPLICATION->SetPageProperty("description", "Техэнерго - надежный производитель и поставщик электротехнической продукции.");
?>
<style>
</style>
        <?// block site navigation?>
	<?$APPLICATION->IncludeFile('/include/block_site_navigation.php');?>

        <?// block banner system orders?>
	<?$APPLICATION->IncludeFile('/include/sale_main_block_sys_orders.php');?>

  <?// block banners (slider)?>
	<?$APPLICATION->IncludeFile('/include/sale_main_block_hero.php');?>
  <?// block slider ?>
	<?//$APPLICATION->IncludeFile('/include/sale_main_block_slider.php');?>

  <?// block catalog ?>
	<?//$APPLICATION->IncludeFile('/include/sale_main_block_catalog.php');?>

  <?// block lower_price ?>
	<?$APPLICATION->IncludeFile('/include/sale_main_block_lower_price.php');?>

  <?// block bestseller ?>
	<?$APPLICATION->IncludeFile('/include/sale_main_block_bestseller.php');?>

  <?// block novelties_goods ?>
	<?$APPLICATION->IncludeFile('/include/sale_main_block_novelties_goods.php');?>

  <?// block goods_day ?>
	<?$APPLICATION->IncludeFile('/include/sale_main_block_goods_day.php');?>

  <?// block shares ?>
	<?$APPLICATION->IncludeFile('/include/sale_main_block_shares.php');?>

  <?// block publication ?>
	<?$APPLICATION->IncludeFile('/include/sale_main_block_publication.php');?>

  <?// block banner 1 ?>
	<?$APPLICATION->IncludeFile('/include/sale_main_block_b1.php');?>

  <?// block ads ?>
	<?$APPLICATION->IncludeFile('/include/sale_main_block_ads.php');?>

  <?// block banner 1 ?>
	<?$APPLICATION->IncludeFile('/include/sale_main_block_b2.php');?>

  <?// block articles ?>
	<?$APPLICATION->IncludeFile('/include/sale_main_block_articles.php');?>

  <?// block tenders ?>
	<?//$APPLICATION->IncludeFile('/include/sale_main_block_tenders.php');?>

  <?// block our equipment?>
	<?$APPLICATION->IncludeFile('/include/sale_main_block_our_equipment.php');?>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>