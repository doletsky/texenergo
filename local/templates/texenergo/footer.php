<?if(!defined('ERROR_404')):?>

	<?if($_REQUEST['ajax_call'] == 'y') die();?>

	<? if ($APPLICATION->GetCurDir() != '/'): ?>
		</div><!-- ./container -->
		</div><!-- ./main -->
	<? endif; ?>

	<?$APPLICATION->IncludeComponent(
		"bitrix:voting.current",
		".default",
		array(
			"CHANNEL_SID" => "TECHENERGO_VOTE",
			//"VOTE_ID" => "1",
			"VOTE_ALL_RESULTS" => "N",
			"AJAX_MODE" => "Y",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600",
			"AJAX_OPTION_ADDITIONAL" => ""
		),
		false
	);?>

	<!-- FOOTER START -->
	<footer class="footer clearfix" itemscope itemtype="http://schema.org/WPFooter">
		<div class="container">
			<div class="twelve">

				<?$APPLICATION->IncludeComponent(
					"bitrix:menu",
					"footer",
					Array(
						"ROOT_MENU_TYPE" => "left_info",
						"MAX_LEVEL" => "3",
						"CHILD_MENU_TYPE" => "child",
						"USE_EXT" => "Y",
						"DELAY" => "N",
						"ALLOW_MULTI_SELECT" => "N",
						"MENU_CACHE_TYPE" => "N",
						"MENU_CACHE_TIME" => "3600",
						"MENU_CACHE_USE_GROUPS" => "Y",
						"MENU_CACHE_GET_VARS" => array()
					)
				);?>

				<div class="line line-footer fullwidth"></div>
			</div>
			<!-- twelve -->

			<div class="twelve">
				<?$APPLICATION->IncludeComponent(
					"bitrix:subscribe.form",
					"footer",
					Array(
						"USE_PERSONALIZATION" => "Y",
						"PAGE" => "#SITE_DIR#personal/subscribe/",
						"SHOW_HIDDEN" => "N",
						"CACHE_TYPE" => "A",
						"CACHE_TIME" => "3600",
						"CACHE_NOTES" => "",
						"AJAX_MODE" => "Y"
					),
				false
				);?>
			</div>


		</div>
		<!-- container -->

		<?//$APPLICATION->IncludeFile('/include/livetex.php');?>

	</footer>
	<!-- FOOTER END -->

	<?$APPLICATION->IncludeComponent(
		"aero:catalog.panel",
		"",
		Array(), false);
	?>

	<?if(defined('SHOW_OVERFLOW_WRAP')):?>
		</div> <!--mainpage-overflow-wrap-->
	<?endif;?>

<?endif;?>
<!-- Rating@Mail.ru counter -->
<script type="text/javascript">
    var _tmr = _tmr || [];
    _tmr.push({id: "50204", type: "pageView", start: (new Date()).getTime()});
    (function (d, w) {
        var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true;
        ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
        var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
        if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
    })(document, window);
</script><noscript>


    <img src="//top-fwz1.mail.ru/counter?id=50204;js=na" style="border:0;" height="1" width="1" alt="Рейтинг@Mail.ru" />
</noscript>
<!-- //Rating@Mail.ru counter -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-2697871-1', 'auto');
ga('send', 'pageview');
</script>
<? $APPLICATION->IncludeFile('/include/livetex.php'); ?>
</body>

</html>
